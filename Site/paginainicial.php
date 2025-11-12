<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

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
    <link rel="stylesheet" href="./css/filtro-pesquisa.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <script defer src="./js/script.js"></script>
	 <script defer src="./js/carousel.js"></script>
    <script defer src="./js/highlight-carousel.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_selector.php'; ?>
    
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
   
    <?php
    include __DIR__ . '/config.php';
    include __DIR__ . '/includes/games.php';

    $em_alta = getTrendingGames($mysqli, 8, 14);
    $populares = getPopularGames($mysqli, 8);
    $novidades = getNewGames($mysqli, 8);

    $candidates = [];
    if (!empty($em_alta)) $candidates[] = $em_alta[0];
    if (!empty($populares)) $candidates[] = $populares[0];
    if (!empty($novidades)) $candidates[] = $novidades[0];
    $seen = [];
    $carouselItems = [];
    foreach ($candidates as $c) {
        if (!isset($c['id'])) continue;
        if (isset($seen[$c['id']])) continue;
        $seen[$c['id']] = true;
        $carouselItems[] = $c;
    }
    ?>

        <!-- Carrossel principal (estático - mantido como antes) -->
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

     
    <?php
    ?>

    <section class="categoria highlights">
        <div class="container">
            <h2>Em alta</h2>
                        <div class="carousel-row">
                            <button class="row-prev" aria-label="Anterior">&#10094;</button>
                            <div class="jogos row-scroll">
                                <?php if (empty($em_alta)): ?>
                                        <p>Sem jogos em alta por enquanto.</p>
                                <?php else: ?>
                                        <?php foreach ($em_alta as $g): ?>
                                                <div class="jogo-card">
                                                    <form action="dashboard.php" method="GET">
                                                        <input type="hidden" name="id" value="<?php echo (int)$g['id']; ?>">
                                                        <button type="submit" class="btn_img">
                                                            <img src="<?php echo htmlspecialchars(game_image($g['imagem'])); ?>" alt="Capa do jogo <?php echo htmlspecialchars($g['nome']); ?>">
                                                            <div class="card-meta">
                                                                <span class="meta-item" title="Avaliações recentes">
                                                                    <i class="fas fa-bolt"></i>
                                                                    <span class="meta-value"><?php echo isset($g['recent_reviews']) ? (int)$g['recent_reviews'] : 0; ?></span>
                                                                </span>
                                                            </div>
                                                        </button>
                                                        <button type="submit" class="jogo-nome"><h3><?php echo htmlspecialchars($g['nome']); ?></h3></button>
                                                    </form>
                                                </div>
                                        <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button class="row-next" aria-label="Próximo">&#10095;</button>
                        </div>

            <h2>Populares</h2>
                        <div class="carousel-row">
                            <button class="row-prev" aria-label="Anterior">&#10094;</button>
                            <div class="jogos row-scroll">
                                <?php if (empty($populares)): ?>
                                        <p>Sem jogos populares por enquanto.</p>
                                <?php else: ?>
                                        <?php foreach ($populares as $g): ?>
                                                <div class="jogo-card">
                                                    <form action="dashboard.php" method="GET">
                                                        <input type="hidden" name="id" value="<?php echo (int)$g['id']; ?>">
                                                        <button type="submit" class="btn_img">
                                                            <img src="<?php echo htmlspecialchars(game_image($g['imagem'])); ?>" alt="Capa do jogo <?php echo htmlspecialchars($g['nome']); ?>">
                                                            <div class="card-meta">
                                                                <span class="meta-item" title="Favoritos">
                                                                    <i class="fas fa-heart"></i>
                                                                    <span class="meta-value"><?php echo isset($g['favorites']) ? (int)$g['favorites'] : 0; ?></span>
                                                                </span>
                                                                <span class="meta-item" title="Nota média">
                                                                    <i class="fas fa-star"></i>
                                                                    <span class="meta-value"><?php echo round($g['avg_rating'],1); ?></span>
                                                                </span>
                                                            </div>
                                                        </button>
                                                        <button type="submit" class="jogo-nome"><h3><?php echo htmlspecialchars($g['nome']); ?></h3></button>
                                                    </form>
                                                </div>
                                        <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button class="row-next" aria-label="Próximo">&#10095;</button>
                        </div>

            <h2>Novidades</h2>
                        <div class="carousel-row">
                            <button class="row-prev" aria-label="Anterior">&#10094;</button>
                            <div class="jogos row-scroll">
                                <?php if (empty($novidades)): ?>
                                        <p>Sem novidades por enquanto.</p>
                                <?php else: ?>
                                        <?php foreach ($novidades as $g): ?>
                                                <div class="jogo-card">
                                                    <form action="dashboard.php" method="GET">
                                                        <input type="hidden" name="id" value="<?php echo (int)$g['id']; ?>">
                                                        <button type="submit" class="btn_img">
                                                            <img src="<?php echo htmlspecialchars(game_image($g['imagem'])); ?>" alt="Capa do jogo <?php echo htmlspecialchars($g['nome']); ?>">
                                                            <div class="card-meta">
                                                                <span class="meta-item" title="Lançamento">
                                                                    <i class="fas fa-calendar-alt"></i>
                                                                    <span class="meta-value"><?php echo htmlspecialchars($g['data_lancamento']); ?></span>
                                                                </span>
                                                            </div>
                                                        </button>
                                                        <button type="submit" class="jogo-nome"><h3><?php echo htmlspecialchars($g['nome']); ?></h3></button>
                                                    </form>
                                                </div>
                                        <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button class="row-next" aria-label="Próximo">&#10095;</button>
                        </div>
        </div>
    </section>

    <!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>

    <script>
        function irParaDashboard(idJogo) {
            window.location.href = 'dashboard.php?id=' + idJogo;
        }
    </script>

</body>

</html>