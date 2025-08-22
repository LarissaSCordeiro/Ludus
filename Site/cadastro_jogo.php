<?php
session_start();
require_once 'config.php';

// ======= Autorização =======
if (!isset($_SESSION['id_usuario'])) {
  header('Location: login.php');
  exit();
}
if (!in_array($_SESSION['tipo'] ?? '', ['desenvolvedor','administrador'])) {
  http_response_code(403);
  echo "Acesso negado: somente desenvolvedores ou administradores podem cadastrar jogos.";
  exit();
}

$userId = (int)$_SESSION['id_usuario'];
$erros = [];
$sucesso = '';

// ======= Carrega listas (gêneros e plataformas) =======
$generos = [];
$plataformas = [];

$resG = $mysqli->query("SELECT id, nome FROM genero ORDER BY nome");
if ($resG) { while ($row = $resG->fetch_assoc()) { $generos[] = $row; } }

$resP = $mysqli->query("SELECT id, nome FROM plataforma ORDER BY nome");
if ($resP) { while ($row = $resP->fetch_assoc()) { $plataformas[] = $row; } }

// ======= Processamento do POST =======
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Campos
  $titulo        = trim($_POST['titulo'] ?? '');
  $descricao     = trim($_POST['descricao'] ?? '');
  $estudio       = trim($_POST['estudio'] ?? '');
  $desenvolvedor = trim($_POST['desenvolvedor'] ?? '');
  $dataLanc      = trim($_POST['data_lancamento'] ?? '');
  $selGeneros    = array_map('intval', $_POST['generos'] ?? []);
  $selPlats      = array_map('intval', $_POST['plataformas'] ?? []);

  // Validação
  if ($titulo === '')         { $erros[] = "Título do jogo é obrigatório."; }
  if ($desenvolvedor === '')  { $erros[] = "Desenvolvedor é obrigatório."; }
  if ($estudio === '')        { $estudio = $desenvolvedor; } // fallback pedido

  // Validação da data (opcional)
  $data_lancamento = null;
  if ($dataLanc !== '') {
    // Aceita formatos yyyy-mm-dd
    $d = DateTime::createFromFormat('Y-m-d', $dataLanc);
    $ok = $d && $d->format('Y-m-d') === $dataLanc;
    if (!$ok) {
      $erros[] = "Data de lançamento inválida (use AAAA-MM-DD).";
    } else {
      $data_lancamento = $dataLanc;
    }
  }

  // Upload da imagem (opcional)
  $destImagem = 'img/jogos/default.png';
  $subiuImagem = isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE;

  if ($subiuImagem) {
    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
      $erros[] = "Falha no upload da imagem (erro {$_FILES['imagem']['error']}).";
    } else {
      $tamanhoMax = 5 * 1024 * 1024; // 5MB
      if ($_FILES['imagem']['size'] > $tamanhoMax) {
        $erros[] = "A imagem excede 5MB.";
      } else {
        $nomeOrig = $_FILES['imagem']['name'];
        $ext = strtolower(pathinfo($nomeOrig, PATHINFO_EXTENSION));
        $permitidas = ['jpg','jpeg','png','webp'];
        if (!in_array($ext, $permitidas, true)) {
          $erros[] = "Formato de imagem inválido. Use JPG, PNG ou WEBP.";
        } else {
          if (!is_dir('img/jogos')) { @mkdir('img/jogos', 0775, true); }
          $novoNome = uniqid('jogo_', true) . '.' . $ext;
          $caminho  = 'img/jogos/' . $novoNome;
          if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho)) {
            $erros[] = "Não foi possível salvar a imagem.";
          } else {
            $destImagem = $caminho;
          }
        }
      }
    }
  }

  // Se tudo ok, insere
  if (!$erros) {
    $mysqli->begin_transaction();
    try {
      // jogo
      $stmt = $mysqli->prepare("
        INSERT INTO jogo (nome, estudio, desenvolvedor, descricao, imagem, data_lancamento, id_usuario)
        VALUES (?, ?, ?, ?, ?, ?, ?)
      ");
      $stmt->bind_param(
        'ssssssi',
        $titulo,
        $estudio,
        $desenvolvedor,
        $descricao,
        $destImagem,
        $data_lancamento, // pode ser null
        $userId
      );
      $stmt->execute();
      $jogoId = $stmt->insert_id;
      $stmt->close();

      // gêneros
      if (!empty($selGeneros)) {
        $stmtG = $mysqli->prepare("INSERT INTO jogo_possui_genero (id_jogo, id_genero) VALUES (?, ?)");
        foreach ($selGeneros as $idg) {
          $stmtG->bind_param('ii', $jogoId, $idg);
          $stmtG->execute();
        }
        $stmtG->close();
      }

      // plataformas
      if (!empty($selPlats)) {
        $stmtP = $mysqli->prepare("INSERT INTO jogo_possui_plataforma (id_jogo, id_plataforma) VALUES (?, ?)");
        foreach ($selPlats as $idp) {
          $stmtP->bind_param('ii', $jogoId, $idp);
          $stmtP->execute();
        }
        $stmtP->close();
      }

      $mysqli->commit();

      // Mensagem e redirecionamento
      $_SESSION['flash_success'] = "Jogo cadastrado com sucesso!";
      header('Location: filtragem.php');
      exit();

    } catch (Throwable $e) {
      $mysqli->rollback();
      // Em caso de erro, se imagem foi salva agora, tenta remover
      if ($subiuImagem && isset($caminho) && file_exists($caminho)) { @unlink($caminho); }
      $erros[] = "Erro ao salvar no banco: " . $e->getMessage();
    }
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cadastrar Jogo | Ludus</title>
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
<header>
  <figure class="logo">
    <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
  </figure>

  <nav id="nav" class="nav-links">
    <a href="filtragem.php">Games</a>
    <a href="perfil.php">
      <img src="img/usuarios/default.png" alt="Perfil do usuário" class="user-avatar">
    </a>
  </nav>

  <div class="search-container">
    <form action="filtragem.php" method="GET">
      <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
      <i class="fas fa-search icon"></i>
    </form>
  </div>

  <div class="hamburger" onclick="toggleMenu()">☰</div>
</header>

<main class="perfil-container" style="max-width:1000px;">
  <section class="perfil-top" style="border-bottom:none; padding-bottom:0;">
    <div class="perfil-info" style="display:flex; align-items:center; gap:1rem;">
      <img src="img/jogos/default.png" alt="Capa do jogo" class="foto-perfil" style="width:70px;height:70px;border-radius:8px;border:2px solid #333;object-fit:cover;">
      <h1 class="perfil-nome" style="color:#d9d9d9;">Cadastrar Jogo</h1>
    </div>
  </section>

  <?php if ($erros): ?>
    <div class="error-message">
      <ul style="margin:0; padding-left:1rem;">
        <?php foreach ($erros as $e): ?>
          <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="cadastro_jogo.php" method="post" enctype="multipart/form-data" style="margin-top:1rem;">
    <!-- Imagem -->
    <label style="display:block; margin:10px 0; color:#d9d9d9;">Imagem (capa)</label>
    <input type="file" name="imagem" accept=".jpg,.jpeg,.png,.webp" />

    <!-- Título -->
    <label style="display:block; margin:10px 0; color:#d9d9d9;">Título do jogo *</label>
    <input type="text" name="titulo" placeholder="Ex.: Unsighted" value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>" required>

    <!-- Descrição -->
    <label style="display:block; margin:10px 0; color:#d9d9d9;">Descrição</label>
    <textarea name="descricao" rows="5" placeholder="Descreva o jogo..." style="width:100%; padding:1rem; border-radius:12px; background:#26203f; color:#d9d9d9; border:1px solid #999; font-size:15px;"><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>

    <!-- Estúdio / Desenvolvedor -->
    <div style="display:flex; gap:1rem; flex-wrap:wrap;">
      <div style="flex:1; min-width:260px;">
        <label style="display:block; margin:10px 0; color:#d9d9d9;">Estúdio (publisher)</label>
        <input type="text" name="estudio" placeholder="Se vazio, usaremos o desenvolvedor" value="<?php echo htmlspecialchars($_POST['estudio'] ?? ''); ?>">
      </div>
      <div style="flex:1; min-width:260px;">
        <label style="display:block; margin:10px 0; color:#d9d9d9;">Desenvolvedor *</label>
        <input type="text" name="desenvolvedor" placeholder="Nome do desenvolvedor/estúdio" value="<?php echo htmlspecialchars($_POST['desenvolvedor'] ?? ''); ?>" required>
      </div>
    </div>

    <!-- Data de Lançamento -->
    <label style="display:block; margin:10px 0; color:#d9d9d9;">Data de lançamento</label>
    <input type="date" name="data_lancamento" value="<?php echo htmlspecialchars($_POST['data_lancamento'] ?? ''); ?>">

    <!-- Gêneros -->
    <div class="categoria" style="padding:0; margin-top:1rem;">
      <h2 style="font-size:1.2rem; margin-bottom:0.5rem;">Gêneros</h2>
      <div style="display:flex; gap:1rem; flex-wrap:wrap;">
        <?php foreach ($generos as $g): ?>
          <?php $ck = in_array((int)$g['id'], $selGeneros ?? []) ? 'checked' : ''; ?>
          <label style="background:#1f1b2e; padding:8px 12px; border-radius:8px; cursor:pointer;">
            <input type="checkbox" name="generos[]" value="<?php echo (int)$g['id']; ?>" <?php echo $ck; ?>>
            <?php echo htmlspecialchars($g['nome']); ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Plataformas -->
    <div class="categoria" style="padding:0; margin-top:1rem;">
      <h2 style="font-size:1.2rem; margin-bottom:0.5rem;">Plataformas</h2>
      <div style="display:flex; gap:1rem; flex-wrap:wrap;">
        <?php foreach ($plataformas as $p): ?>
          <?php $ck = in_array((int)$p['id'], $selPlats ?? []) ? 'checked' : ''; ?>
          <label style="background:#1f1b2e; padding:8px 12px; border-radius:8px; cursor:pointer;">
            <input type="checkbox" name="plataformas[]" value="<?php echo (int)$p['id']; ?>" <?php echo $ck; ?>>
            <?php echo htmlspecialchars($p['nome']); ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <button id="btn" type="submit" style="margin-top:1.5rem;"><strong>Salvar</strong></button>
  </form>
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
