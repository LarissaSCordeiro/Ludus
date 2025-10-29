<?php
session_start();
require_once("config.php");

header('Content-Type: application/json');

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(["status" => "erro", "mensagem" => "Você precisa estar logado para avaliar."]);
    exit;
}

$user_id = $_SESSION['id_usuario'];
$id_jogo = isset($_POST['id_jogo']) ? (int) $_POST['id_jogo'] : 0;
$nota = isset($_POST['nota']) ? floatval($_POST['nota']) : 0;

// Validação básica
if ($id_jogo === 0 || $nota < 0 || $nota > 5) { // permite 0
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
        // Atualiza avaliação existente
        $stmt->close();
        $update = $mysqli->prepare("UPDATE avaliacao SET nota = ?, data_avaliacao = NOW() WHERE id_usuario = ? AND id_jogo = ?");
        $update->bind_param("dii", $nota, $user_id, $id_jogo);
        $update->execute();
        $update->close();
    } else {
        // Insere nova avaliação
        $stmt->close();
        $insert = $mysqli->prepare("INSERT INTO avaliacao (nota, data_avaliacao, id_usuario, id_jogo) VALUES (?, NOW(), ?, ?)");
        $insert->bind_param("dii", $nota, $user_id, $id_jogo);
        $insert->execute();
        $insert->close();
    }

    // Calcula a nova média da comunidade
    $media_stmt = $mysqli->prepare("SELECT AVG(nota) AS media_comunidade FROM avaliacao WHERE id_jogo = ?");
    $media_stmt->bind_param("i", $id_jogo);
    $media_stmt->execute();
    $media_result = $media_stmt->get_result();
    $media_row = $media_result->fetch_assoc();
    $media_comunidade = isset($media_row['media_comunidade']) ? round(floatval($media_row['media_comunidade']), 1) : 0;
    $media_stmt->close();

    // Calcula o total de avaliações
    $total_stmt = $mysqli->prepare("SELECT COUNT(*) AS total_avaliacoes FROM avaliacao WHERE id_jogo = ?");
    $total_stmt->bind_param("i", $id_jogo);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_avaliacoes = isset($total_row['total_avaliacoes']) ? intval($total_row['total_avaliacoes']) : 0;
    $total_stmt->close();

    // Retorna JSON com dados atualizados
    echo json_encode([
        "status" => "ok",
        "mensagem" => "Avaliação salva com sucesso.",
        "media_comunidade" => $media_comunidade,
        "total_avaliacoes" => $total_avaliacoes
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao salvar a avaliação."]);
}

$mysqli->close();