<?php 

if (isset($_SESSION['user_id'])) {
    header('Location: paginainicial.php');
    exit();
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

    <!-- Cabeçalho -->

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

    <!-- Parte principal do site -->
    <main>

        <!-- Carrosel de imagens (linkado ao JS) -->
        <div class="carousel">
            <button class="nav prev">&#10094;</button>

            <!-- Imagens do carrosel -->
            <div class="carousel-images" id="carouselImages">

                <div class="carousel-slide">
                    <img src="img/Soldier.jpeg" alt="Soldier">
                    <div class="overlay">
                        <h2 class="h2-overlay">Clique <span>para ver mais</span></h2>
                    </div>
                </div>

                <div class="carousel-slide">
                    <img src="img/Everest.jpeg" alt="Everest">
                    <div class="overlay">
                        <h2 class="h2-overlay">Clique <span>para ver mais</span></h2>
                    </div>
                </div>

                <div class="carousel-slide">
                    <img src="img/Montanhas.jpeg" alt="Desert">
                    <div class="overlay">
                        <h2 class="h2-overlay">Clique <span>para ver mais</span></h2>
                    </div>
                </div>

                <div class="carousel-slide">
                    <img src="img/Eden-img.jpeg" alt="Eden">
                    <div class="overlay">
                        <h2 class="h2-overlay">Clique <span>para ver mais</span></h2>
                    </div>
                </div>
            </div>

            <button class="nav next">&#10095;</button>
            <!-- Pontinhos do slider -->
            <div class="dots" id="dotsContainer"></div>
        </div>

        <div class="nav-center">

        </div>

        <!-- Sobre jogos indie -->

        <section class="container-pai">
            <section class="cta-start">
                <div class="cta-container">
                    <h2>Junte-se ao Ludus</h2>
                    <p>Descubra e avalie os melhores jogos independentes criados por brasileiros.</p>
                    <div class="cta-buttons">
                        <a href="cadastro.php" class="a-Button">Criar Conta Grátis</a>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <!-- Rodape -->
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