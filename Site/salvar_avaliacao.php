<?php
session_start();
require_once("config.php");

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["status" => "erro", "mensagem" => "Você precisa estar logado para avaliar."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$id_jogo = isset($_POST['id_jogo']) ? (int) $_POST['id_jogo'] : 0;
$nota = isset($_POST['nota']) ? floatval($_POST['nota']) : 0;

if ($id_jogo === 0 || $nota <= 0 || $nota > 5) {
    http_response_code(400);
    echo json_encode(["status" => "erro", "mensagem" => "Dados inválidos."]);
    exit;
}

try {
    // Verifica se o usuário já avaliou
    $stmt = $mysqli->prepare("SELECT id FROM avaliacao WHERE id_usuario = ? AND id_jogo = ?");
    $stmt->bind_param("ii", $user_id, $id_jogo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        $update = $mysqli->prepare("UPDATE avaliacao SET nota = ?, data_avaliacao = NOW() WHERE id_usuario = ? AND id_jogo = ?");
        $update->bind_param("dii", $nota, $user_id, $id_jogo);
        $update->execute();
        $update->close();
    } else {
        $stmt->close();
        $insert = $mysqli->prepare("INSERT INTO avaliacao (nota, data_avaliacao, id_usuario, id_jogo) VALUES (?, NOW(), ?, ?)");
        $insert->bind_param("dii", $nota, $user_id, $id_jogo);
        $insert->execute();
        $insert->close();
    }

    echo json_encode(["status" => "ok", "mensagem" => "Avaliação salva com sucesso."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar a avaliação."]);
}
