<?php
session_start();

// Se não estiver logado, redireciona para login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Define a imagem de perfil
$foto_perfil = isset($_SESSION['user_foto']) && !empty($_SESSION['user_foto'])
    ? $_SESSION['user_foto']
    : 'img/usuarios/default.png';
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
	 <script defer src="./js/carousel.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Cabeçalho -->
    <header>
        <figure class="logo">
            <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
        </figure>

        <!-- Navegação do usuário logado -->
        <nav id="nav" class="nav-links">
            <a href="filtragem.php">Games</a>
            <a href="perfil.php">
                <img src="img/usuarios/default.png" alt="Perfil do usuário" class="user-avatar">
            </a>
        </nav>

        <!-- Barra de pesquisa -->
        <div class="search-container">
            <form action="pesquisa.php" method="GET">
                <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
                <i class="fas fa-search icon"></i>
            </form>
        </div>

        <!-- Menu sanduíche -->
        <div class="hamburger" onclick="toggleMenu()">
            ☰
        </div>
    </header>
    <!-- Toast de sucesso do login -->
    <script src="./js/toast.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('sucesso') === '1') {
                LudusToast('Login realizado com sucesso!');
            }
        });
    </script>
    <!-- Conteúdo principal -->
    <main>

        <!-- Carrossel -->
        <div class="carousel">
            <button class="nav prev">&#10094;</button>
            <div class="carousel-images" id="carouselImages">
                <div class="carousel-slide" onclick="irParaDashboard(1)">
                    <img src="img/EnigmaBanner.jpg" alt="Enigma do Medo">
                    <div class="overlay">
                        <a>
                            <h2 class="h2-overlay">E<span>nigma do Medo</span></h2>
                        </a>
                    </div>
                </div>
                <div class="carousel-slide" onclick="irParaDashboard(2)">
                    <img src="img/DandaraBanner.avif" alt="Dandara">
                    <div class="overlay">
                        <h2 class="h2-overlay">D<span>andara</span></h2>
                    </div>
                </div>
                <div class="carousel-slide" onclick="irParaDashboard(9)">
                    <img src="img/KazeBanner.jpg" alt="Kaze and the wild Masks">
                    <div class="overlay">
                        <h2 class="h2-overlay">K<span>aze and the wild Masks</span></h2>
                    </div>
                </div>
                <div class="carousel-slide" onclick="irParaDashboard(3)">
                    <img src="img/AdoreBanner.jpg" alt="Adore">
                    <div class="overlay">
                        <h2 class="h2-overlay">A<span>dore</span></h2>
                    </div>
                </div>
            </div>
            <button class="nav next">&#10095;</button>
            <div class="dots" id="dotsContainer"></div>
        </div>

        <div class="nav-center"></div>

        <!-- Call to action -->
        <section class="container-pai">
            <section class="cta-start">
                <div class="cta-container">
                    <h2>Junte-se ao Ludus</h2>
                    <p>Descubra e avalie os melhores jogos independentes criados por brasileiros.</p>
                    <div class="cta-buttons">
                        <a href="filtragem.php" class="a-Button">Explorar Games</a>
                    </div>
                </div>
            </section>
        </section>

    </main>

    <!-- Rodapé -->
    <footer class="footer-nav">
        <div class="social-icons">
            <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
            <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i
                    class="fab fa-github"></i></a>
        </div>

        <span>Ludus • v0.1</span>
    </footer>

    <script>
        function irParaDashboard(idJogo) {
            window.location.href = 'dashboard.php?id=' + idJogo;
        }
    </script>

</body>

</html>