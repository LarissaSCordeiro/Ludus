<?php
session_start();
require_once("config.php");


if (isset($_SESSION['user_id'])) {
  $foto_perfil = isset($_SESSION['user_foto']) && !empty($_SESSION['user_foto']) ? $_SESSION['user_foto'] : 'img/usuarios/default.png';
}

 $valorPesquisa = '';
            if (isset($_GET['pesquisa'])) {
              $valorPesquisa = htmlspecialchars($_GET['pesquisa']);
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
  <script defer src="./js/filtro-script.js"></script>
  <script defer src="./js/pesquisa.ajax.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    .card-meta {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      margin-top: 8px;
      font-size: 0.95rem;
      justify-content: center;
      z-index: 1;
    }

    .card-meta .meta-item {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 4px 8px;
      border-radius: 8px;
      background-color: #3a3a5c5b;
      transition: background 0.3s ease, transform 0.2s ease;
      cursor: default;
      z-index: 1;
    }

    .card-meta .meta-item:hover {
      transform: translateY(-2px);
    }

    .card-meta .fa-star {
      color: #ffcc00;
      font-size: 1.1rem;
      animation: shineAnim 2.5s infinite;
    }

    .card-meta .fa-comment {
      color: #797979ff;
      font-size: 1.05rem;
      animation: bounceAnim 3s infinite;
    }

    .card-meta .meta-value {
      font-weight: bold;
      color: #fff;
      background-color: #3a3a5c5b;
      text-shadow: 0 0 3px rgba(0, 0, 0, 0.3);
      transition: transform 0.2s ease;
    }

    .card-meta .meta-item:hover .meta-value {
      transform: scale(1.15);
    }

    /* Animação de brilho na estrela */
    @keyframes shineAnim {

      0%,
      100% {
        text-shadow: 0 0 4px #ffe57eff, 0 0 8px #ffae00ff;
      }

      50% {
        text-shadow: 0 0 6px #fff4cc, 0 0 20px #ffae00ff;
      }
    }

    /* Animação de pulinho no comentário */
    @keyframes bounceAnim {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-2px);
      }
    }

    .jogo-card {
      background: linear-gradient(145deg, rgba(43, 43, 68, 0.9), rgba(30, 30, 48, 0.95));
      border-radius: 12px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      position: relative;
      border: 2px solid rgba(0, 0, 0, 0);
      z-index: 0;
	  
    }

    .jogo-card::after {
      content: "";
      position: absolute;
      top: -50%;
      left: -100%;
      width: 200%;
      height: 200%;
      background: linear-gradient(120deg, transparent 0%, rgba(216, 140, 229, 0.1) 50%, transparent 100%);
      transform: rotate(25deg);
      animation: shine 5s infinite;
      z-index: -1;
    }

    @keyframes shine {
      0% {
        transform: translateX(-100%) rotate(25deg);
        opacity: 0;
      }

      40%,
      60% {
        opacity: 1;
      }

      100% {
        transform: translateX(100%) rotate(25deg);
        opacity: 0;
      }
    }

    .jogo-card:hover {
      border: 2px solid #5b5b80ff;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
	  
    }

    .jogo-card .btn_img {
      position: relative;
      overflow: hidden;
      border: none;
      padding: 0;
      display: block;
      width: 100%;
      height: 370px;
      /* antes era 260px — aumenta altura */
	  cursor:pointer;
    }

    .jogo-card img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* mantém proporção, mas corta excesso */
      object-position: center top;
      /* foca mais no topo da imagem */
      transition: transform 0.4s ease;
    }

    .jogo-card:hover img {
      transform: scale(1.05);
    }

    /* Overlay gradiente */
    .jogo-card .btn_img::after {
      content: "";
      position: absolute;
      inset: 0;
      background: linear-gradient(to top, rgba(61, 22, 151, 0.5), transparent);
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    .jogo-card:hover .btn_img::after {
      opacity: 1;
    }

    /* Meta info sobre a imagem */
    .jogo-card .card-meta {
      position: absolute;
      bottom: 8px;
      left: 8px;
      display: flex;
      gap: 10px;
      background: rgba(0, 0, 0, 0.5);
      padding: 4px 8px;
      border-radius: 8px;
      font-size: 0.85rem;
      backdrop-filter: blur(4px);
      animation: fadeInMeta 0.3s ease forwards;
    }

    @keyframes fadeInMeta {
      from {
        opacity: 0;
        transform: translateY(10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Ícones */
    .card-meta i {
      transition: transform 0.2s ease;
    }

    .card-meta .meta-item:hover i {
      transform: scale(1.2);
    }

    /* Título */
    .jogo-card h3 {
      margin: 10px 0;
      text-align: center;
      transition: color 0.3s ease;
    }

    .jogo-card:hover h3 {
      color: #f4961e;
    }

    .jogo-nome {
      background: linear-gradient(145deg, rgba(43, 43, 68, 0), rgba(30, 30, 48, 0));
      style: none;
      border: none;
      z-index: 0;
      color: #caaec8;
      font-family: 'Comfortaa', sans-serif;
	  cursor:pointer;
    }

    .btn_img {
      background: linear-gradient(145deg, rgba(43, 43, 68, 0), rgba(30, 30, 48, 0));
    }
  </style>
</head>

<body>
  <header>
    <!-- Logo do Ludus -->
    <figure class="logo">
      <?php if (isset($_SESSION['user_id'])) { ?>
        <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a> <?php } else { ?>
        <a href="index.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a> <?php } ?>
    </figure>

    <!-- Barra de navegaçao -->
    <nav id="nav" class="nav-links">
      <!-- Botao de entrar e links -->
      <?php if (!empty($_SESSION['user_id'])) { ?>
        <a href="perfil.php"><img src="img/usuarios/default.png" alt="Perfil do usuário" class="user-avatar"></a>
      <?php } else { ?>
        <a href="login.php" class="a-Button">Entrar</a> 
		 <a href="cadastro.php">Criar uma conta</a>
		<?php } ?>
    </nav>
	
	 <div class="search-container">
           <form action="filtragem.php" method="GET">
                <input type="text" id="searchInput" name="pesquisa" placeholder="Pesquisar..."
                  value="<?php echo $valorPesquisa; ?>" required>
                <i class="fas fa-search icon"></i>
              </form>
        </div>


    <!-- Ícone do menu sanduíche -->
    <div class="hamburger" onclick="toggleMenu()">
      ☰
    </div>

  </header>
  
    <div class="conteudo">
      <div class="coluna-principal">
        <section class="categoria">
                
                <div class="buttons-genre">
                <?php
                 $consulta = $mysqli->prepare("SELECT nome FROM genero");
                  $consulta->execute();
                  $resultado = $consulta->get_result();
                   while ($genero = $resultado->fetch_assoc()) {
                   echo '<button type="button" class="btn-genre" data-genero="' . htmlspecialchars($genero['nome']) . '">' . htmlspecialchars($genero['nome']) . '</button>';
				  }
                 ?>
                </div>
          <?php

          if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['pesquisa'])) {
            $termoBruto = trim($_GET['pesquisa']);
            $termo = '%' . $termoBruto . '%';

            $consulta = $mysqli->prepare("
            SELECT 
              jogo.id, 
              jogo.nome, 
              jogo.imagem,
              COALESCE(ROUND(AVG(avaliacao.nota), 1), 0) AS media_avaliacao,
              COUNT(DISTINCT comentario.id) AS total_comentarios
            FROM jogo
            LEFT JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo
            LEFT JOIN genero ON genero.id = jogo_possui_genero.id_genero
            LEFT JOIN avaliacao ON jogo.id = avaliacao.id_jogo
            LEFT JOIN comentario ON avaliacao.id = comentario.id_avaliacao
            WHERE jogo.nome LIKE ? OR genero.nome LIKE ?
            GROUP BY jogo.id
          ");
            $consulta->bind_param("ss", $termo, $termo);
            echo "<h2 id='titulo-resultados'>Resultados para: <span>" . htmlspecialchars($termoBruto) . "</span></h2>";

          }  elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['btn'])) {
               $filtragem = $_GET['btn'];

               $consulta = $mysqli->prepare("SELECT 
               jogo.id, 
               jogo.nome, 
               jogo.imagem,
               COALESCE(ROUND(AVG(avaliacao.nota), 1), 0) AS media_avaliacao,
               COUNT(DISTINCT comentario.id) AS total_comentarios
               FROM jogo
               JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo
               JOIN genero ON genero.id = jogo_possui_genero.id_genero
               LEFT JOIN avaliacao ON jogo.id = avaliacao.id_jogo
               LEFT JOIN comentario ON avaliacao.id = comentario.id_avaliacao
               WHERE genero.nome = ?
               GROUP BY jogo.id");
			   
               $consulta->bind_param("s", $filtragem);
               echo "<h2>" . htmlspecialchars($filtragem) . "</h2>";
          } else {
            $consulta = $mysqli->prepare("
            SELECT 
              jogo.id, 
              jogo.nome, 
              jogo.imagem,
              COALESCE(ROUND(AVG(avaliacao.nota), 1), 0) AS media_avaliacao,
              COUNT(DISTINCT comentario.id) AS total_comentarios
            FROM jogo
            LEFT JOIN avaliacao ON jogo.id = avaliacao.id_jogo
            LEFT JOIN comentario ON avaliacao.id = comentario.id_avaliacao
            GROUP BY jogo.id
          ");
            echo "<h2>Todos os Jogos :</h2>";
          }

          $consulta->execute();
          $resultado = $consulta->get_result();
          ?>

          <div class="jogos">
            <?php
            if ($resultado->num_rows > 0) {
              while ($jogo = $resultado->fetch_assoc()) { ?>
                <div class="jogo-card">
                  <form action="dashboard.php" method="GET">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($jogo['id']); ?>">
                    <button type="submit" class="btn_img">
                      <img src="<?php echo htmlspecialchars($jogo['imagem']); ?>"
                        alt="Capa do jogo <?php echo htmlspecialchars($jogo['nome']); ?>">

                      <div class="card-meta">
                        <span class="meta-item" title="Nota da comunidade">
                          <i class="fas fa-star"></i>
                          <span class="meta-value">
                            <?php echo isset($jogo['media_avaliacao']) ? number_format((float) $jogo['media_avaliacao'], 1) : '0.0'; ?>
                          </span>
                        </span>
                        <span class="meta-item" title="Número de comentários">
                          <i class="fas fa-comment"></i>
                          <span class="meta-value">
                            <?php echo isset($jogo['total_comentarios']) ? (int) $jogo['total_comentarios'] : 0; ?>
                          </span>
                        </span>
                      </div>
                    </button>
                    <button type="submit" class="jogo-nome">
                      <h3><?php echo htmlspecialchars($jogo['nome']); ?></h3>
                    </button>
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