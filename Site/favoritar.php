<?php
session_start();
require 'config.php';

$jogo_id = intval($_POST['jogo_id']);
$usuario_id = intval($_POST['usuario_id']);

if (!$jogo_id || !$usuario_id) {
    echo json_encode(['status' => 'erro']);
    exit;
}

// Verifica se já está favoritado
$check = $mysqli->prepare("SELECT 1 FROM usuario_favorita_jogo WHERE id_usuario = ? AND id_jogo = ?");
$check->bind_param("ii", $usuario_id, $jogo_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    // Já favoritado, remove
    $delete = $mysqli->prepare("DELETE FROM usuario_favorita_jogo WHERE id_usuario = ? AND id_jogo = ?");
    $delete->bind_param("ii", $usuario_id, $jogo_id);
    $delete->execute();
    echo json_encode(['status' => 'desfavoritado']);
} else {
    // Não favoritado, insere
    $insert = $mysqli->prepare("INSERT INTO usuario_favorita_jogo (id_usuario, id_jogo) VALUES (?, ?)");
    $insert->bind_param("ii", $usuario_id, $jogo_id);
    $insert->execute();
    echo json_encode(['status' => 'favoritado']);
}

