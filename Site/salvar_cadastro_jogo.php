<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso inválido.");
}

// ===== Função para limpar dados =====
function limpar($valor) {
    return htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8');
}

// ===== Pegando dados do formulário =====
$nome = limpar($_POST['nome_jogo'] ?? '');
$estudio = limpar($_POST['estudio'] ?? '');
$desenvolvedor = limpar($_POST['desenvolvedor'] ?? '');
$descricao = limpar($_POST['descricao'] ?? '');
$data_lancamento = $_POST['data_lancamento'] ?? null;
$id_usuario = $_SESSION['id_usuario'] ?? null;

$generos = $_POST['generos'] ?? [];
$plataformas = $_POST['plataformas'] ?? [];

if (!$nome || !$id_usuario) {
    die("Campos obrigatórios faltando.");
}

// ===== Upload da imagem =====
$imagem_nome = 'img/jogos/default.png'; // Default
if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
    $arquivoTmp = $_FILES['capa']['tmp_name'];
    $nomeArquivo = time() . '_' . basename($_FILES['capa']['name']);
    $destino = 'uploads/' . $nomeArquivo;

    $extensao = strtolower(pathinfo($destino, PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extensao, $permitidos)) {
        die('Formato de arquivo inválido.');
    }

    if (!move_uploaded_file($arquivoTmp, $destino)) {
        die('Erro ao enviar a imagem.');
    }

    $imagem_nome = $destino;
}

// ===== Inserção na tabela jogo =====
$stmt = $mysqli->prepare("
    INSERT INTO jogo (nome, estudio, desenvolvedor, descricao, imagem, data_lancamento, id_usuario)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("ssssssi", $nome, $estudio, $desenvolvedor, $descricao, $imagem_nome, $data_lancamento, $id_usuario);

if (!$stmt->execute()) {
    die("Erro ao salvar jogo: " . $stmt->error);
}

$id_jogo = $stmt->insert_id;

// ===== Inserção na tabela jogo_possui_genero =====
if (!empty($generos)) {
    $stmtGenero = $mysqli->prepare("INSERT INTO jogo_possui_genero (id_jogo, id_genero) VALUES (?, ?)");
    foreach ($generos as $id_genero) {
        $id_genero = (int)$id_genero;
        $stmtGenero->bind_param("ii", $id_jogo, $id_genero);
        $stmtGenero->execute();
    }
}

// ===== Inserção na tabela jogo_possui_plataforma =====
if (!empty($plataformas)) {
    $stmtPlataforma = $mysqli->prepare("INSERT INTO jogo_possui_plataforma (id_jogo, id_plataforma) VALUES (?, ?)");
    foreach ($plataformas as $id_plataforma) {
        $id_plataforma = (int)$id_plataforma;
        $stmtPlataforma->bind_param("ii", $id_jogo, $id_plataforma);
        $stmtPlataforma->execute();
    }
}

$_SESSION['msg_sucesso'] = "Jogo cadastrado com sucesso!";
header('Location: cadastro_jogo.php');
exit;
?>
