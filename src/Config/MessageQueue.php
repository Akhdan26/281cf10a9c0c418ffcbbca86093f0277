<?php

namespace Src\Config;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MessageQueue
{
    private $channel;
    private $queueName = 'email_queue';

    public function __construct()
    {
        $host = $_ENV['QUEUE_HOST'];
        $port = $_ENV['QUEUE_PORT'];
        $user = $_ENV['QUEUE_USERNAME'];
        $pass = $_ENV['QUEUE_PASSWORD'];
        
        $connection = new AMQPStreamConnection($host, $port, $user, $pass);
        $this->channel = $connection->channel();

        // Declare a queue
        $this->channel->queue_declare($this->queueName, false, true, false, false);

    }

    public function enqueueMessage($message)
    {
        // Convert the message to JSON
        $jsonMessage = json_encode($message);

        // Create a new message
        $amqpMessage = new AMQPMessage($jsonMessage, ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);

        // Publish the message to the queue
        $this->channel->basic_publish($amqpMessage, '', $this->queueName);
    }

    public function processQueue()
    {
        // Define the callback function for processing messages
        $callback = function ($message) {
            $this->sendEmailFromQueue($message->body);
            $message->ack(); // Acknowledge the message
        };

        // Consume messages from the queue
        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);

        // Wait for incoming messages
        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }

    private function sendEmailFromQueue($message)
    {
        $emailData = json_decode($message, true);

        // Implement the email sending logic here
        $mailer = new PHPMailer(true);

        try {
            // Configure PHPMailer settings
            $mailer->isSMTP();
            $mailer->Host = 'sandbox.smtp.mailtrap.io';
            $mailer->SMTPAuth = true;
            $mailer->Port = 2525;
            $mailer->Username = 'd6a9b38d8c9fd0';
            $mailer->Password = '9285868eb7f938';

            $mailer->setFrom('akhdanhifzhan@levart.id', 'Akhdan Hifzhan');
            $mailer->addAddress($emailData['to']);
            $mailer->Subject = $emailData['subject'];
            $mailer->Body = $emailData['message'];

            // Send the email
            $mailer->send();

            // For demonstration, let's print the email data
            echo "Sending email to: {$emailData['to']}, Subject: {$emailData['subject']}, Message: {$emailData['message']}\n";
        } catch (Exception $e) {
            // Handle exceptions
            echo 'Message could not be sent. Mailer Error: ', $mailer->ErrorInfo;
        }

    }

    public function __destruct()
    {
        $this->channel->close();
    }
}
