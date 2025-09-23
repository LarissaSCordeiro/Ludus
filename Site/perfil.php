<?php
session_start();
require_once("config.php");

$id_usuario = $_SESSION['id_usuario'];

// Pega os dados do usuário
$stmt = $mysqli->prepare("SELECT nome, foto_perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id_usuario']);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
  echo "Usuário não encontrado.";
  exit();
}

$usuario = $resultado->fetch_assoc();
$nomeUsuario = $usuario['nome'];
$foto_perfilPerfil = $usuario['foto_perfil'] ?: 'img/usuarios/default.png';

// Pega as 3 avaliações mais recentes do usuário
$avaliacoesStmt = $mysqli->prepare("
  SELECT 
    j.id AS id_jogo,
    j.nome AS nome_jogo, 
    j.imagem AS foto_perfil_jogo, 
    c.texto AS comentario,
    a.data_avaliacao
  FROM avaliacao a
  JOIN jogo j ON a.id_jogo = j.id
  LEFT JOIN comentario c ON c.id_avaliacao = a.id
  WHERE a.id_usuario = ?
  ORDER BY a.data_avaliacao DESC
  LIMIT 3
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
  <title>Ludus | Jogos Indie BR</title>
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
      <a href="cadastro_jogo.php" class="btn-editar">Cadastrar jogo</a>
    </section>

    <nav class="perfil-nav">
      <a href="perfil.php" class="ativo">Perfil</a>
      <a href="perfil_avaliacoes.php">Avaliações</a>
      <a href="perfil_favoritos.php">Favoritos</a>
      <a href="perfil_curtidas.php">Curtidas</a>
    </nav>

    <section class="recentes">
      <h2 class="recently-reviewed-title">Avaliações Recentes</h2>
      <div class="avaliacoes-recentes">
        <?php if ($avaliacoes->num_rows > 0): ?>
          <?php while ($row = $avaliacoes->fetch_assoc()): ?>
            <a href="dashboard.php?id=<?php echo $row['id_jogo']; ?>" class="avaliacao-link">
              <div class="avaliacao">
                <img src="<?php echo htmlspecialchars($row['foto_perfil_jogo']); ?>"
                  alt="<?php echo htmlspecialchars($row['nome_jogo']); ?>">
                <div class="avaliacao-info">
                  <h3><?php echo htmlspecialchars($row['nome_jogo']); ?></h3>
                  <p>
                    "<?php echo htmlspecialchars(!empty($row['comentario']) ? $row['comentario'] : 'Você ainda não comentou nessa avaliação!'); ?>"
                  </p>
                </div>
              </div>
            </a>
          <?php endwhile; ?>
        <?php else: ?>
          <p style="color: #888; text-align: center;">Nenhuma avaliação feita ainda.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <!-- Rodapé -->
  <?php include __DIR__ . '/footers/footer.php'; ?>

</body>

</html>