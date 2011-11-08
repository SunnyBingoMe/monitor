#i/usr/perl
#tcpclinet
use strict 'vars';
use IO::Socket::INET;


my $data;
my ($socket,$client);
my $shutdown_sunny = 0;
while(1){
	$shutdown_sunny++;
	print $shutdown_sunny;
	print "\n";
	if($shutdown_sunny >= 20){exit;}
	system('ssh root@monitormysql.no-ip.org shutdown -P now');
}
print $shutdown_sunny;

$socket = new IO::Socket::INET(
PeerHost => '192.168.174.1',
PeerPort => '9999',
Proto => 'tcp',
) or die "Error in sockey creation: $!\n";

print "TCP Connection Success.\n";


$data = "shutdown";

#print $socket "$data\n";
$socket->send($data);

$socket->close();






