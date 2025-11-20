<?php
require_once "config.php";
session_start();
header('Content-Type: application/json');

if (!isset($_POST['id'])) {
	echo json_encode(["success" => false, "message" => "ID não enviado."]);
	exit;
}

$id = intval($_POST['id']);

// Verifica se o jogo existe e obtém o dono
$check = $mysqli->prepare("SELECT id, id_usuario FROM jogo WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$res = $check->get_result();

if ($res->num_rows === 0) {
	echo json_encode(["success" => false, "message" => "Jogo não encontrado."]);
	$check->close();
	exit;
}

$row = $res->fetch_assoc();
$owner_id = $row['id_usuario'];
$check->close();

// Verifica permissão: só o dono do jogo ou administrador pode excluir
$current_user_id = $_SESSION['id_usuario'] ?? null;
$allowed = false;
if ($current_user_id) {
	if ($current_user_id == $owner_id) {
		$allowed = true;
	} else {
		// checa se é administrador
		$u = $mysqli->prepare("SELECT tipo FROM usuario WHERE id = ?");
		$u->bind_param("i", $current_user_id);
		$u->execute();
		$ru = $u->get_result();
		$tipo = $ru->fetch_assoc()['tipo'] ?? '';
		$u->close();
		if ($tipo === 'administrador') $allowed = true;
	}
}

if (! $allowed) {
	echo json_encode(["success" => false, "message" => "Permissão negada."]);
	exit;
}

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