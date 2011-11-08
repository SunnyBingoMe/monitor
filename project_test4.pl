#! /usr/local/bin/perl

use strict;
#use warnings;
use DBI;
use DBD::mysql;
use Net::SNMP;
#use Email::Send;
#use Email::Send::Gmail;
#use Email::Simple::Creator;
use IO::Socket::INET;


##################################################MYSQL CONFIG#########################################################################


# MYSQL CONFIG VARIABLES
my $platform = "mysql";
my $port = "3306";
my $dbhost = "monitormysql.no-ip.org";
my $database = "";
my $tablename = "";
my $query;
my $query_handle;
my (@return_value);
my $dbuser = 'root';
my $dbpass ;


#open (filehandle,"u_p.bin") or die "I am unable to open";
#while(<filehandle>)
#{
 # $dbpass= unpack('n*',$_);
#}
#close(filehandle);

$dbpass = "toonnalux";

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

$query = "USE monitor";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "Unable to find ups database : $query_handle::errstr\n";


###############################################################################################################################


my (@host,$dbrow,@varbindlist,$errstatus,$interval,@statistic,@statoid,$houryes,$noerror,@threashold1,@threashold2,@threasholdoid,@email);
my (@community,@version,$needmail);
#my $OID_upsIdentModel = '1.3.6.1.2.1.33.1.1.2.0';
#my $OID_upsIdentName = '1.3.6.1.2.1.33.1.1.5.0';
#my $OID_upsIdentManufacturer = '1.3.6.1.2.1.33.1.1.1.0';

#open(filehandle,'configuration.txt') or die "can not open the file";
#foreach (<filehandle>)
#{
#	chomp;
#	$interval= $_;
#}
#close(filehandle);

my $sampleHourLimit = 6;
my $numSample;
my $numSampleHour = 0;
my $errorLimit = 10;
my  $sampleLimit;

$query = "SELECT * FROM monitorConfig";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "$query_handle::errstr\n";
while(@return_value = $query_handle->fetchrow_array())
{
	$interval = @return_value[0];
	$sampleHourLimit = @return_value[2];
	$errorLimit = @return_value[4];
}


print "in = $interval, samplehorlimit = $sampleHourLimit\n";


 
$sampleLimit = 8 / $interval;





###############################################STORING PROCESS ID##################################################################
#$query = "DELETE FROM processId";
#$query_handle = $connect->prepare($query);
#$query_handle->execute() or die "$query_handle::errstr\n";

$query = "UPDATE monitorConfig SET perlPid = '$$'";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "$query_handle::errstr\n";

###################################################################################################################################


#############################################################main##################################################################

my $hourreturn = 0;
$houryes = 0;
$noerror = 0;
my $shutflag = 0;

for($numSample=0;;$numSample++)
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
			#$query = "INSERT INTO monitorErrorLog(ip, description) VALUES ('$host[$i]', '$error')";
			#$query_handle = $connect->prepare($query);
			#$query_handle->execute();
			log_error($host[$i],$error);
			$noerror++;
			error_check($host[$i]);
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
			error_check($hostname);
		}
		if($houryes == 1)
		{
			$query = "DELETE FROM monitorSample WHERE ip = '$host[$i]' ORDER BY timeStamp LIMIT 1";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
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
	if($errstatus == 1)
	{	
		my $newinterval = $interval - 2;
		if($newinterval > 0)
		{
			sleep($newinterval);
		}
		$errstatus = 0;
	}
	else
	{
		sleep($interval)
	}
	
	
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
	$query = "SELECT emailsent FROM monitorDeviceAndOid WHERE ip = '$_[0]'";
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
		#$query = "INSERT INTO monitorErrorLog(ip, description) VALUES ('$hostname', '$err')";
		#$query_handle = $connect->prepare($query);
		#$query_handle->execute();	
		log_error($hostname,$err);
		$errstatus = 1;
		$noerror++;
		error_check($hostname);
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
				}
				if($tr1[0] == 'min')
				{
					#print "min:1, res = $varresult, tr = $tr1[1] \n";
					if($varresult < $tr1[1])
					{
						if($tr1[2] eq 'email')
						{
							print " min:1:message = $tr1[3], mail = $mail , need = $needmail\n";
							$needmail = email_need($iphost);
							if($needmail eq 'N')
							{
								send_mail($tr1[3],$mail);
								$query = "UPDATE monitorDeviceAndOid SET emailSent = 'Y' WHERE ip = '$iphost'";
								$query_handle = $connect->prepare($query);
								$query_handle->execute();	
								my $mail = get_email($iphost);
								print "n = $needmail, Email sent.\n";
							}
						}
						else
						{
							print "min:1:shut = $iphost\n";
							$shutflag = 1;
							shut_server($iphost);
						}
					}
					else
					{
						$query = "UPDATE monitorDeviceAndOid SET emailSent = 'N' WHERE ip = '$iphost'";
						$query_handle = $connect->prepare($query);
						$query_handle->execute();
						$shutflag = 0;
					}
				}
				else
				{
					print "max:1\n";
					if($varresult > $tr1[1])
					{
						if($tr1[2] eq 'email')
						{
							print " max:1:message = $tr1[3], mail = $mail\n";
							if($needmail eq 'N')
							{
								send_mail($tr1[3],$mail);
								$query = "UPDATE monitorDeviceAndOid SET emailSent = 'Y' WHERE ip = '$iphost'";
								$query_handle = $connect->prepare($query);
								$query_handle->execute();	
								print "Email sent.";
							}
						}
						else
						{
							print "max:1:shut = $iphost\n";
							$shutflag = 1;
							shut_server($iphost);
						}
					}
					else
					{
						$query = "UPDATE monitorDeviceAndOid SET emailSent = 'N' WHERE ip = '$iphost'";
						$query_handle = $connect->prepare($query);
						$query_handle->execute();
						$shutflag = 0;
					}
					
				}
				if($threashold2[$x] ne '')
				{
					@tr2 = split(/:/, $threashold1[$x]);
				}
				if($tr2[0] == 'min')
				{
					if($varresult < $tr2[1])
					{
						if($tr2[2] eq 'email')
						{
							print " min:2:message = $tr2[3], mail = $mail\n";
							if($needmail eq 'N')
							{
								send_mail($tr2[3],$mail);
								$query = "UPDATE monitorDeviceAndOid SET emailSent = 'Y' WHERE ip = '$iphost'";
								$query_handle = $connect->prepare($query);
								$query_handle->execute();	
								print "Email sent.";
							}
						}
						else
						{
							print "min:2:shut = $iphost\n";
							$shutflag = 1;
							shut_server($iphost);
						}
					}
					else
					{
						$query = "UPDATE monitorDeviceAndOid SET emailSent = 'N' WHERE ip = '$iphost'";
						$query_handle = $connect->prepare($query);
						$query_handle->execute();
						$shutflag = 0;
					}
				}
				else
				{
					if($varresult > $tr2[1])
					{
						if($tr2[2] eq 'email')
						{
							print " max:2:message = $tr2[3], mail = $mail\n";
							if($needmail eq 'N')
							{
								send_mail($tr2[3],$mail);
								$query = "UPDATE monitorDeviceAndOid SET emailSent = 'Y' WHERE ip = '$iphost'";
								$query_handle = $connect->prepare($query);
								$query_handle->execute();	
								print "Email sent.";
							}
						}
						else
						{
							print "max:2:shut = $iphost\n";
							$shutflag = 1;
							shut_server($iphost);
						}
					}
					else
					{
						$query = "UPDATE monitorDeviceAndOid SET emailSent = 'N' WHERE ip = '$iphost'";
						$query_handle = $connect->prepare($query);
						$query_handle->execute();
						$shutflag = 0;
					}
				}
				
				#print "ip = $iphost , r =  $r\n";
			}
		}
	}	
		
		
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
			#print "i am here\n";
			$query1 .=  ",NULL";		
		}
		
		$query1 .= ")";
		#print "query = $query1\n";
		$query_handle = $connect->prepare($query1);
		$query_handle->execute();
		#}
		$numSampleHour ++;
		if($numSampleHour > $sampleHourLimit)
		{
			$query = "DELETE FROM monitorHourLog WHERE ip = '$_[0]' ORDER BY timeStamp LIMIT 1";
			$query_handle = $connect->prepare($query);
			$query_handle->execute();
			#print "hour limit\n";
		}
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
	if($noerror > $errorLimit )
	{
		$noerror = $errorLimit + 2;
		$query = "DELETE FROM monitorErrorLog WHERE ip = '$_[0]' ORDER BY timeStamp LIMIT 1";
		$query_handle = $connect->prepare($query);
		$query_handle->execute();
		
	}
}

sub send_mail
{
	#my $email = Email::Simple->create(
    #  header => [
    #      From    => 'temptestsmtp@gmail.com',
    #      To      => $_[1],
    #      Subject => 'Alarm',
    #  ],
    #  body => $_[0],
	#);

	#my $sender = Email::Send->new(
	#	{   mailer      => 'Gmail',
	#		mailer_args => [
    #          username => 'temptestsmtp@gmail.com',
    #          password => 'smtptesttemp',
    #      ]
	#	}
	#);
	#eval { $sender->send($email) };
	#die "Error sending email: $@" if $@;
	
	print "in the send_mail\n";
}

sub shut_server
{
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
	
	my $data;
	my ($socket,$client);

	
	for(my $ser = 0 ; $ser <= @attachedServer-1 ; $ser++)
	{
		$socket = new IO::Socket::INET(
		PeerHost => '$attachedServer[$ser]',
		PeerPort => '$ports[$ser]',
		Proto => 'tcp',
		) or print "Can not make the socket...";
		$socket->send($secretmessage[$ser]);
		$socket->close();
	}
	
}