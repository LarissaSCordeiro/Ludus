<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$id_usuario = $_SESSION['user_id'];

// Pega dados do usuário
$stmt = $mysqli->prepare("SELECT nome, foto_perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
  echo "Usuário não encontrado.";
  exit();
}
$usuario = $result->fetch_assoc();
$nomeUsuario = $usuario['nome'];
$fotoPerfil = $usuario['foto_perfil'] ?: 'img/usuarios/default.png';

// Pega todas as avaliações do usuário
$avaliacoesStmt = $mysqli->prepare("
  SELECT j.nome AS nome_jogo, j.imagem AS imagem_jogo, a.texto, a.data_avaliacao
  FROM avaliacao a
  JOIN jogo j ON a.id_jogo = j.id
  WHERE a.id_usuario = ?
  ORDER BY a.data_avaliacao DESC
");
$avaliacoesStmt->bind_param("i", $id_usuario);
$avaliacoesStmt->execute();
$avaliacoes = $avaliacoesStmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Avaliações | Ludus</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="./css/perfil.css">
  <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <header>
    <figure class="logo">
      <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
    </figure>

    <nav id="nav" class="nav-links">
      <a href="filtragem.php">Games</a>
      <a href="perfil.php">
        <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Perfil do usuário" class="user-avatar">
      </a>
    </nav>

     <div class="search-container">
            <form action="pesquisa.php" method="GET">
                <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
                <i class="fas fa-search icon"></i>
            </form>
        </div>

    <div class="hamburger" onclick="toggleMenu()">☰</div>
  </header>

  <main class="perfil-container">
    <section class="perfil-top">
      <div class="perfil-info">
        <img src="<?php echo htmlspecialchars($fotoPerfil); ?>" alt="Foto de perfil" class="foto-perfil">
        <h1 class="perfil-nome"><?php echo htmlspecialchars($nomeUsuario); ?></h1>
      </div>
      <a href="editar_perfil.php" class="btn-editar">Editar perfil</a>
    </section>

    <nav class="perfil-nav">
      <a href="perfil.php">Perfil</a>
      <a href="perfil_avaliacoes.php" class="ativo">Avaliações</a>
      <a href="perfil_favoritos.php">Favoritos</a>
      <a href="perfil_curtidas.php">Curtidas</a>
    </nav>

    <section class="recentes">
      <div class="avaliacoes-recentes">
        <?php if ($avaliacoes->num_rows > 0): ?>
          <?php while ($row = $avaliacoes->fetch_assoc()): ?>
            <div class="avaliacao">
              <img src="<?php echo htmlspecialchars($row['imagem_jogo']); ?>" alt="<?php echo htmlspecialchars($row['nome_jogo']); ?>">
              <div class="avaliacao-info">
                <h3><?php echo htmlspecialchars($row['nome_jogo']); ?></h3>
                <p>"<?php echo htmlspecialchars($row['comentario']); ?>"</p>
                <small style="color: #888;"><?php echo date('d/m/Y', strtotime($row['data'])); ?></small>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="color: #888; text-align: center;">Nenhuma avaliação feita ainda.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer class="footer-nav">
    <div class="social-icons">
      <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
      <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i class="fab fa-github"></i></a>
    </div>
    <span>Ludus • v0.1</span>
  </footer>
</body>
</html>
