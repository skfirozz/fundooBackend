<?php

namespace App\Libraries;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class RBMQReceiver
{
    public function receiveMail()
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);

        $callback = function ($msg) {

            $data = json_decode($msg->body, true);

            $from = $data['from'];
            $from_email = $data['from_email'];
            $to_email = $data['to_email'];
            $subject = $data['subject'];
            $message = $data['message'];

            $transport = (new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
                ->setUsername('shaikfiroz838@gmail.com')
                ->setPassword('firoz111');

            $mailer = new \Swift_Mailer($transport);

            $message = (new \Swift_Message($subject))
                ->setFrom([$data['from'] => 'shaik firoz'])
                ->setTo([$to_email])
                ->setBody($message);

            $result = $mailer->send($message);

            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $channel->basic_consume('hello', '', false, true, false, false, $callback);
        while (count($channel->callbacks)) {
            $channel->wait();
            break;
        }

        // $channel->close();
        // $connection->close();   

    }
}