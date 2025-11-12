<?php
session_start();
require_once("config.php");

$id_usuario = $_SESSION['id_usuario'];

// Pega os dados do usuário
$stmt = $mysqli->prepare("SELECT nome, foto_perfil, tipo FROM usuario WHERE id = ?");
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
$tipoUsuario = $usuario['tipo'];

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
        <h1 class="perfil-nome">
          <?php echo htmlspecialchars($nomeUsuario); ?>
          <?php if ($tipoUsuario === 'desenvolvedor'): ?>
            <span class="badge dev">DEV</span>
          <?php elseif ($tipoUsuario === 'administrador'): ?>
            <span class="badge adm">ADM</span>
          <?php endif; ?>
        </h1>
      </div>

      <a href="editar_perfil.php" class="icon-link" title="Editar perfil"><i class="fa-solid fa-user-pen"></i></a>
      <?php if ($tipoUsuario === 'administrador' || $tipoUsuario === 'desenvolvedor'): ?>
        <a href="cadastro_jogo.php" class="icon-link" title="Cadastrar jogo">
          <i class="fa-solid fa-gamepad"></i>
        </a>
      <?php endif; ?>
      <a href="logout.php" class="btn-logout">Sair da conta</a>
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

  <?php if (isset($_GET['msg'])): ?>
    <script src="js/toast.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const msg = "<?php echo $_GET['msg']; ?>";
        if (msg === "sucesso") {
          LudusToast("Perfil atualizado com sucesso!");
        } else if (msg === "erro") {
          LudusToast("Erro ao atualizar perfil.", true);
        } else if (msg === "erro_senha") {
          LudusToast("Senha atual incorreta.", true);
        }
      });
    </script>
  <?php endif; ?>

</body>


</html>

