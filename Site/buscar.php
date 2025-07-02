<?php
require_once("config.php");

if (isset($_GET['pesquisa'])) {
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
  $consulta->execute();
  $resultado = $consulta->get_result();

  if ($resultado->num_rows > 0) {
    while ($jogo = $resultado->fetch_assoc()) {
      ?>
      <div class="jogo-card">
        <form action="dashboard.php" method="POST">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($jogo['id']); ?>">
          <button type="submit" class="btn_img">
            <img src="<?php echo htmlspecialchars($jogo['imagem']); ?>" alt="Capa do jogo <?php echo htmlspecialchars($jogo['nome']); ?>">
          </button>
          <p class="jogo-nome"><?php echo htmlspecialchars($jogo['nome']); ?></p>
        </form>
      </div>
      <?php
    }
  } else {
    echo '<p>Nenhum jogo encontrado.</p>';
  }
}

