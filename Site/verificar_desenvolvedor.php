<?php
// Página para verificar e confirmar o status de desenvolvedor

session_start();
require_once 'config.php';

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$mensagem = '';
$tipo_mensagem = '';

if (empty($token)) {
    $mensagem = 'Token inválido ou ausente.';
    $tipo_mensagem = 'erro';
} else {
    try {
        $stmt = $mysqli->prepare("
            SELECT id, id_usuario, email, status, data_expiracao, data_verificacao
            FROM verificacao_desenvolvedor
            WHERE token = ?
        ");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            $mensagem = 'Token inválido ou não encontrado.';
            $tipo_mensagem = 'erro';
        } else {
            $solicitacao = $result->fetch_assoc();
            $id_usuario = $solicitacao['id_usuario'];
            $status_atual = $solicitacao['status'];
 
            if ($status_atual === 'confirmado') {
                $mensagem = 'Este token já foi utilizado anteriormente. Você já é um desenvolvedor!';
                $tipo_mensagem = 'sucesso';
            } else if ($status_atual === 'rejeitado') {
                $mensagem = 'Este link foi rejeitado. Por favor, solicite uma nova verificação.';
                $tipo_mensagem = 'erro';
            } else {
                $data_expiracao = strtotime($solicitacao['data_expiracao']);
                if ($data_expiracao < time()) {
                    $stmt = $mysqli->prepare("
                        UPDATE verificacao_desenvolvedor
                        SET status = 'rejeitado', data_verificacao = NOW()
                        WHERE id = ?
                    ");
                    $stmt->bind_param("i", $solicitacao['id']);
                    $stmt->execute();
                    
                    $mensagem = 'Este link de verificação expirou. Por favor, solicite uma nova verificação em seu perfil.';
                    $tipo_mensagem = 'erro';
                } else {
                    $mysqli->begin_transaction();
                    
                    $stmt = $mysqli->prepare("
                        UPDATE usuario
                        SET tipo = 'desenvolvedor'
                        WHERE id = ?
                    ");
                    $stmt->bind_param("i", $id_usuario);
                    $stmt->execute();
                    
                    $stmt = $mysqli->prepare("
                        UPDATE verificacao_desenvolvedor
                        SET status = 'confirmado', data_verificacao = NOW()
                        WHERE token = ?
                    ");
                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    
                    $mysqli->commit();
                    
                    $mensagem = 'Parabéns! Sua identidade de desenvolvedor foi confirmada com sucesso. Você agora tem acesso às funcionalidades de desenvolvedor na plataforma.';
                    $tipo_mensagem = 'sucesso';
                           
                    error_log("Desenvolvedor verificado: ID=" . $id_usuario . " em " . date('Y-m-d H:i:s'));
                }
            }
        }
    } catch (Exception $e) {
        error_log("Erro em verificar_desenvolvedor.php: " . $e->getMessage());
        $mensagem = 'Ocorreu um erro ao processar sua verificação. Por favor, tente novamente ou contate o suporte.';
        $tipo_mensagem = 'erro';
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ludus | Jogos Indie BR</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #0e0a1a 0%, #1a1529 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 1rem;
            color: #fff;
        }
        
        .container {
            background: #2b2640;
            border-radius: 12px;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            text-align: center;
            border: 1px solid #3a3a5a;
        }
        
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .icon.sucesso {
            color: #e91e63;
        }
        
        .icon.erro {
            color: #ff6b6b;
        }
        
        .icon.info {
            color: #f4961e;
        }
        
        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            color: #cd3dff;
        }
        
        h1.sucesso {
            color: #e91e63;
        }
        
        h1.erro {
            color: #ff6b6b;
        }
        
        p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            color: #ccc;
        }
        
        .mensagem-box {
            background: rgba(214, 12, 53, 0.1);
            border-left: 4px solid #e91e63;
            padding: 1rem;
            border-radius: 6px;
            margin: 1.5rem 0;
            text-align: left;
        }
        
        .mensagem-box.erro {
            background: rgba(255, 107, 107, 0.1);
            border-left-color: #ff6b6b;
        }
        
        .mensagem-box.info {
            background: rgba(244, 150, 30, 0.1);
            border-left-color: #f4961e;
        }
        
        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            background: linear-gradient(135deg, #cd3dff, #e91e63);
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: bold;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(214, 12, 53, 0.1);
        } 
                  
        .buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .buttons a {
            flex: 1;
            min-width: 120px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($tipo_mensagem === 'sucesso'): ?>
            <div class="icon sucesso">✓</div>
            <h1 class="sucesso">Verificação Concluída!</h1>
            <p>Sua identidade de desenvolvedor foi confirmada com sucesso.</p>
            <div class="mensagem-box">
                <p><strong>Próximos passos:</strong></p>
                <ul style="text-align: left; margin-left: 1rem;">
                    <li>Você agora tem acesso às funcionalidades de desenvolvedor</li>
                    <li>Pode enviar e gerenciar seus próprios jogos</li>
                </ul>
            </div>
        <?php elseif ($tipo_mensagem === 'erro'): ?>
            <div class="icon erro">✕</div>
            <h1 class="erro">Verificação Falhou</h1>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
            <div class="mensagem-box erro">
                <p><strong>O que você pode fazer:</strong></p>
                <ul style="text-align: left; margin-left: 1rem;">
                    <li>Solicitar uma nova verificação em seu perfil</li>
                    <li>Verificar se o link não expirou (48 horas)</li>
                </ul>
            </div>
        <?php else: ?>
            <div class="icon info">!</div>
            <h1>Verificação</h1>
            <p><?php echo htmlspecialchars($mensagem); ?></p>
        <?php endif; ?>
        
        <div class="buttons">
            <?php if ($tipo_mensagem === 'sucesso'): ?>
                <a href="perfil.php" class="btn">Ir para Perfil</a>
            <?php else: ?>
                <a href="perfil.php" class="btn">Voltar ao Perfil</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
