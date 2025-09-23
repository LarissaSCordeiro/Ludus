<?php
session_start();
require_once("config.php");


if (isset($_SESSION['user_id'])) {
  $foto_perfil = isset($_SESSION['user_foto']) && !empty($_SESSION['user_foto']) ? $_SESSION['user_foto'] : 'img/usuarios/default.png';
}

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
  <script defer src="./js/filtro-script.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
  <!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_selector.php'; ?>
  
    <div class="conteudo">
      <div class="coluna-principal">
        <section class="categoria">
    <div class="titulo-filtros">
  <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['btn'])) {
        $filtragem = $_GET['btn'];
        echo "<h2>" . htmlspecialchars($filtragem) . "</h2>";
    } else {
        echo "<h2>Todos os Jogos</h2>";
    }
  ?>
  <button type="button" id="btn-main">Aplicar filtros</button>
</div>
    
    <div id="buttons-genre">
        <?php
        $consulta = $mysqli->prepare("SELECT nome FROM genero");
        $consulta->execute();
        $resultado = $consulta->get_result();
        while ($genero = $resultado->fetch_assoc()) {
            echo '<button type="button" class="btn-genre" data-genero="' . 
                 htmlspecialchars($genero['nome']) . '">' . 
                 htmlspecialchars($genero['nome']) . '</button>';
        }
        ?>
    </div>

          <?php
		   if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['btn'])) {
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
  <!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>
  
</body>

</html>