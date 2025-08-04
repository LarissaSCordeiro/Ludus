<?php
require_once("config.php");

if (isset($_GET['pesquisa'])) {
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
  $consulta->execute();
  $resultado = $consulta->get_result();

  if ($resultado->num_rows > 0) {
    while ($jogo = $resultado->fetch_assoc()) {
      ?>
      <div class="jogo-card">
        <form action="dashboard.php" method="POST">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($jogo['id']); ?>">
          <button type="submit" class="btn_img">
            <img src="<?php echo htmlspecialchars($jogo['imagem']); ?>"
              alt="Capa do jogo <?php echo htmlspecialchars($jogo['nome']); ?>">
            <div class="card-meta">
              <span class="meta-item" title="Nota da comunidade">
                <i class="fas fa-star"></i>
                <span class="meta-value"><?php echo number_format((float) $jogo['media_avaliacao'], 1); ?></span>
              </span>
              <span class="meta-item" title="Número de comentários">
                <i class="fas fa-comment"></i>
                <span class="meta-value"><?php echo (int) $jogo['total_comentarios']; ?></span>
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
}
?>