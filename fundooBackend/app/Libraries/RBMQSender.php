<?php

namespace App\Libraries;

use Illuminate\Http\Request;

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RBMQSender
{
    public function sendMail($toEmail, $subject, $body)
    {
        $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        $channel->queue_declare('hello', false, false, false, false);
        $data = json_encode(array(
            "from" => "shaikfiroz838@gmail.com",
            "from_email" => "shaikfiroz838@gmail.com",
            "to_email" => $toEmail,
            "subject" => $subject,
            "message" => $body
        ));
        $msg = new AMQPMessage($data, array('delivery_mode' => 2));
        $channel->basic_publish($msg, '', 'hello');

        $obj = new RBMQReceiver();
        $obj->receiveMail();
        
        $channel->close();
        $connection->close();
        return true;
    }
}
