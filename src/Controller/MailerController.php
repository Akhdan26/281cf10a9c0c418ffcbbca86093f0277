<?php
namespace Src\Controller;

use Src\APIGateway\MailTableGateway;
use Src\Config\MessageQueue;

class mailerController {

    private $db;
    private $requestMethod;

    private $mailGateway;

    public function __construct($db, $requestMethod)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;

        $this->mailGateway = new MailTableGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getAllEmails();
                break;
            case 'POST':
                $response = $this->storeAndSendEmail();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllEmails()
    {
        $result = $this->mailGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function storeAndSendEmail()
    {
        $input = $_POST;
        if (! $this->validateEmail($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->enqueueEmail($input);
        $result = $this->mailGateway->insert($input);

        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function validateEmail($input)
    {
        if (! isset($input['to_email'])) {
            return false;
        }
        if (! isset($input['subject'])) {
            return false;
        }
        if (! isset($input['message'])) {
            return false;
        }
        return true;
    }

    private function enqueueEmail($input)
    {
        $messageData = [
            'to' => $input['to_email'],
            'subject' => $input['subject'],
            'message' => $input['message']
        ];

        $messageQueue = new MessageQueue();
        $messageQueue->enqueueMessage($messageData);
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}