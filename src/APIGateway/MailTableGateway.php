<?php
namespace Src\APIGateway;

use Src\Config\Database;

class MailTableGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        try {
            $statement = pg_query($this->db, 'SELECT * FROM emails');
            
            if (!$statement) {
                throw new \Exception("Error executing the query: " . pg_last_error($this->db));
            }
            
            $result = pg_fetch_all($statement);
            return $result;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function insert($input)
    {
        try {
            $query = "INSERT INTO emails (to_email, subject, message) VALUES ($1, $2, $3)";
            $result = pg_query_params(Database::getConnection(), $query, $input);
            
            if ($result) {
                return ['status' => 'success', 'message' => 'Email stored and will be sent shortly.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to store email.'];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}