#i/usr/perl
#tcpclinet
use strict 'vars';
use IO::Socket::INET;


my $data;
my ($socket,$client);

$socket = new IO::Socket::INET(
PeerHost => 'laptop.sunnyboy.me',#'127.0.0.1',
PeerPort => '9999',
Proto => 'tcp',
) or die "Error in sockey creation: $!\n";

print "TCP Connection Success.\n";


$data = "shutdown";

#print $socket "$data\n";
$socket->send($data);

$socket->close();






