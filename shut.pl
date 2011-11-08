#i/usr/perl
#TCP Server

use strict 'vars';
use IO::Socket::INET;


my ($socket,$client);
my ($peeraddress,$peerport);
my $data;

#print "'$ARGV[0]'\n";

$socket = new IO::Socket::INET(
LocalHost => '127.0.0.1',
LocalPort => $ARGV[0],
Proto => 'tcp',
Listen => 5,
Reuse => 1
) or die "Error in socket creation : $!\n";

print "Server waiting for client connection on port '$ARGV[0]'...\n";


while(1)
{
$client = $socket->accept();

#$peeraddress = $client -> peerhost();
#$peerport = $client -> peerport();

#print "Accepted new connection from : $peeraddress,$peerport\n";

$data = <$client>;
#$client->recv($data,1024);

print "Received from client : $data\n";
if($data eq $ARGV[1])
{

 print "shutting down\n";
 system('shutdown -P now');

}

#sleep(1);

}

$socket->close();