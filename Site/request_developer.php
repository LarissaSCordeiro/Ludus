<?php
// request_developer.php
// Endpoint para solicitar verificação de desenvolvedor

session_start();
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

// Verificar se usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

// Verificar se é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$motivo = isset($_POST['motivo']) ? trim($_POST['motivo']) : '';

// Validar dados
if (empty($motivo) || strlen($motivo) < 10) {
    http_response_code(400);
    echo json_encode(['erro' => 'O motivo deve ter no mínimo 10 caracteres']);
    exit;
}

if (strlen($motivo) > 1000) {
    http_response_code(400);
    echo json_encode(['erro' => 'O motivo não pode exceder 1000 caracteres']);
    exit;
}

try {
    // Obter email do usuário
    $stmt = $mysqli->prepare("SELECT email, tipo FROM usuario WHERE id = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Usuário não encontrado");
    }
    
    $usuario = $result->fetch_assoc();
    $email = $usuario['email'];
    $tipo = $usuario['tipo'];
    
    // Verificar se já é desenvolvedor
    if ($tipo !== 'jogador') {
        http_response_code(400);
        echo json_encode(['erro' => 'Você já possui permissões de desenvolvedor']);
        exit;
    }
    
    // Verificar se há uma solicitação pendente ativa
    $stmt = $mysqli->prepare("
        SELECT id FROM verificacao_desenvolvedor 
        WHERE id_usuario = ? AND status = 'pendente' AND data_expiracao > NOW()
    ");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(['erro' => 'Você já possui uma solicitação pendente. Aguarde a resposta ou tente novamente em 48 horas']);
        exit;
    }
    
    // Gerar token aleatório
    $token = bin2hex(random_bytes(32));
    $data_expiracao = date('Y-m-d H:i:s', time() + (48 * 3600)); // 48 horas
    
    // Inserir solicitação no banco de dados
    $stmt = $mysqli->prepare("
        INSERT INTO verificacao_desenvolvedor (id_usuario, email, token, motivo, status, data_expiracao)
        VALUES (?, ?, ?, ?, 'pendente', ?)
    ");
    $stmt->bind_param("issss", $id_usuario, $email, $token, $motivo, $data_expiracao);
    $stmt->execute();
    
    // Construir link de verificação - com caminho correto relativo
    $link_verificacao = "verificar_desenvolvedor.php?token=" . urlencode($token);
    
    // Preparar email
    $assunto = "Confirme seu status de desenvolvedor no Ludus";
    
    $corpo_html = "
    <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f5f5f5; }
                .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #00d4ff, #0099ff); color: white; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px; }
                .content { color: #333; line-height: 1.6; }
                .button-container { text-align: center; margin: 30px 0; }
                .btn { background: linear-gradient(135deg, #00d4ff, #0099ff); color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; display: inline-block; }
                .footer { text-align: center; margin-top: 30px; color: #999; font-size: 12px; border-top: 1px solid #eee; padding-top: 20px; }
                .warning { background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px; }
            </style>
        </head>
        <body>
            <div class=\"container\">
                <div class=\"header\">
                    <h1>🎮 Ludus - Verificação de Desenvolvedor</h1>
                </div>
                
                <div class=\"content\">
                    <h2>Olá!</h2>
                    <p>Recebemos sua solicitação para se tornar desenvolvedor na plataforma Ludus.</p>
                    
                    <p><strong>Para confirmar seu status de desenvolvedor, clique no botão abaixo:</strong></p>
                    
                    <div class=\"button-container\">
                        <a href=\"" . $link_verificacao . "\" class=\"btn\">Confirmar Identidade de Desenvolvedor</a>
                    </div>
                    
                    <p><strong>Ou copie este link no seu navegador:</strong><br>
                    <a href=\"" . $link_verificacao . "\">" . $link_verificacao . "</a></p>
                    
                    <div class=\"warning\">
                        <p><strong>⚠️ Atenção:</strong> Este link expirará em <strong>48 horas</strong>.</p>
                    </div>
                    
                    <p>Se você não fez essa solicitação, ignore este email.</p>
                    
                    <hr style=\"margin: 30px 0; border: none; border-top: 1px solid #eee;\">
                    
                    <p><strong>Motivo da solicitação:</strong></p>
                    <p style=\"background-color: #f9f9f9; padding: 15px; border-radius: 4px; border-left: 4px solid #cd3dff;\">
                        " . nl2br(htmlspecialchars($motivo)) . "
                    </p>
                </div>
                
                <div class=\"footer\">
                    <p>&copy; 2024 Ludus. Todos os direitos reservados.</p>
                    <p>Esta é uma mensagem automática. Por favor, não responda este email.</p>
                </div>
            </div>
        </body>
    </html>
    ";
    
    // Headers para email HTML
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: noreply@ludus.local" . "\r\n";
    
    // Link de verificação (sempre disponível para teste em desenvolvimento)
    $link_teste = "verificar_desenvolvedor.php?token=" . urlencode($token);
    
    // Tentar enviar email (opcional em desenvolvimento)
    $mail_enviado = @mail($email, $assunto, $corpo_html, $headers);
    
    // Log para debug
    error_log("Email para " . $email . " - token: " . $token . " - resultado: " . ($mail_enviado ? "sucesso" : "falha"));
    
    // Retornar sucesso com link de teste sempre visível
    http_response_code(200);
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Sua solicitação foi criada com sucesso! Clique no link abaixo para confirmar sua identidade de desenvolvedor:',
        'link_teste' => $link_teste,
        'token' => $token // Apenas para desenvolvimento/debug
    ]);
    
} catch (Exception $e) {
    error_log("Erro em request_developer.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao processar solicitação: ' . $e->getMessage()]);
}

$mysqli->close();
?>
