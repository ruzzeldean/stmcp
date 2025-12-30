<?php
require_once __DIR__ . '/error_logging.php';
include __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

class Database
{
  private $conn;

  public function __construct()
  {
    if (!isset($_ENV['DB_HOST'])) {
      $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
      $dotenv->load();
    }

    $this->conn = $this->connect();
  }

  private function connect()
  {
    $dbHost = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_NAME'];
    $dbUsername = $_ENV['DB_USER'];
    $dbPassword = $_ENV['DB_PASS'];
    $dbPort = $_ENV['DB_PORT'];

    $dsn = "mysql:host={$dbHost};dbname={$dbName};port={$dbPort};charset=utf8mb4";

    try {
      $conn = new PDO($dsn, $dbUsername, $dbPassword);
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

  public function getConnection()
  {
    return $this->conn;
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

  public function lastInsertId()
  {
    return $this->conn->lastInsertId();
  }

  public function beginTransaction()
  {
    return $this->conn->beginTransaction();
  }

  public function inTransaction()
  {
    return $this->conn->inTransaction();
  }

  public function commit()
  {
    return $this->conn->commit();
  }

  public function rollBack()
  {
    return $this->conn->rollBack();
  }

  public function getUserID($userID)
  {
    $sql = 'SELECT users.user_id FROM users WHERE user_id = :user_id LIMIT 1';

    return $this->fetchOne($sql, [':user_id' => $userID]);
  }

  public function getPostById($postId)
  {
    $sql = "SELECT
              post_id, title, category, content, image_path, status, created_at
            FROM posts
            WHERE post_id = :post_id AND status = 'Published'
            LIMIT 1";

    return $this->fetchOne($sql, ['post_id' => $postId]);
  }
}
