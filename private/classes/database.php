<?php

require_once __DIR__ . '/../../config/error_logging.php';
require_once __DIR__ . '/../../.env/credentials.php';

class Database
{
  private $conn;

  public function __construct()
  {
    $this->conn = $this->connect();
  }

  private function connect()
  {
    $dsn = "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8mb4";

    try {
      $conn = new PDO($dsn, DBUSER, DBPASS);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      return $conn;
    } catch (PDOException $e) {
      error_log('Database connection failed: ' . $e->getMessage());
      http_response_code(500);
      exit('Database connection failed. Please try again later.');
    }
  }

  public function execute($query, $params = [])
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt;
  }

  public function fetchAll($query, $params = [])
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll() ?: [];
  }

  public function fetchOne($query, $params = [])
  {
    $stmt = $this->conn->prepare($query);
    $stmt->execute($params);
    return $stmt->fetch() ?: null;
  }

  public function getUserID($userID)
  {
    $sql = 'SELECT users.user_id FROM users WHERE user_id = :user_id LIMIT 1';

    return $this->fetchOne($sql, [':user_id' => $userID]);
  }
}
