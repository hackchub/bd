<?php
require 'db.php';
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $status = $_GET['status'] ?? 'approved';
    if ($status === 'all') {
        $stmt = $pdo->query("SELECT * FROM wishes ORDER BY created_at DESC");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM wishes WHERE status=? ORDER BY created_at DESC");
        $stmt->execute([$status]);
    }
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}

if ($method === 'POST') {
    $d = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare("INSERT INTO wishes (name,message,anonymous) VALUES (?,?,?)");
    $stmt->execute([$d['name'],$d['message'],$d['anonymous']]);
    echo json_encode(['ok'=>true]);
}

if ($method === 'DELETE') {
    $id = $_GET['id'];
    $pdo->prepare("DELETE FROM wishes WHERE id=?")->execute([$id]);
    echo json_encode(['ok'=>true]);
}

// Extra: Approve wish
if ($method === 'PUT') {
    parse_str(file_get_contents("php://input"), $d);
    $stmt = $pdo->prepare("UPDATE wishes SET status='approved' WHERE id=?");
    $stmt->execute([$d['id']]);
    echo json_encode(['ok'=>true]);
}

// Extra: Like wish
if ($method === 'PATCH') {
    parse_str(file_get_contents("php://input"), $d);
    $stmt = $pdo->prepare("UPDATE wishes SET likes = likes + 1 WHERE id=?");
    $stmt->execute([$d['id']]);
    echo json_encode(['ok'=>true]);
}
