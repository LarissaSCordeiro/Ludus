<?php
session_start();
require_once("config.php");

// Garante que o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Pega os dados do formulário
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$senha_atual = $_POST['senha_atual'];
$nova_senha = $_POST['nova_senha'];
$confirmar_senha = $_POST['confirmar_senha'];

// Verifica se usuário existe e pega senha atual
$stmt = $mysqli->prepare("SELECT senha, foto_perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

if (!$usuario) {
    header("Location: editar_perfil.php?erro=usuario_nao_encontrado");
    exit();
}

// Confere senha atual
if (!password_verify($senha_atual, $usuario['senha'])) {
    header("Location: editar_perfil.php?erro=senha_incorreta");
    exit();
}

// Upload da foto de perfil
$foto_perfil = $usuario['foto_perfil']; // mantém a existente por padrão
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $novo_nome = "user_" . $id_usuario . "." . $ext;
    $caminho = "img/usuarios/" . $novo_nome;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminho)) {
        $foto_perfil = $caminho;
    }
}

// Atualiza senha se preenchida
if (!empty($nova_senha)) {
    if ($nova_senha !== $confirmar_senha) {
        header("Location: editar_perfil.php?erro=senha_nao_coincide");
        exit();
    }
    $hash = password_hash($nova_senha, PASSWORD_BCRYPT);
    $update = $mysqli->prepare("UPDATE usuario SET nome=?, email=?, senha=?, foto_perfil=? WHERE id=?");
    $update->bind_param("ssssi", $nome, $email, $hash, $foto_perfil, $id_usuario);
} else {
    $update = $mysqli->prepare("UPDATE usuario SET nome=?, email=?, foto_perfil=? WHERE id=?");
    $update->bind_param("sssi", $nome, $email, $foto_perfil, $id_usuario);
}

// Executa a atualização
if ($update->execute()) {
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;
    $_SESSION['foto_perfil'] = $foto_perfil;
    header("Location: perfil.php?msg=sucesso");
    exit();
} else {
    header("Location: perfil.php?msg=erro");
    exit();
}

?>
