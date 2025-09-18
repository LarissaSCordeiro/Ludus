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

    <!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>

</body>

</html>