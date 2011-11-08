#! /usr/local/bin/perl

use strict;
#use warnings;
use DBI;
use DBD::mysql;
use Net::SNMP;


##################################################MYSQL CONFIG#########################################################################

my $sunnystop = 0;

# MYSQL CONFIG VARIABLES
my $platform = "mysql";
my $port = "3306";
my $dbhost = "monitormysql.no-ip.org";
my $database = "monitor";
my $dbuser = 'root';
my $dbpass = 'toonnalux';

my $tablename = "";
my $query;
my $query_handle;
my (@return_value);



#open (filehandle,"u_p.bin") or die "I am unable to open";
#while(<filehandle>)
#{
 # $dbpass= unpack('n*',$_);
#}
#close(filehandle);


#open(filehandle,'u_p.txt') or die "can not open the file";
#foreach (<filehandle>)
#{
#	chomp;
#	$dbpass = $_;
#}
#close(filehandle);

#DATA SOURCE NAME
my $dsn = "dbi:mysql:$database:$dbhost:$port";

# PERL DBI CONNECT
my $connect = DBI->connect($dsn, $dbuser, $dbpass) or die "Unable to connect: $DBI::errstr\n";

$query = "USE $database";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "Unable to find ups database : $query_handle::errstr\n";


$query = "SELECT perlPid FROM monitorConfig";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "$query_handle::errstr\n";
@return_value = $query_handle->fetchrow_array();
if (@return_value[0]){
	print "I am already running.\n";
	exit;
}

$query = "UPDATE monitorConfig SET perlPid = '$$' ";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "Unable update perl pid\n";


###############################################################################################################################


my (@host,$dbrow,@varbindlist,$errstatus,$interval,@statistic,@statoid,$houryes,$noerror);
#my $OID_upsIdentModel = '1.3.6.1.2.1.33.1.1.2.0';
#my $OID_upsIdentName = '1.3.6.1.2.1.33.1.1.5.0';
#my $OID_upsIdentManufacturer = '1.3.6.1.2.1.33.1.1.1.0';

open(filehandle,'configuration.txt') or die "can not open the file";
foreach (<filehandle>)
{
	chomp;
	$interval= $_;
}
close(filehandle);

my $numSample;
my $numSampleHour = 0;
 
my  $sampleLimit = 6 / $interval;
my $sampleHourLimit = 10;

###############################################STORING PROCESS ID##################################################################
#$query = "DELETE FROM processId";
#$query_handle = $connect->prepare($query);
#$query_handle->execute() or die "$query_handle::errstr\n";
#
#$query = "INSERT INTO processId(pid) VALUES('$$')";
#$query_handle = $connect->prepare($query);
#$query_handle->execute() or die "$query_handle::errstr\n";

###################################################################################################################################


#############################################################main##################################################################

my $hourreturn = 0;
$houryes = 0;
$noerror = 0;

for($numSample=0;;$numSample++)
{
	$sunnystop++;
	if($sunnystop >= 2000){exit;}
	
	get_host();
	$errstatus = 0;
	for(my $i=0;$i<=@host-1;$i++) 
	{
		my ($session, $error) = Net::SNMP->session(
			-hostname    => $host[$i],
			-community   => 'public',
			#-port		 => '8001',
			-nonblocking => 1,
			-timeout	 => 1,
			-translate	 => 0,
		);

		if (!defined $session) {
			printf "ERROR: Failed to create session for host '%s': %s.\n",
				$host[$i], $error;
			
			#my $Time = get_time();
			#$query = "INSERT INTO monitorErrorLog(ip, description) VALUES ('$host[$i]', '$error')";
			#$query_handle = $connect->prepare($query);
			#$query_handle->execute();
			log_error($host[$i],$error);
			$noerror++;
			error_check();
			next;
		}

		get_oid($host[$i]);
		$hourreturn = get_hourdata($host[$i]);
		#print "ho = $hourreturn\n";
		#print "@varbindlist\n";
		my $result = $session->get_request(
			-varbindlist => \@varbindlist,
			-callback    => [ \&get_callback, $host[$i] ],
		);
	
		if (!defined $result) {
			printf "ERROR: Failed to queue get request for host '%s': %s.\n",
				$session->hostname(), $session->error();
			
			#my $Time = get_time();
			my $err = $session->error();
			my $hostname = $session->hostname();
			#$query = "INSERT INTO monitorErrorLog(ip, description) VALUES ('$hostname', '$err')";
			#$query_handle = $connect->prepare($query);
			#$query_handle->execute();
			log_error($hostname,$err);			
			$errstatus = 1;
			$noerror++;
			error_check();
		}
		if($houryes == 1)
		{
			#$query = "DELETE FROM monitorSample WHERE ip = '$host[$i]' ORDER BY timeStamp LIMIT 1";
			#$query_handle = $connect->prepare($query);
			#$query_handle->execute();
		}
		
		if($numSampleHour > $sampleHourLimit)
		{
			#$query = "DELETE FROM monitorHourLog WHERE ip = '$host[$i]' ORDER BY timeStamp LIMIT 1";
			#$query_handle = $connect->prepare($query);
			#$query_handle->execute();
		}
		

	} #FOR loop which is for number of devices

	snmp_dispatcher();
	if($hourreturn == 1)
	{
		$hourreturn = 0;
		$numSample = 0;
		$houryes = 1;
		#print "r = $hourreturn,n= $numSample\n";
	}
	#print "n= $numSample\n";
	
	
	my $flowControl = 0;
	while(1){
		$query = "SELECT stop, probeInterval FROM monitorConfig";
		$query_handle = $connect->prepare($query);
		$query_handle->execute() or die "$query_handle::errstr\n";
		@return_value = $query_handle->fetchrow_array();
		if (@return_value[0] == '1'){
			$query = "UPDATE monitorConfig SET perlPid=NULL ";
			$query_handle = $connect->prepare($query);
			$query_handle->execute() or die "$query_handle::errstr\n";
			exit;
		}
		$interval = @return_value[1];
		if($errstatus == 1){
			$flowControl = $flowControl + 2;
			$errstatus = 0;
		}
		if($flowControl >= $interval){
			last;
		}
		else{
			#print "\ni am sleeping";
			sleep(1);
			$flowControl++;
			next;
		}
	}

} #FOR loop which should be infinite loop

##############################################End of main################################################################



#########################################################################################################################
####################################################Functions############################################################
#########################################################################################################################


#################################################HOSTS IDENTIFICATION####################################################

sub get_host
{
	$query = "SELECT ip FROM monitorDeviceList";
	$query_handle = $connect->prepare($query);
	$query_handle->execute() or die "$query_handle::errstr\n";
	$dbrow = 0;
	while(@return_value = $query_handle->fetchrow_array())
	{
		$host[$dbrow] = @return_value[0];
		$dbrow++;
	}
}


sub get_callback
{
    my ($session, $location) = @_;
    my $result = $session->var_bind_list();
    if (!defined $result) {
		printf "ERROR: Get request failed for host '%s': %s.\n",
            $session->hostname(), $session->error();
			
		#my $Time = get_time();
		my $err = $session->error();
		my $hostname =  $session->hostname();
		#$query = "INSERT INTO monitorErrorLog(ip, description) VALUES ('$hostname', '$err')";
		#$query_handle = $connect->prepare($query);
		#$query_handle->execute();	
		log_error($hostname,$err);
		$errstatus = 1;
		$noerror++;
		error_check();
        return;
    }

	my $iphost = $session->hostname();
	@varbindlist = $session->var_bind_names();
	printf "The model for host '%s' is %s, name is %s, manufacturer is %s, sysuptime is %s.\n",
        $session->hostname(), $result->{$varbindlist[0]}, $result->{$varbindlist[1]},$result->{$varbindlist[2]},$result->{$varbindlist[3]};
	#my $Time = get_time();
	
	my $maxoid;
	my $varoid;
	$query = "SELECT MAX(numberOfOid) FROM monitorDeviceList";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();	
	while(@return_value = $query_handle->fetchrow_array())
	{
		$maxoid = @return_value[0];
	}
	
	$varoid = @varbindlist;
	$query  = "INSERT INTO monitorSample VALUES (NULL , NULL,'$iphost'";
	
	
	
	
	
	for(my $oids = 1;$oids <= @varbindlist;$oids++)
	{
		my $res = $result->{$varbindlist[$oids-1]};
		$query .= ",'" . "$res" . "'";
			
	}
	for(my $c = 1;$c < $maxoid - ($varoid - 1); $c++)
	{
		#print "i am here\n";
		$query .=  ",NULL";		
	}
	
	$query .= ")";
	#print "\n\n $query\n\n";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
		
    return;
}


sub get_time
{
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
	$year += 1900;
	my @month_abbr = qw( Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec );
	my $T = $year." $month_abbr[$mon]"." $mday"." $hour".":$min".":$sec\n";
}


sub get_oid
{
	@varbindlist = ();
	@statistic = ();
	$query = "SELECT oid, needStatisticAndThreshold FROM monitorDeviceAndOid WHERE ip = '$_[0]' ORDER BY id ASC";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();	
	while(@return_value = $query_handle->fetchrow_array())
	{
		#print "@return_value[0]\n";
		push(@varbindlist, @return_value[0]);
		push(@statistic, @return_value[1]);
	}
	#print "@statistic\n\n\n";
}

sub get_statisticoid
{
	@statoid = ();
	$query = "SELECT oid FROM monitorDeviceAndOid WHERE ip = '$_[0]' AND needStatisticAndThreshold = 'Y' ORDER BY id ASC";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();	
	while(@return_value = $query_handle->fetchrow_array())
	{
		#print "@return_value[0]\n";
		push(@statoid, @return_value[0]);
	}
	#print "@statoid\n\n\n";
}

sub get_hourdata
{
	my $nostatoid = 0;
	my $avgresult;
	my $maxstatoid;
	if($numSample >= $sampleLimit)
	{
		
		#for(my $hip=0;$hip<=@host-1;$hip++)
		#{
			my $query1 = "INSERT INTO monitorHourLog VALUES(NULL, NULL, '$_[0]'";
			get_statisticoid($_[0]);
			for(my $so=0;$so<=@statistic-1;$so++)
			{
				if($statistic[$so] =~ /Y/)
				{	
					my $so1 = $so + 1;
					$query = "SELECT oid$so1 FROM monitorSample WHERE ip = '$_[0]'";
					#print "q = $query\n";
					$query_handle = $connect->prepare($query);
					$query_handle->execute();
					my $sum = 0;
					my $nosam = 0;
					while(@return_value = $query_handle->fetchrow_array())
					{
						#print "\nr = $return_value[0]\n";
						$sum += @return_value[0];
						$nosam ++;
						#print "sum = $sum,no = $nosam\t";
					}
					$avgresult = $sum / $nosam;
					$query1 .= ",'$avgresult'";
					$nostatoid ++;
				}
			}
		
		$query = "SELECT MAX(numberOfStatisticOid) FROM monitorDeviceList";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();	
		while(@return_value = $query_handle->fetchrow_array())
		{
			$maxstatoid = @return_value[0];
		}
		
		#print "max = $maxstatoid,so = $nostatoid\n";
		for(my $c = 1;$c <= ($maxstatoid - $nostatoid); $c++)
		{
			print "i am here\n";
			$query1 .=  ",NULL";		
		}
		
		$query1 .= ")";
		#print "query = $query1\n";
		$query_handle = $connect->prepare($query1);
		$query_handle->execute();
		#}
		$numSampleHour ++;
		my $ret = 1;
	}
}

sub log_error
{
	$query = "INSERT INTO monitorErrorLog(ip, description) VALUES ('$_[0]', '$_[1]')";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
}

sub error_check
{
	if($noerror >= 1000)
	{
		$noerror = 1001;
		$query = "DELETE FROM monitorErrorLog ORDER BY timeStamp LIMIT 1";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		
	}
}
