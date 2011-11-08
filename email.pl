use Net::SMTP;
$smtp = Net::SMTP->new("mailhost.gmail.com",
					Hello => 'my.host.com',
					Timeout => 60);
# Simple Email Function
# ($to, $from, $subject, $message)

sendEmail("rajyalaxmi.malneedy\@gmail.com",
		"test", 
		"Simple email.", 
		"This is a test of the email function.");
sub sendEmail
{
	my ($to, $from, $subject, $message) = @_;
	my $sendmail = '/usr/lib/sendmail';
	open(MAIL, "|$sendmail -oi -t");
	print MAIL "From: $from\n";
	print MAIL "To: $to\n";
	print MAIL "Subject: $subject\n\n";
	print MAIL "$message\n";
	close(MAIL);
}

exit;