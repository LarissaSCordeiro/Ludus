<?php
session_start();
require_once("config.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
  header("Location: login.php");
  exit();
}

$id_usuario = $_SESSION['id_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ludus | Jogos Indie BR</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/cadastro_jogo.css">
  <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
</head>

<body>
  <?php include __DIR__ . '/headers/header_selector.php'; ?>

  <main class="cadastro-jogo-container">
    <div class="cadastro-jogo-card">
      <form id="cadastroJogoForm" enctype="multipart/form-data" method="post" action="salvar_cadastro_jogo.php">

        <!-- Lado esquerdo: capa do jogo -->
        <div class="cadastro-jogo-left">
          <img src="img/jogos/default.png" alt="Capa do jogo" class="capa-jogo">
          <div class="drop-area" id="dropArea">
            Clique ou arraste a capa do jogo
            <input type="file" id="fileInput" name="capa" accept="image/*" hidden>
          </div>
        </div>

        <!-- Lado direito: dados do jogo -->
        <div class="cadastro-jogo-right">
          <h2>Cadastrar Novo Jogo</h2>

          <div class="form-group">
            <label for="nome_jogo">Nome do Jogo</label>
            <input type="text" id="nome_jogo" name="nome_jogo" required>
          </div>

          <div class="form-group">
            <label for="estudio">Estúdio</label>
            <input type="text" id="estudio" name="estudio" placeholder="Opcional">
          </div>

          <div class="form-group">
            <label for="desenvolvedor">Desenvolvedor</label>
            <input type="text" id="desenvolvedor" name="desenvolvedor" placeholder="Opcional">
          </div>

          <div class="form-group">
            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" rows="5"></textarea>
          </div>

          <div class="form-group">
            <label for="data_lancamento">Data de Lançamento</label>
            <input type="date" id="data_lancamento" name="data_lancamento">
          </div>
          <div class="form-group">
            <label for="generos">Gêneros</label>
            <select id="generos" name="generos[]" multiple required>
              <?php
              $query = $mysqli->query("SELECT id, nome FROM genero ORDER BY nome");
              while ($row = $query->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
              }
              ?>
            </select>
          </div>

          <div class="form-group">
            <label for="plataformas">Plataformas</label>
            <select id="plataformas" name="plataformas[]" multiple required>
              <?php
              $query = $mysqli->query("SELECT id, nome FROM plataforma ORDER BY nome");
              while ($row = $query->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome']}</option>";
              }
              ?>
            </select>
          </div>


          <div class="button-align">
            <button type="submit"><i class="fas fa-save"></i> Cadastrar Jogo</button>
            <a href="index.php" class="btn-cancelar"><i class="fas fa-arrow-left"></i> Cancelar</a>
          </div>

        </div>

      </form>
    </div>
  </main>

  <?php include __DIR__ . '/footers/footer.php'; ?>

  <script>
    const dropArea = document.getElementById("dropArea");
    const fileInput = document.getElementById("fileInput");
    const previewImg = document.querySelector(".cadastro-jogo-left img");

    dropArea.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", e => {
      const file = e.target.files[0];
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = () => previewImg.src = reader.result;
        reader.readAsDataURL(file);
      }
    });
  </script>

  <script>
    const choicesGeneros = new Choices('#generos', {
        removeItemButton: true,
        duplicateItemsAllowed: false,
        addItems: true,
        placeholderValue: 'Selecione ou adicione gêneros'
    });

    const choicesPlataformas = new Choices('#plataformas', {
        removeItemButton: true,
        duplicateItemsAllowed: false,
        addItems: true,
        placeholderValue: 'Selecione ou adicione plataformas'
    });
</script>

</body>

</html>