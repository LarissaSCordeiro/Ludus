<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <script src="./js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <!-- Interface -->
    <header>
        <section class="logo">
            <a href="index.html">
                <img src="img/NewLudusLogo.png" alt="Logotipo">
            </a>
        </section>

        <nav id="nav" class="nav-links">
            <a href="cadastro.html" class="a-Button">Criar uma conta</a>
            <a href="catalogo.html">Games</a>
        </nav>

        <section class="search-container">
            <input type="text" placeholder="Pesquisar..." />
            <i class="fas fa-search icon"></i>
        </section>

        <div class="hamburger" onclick="toggleMenu()">
            ☰
        </div>
    </header>

    <main id="dash_main">
        <?php
        require_once("config.php");
        $id = $_POST['id'];

        $consulta = $mysqli->prepare("SELECT jogo.nome, jogo.descricao, jogo.imagem, jogo.data_lancamento, jogo.estudio, jogo.desenvolvedor, GROUP_CONCAT(DISTINCT genero.nome ORDER BY genero.nome SEPARATOR ', ') AS genero, GROUP_CONCAT(DISTINCT plataforma.nome ORDER BY plataforma.nome SEPARATOR ', ') AS plataforma FROM jogo LEFT JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo LEFT JOIN genero ON genero.id = jogo_possui_genero.id_genero LEFT JOIN jogo_possui_plataforma ON jogo.id = jogo_possui_plataforma.id_jogo LEFT JOIN plataforma ON plataforma.id = jogo_possui_plataforma.id_plataforma WHERE jogo.id = ? GROUP BY jogo.id");

        $consulta->bind_param("i", $id);
        $consulta->execute();
        $resultado = $consulta->get_result();
        $jogo = $resultado->fetch_assoc();
        ?>

        <figure class="dash_img">
            <img src="<?php echo $jogo["imagem"]; ?>" alt="Capa Jogo">
        </figure>

        <article>
            <h1><?php echo $jogo["nome"]; ?></h1>

            <h3>Estúdio:</h3>
            <p><?php echo $jogo["estudio"]; ?></p>

            <h3>Desenvolvedor:</h3>
            <p><?php echo $jogo["desenvolvedor"]; ?></p>

            <h3>Data de Lançamento:</h3>
            <p><?php echo date("d/m/Y", strtotime($jogo['data_lancamento'])); ?></p>

            <h3>Descrição:</h3>
            <p><?php echo $jogo["descricao"]; ?></p>

            <h3>Plataformas:</h3>
            <p><?php echo $jogo["plataforma"]; ?></p>

            <h3>Gêneros:</h3>
            <p><?php echo $jogo["genero"]; ?></p>
        </article>
    </main>

    <footer class="footer-nav">
        <div class="social-icons">
            <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
            <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
        </div>
        <span>Ludus • v0.1</span>
    </footer>

</body>
</html>
