<?php
session_start();
require_once 'config.php';

$erro = '';

if (isset($_SESSION['id_usuario'])) {
    header('Location: paginainicial.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        $stmt = $mysqli->prepare("SELECT id, nome, senha, tipo FROM usuario WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $user_nome, $hashed_password, $user_tipo);
            $stmt->fetch();

            if (password_verify($senha, $hashed_password)) {
                $_SESSION['id_usuario'] = $user_id;
                $_SESSION['nome'] = $user_nome;
                $_SESSION['email'] = $email;
                $_SESSION['tipo'] = $user_tipo;

                header("Location: paginainicial.php");
                exit();
            } else {
                $erro = "E-mail ou senha incorretos.";
            }
        } else {
            $erro = "E-mail ou senha incorretos.";
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
            <a href="cadastro.php" class="a-Button">Criar uma conta</a>
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
    <main>

        <!-- Section do background atrás do container de login -->
        <section class="log-page-background">

            <section class="overlay">
            </section>

            <section id="slogan">
                <h1>Faça login para avaliar e comentar sobre seus jogos indie favoritos</h1>
            </section>
            <!-- Section do container de login -->
            <section class="log">
                <article id="log">
                    <?php if (!empty($erro)): ?>
                        <div class="error-message">
                            <p><?php echo $erro; ?></p>
                        </div>
                    <?php endif; ?>
                    <h2 id="titulo">Login</h2>
                    <form action="login.php" method="post">
                        <input type="email" name="email" id="email" placeholder="Email" required>
                        <input type="password" name="senha" id="senha" minlength="6" placeholder="Senha" required>
                        <button type="submit" name="login" id="btn"><strong>Entrar</strong></button>
                        <a class="cadastro-return" href="cadastro.php">Não tem uma conta? Clique Aqui.</a>
                        <a class="cadastro-return" href="#">Esqueceu a senha?</a>
                    </form>
                </article>
            </section>

    </main>
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