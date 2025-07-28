<?php
require_once __DIR__ . '/../../../config/connection.php';

function getUpcomingPosts($page = 1, $limit = 2)
{
  global $conn;

  $offset = ($page - 1) * $limit;

  try {
    $sql = 'SELECT post_id, title, category, image_path, content, created_at
            FROM posts
            WHERE category = :category AND status = :status
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset';

    $loadUpcoming = $conn->prepare($sql);
    $loadUpcoming->bindValue(':category', 'Upcoming', PDO::PARAM_STR);
    $loadUpcoming->bindValue(':status', 'Published', PDO::PARAM_STR);
    $loadUpcoming->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $loadUpcoming->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $loadUpcoming->execute();

    $upcomingPosts = $loadUpcoming->fetchAll();

    return $upcomingPosts;
  } catch (Throwable $ex) {
    error_log('Error loading upcoming posts: ' . $ex->getMessage());
    return [];
  }
}

function getUpcomingPostCount()
{
  global $conn;

  try {
    $sql = 'SELECT COUNT(*) FROM posts WHERE category = :category AND status = :status';
    $countStmt = $conn->prepare($sql);
    $countStmt->execute(['category' => 'Upcoming', 'status' => 'Published']);

    return (int) $countStmt->fetchColumn();
  } catch (Throwable $ex) {
    error_log('Error count upcoming posts: ' . $ex->getMessage());
    return 0;
  }
}
