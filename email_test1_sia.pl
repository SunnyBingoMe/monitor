#!/usr/bin/perl
use strict;
use warnings;
use Email::Send;
use Email::Send::Gmail;
use Email::Simple::Creator;

my $email = Email::Simple->create(
    header => [
        From    => 'temptestsmtp@gmail.com',
        To      => 'sunnybingome@gmail.com',
        Subject => 'Server down',
    ],
    body => 'The server is down. Start panicing.',
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
die "Error sending email: $@" if $@;