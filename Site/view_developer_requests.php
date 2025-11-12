<?php
// Script para visualizar tokens de verificação gerados
require_once 'config.php';

// Buscar as solicitações mais recentes
$query = "SELECT id, id_usuario, email, token, motivo, status, data_solicitacao, data_expiracao FROM verificacao_desenvolvedor ORDER BY data_solicitacao DESC LIMIT 10";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    echo "=== SOLICITAÇÕES DE DESENVOLVEDOR ===\n\n";
    
    while ($row = $result->fetch_assoc()) {
        echo "─────────────────────────────────────────────────────\n";
        echo "ID: " . $row['id'] . "\n";
        echo "ID Usuário: " . $row['id_usuario'] . "\n";
        echo "Email: " . $row['email'] . "\n";
        echo "Status: " . $row['status'] . "\n";
        echo "Criado em: " . $row['data_solicitacao'] . "\n";
        echo "Expira em: " . $row['data_expiracao'] . "\n";
        echo "\n🔑 TOKEN: " . $row['token'] . "\n";
        echo "\n📝 MOTIVO: " . substr($row['motivo'], 0, 100) . "...\n";
        
        // Gerar o link de verificação
        $base_url = "http://localhost/Site/verificar_desenvolvedor.php?token=";
        $link = $base_url . urlencode($row['token']);
        echo "\n🔗 LINK DE VERIFICAÇÃO:\n" . $link . "\n";
        echo "─────────────────────────────────────────────────────\n\n";
    }
} else {
    echo "Nenhuma solicitação de desenvolvedor encontrada.\n";
}

$mysqli->close();
?>
