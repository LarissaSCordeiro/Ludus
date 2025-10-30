<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: paginainicial.php");
    exit;
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
	<script defer src="./js/carousel.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_selector.php'; ?>

            <!-- Imagens do carrosel -->
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
   

    <!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>

</body>

</html>