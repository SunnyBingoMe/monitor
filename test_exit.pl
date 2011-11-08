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


$query = "UPDATE monitorConfig SET perlPid = '$$' ";
$query_handle = $connect->prepare($query);
$query_handle->execute() or die "Unable update perl pid\n";

sleep(15);
exit(0);