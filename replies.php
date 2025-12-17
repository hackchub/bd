<?php
require 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $id = $_GET['wish_id'];
    $stmt = $pdo->prepare("SELECT * FROM replies WHERE wish_id=? ORDER BY created_at ASC");
    $stmt->execute([$id]);
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO replies (wish_id,name,message) VALUES (?,?,?)");
    $stmt->execute([$d['wish_id'],$d['name'],$d['message']]);
    echo json_encode(['ok'=>true]);
}
