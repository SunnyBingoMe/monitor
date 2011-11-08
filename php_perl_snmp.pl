use Net::SNMP;

###########################  Author : BinSun@mail.com 2011-04-22 ###################
$sayOk = 0; $debug = 1; $dDebug = 1; $debugOk = 0; $dDebugOk = 0;
sub say		{foreach (@_){print "$_<br/>\n";}}
sub sayOk	{if($sayOk){say @_;}}	sub endl{print "\n";}
sub debug	{if($debug){say @_;}}	sub debugOk	{if($debugOk){say @_;}}
sub dDebug	{if($dDebug){say @_;}}	sub dDebugOk{if($dDebugOk){say @_;}}
####################################################################################

$i = 0;
@gHostList = ();

sub getHostList
{
	debugOk "ok: getHostList[]=@_";
	my $tigetHostList = 0;
	my @hostRange = ((255,255),(255,255),(255,255),(255,255));
	my @weight = (1,2,4,8, 16,32,64,128);
	my $sNetworkIp = '0.0.0.0';
	my $iNetworkMaskDecimalNr = 32;
	my @networkIpSplited = (0,0,0,0);
	my $remainZeroCount = 0;


	($sNetworkIp, $iNetworkMaskDecimalNr) = split("/",$_[0]);
	@networkIpSplited = split(/\./,$sNetworkIp);
	@hostRange = ([$networkIpSplited[0],$networkIpSplited[0]],
				[$networkIpSplited[1],$networkIpSplited[1]],
				[$networkIpSplited[2],$networkIpSplited[2]],
				[$networkIpSplited[3],$networkIpSplited[3]]);
	if ($iNetworkMaskDecimalNr >= 24){
		debugOk "iNetworkMaskDecimalNr >= 24";
		$remainZeroCount = 32 - $iNetworkMaskDecimalNr;
		for ($tigetHostList = $remainZeroCount;
			$tigetHostList > 0;
			$tigetHostList--){
			$hostRange[3][1] += $weight[$tigetHostList - 1];
			debugOk "hostRange[3][1]=$hostRange[3][1]";
		}
	}else {
		say "ERR: mask would be a decimal number lager than 24";
		exit;
	}
	debugOk @hostRange;
	debugOk "hostRange[2][1]=$hostRange[2][1]";
	
	#return @hostRange;
	
	if ($iNetworkMaskDecimalNr == 32){
		push(@gHostList, $hostRange[0][0].".".$hostRange[1][0].".".$hostRange[2][0].".".$hostRange[3][0]);
		return @gHostList;
	}
	#for (; $hostRange[0][0] <= $hostRange[0][1]; $hostRange[0][0]++){
	for (; $hostRange[1][0] <= $hostRange[1][1]; $hostRange[1][0]++){
		for (; $hostRange[2][0] <= $hostRange[2][1]; $hostRange[2][0]++){
			for (; $hostRange[3][0] <= $hostRange[3][1]; $hostRange[3][0]++){
				push(@gHostList, $hostRange[0][0].'.'.$hostRange[1][0].'.'.$hostRange[2][0].'.'.$hostRange[3][0]);
			}
			$hostRange[3][0] = 0;
		}
		$hostRange[2][0] = 0;
	}
	shift(@gHostList);
	pop(@gHostList);
	debugOk "ok: gHostList=\n"."@gHostList";
	
}

if ($#ARGV == 2){
	$sNetworkIpAndMask = $ARGV[0];
	$community = $ARGV[1];
	$oid = $ARGV[2];
	debugOk "ARGV[0]=$ARGV[0]";
	debugOk "ARGV[1]=$ARGV[1]";
}else {
	say "\nERR: you could use \"<NetworkIP/Mask> <Community> <oid>\" as the command line arguments,
and the NetworkIP should NOT be one hostIp.";
	exit;
}

getHostList($sNetworkIpAndMask);

#$sysDescrOid  = '1.3.6.1.2.1.1.1.0';
#$sysUpTimeOid = '1.3.6.1.2.1.1.3.0';
#$ifNumberOid  = '1.3.6.1.2.1.2.1.0';

#@oidList = ("$sysDescrOid","$sysUpTimeOid","$ifNumberOid");
@oidList = ("$oid");

#say "\n\thost IP \tsystem Description \tUpTime \t# of Interfaces\n";
my $tHostIndex = 1;
foreach $host (@gHostList){
	($session,$error)= Net::SNMP->session(
			-hostname	=>	$host,
			-community	=>	$community, 
			-timeout	=>	1,
			-version	=>	'2c',
			-translate	=>	0);
	if (defined($session)){
		$result=$session->get_request(-varbindlist => \@oidList);
		if(defined($result)){
			say "$host : \t$result->{$oid}";
		}else {
			say "$host : ERR: cannot get reply";
		}
		$tHostIndex++;
	}else {
		say "ERR: cannot create session:$error.";
	}
}
$session->close;
exit;
