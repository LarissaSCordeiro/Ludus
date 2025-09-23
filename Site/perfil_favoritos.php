<?php
session_start();
require_once("config.php");

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id_usuario = $_SESSION['user_id'];

// Pega os dados do usuário
$stmt = $mysqli->prepare("SELECT nome, foto_perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
  echo "Usuário não encontrado.";
  exit();
}

$usuario = $resultado->fetch_assoc();
$nomeUsuario = $usuario['nome'];
$foto_perfilPerfil = $usuario['foto_perfil'] ?: 'img/usuarios/default.png';

// Pega os jogos favoritados pelo usuário
$favoritosStmt = $mysqli->prepare("
  SELECT j.id AS id_jogo, j.nome AS nome_jogo, j.imagem AS imagem_jogo, j.descricao
  FROM usuario_favorita_jogo uf
  JOIN jogo j ON uf.id_jogo = j.id
  WHERE uf.id_usuario = ?
");
$favoritosStmt->bind_param("i", $id_usuario);
$favoritosStmt->execute();
$favoritos = $favoritosStmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Favoritos | Ludus</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/perfil.css">
  <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <!-- Cabeçalho -->
  <?php include __DIR__ . '/headers/header_selector.php'; ?>

  <main class="perfil-container">
    <section class="perfil-top">
      <div class="perfil-info">
        <img src="<?php echo htmlspecialchars($foto_perfilPerfil); ?>" alt="Foto de perfil" class="foto-perfil">
        <h1 class="perfil-nome"><?php echo htmlspecialchars($nomeUsuario); ?></h1>
      </div>
      <a href="editar_perfil.php" class="btn-editar">Editar perfil</a>
    </section>

    <nav class="perfil-nav">
      <a href="perfil.php">Perfil</a>
      <a href="perfil_avaliacoes.php">Avaliações</a>
      <a href="perfil_favoritos.php" class="ativo">Favoritos</a>
      <a href="perfil_curtidas.php">Curtidas</a>
    </nav>

    <section class="recentes">
      <div class="avaliacoes-recentes">
        <?php if ($favoritos->num_rows > 0): ?>
          <?php while ($row = $favoritos->fetch_assoc()): ?>
            <a href="dashboard.php?id=<?php echo $row['id_jogo']; ?>" class="avaliacao-link">
              <div class="avaliacao">
                <img src="<?php echo htmlspecialchars($row['imagem_jogo']); ?>" alt="<?php echo htmlspecialchars($row['nome_jogo']); ?>">
                <div class="avaliacao-info">
                  <h3><?php echo htmlspecialchars($row['nome_jogo']); ?></h3>
                  <p><?php echo htmlspecialchars(mb_strimwidth($row['descricao'], 0, 100, "...")); ?></p>
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="color: #888; text-align: center;">Você ainda não favoritou nenhum jogo.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <?php include __DIR__ . '/footers/footer.php'; ?>

</body>
</html>
