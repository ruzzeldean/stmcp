<?php

function getPosts(PDO $conn, string $category, int $limit, int $page = 1)
{
  $offset = ($page - 1) * $limit;

  try {
    $sql = 'SELECT post_id, title, category, image_path, created_at
            FROM posts
            WHERE category = :category AND status = :status
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset';

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
    $stmt->bindValue(':status', 'Published', PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
  } catch (Throwable $ex) {
    error_log("Error loading upcoming posts ({$category}): " . $ex->getMessage());
    return [];
  }
}

function getpostCount(PDO $conn, string $category)
{
  try {
    $sql = 'SELECT COUNT(*) FROM posts WHERE category = :category AND status = :status';
    $stmt = $conn->prepare($sql);
    $stmt->execute(['category' => $category, 'status' => 'Published']);

    return (int) $stmt->fetchColumn();
  } catch (Throwable $ex) {
    error_log("Error count upcoming posts ({$category}): " . $ex->getMessage());
    return 0;
  }
}

function getPostByID(PDO $conn, int $id)
{
  try {
    $stmt = $conn->prepare('SELECT * FROM posts WHERE post_id = :post_id AND status = :status LIMIT 1');
    $stmt->execute(['post_id' => $id, 'status' => 'Published']);

    return $stmt->fetch() ?: null;
  } catch (Throwable $ex) {
    error_log('Error retrieving post (view post page): ' . $ex->getMessage());
    return null;
  }
}

function getLatestPosts(PDO $conn, int $limit)
{
  $limit = max(1, $limit);
  try {
    $sql = 'SELECT post_id, title, category, image_path, created_at
            FROM posts
            WHERE status = :status
            ORDER BY created_at DESC
            LIMIT :limit';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':status', 'Published', PDO::PARAM_STR);
    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
  } catch (Throwable $ex) {
    error_log("Error loading latest posts (view post page): " . $ex->getMessage());
    return [];
  }
}