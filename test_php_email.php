<?php

       require_once "Mail.php";
        
        print "sadfsdf";
        $from = "temptestsmtp <temptestsmtp@gmail.com>";
        $to = "Sunny <SunnyBoyMe@gmail.com>";
        $subject = "Hi!";
        $body = "Hi,\n\nHow are you ,dude?";

        $host = "ssl://smtp.gmail.com";
        $port = "465";
        $username = "<temptestsmtp.gmail.com>";
        $password = "smtptesttemp";

        $headers = array ('From' => $from,
          'To' => $to,
          'Subject' => $subject);
        $smtp = Mail::factory('smtp',
          array ('host' => $host,
            'port' => $port,
            'auth' => true,
            'username' => $username,
            'password' => $password));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) {
          echo("<p>" . $mail->getMessage() . "</p>");
         } else {
          echo("<p>Message successfully sent!</p>");
         }

    ?>