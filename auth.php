<?php
require 'db.php';
$data = json_decode(file_get_contents('php://input'), true);

$stmt = $pdo->prepare("SELECT id FROM admins WHERE pin_hash = SHA2(?,256)");
$stmt->execute([$data['pin']]);

echo json_encode(['ok' => (bool)$stmt->fetch()]);
