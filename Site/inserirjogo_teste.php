<?php
require_once "config.php";

$nome = "Jogo de Teste";
$estudio = "Estúdio Teste";
$desenvolvedor = "Dev Teste";
$descricao = "Este é um jogo inserido para testar a exclusão.";
$imagem = "https://via.placeholder.com/300x200.png?text=Jogo+Teste";
$id_usuario = 1; // <- coloque o ID de um usuário válido

$generos = [1, 2];
$plataformas = [1, 3];

try {
    $mysqli->begin_transaction();

    $stmt = $mysqli->prepare("INSERT INTO jogo (nome, estudio, desenvolvedor, descricao, imagem, id_usuario) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nome, $estudio, $desenvolvedor, $descricao, $imagem, $id_usuario);
    $stmt->execute();
    $jogoId = $stmt->insert_id;
    $stmt->close();

    $stmtGenero = $mysqli->prepare("INSERT INTO jogo_possui_genero (id_jogo, id_genero) VALUES (?, ?)");
    foreach ($generos as $idGenero) {
        $stmtGenero->bind_param("ii", $jogoId, $idGenero);
        $stmtGenero->execute();
    }
    $stmtGenero->close();

    $stmtPlataforma = $mysqli->prepare("INSERT INTO jogo_possui_plataforma (id_jogo, id_plataforma) VALUES (?, ?)");
    foreach ($plataformas as $idPlataforma) {
        $stmtPlataforma->bind_param("ii", $jogoId, $idPlataforma);
        $stmtPlataforma->execute();
    }
    $stmtPlataforma->close();

    $mysqli->commit();

    echo "Jogo de teste inserido com ID: $jogoId";
} catch (Exception $e) {
    $mysqli->rollback();
    echo "Erro ao inserir jogo de teste: " . $e->getMessage();
}
