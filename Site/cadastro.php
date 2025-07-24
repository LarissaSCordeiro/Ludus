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

            header('Location: paginainicial.php');
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
    <script defer src="./js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Interface -->
    <header>
        <!-- Logo do Ludus -->
        <div class="logo">
            <a href="index.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
        </div>

        <!-- Barra de navegaçao -->
        <nav id="nav" class="nav-links">
            <!-- Botao de entrar e links -->
            <a href="login.php" class="a-Button">Entrar</a>
            <a href="cadastro.php">Criar uma conta</a>
            <a href="filtragem.php">Games</a>
        </nav>

        <!-- Barra de pesquisa personalizada -->
        <div class="search-container">
            <form action="filtragem.php" method="GET">
                <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
                <i class="fas fa-search icon"></i>
            </form>
        </div>

        <!-- Ícone do menu sanduíche -->
        <div class="hamburger" onclick="toggleMenu()">
            ☰
        </div>

    </header>

    <!-- Apresentacao do site -->
    <section class="log-page-background">

        <section class="overlay">
        </section>

        <section id="slogan">
            <h1>A comunidade de quem cria com paixão e quem joga com propósito</h1>
        </section>

        <section class="cad">
            <article id="cad">
                <h2 id="titulo">Cadastro</h2>

                <!-- Exibição de erros -->
                <?php if (!empty($erros)): ?>
                    <div class="error-message">
                        <?php foreach ($erros as $erro): ?>
                            <p><?php echo $erro; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="cadastro.php" method="post">
                    <input type="email" name="email" id="email" placeholder="Email" required
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

                    <input type="text" name="usuario" id="usuario" placeholder="Usuário" required
                        value="<?php echo isset($_POST['usuario']) ? htmlspecialchars($_POST['usuario']) : ''; ?>">

                    <input type="password" name="senha" id="senha" minlength="6" placeholder="Senha" required>

                    <input type="password" name="confirmar_senha" id="confirmar_senha" minlength="6"
                        placeholder="Confirmar Senha" required>

                    <button type="submit" id="btn"><strong>Enviar</strong></button>
                    <a class="login-return" href="login.php">Já tem uma conta? Faça Login.</a>
                </form>
            </article>
        </section>

    </section>

    <!-- Contatos -->
    <footer class="footer-nav">
        <!-- Redes sociais -->
        <div class="social-icons">
            <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
            <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i
                    class="fab fa-github"></i></a>
        </div>

        <span>Ludus • v0.1</span>
    </footer>
</body>

</html>