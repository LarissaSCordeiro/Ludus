<?php
require_once "config.php";
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
echo json_encode(["success" => false, "message" => "ID não enviado."]);
exit;
}

$id = intval($_POST['id']);

// Verifica se o jogo existe
$check = $mysqli->prepare("SELECT id FROM jogo WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
echo json_encode(["success" => false, "message" => "Jogo não encontrado."]);
$check->close();
exit;
}
$check->close();

try {
$mysqli->begin_transaction();

$stmt1 = $mysqli->prepare("DELETE FROM jogo_possui_genero WHERE id_jogo = ?");
$stmt1->bind_param("i", $id);
$stmt1->execute();
$stmt1->close();

$stmt2 = $mysqli->prepare("DELETE FROM jogo_possui_plataforma WHERE id_jogo = ?");
$stmt2->bind_param("i", $id);
$stmt2->execute();
$stmt2->close();

$stmt3 = $mysqli->prepare("DELETE FROM jogo WHERE id = ?");
$stmt3->bind_param("i", $id);
$stmt3->execute();
$stmt3->close();

$mysqli->commit();

echo json_encode(["success" => true]);
} catch (Exception $e) {
$mysqli->rollback();
echo json_encode(["success" => false, "message" => "Erro ao excluir."]);
}