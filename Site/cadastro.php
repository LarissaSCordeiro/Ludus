<?php
require_once 'config.php';
session_start();

$erros = [];

// Quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebendo dados
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $usuario = trim(htmlspecialchars($_POST['usuario']));
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Por favor, insira um e-mail válido.';
    }

    if (strlen($usuario) < 3) {
        $erros[] = 'O nome de usuário deve ter pelo menos 3 caracteres.';
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
        $erros[] = 'O nome de usuário deve conter apenas letras, números e underline.';
    }

    if (strlen($senha) < 6) {
        $erros[] = 'A senha deve ter no mínimo 6 caracteres.';
    }

    if ($senha !== $confirmar_senha) {
        $erros[] = 'As senhas não coincidem.';
    }

    // Verificar se e-mail já existe
    $stmt = $mysqli->prepare("SELECT id FROM usuario WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $erros[] = 'Este e-mail já está cadastrado.';
    }
    $stmt->close();

    // Verificar se usuário já existe
    $stmt = $mysqli->prepare("SELECT id FROM usuario WHERE nome = ?");
    $stmt->bind_param('s', $usuario);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $erros[] = 'Este nome de usuário já está em uso.';
    }
    $stmt->close();

    // Se não houver erros, cadastrar
    if (empty($erros)) {
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
        $foto_padrao = 'img/usuarios/default.png';
        $tipo = 'jogador'; // Por padrão, jogador

        $stmt = $mysqli->prepare("INSERT INTO usuario (nome, email, senha, foto_perfil, tipo) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $usuario, $email, $senha_hash, $foto_padrao, $tipo);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_nome'] = $usuario;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_tipo'] = $tipo;
            header('Location: login.php?sucesso=1');
            exit();
        } else {
            $erros[] = 'Erro ao cadastrar. Tente novamente.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<body>
    <!-- Interface -->
    <!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_selector.php'; ?>

    <!-- Apresentacao do site -->
    <main class="main-cad-log">

        <section id="slogan">
            <h3>Cadastre-se para avaliar e comentar sobre os seus jogos indie favoritos</h3>
        </section>

        <section class="cad">
            <article id="cad">
                <h2 id="titulo">Cadastro</h2>


                <!-- Exibição de erros + toast -->
                <?php if (!empty($erros)): ?>
                    <div class="error-message">
                        <?php foreach ($erros as $erro): ?>
                            <p><?php echo $erro; ?></p>
                        <?php endforeach; ?>
                    </div>
                    <script>
                        window.addEventListener('DOMContentLoaded', function() {
                            LudusToast("<?php echo addslashes($erros[0]); ?>", true);
                        });
                    </script>
                <?php endif; ?>
                <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == '1'): ?>
                    <!-- Toast de sucesso removido, agora será exibido no login.php -->
                <?php endif; ?>

                <form action="cadastro.php" method="post">
                    <input type="email" name="email" id="email" placeholder="Email" required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

                    <input type="text" name="usuario" id="usuario" placeholder="Usuário" required
                        value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">

                    <input type="password" name="senha" id="senha" minlength="6" placeholder="Senha" required>

                    <input type="password" name="confirmar_senha" id="confirmar_senha" minlength="6"
                        placeholder="Confirmar Senha" required>

                    <button type="submit" id="btn"><strong>Cadastrar</strong></button>
                    <a class="login-return" href="login.php"> Já possui uma conta? Fazer Login</a>
                </form>
            </article>
        </section>

    </main>

    <!-- Contatos -->
    <!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>
    <script src="./js/script.js"></script>
    <script src="./js/toast.js"></script>
</body>

</html>