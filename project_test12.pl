#! /usr/local/bin/perl

use strict;
use DBI;
use DBD::mysql;
use Net::SNMP;
use POSIX;
#use Email::Send;
#use Email::Send::Gmail;
#use Email::Simple::Creator;
use IO::Socket::INET;


##################################################MYSQL CONFIG#########################################################################


# MYSQL CONFIG VARIABLES
my $platform = "mysql";
my $port = "3306";
my $dbhost = "127.0.0.1";#"monitormysql1988.zapto.org";
my $database = "PG01";
my $tablename = "";
my $query;
my $query_handle;
my (@return_value);
my $dbuser = 'pg01';#'root';
my $dbpass = 'pg01abc';#'toonnalux';


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
$query_handle->execute() or die "Unable to find database : $query_handle::errstr\n";

#$query = "DELETE FROM monitorSample";
#$query_handle = $connect->prepare($query);
#$query_handle->execute();


###############################################################################################################################


my (@host,$dbrow,@varbindlist,$errstatus,$interval,@statistic,@statoid,$houryes,$noerror,@threashold1,@threashold2,@threasholdoid,@email);
my (@community,@version,$needmail);


my $sampleHourLimit = 6;
my $numSample;
my $numSample1;
my $numSampleHour ;
my $numSampleDay = 0;
my $numSampleMonth = 0;
my $errorLimit = 10;
my $sampleLimit;
my $sampleDayLimit = 1080;
my $hourbyinterval;
my $nostatoid;
my $sampleMonthLimit = 240 ;


my $oldFlowControl = time;
my $flowControl = time;

$query = "SELECT * FROM monitorConfig";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "$query_handle::errstr\n";
while(@return_value = $query_handle->fetchrow_array())
{
	$interval = @return_value[0];
	$sampleHourLimit = @return_value[2];
	$errorLimit = @return_value[4];
	$sampleLimit = @return_value[5];
	$numSample1 = @return_value[6];;
	$numSampleHour = @return_value[7];
	$noerror = @return_value[8];
	$numSampleDay = @return_value[10];
	$numSampleMonth = @return_value[11];
	$sampleDayLimit = @return_value[12];
	$sampleMonthLimit = @return_value[13];
}



print "interval = $interval, samplehourlimit = $sampleHourLimit, numberofhour = $numSampleHour\n";
print "errorlimit = $errorLimit, sampleLimit = $sampleLimit, numSample = $numSample1, noerror = $noerror\n";
print "numSampleDay = $numSampleDay, numSampleMonth = $numSampleMonth, sampleDayLimit = $sampleDayLimit, sampleMonthLimit = $sampleMonthLimit\n";

 
#$sampleLimit = $sampleLimit / $interval;
#$interval = 1200; #for test

$hourbyinterval = floor(3600/$interval);
print "\n\n hourbyinterval = $hourbyinterval\n\n";


###############################################STORING PROCESS ID##################################################################
#$query = "DELETE FROM processId";
#$query_handle = $connect->prepare($query);
#$query_handle->execute() or die "$query_handle::errstr\n";

$query = "SELECT perlPid FROM monitorConfig";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "$query_handle::errstr\n";
@return_value = $query_handle->fetchrow_array();
if (@return_value[0]){
	print "I am already running.\n";
	exit;
}

$query = "UPDATE monitorConfig SET perlPid = '$$'";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "$query_handle::errstr\n";

###################################################################################################################################


#############################################################main##################################################################

my $hourreturn = 0;
my $dayreturn = 0;
my $monthreturn = 0;
my $dayyes = 0;
my $day30 = 0;
$houryes = 0;
my $hour24 = 0;

my $shutflag = 0;
for(;;$numSample++)
{
	get_host();
	$errstatus = 0;
	for(my $i=0;$i<=@host-1;$i++) 
	{
		my ($session, $error) = Net::SNMP->session(
			-hostname    => $host[$i],
			-community   => $community[$i],
			#-port		 => '8001',
			-nonblocking => 1,
			-timeout	 => 1,
			-translate	 => 0,
			-version	 => $version[$i]	
		);

		if (!defined $session) {
			printf "ERROR: Failed to create session for host '%s': %s.\n",
				$host[$i], $error;
			
			#my $Time = get_time();
			log_error($host[$i],$error);
			$noerror++;
			error_check($host[$i],$noerror);
			$needmail = email_need($host[$i]);
			my $mail = get_email($host[$i]);
			if($needmail eq 'N')
			{
				send_mail($error,$mail);
				$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$host[$i]'";
				$query_handle = $connect->prepare($query);
				$query_handle->execute();
			}
			next;
		}

		get_oid($host[$i]);
		$hourreturn = get_hourdata($host[$i]);
		
		get_oid($host[$i]);
		$dayreturn = get_daydata($host[$i]);
		
		get_oid($host[$i]);
		$monthreturn = get_monthdata($host[$i]);
		
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
			log_error($hostname,$err);			
			$errstatus = 1;
			$noerror++;
			error_check($hostname, $noerror);
			$needmail = email_need($host[$i]);
			my $mail = get_email($host[$i]);
			if($needmail eq 'N')
			{
				send_mail($err,$mail);
				$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$host[$i]'";
				$query_handle = $connect->prepare($query);
				$query_handle->execute();
			}
		}
		
		
		
		if($numSample1 >= $sampleLimit)
		{
			$query = "DELETE FROM monitorSample WHERE ip = '$host[$i]' ORDER BY id LIMIT 1";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
		}
		
		
#		if($houryes == 1)
#		{
#			$query = "DELETE FROM monitorSample WHERE ip = '$host[$i]' ORDER BY id LIMIT 1";
#			$query_handle = $connect->prepare($query);
#			$query_handle->execute();
#		}
		
		
		
#		if($dayyes == 1)
#		{
#			$query = "DELETE FROM monitorSample WHERE ip = '$host[$i]' ORDER BY id LIMIT 1";
#			$query_handle = $connect->prepare($query);
#			$query_handle->execute();
#		}
		
	} #FOR loop which is for number of devices

	snmp_dispatcher();
	if($hourreturn == 1)
	{
		$hourreturn = 0;
		$numSample = 0;
		$hour24++;
		#if($hour24 >= 24)
		#{
		#	$houryes =1 ;
		#}
	}
	
	
	
	if($dayreturn == 1)
	{
		$dayreturn = 0;
		$hour24 = 0;
		$day30 ++;
	}
	
	
	if($monthreturn == 1)
	{
		$monthreturn = 0;
		$day30 = 0;
		
	}
	
	

	$flowControl = time;
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
		#if($errstatus == 1){
		#	$flowControl = $flowControl + 2;
		#	$errstatus = 0;
		#}
		if($flowControl - $oldFlowControl >= $interval){
			last;
		}
		else{
			#print "\ni am sleeping";
			sleep(1);
			$flowControl = time;
			next;
		}
	}
	$oldFlowControl = time;
	$numSample1++;
	$query = "UPDATE monitorConfig SET numberOfSample = '$numSample1'";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
	
	
} #FOR loop which should be infinite loop

##############################################End of main################################################################



#########################################################################################################################
####################################################Functions############################################################
#########################################################################################################################


#################################################HOSTS IDENTIFICATION####################################################

sub get_host
{
	$query = "SELECT * FROM monitorDeviceList WHERE numberOfOid > 0";
	$query_handle = $connect->prepare($query);
	$query_handle->execute() or die "$query_handle::errstr\n";
	$dbrow = 0;
	while(@return_value = $query_handle->fetchrow_array())
	{
		$host[$dbrow] = @return_value[1];
		$version[$dbrow] = @return_value[3];
		$community[$dbrow] = @return_value[4];
		$email[$dbrow] = @return_value[5];
		$dbrow++;
	}
	#print "com = @community, email = @email\n";
}

sub get_email
{
	$query = "SELECT * FROM monitorDeviceList WHERE ip = '$_[0]'";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
	my $mail;
	while(@return_value = $query_handle->fetchrow_array())
	{
		$mail = @return_value[5];
		#$needmail = @return_value[8];
	}
	$mail;
}


sub email_need
{
	$query = "SELECT emailsent FROM monitorDeviceList WHERE ip = '$_[0]'";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
	my $need;
	while(@return_value = $query_handle->fetchrow_array())
	{
		$need = @return_value[0];
	}
	$need;
}

sub get_threashold
{
	@threasholdoid = ();
	@threashold1 = ();
	@threashold2 = ();
	
	$query = "SELECT * FROM monitorThreshold WHERE ip = '$_[0]'";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
	my $row = 0;
	while(@return_value = $query_handle->fetchrow_array())
	{
		@threasholdoid[$row] = @return_value[2];
		@threashold1 [$row] = @return_value[3];
		@threashold2 [$row]= @return_value[4];
		$row++;
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
		log_error($hostname,$err);
		$errstatus = 1;
		$noerror++;
		error_check($hostname, $noerror);
		$needmail = email_need($hostname);
		my $mail = get_email($hostname);
		if($needmail eq 'N')
		{
			send_mail($err,$mail);
			$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$hostname'";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
		}
        return;
    }

	my $iphost = $session->hostname();
	@varbindlist = $session->var_bind_names();
	#printf "The model for host '%s' is %s, name is %s, manufacturer is %s, sysuptime is %s.\n",
     #   $session->hostname(), $result->{$varbindlist[0]}, $result->{$varbindlist[1]},$result->{$varbindlist[2]},$result->{$varbindlist[3]};
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
		
	#get_oid($iphost);
	get_threashold($iphost);
	my $mail = get_email($iphost);
	my (@tr1,@tr2);
	
	for(my $v = 1; $v <= @varbindlist-1 ; $v++)
	{
		for(my $x=0 ; $x<=@threasholdoid-1 ; $x++)
		{
			#print "$v,varbin='$varbindlist[$v]', $x, threashold = '$threasholdoid[$x]' \n\n";
			if($varbindlist[$v] =~ /$threasholdoid[$x]/)
			{
				my $varresult= $result->{$varbindlist[$v]};
				#print "Threashold1 is $threashold1[$x]\n";
				if($threashold1[$x] ne '')
				{
					#print "treashold is not null\n";
					@tr1 = split(/:/, $threashold1[$x]);
					#print "@tr1\n";
				
					if($tr1[0] eq 'min')
					{
						#print "min:1, res = $varresult, tr = $tr1[1] \n";
						if($varresult < $tr1[1])
						{
							$query = "UPDATE monitorDeviceList SET statusError = 'Y'  WHERE ip = '$iphost'";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							$query = "UPDATE monitorDeviceAndOid SET errorStatus = 'Y'  WHERE ip = '$iphost' AND oid='$threasholdoid[$x]' ";
							#print $query."\n";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							log_error("$iphost", "$threasholdoid[$x],$tr1[3]");
							$noerror++;
							error_check($iphost, $noerror);

							if($tr1[2] eq 'email')
							{
								$needmail = email_need($iphost);
								#print " min:1:ip = '$iphost', message = $tr1[3], mail = $mail , need = $needmail\n";
								if($needmail eq 'N')
								{
									send_mail($tr1[3],$mail);
									$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$iphost'";
									$query_handle = $connect->prepare($query);
									$query_handle->execute();
									#log_error("$iphost", "$tr1[3]");
									#$noerror++;
									#error_check($iphost, $noerror);
									#print "Email sent.\n";
								}
							}
							else
							{
								#print "min:1:shut = $iphost\n";
								$shutflag = 1;
								log_error("$iphost", "shutting down attached servers.");
								$noerror++;
								error_check($iphost, $noerror);
								shut_server($iphost);
							}
						}
					}
					else
					{
						#print "max:1\n";
						if($varresult > $tr1[1])
						{
							$query = "UPDATE monitorDeviceList SET statusError = 'Y'  WHERE ip = '$iphost'";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							$query = "UPDATE monitorDeviceAndOid SET errorStatus = 'Y'  WHERE ip = '$iphost' AND oid='$threasholdoid[$x]' ";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							log_error("$iphost", "$threasholdoid[$x],$tr1[3]");
							$noerror++;
							error_check($iphost, $noerror);

							if($tr1[2] eq 'email')
							{
								$needmail = email_need($iphost);
								#print " max:1:ip = '$iphost',message = $tr1[3], mail = $mail\n";
								if($needmail eq 'N')
								{
									send_mail($tr1[3],$mail);
									$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$iphost'";
									$query_handle = $connect->prepare($query);
									$query_handle->execute();
									#log_error("$iphost", "$tr1[3]");	
									#$noerror++;
									#error_check($iphost, $noerror);
									#print "Email sent.";
								}
							}
							else
							{
								#print "max:1:shut = $iphost\n";
								$shutflag = 1;
								log_error("$iphost", "shutting down attached servers.");
								$noerror++;
								error_check($iphost, $noerror);
								shut_server($iphost);
							}
						}
						
					}
				}
				if($threashold2[$x] ne '')
				{
					@tr2 = split(/:/, $threashold2[$x]);
				
					if($tr2[0] eq 'min')
					{
						if($varresult < $tr2[1])
						{
							$query = "UPDATE monitorDeviceList SET statusError = 'Y'  WHERE ip = '$iphost'";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							$query = "UPDATE monitorDeviceAndOid SET errorStatus = 'Y'  WHERE ip = '$iphost' AND oid='$threasholdoid[$x]' ";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							log_error("$iphost", "$threasholdoid[$x],$tr2[3]");
							$noerror++;
							error_check($iphost, $noerror);

							if($tr2[2] eq 'email')
							{
								$needmail = email_need($iphost);
								#print " min:2:ip = '$iphost', message = $tr2[3], mail = $mail\n";
								if($needmail eq 'N')
								{
									send_mail($tr2[3],$mail);
									$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$iphost'";
									$query_handle = $connect->prepare($query);
									$query_handle->execute();	
									#log_error("$iphost", "$tr2[3]");
									#$noerror++;
									#error_check($iphost, $noerror);
									#print "Email sent.";
								}
							}
							else
							{
								#print "min:2:ip = '$iphost', shut = $iphost\n";
								$shutflag = 1;
								log_error("$iphost", "shutting down attached servers.");
								$noerror++;
								error_check($iphost, $noerror);
								shut_server($iphost);
							}
						}
					}
					else
					{
						if($varresult > $tr2[1])
						{
							$query = "UPDATE monitorDeviceList SET statusError = 'Y'  WHERE ip = '$iphost'";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							$query = "UPDATE monitorDeviceAndOid SET errorStatus = 'Y'  WHERE ip = '$iphost' AND oid='$threasholdoid[$x]' ";
							$query_handle = $connect->prepare($query);
							$query_handle->execute();
							log_error("$iphost", "$threasholdoid[$x],$tr2[3]");
							$noerror++;
							error_check($iphost, $noerror);

							if($tr2[2] eq 'email')
							{
								$needmail = email_need($iphost);
								#print " max:2:ip = '$iphost', message = $tr2[3], mail = $mail\n";
								if($needmail eq 'N')
								{
									send_mail($tr2[3],$mail);
									$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$iphost'";
									$query_handle = $connect->prepare($query);
									$query_handle->execute();	
									#log_error("$iphost", "$tr2[3]");
									#$noerror++;
									#error_check($iphost, $noerror);
									#print "Email sent.";
								}
							}
							else
							{
								#print "max:2:ip = '$iphost',shut = $iphost\n";
								$shutflag = 1;
								log_error("$iphost", "shutting down attached servers.");
								$noerror++;
								error_check($iphost, $noerror);
								shut_server($iphost);
							}
						}
					}
				}
				#print "ip = $iphost , r =  $r\n";
			}
		}
	}	
		
		
    return;
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
	$nostatoid = 0;
	my $avgresult;
	my $maxstatoid;
	if($numSample >= ($hourbyinterval))
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
					$query = "SELECT oid$so1 FROM monitorSample WHERE ip = '$_[0]' ORDER BY timeStamp DESC LIMIT $hourbyinterval ";
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
					if($nosam != 0)
					{
						$avgresult = $sum / $nosam;
					}
					else
					{
						$avgresult = 0;
					}
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
			#print "i am here\n";
			$query1 .=  ",NULL";		
		}
		
		$query1 .= ")";
		#print "query = $query1\n";
		$query_handle = $connect->prepare($query1);
		$query_handle->execute();
		#}
		$numSampleHour ++;
		
		$query = "UPDATE monitorConfig SET numberOfHour = '$numSampleHour'";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		
		if($numSampleHour > $sampleHourLimit)
		{
			$query = "DELETE FROM monitorHourLog WHERE ip = '$_[0]' ORDER BY id LIMIT 1";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
			#print "hour limit\n";
		}
		my $ret = 1;
	}
}


sub get_daydata
{
	my $avgresult;
	my $maxstatoid;
	if($hour24 >= 24)
	{
		
		my $query1 = "INSERT INTO monitorDayLog VALUES(NULL, NULL, '$_[0]'";
		get_statisticoid($_[0]);
		$query = "SELECT numberOfStatisticOid FROM monitorDeviceList WHERE ip = '$_[0]'";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		while(@return_value = $query_handle->fetchrow_array())
		{
			$nostatoid = @return_value[0];
		}
		for(my $so=0;$so<=$nostatoid-1;$so++)
		{
			#print "ss= $nostatoid\n";
			my $so1 = $so + 1;
			$query = "SELECT statisticOid$so1 FROM monitorHourLog WHERE ip = '$_[0]' ORDER BY timeStamp DESC LIMIT 24";
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
			if($nosam != 0)
			{
				$avgresult = $sum / $nosam;
			}
			else
			{
				$avgresult = 0;
			}
			$query1 .= ",'$avgresult'";
			#}
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
			#print "i am here\n";
			$query1 .=  ",NULL";		
		}
		
		$query1 .= ")";
		#print "query = $query1\n";
		$query_handle = $connect->prepare($query1);
		$query_handle->execute();
		#}
		$numSampleDay ++;
		
		$query = "UPDATE monitorConfig SET numberOfDay = '$numSampleDay'";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		
		if($numSampleDay > $sampleDayLimit)
		{
			$query = "DELETE FROM monitorDayLog WHERE ip = '$_[0]' ORDER BY id LIMIT 1";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
			#print "hour limit\n";
		}
		my $ret = 1;
	}
}





sub get_monthdata
{
	my $avgresult;
	my $maxstatoid;
	if($day30 >= 30)
	{
		
		my $query1 = "INSERT INTO monitorMonthLog VALUES(NULL, NULL, '$_[0]'";
		get_statisticoid($_[0]);
		$query = "SELECT numberOfStatisticOid FROM monitorDeviceList WHERE ip = '$_[0]'";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		while(@return_value = $query_handle->fetchrow_array())
		{
			$nostatoid = @return_value[0];
		}
		for(my $so=0;$so<=$nostatoid-1;$so++)
		{
			#print "ss= $nostatoid\n";
			my $so1 = $so + 1;
			$query = "SELECT statisticOid$so1 FROM monitorDayLog WHERE ip = '$_[0]' ORDER BY timeStamp DESC LIMIT 30";
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
			if($nosam != 0)
			{
				$avgresult = $sum / $nosam;
			}
			else
			{
				$avgresult = 0;
			}
			$query1 .= ",'$avgresult'";
			#}
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
			#print "i am here\n";
			$query1 .=  ",NULL";		
		}
		
		$query1 .= ")";
		#print "query = $query1\n";
		$query_handle = $connect->prepare($query1);
		$query_handle->execute();
		#}
		$numSampleMonth ++;
		
		$query = "UPDATE monitorConfig SET numberOfMonth = '$numSampleMonth'";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		
		if($numSampleMonth > $sampleMonthLimit)
		{
			$query = "DELETE FROM monitorMonthLog WHERE ip = '$_[0]' ORDER BY id LIMIT 1";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
			#print "hour limit\n";
		}
		my $ret = 1;
	}
}
















sub log_error
{
	$query = "UPDATE monitorDeviceList SET statusError='Y' WHERE ip='$_[0]' ";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();

	$query = "INSERT INTO monitorErrorLog VALUES (NULL, NULL, '$_[0]', '$_[1]')";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
}

sub error_check
{
	$query = "UPDATE monitorConfig SET numberOfError = '$_[1]'";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
	
	if($noerror > $errorLimit )
	{
		$noerror = $errorLimit + 2;
		$query = "DELETE FROM monitorErrorLog WHERE ip = '$_[0]' ORDER BY id LIMIT 1";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		
	}
}

sub send_mail
{
	my $email = Email::Simple->create(
      header => [
          From    => 'temptestsmtp@gmail.com',
          To      => $_[1],
          Subject => 'Alarm',
      ],
      body => $_[0],
	);

	my $sender = Email::Send->new(
		{   mailer      => 'Gmail',
			mailer_args => [
              username => 'temptestsmtp@gmail.com',
              password => 'smtptesttemp',
          ]
		}
	);
	eval { $sender->send($email) };
	#die "Error sending email: $@" if $@;
	
	#print "in the send_mail\n";
}

sub shut_server
{
	my ($socket,$client);
	my $erflag = 0;
	my @attachedServer = ();
	my @ports = ();
	my @secretmessage = ();
	$query = "SELECT * FROM monitorAttachedServer WHERE ip = '$_[0]'";
	$query_handle = $connect->prepare($query);
	$query_handle->execute();
	my $row = 0;
	while(@return_value = $query_handle->fetchrow_array())
	{
		$attachedServer[$row] = @return_value[2];
		$row ++;
	}
	#print "\n@attachedServer\n";
	
	for(my $ser = 0 ; $ser <= @attachedServer-1 ; $ser++)
	{
		#print "$ser:'$attachedServer[$ser]'\n";
		$socket = new IO::Socket::INET(
		PeerHost => $attachedServer[$ser],
		PeerPort => $ports[$ser],
		Proto => 'tcp',
		) or $erflag = 1;
		if($erflag == 1)
		{
			print "Can not make the socket via '$attachedServer[$ser]'\n";
			$needmail = email_need($_[0]);
			my $mail = get_email($_[0]);
			if($needmail eq 'N')
			{
				send_mail("'$_[0]' can not connect to attached servers",$mail);
				$query = "UPDATE monitorDeviceList SET emailSent = 'Y', statusError = 'Y'  WHERE ip = '$_[0]'";
				$query_handle = $connect->prepare($query);
				$query_handle->execute();
			}
			$query = "INSERT INTO monitorErrorLog VALUES (NULL, NULL, '$_[0]', 'Can not connect to $attachedServer[$ser]')";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
			$noerror++;
			error_check($_[0], $noerror);
			$erflag = 0;
		}
		else
		{
			#print "else\n";
			$socket->send($secretmessage[$ser]);
			$socket->close();
		}
		
	}
	
}