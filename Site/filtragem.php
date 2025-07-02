<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ludus | Jogos Indie BR</title>
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
  <script defer src="./js/script.js"></script>
  <script defer src="./js/pesquisa.ajax.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <header>
    <div class="logo">
      <a href="index.html"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
    </div>

    <nav>
      <a href="login.php" class="a-Button">Entrar</a>
      <a href="cadastro.php">Criar uma conta</a>
    </nav>

    <div class="hamburger" onclick="toggleMenu()">☰</div>
  </header>

  <main>
    <div class="carousel">
      <button class="nav prev">&#10094;</button>
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
          <img src="img/Montanhas.jpeg" alt="Montanhas">
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
      <div class="dots" id="dotsContainer"></div>
    </div>

    <div class="conteudo">
      <div class="coluna-principal">
        <section class="categoria">
          <div class="filtro-pesquisa">
            <div class="categoria_genero">
              <p>Escolher gênero</p>
              <nav>
                <button class="menu-toggle" type="button">gêneros ▾</button>
                <div class="menu-opcoes">
                  <form method="POST">
                    <?php
                    require_once("config.php");
                    $consulta = $mysqli->prepare("SELECT nome FROM genero");
                    $consulta->execute();
                    $resultado = $consulta->get_result();
                    while ($genero = $resultado->fetch_assoc()) {
                      echo '<button type="submit" name="btn" value="' . htmlspecialchars($genero['nome']) . '">' . htmlspecialchars($genero['nome']) . '</button>';
                    }
                    ?>
                  </form>
                </div>
              </nav>
            </div>

            <!-- Barra de pesquisa aqui -->
            <?php
            $valorPesquisa = '';
            if (isset($_GET['pesquisa'])) {
              $valorPesquisa = htmlspecialchars($_GET['pesquisa']);
            }
            ?>

            <div class="search-container">
              <form action="filtragem.php" method="GET">
                <input type="text" id="searchInput" name="pesquisa" placeholder="Pesquisar..."
                  value="<?php echo isset($_GET['pesquisa']) ? htmlspecialchars($_GET['pesquisa']) : ''; ?>" required>
                <i class="fas fa-search icon"></i>
              </form>
            </div>
          </div>

          </nav>

          <?php
          require_once("config.php");

          // Primeiro: checa se é uma busca via GET
          if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['pesquisa'])) {
            $termoBruto = trim($_GET['pesquisa']);
            $termo = '%' . $termoBruto . '%';

            $consulta = $mysqli->prepare("
            SELECT jogo.id, jogo.nome, jogo.imagem 
            FROM jogo
            LEFT JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo
            LEFT JOIN genero ON genero.id = jogo_possui_genero.id_genero
            WHERE jogo.nome LIKE ? OR genero.nome LIKE ?
            GROUP BY jogo.id
            ");

            $consulta->bind_param("ss", $termo, $termo);
            echo "<h2 id='titulo-resultados'>Resultados para: <span>" . htmlspecialchars($termoBruto) . "</span></h2>";

            // Segundo: checa se é filtro por botão via POST
          } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['btn'])) {
            $filtragem = $_POST['btn'];

            $consulta = $mysqli->prepare("
            SELECT jogo.id, jogo.nome, jogo.imagem 
            FROM jogo
            JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo
            JOIN genero ON genero.id = jogo_possui_genero.id_genero
            WHERE genero.nome = ?
            ");

            $consulta->bind_param("s", $filtragem);
            echo "<h2>" . htmlspecialchars($filtragem) . "</h2>";

            // Terceiro: nenhum filtro → mostra todos os jogos
          } else {
            $consulta = $mysqli->prepare("SELECT id, nome, imagem FROM jogo");
            echo "<h2>Todos os Jogos :</h2>";
          }

          $consulta->execute();
          $resultado = $consulta->get_result();
          ?>


          <div class="jogos">
            <?php
            if ($resultado->num_rows > 0) {
              while ($jogo = $resultado->fetch_assoc()) {
                ?>
                <div class="jogo-card">
                  <form action="dashboard.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($jogo['id']); ?>">
                    <button type="submit" class="btn_img">
                      <img src="<?php echo htmlspecialchars($jogo['imagem']); ?>"
                        alt="Capa do jogo <?php echo htmlspecialchars($jogo['nome']); ?>">
                    </button>
                    <p class="jogo-nome"><?php echo htmlspecialchars($jogo['nome']); ?></p>
                  </form>
                </div>
                <?php
              }
            } else {
              echo '<p>Nenhum jogo encontrado.</p>';
            }
            ?>

          </div>
        </section>
      </div>
    </div>
  </main>

  <footer class="footer-nav">
    <figure class="social-icons">
      <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
      <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i
          class="fab fa-github"></i></a>
    </figure>
    <span>Ludus • v0.1</span>
  </footer>
</body>

</html>