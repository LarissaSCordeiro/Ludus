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
  <title>Ludus | Jogos Indie BR</title>
  <link rel="stylesheet" href="./css/style.css" />
  <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    .edit-form-container {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      width: 90%;
      max-width: 1100px;
      margin: 40px auto;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      z-index: 4;
    }

    .edit-image {
      flex: 1;
      min-width: 300px;
      background: linear-gradient(145deg, rgba(43, 43, 68, 0.9), rgba(30, 30, 48, 0.95));
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: start;
      padding: 20px;
      width: 40%;
      max-width: 400px;
      box-sizing: border-box;
    }

    .edit-image img {
      width: 80%;
      max-height: 80%;
      object-fit: cover;
      border-radius: 20px;
      margin-bottom: 10px;
    }

    .drop-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
    }

    .drop-area {
      border: 2px dashed #aaa;
      border-radius: 15px;
      width: 90%;
      max-width: 300px;
      margin-top: 10px;
      padding: 12px;
      text-align: center;
      color: #ccc;
      cursor: pointer;
      background-color: #2b2b44;
      transition: background 0.3s ease;
      font-size: 0.9rem;
      position: relative;
    }

    .drop-area input[type="file"] {
      display: block;
      margin: 10px auto 0 auto;
      width: 100%;
      max-width: 95%;
      color: #ccc;
      background: transparent;
      border: none;
      font-size: 1rem;
      font-family: inherit;
      padding: 6px 0;
      cursor: pointer;
    }
    .drop-area input[type="file"]::-webkit-file-upload-button {
      background: #26203f;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 6px 16px;
      font-size: 1rem;
      font-family: inherit;
      cursor: pointer;
      margin-right: 10px;
      transition: background 0.2s;
    }
    .drop-area input[type="file"]:hover::-webkit-file-upload-button {
      background: #3a3a5a;
    }
    .drop-area input[type="file"]::-ms-browse {
      background: #26203f;
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 6px 16px;
      font-size: 1rem;
      font-family: inherit;
      cursor: pointer;
      margin-right: 10px;
    }
    .drop-area input[type="file"]:focus {
      outline: none;
    }

    .drop-area:hover {
      background-color: #3a3a5a;
    }

    .form-flex-wrapper {
      display: flex;
      flex-direction: row;
      width: 100%;
      background-color: none;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      flex-wrap: wrap;
    }

    .form-wrapper {
      width: 60%;
      box-sizing: border-box;
      position: relative;
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      min-height: 0;
      height: auto;
    }

    .form-animation-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
      min-height: 0;
      overflow: hidden;
    }

    .input-style {
      height: 50px !important;
      border: 1px solid #0e0a1a !important;
      background-color: #0e0a1a !important;
      width: 80% !important;
      color: #d9d9d9 !important;
    }

    .input-style-container {
      display: flex;
      justify-content: center;
    }

    .edit-form,
    .edit-fields {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(145deg, rgba(43, 43, 68, 0.9), rgba(30, 30, 48, 0.95));
      padding: 30px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      transition: opacity 0.4s ease;
    }

    .edit-form h2,
    .edit-fields h2 {
      color: #f4961e;
      font-size: 1.8rem;
      text-align: center;
      border-bottom: 2px solid #f4961e;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    textarea {
      min-width: 0;
      max-width: 100%;
      resize: vertical;
      max-height: 200px;
      overflow-y: auto;
      box-sizing: border-box;
      color: #d9d9d9;
      background: #0e0a1a;
      border-radius: 20px;
      border: 1px solid #0e0a1a;
      padding: 10px;
    }

    #formStep2 {
      flex: 1;
      max-height: 100%;
      background-color: #1b1c2e;
      z-index: 2;
      position: relative;
      overflow-y: auto;
      padding-right: 10px;
      min-height: 0;
      height: calc(100vh - 10px);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 20px;
      border: 1px solid #ccc;
      outline: none;
    }

    .button-align {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 60px;
    }

    button {
      background-color: #2e7d32;
      color: white;
      border: none;
      width: 80%;
      padding: 12px 20px;
      border-radius: 20px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background-color: #1b5e20;
      transform: scale(1.05);
    }

    #salvarBtn {
      background-color: #f4961e;
      color: #ffffffff;
    }

    #salvarBtn:hover {
      background-color: #925200;
    }

    .seta-icon {
      transition: transform 0.3s ease;
    }

    @keyframes setaDeslizaDireita {
      0%, 100% { transform: translateX(0); }
      50% { transform: translateX(6px); }
    }
    @keyframes setaDeslizaEsquerda {
      0%, 100% { transform: translateX(0); }
      50% { transform: translateX(-6px); }
    }
    #continuarBtn:hover .fa-arrow-right {
      animation: setaDeslizaDireita 0.6s infinite;
    }
    #voltarBtn:hover .fa-arrow-left {
      animation: setaDeslizaEsquerda 0.6s infinite;
    }
    .hidden {
      display: none;
    }
    .slide-in-right {
      animation: slideInRight 0.4s ease forwards;
    }
    .slide-in-left {
      animation: slideInLeft 0.4s ease forwards;
    }
    @keyframes slideInRight {
      from { opacity: 0; transform: translateX(60px); }
      to { opacity: 1; transform: translateX(-10px); }
    }
    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-60px); }
      to { opacity: 1; transform: translateX(0); }
    }
    /* Mobile */
    @media (max-width: 768px) {
      .edit-form-container {
        flex-direction: column;
        align-items: center;
      }
      .edit-image,
      .form-wrapper {
        width: 100%;
        max-width: 100%;
      }
      .edit-image {
        margin-bottom: 1rem;
      }
      .edit-fields,
      .edit-form {
        padding: 1rem;
      }
      .form-wrapper {
        overflow: visible;
      }
      .form-animation-wrapper {
        position: relative;
        overflow: hidden;
      }
      .edit-form,
      .edit-fields {
        position: relative;
      }
    }
    /* Desktop (lado a lado) */
    /* Responsivo */
    @media (max-width: 768px) {
      .form-flex-wrapper {
        flex-direction: column;
      }
      .edit-image,
      .form-wrapper {
        width: 100%;
      }
    }
    /* Modal */
    .modal.hidden-force {
      display: none !important;
      opacity: 0 !important;
      visibility: hidden !important;
      pointer-events: none !important;
    }
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(15, 15, 30, 0.75);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 999;
    }
    .modal-content {
      background: linear-gradient(145deg, rgba(43, 43, 68, 0.95), rgba(30, 30, 48, 0.98));
      padding: 30px;
      border-radius: 20px;
      color: #fff;
      max-width: 500px;
      width: 90%;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      animation: fadeInUp 0.4s ease forwards;
      opacity: 0;
    }
    .modal-content h3 {
      font-size: 1.6rem;
      margin-bottom: 10px;
      color: #f4961e;
    }
    .modal-content p {
      margin-bottom: 20px;
      font-size: 1rem;
    }
    .modal-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
    }
    .btn-vermelho {
      background-color: #c62828;
      color: white;
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .btn-vermelho:hover {
      background-color: #a21515;
    }
    .btn-verde {
      background-color: #2e7d32;
      color: white;
      padding: 10px 20px;
      border-radius: 20px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    .btn-verde:hover {
      background-color: #1b5e20;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(40px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .info-card {
      background-color: #2b2b44;
      border-radius: 15px;
      padding: 15px 20px;
      margin-bottom: 15px;
      color: white;
      font-size: 0.95rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    /* Choices.js ajustes visuais */
    .choices__inner {
      background-color: rgba(30, 30, 48, 0.95) !important;
      color: #f5f5f5 !important;
      border: none !important;
      border-radius: 16px !important;
    }
    .choices__item {
      background-color: #0e0a1a !important;
      color: #f5f5f5 !important;
      padding: 4px 10px !important;
      margin: 3px 5px !important;
      display: flex;
      align-items: center;
      border: none !important;
    }
    .choices__list--dropdown,
    .choices__list[aria-expanded] {
      background-color: #1f2235 !important;
      border-radius: 12px !important;
      border: 1px solid #0e0a1a !important;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
      color: #f1f1f1;
      padding: 8px 0;
      z-index: 999;
    }
    .choices__list--dropdown .choices__item {
      padding: 8px 16px;
      color: #f1f1f1 !important;
      background-color: rgba(30, 30, 48, 0.95) !important;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }
    .choices__list--dropdown .choices__item--selectable.is-highlighted {
      background-color: #2a2d45 !important;
      color: #fff !important;
    }
    .choices__input {
      background-color: #2b2b44 !important;
      padding-left: 8px !important;
      border-radius: 16px !important;
      color: #f5f5f5 !important;
    }
    .choices__button {
      border-radius: 50% !important;
      background-color: #c0392b !important;
      color: white !important;
      font-size: 12px !important;
      border: none !important;
      width: 20px !important;
      height: 20px !important;
      line-height: 20px !important;
      text-align: center !important;
      margin-left: 6px !important;
      padding: 0 !important;
      display: inline-block !important;
      vertical-align: middle !important;
      transition: background-color 0.2s ease-in-out;
    }
    .choices__button:hover {
      background-color: #75231bff !important;
    }
    .drop-area.highlight-drop {
      background-color: #0e0a1a !important;
      border: 2px dashed #00aaff;
      transition: background-color 0.3s ease;
    }
  </style>
</head>
<body>
<!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_selector.php'; ?>
<main>
  <div class="edit-form-container">
    <?php if ($erros): ?>
      <div class="error-message" style="width:100%;">
        <ul style="margin:0; padding-left:1rem;">
          <?php foreach ($erros as $e): ?>
            <li><?php echo htmlspecialchars($e); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form id="cadastroForm" action="cadastro_jogo.php" method="post" enctype="multipart/form-data" class="form-flex-wrapper">
      <!-- Coluna da imagem -->
      <div class="edit-image">
        <img src="<?php echo isset($_POST['imagem']) ? htmlspecialchars($_POST['imagem']) : 'img/jogos/default.png'; ?>" alt="Imagem do jogo" id="previewImg" style="object-fit:cover;">
        <div class="drop-wrapper">
          <div class="drop-area" id="dropArea">
            Arraste nova imagem ou clique para escolher
            <input class="file-input" type="file" id="fileInput" name="imagem" accept="image/*">
          </div>
        </div>
      </div>
      <!-- Coluna dos campos -->
      <div class="form-wrapper">
        <div class="form-animation-wrapper">
          <!-- Etapa 1 -->
          <div class="edit-form" id="formStep1">
            <h2>Cadastrar Jogo</h2>
            <div class="form-group">
              <label for="titulo" class="input-label">Título do jogo <span style="color:#f4961e;">*</span></label>
              <div class="input-style-container">
                <input class="input-style" id="titulo" type="text" name="titulo" placeholder="Digite o título do jogo" value="<?php echo htmlspecialchars($_POST['titulo'] ?? ''); ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="estudio" class="input-label">Estúdio (publisher)</label>
              <div class="input-style-container">
                <input class="input-style" id="estudio" type="text" name="estudio" placeholder="Digite o estúdio" value="<?php echo htmlspecialchars($_POST['estudio'] ?? ''); ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="desenvolvedor" class="input-label">Desenvolvedor <span style="color:#f4961e;">*</span></label>
              <div class="input-style-container">
                <input class="input-style" id="desenvolvedor" type="text" name="desenvolvedor" placeholder="Digite o desenvolvedor" value="<?php echo htmlspecialchars($_POST['desenvolvedor'] ?? ''); ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="data_lancamento" class="input-label">Data de lançamento</label>
              <div class="input-style-container">
                <input class="input-style" id="data_lancamento" type="date" name="data_lancamento" placeholder="Data de lançamento" value="<?php echo htmlspecialchars($_POST['data_lancamento'] ?? ''); ?>">
              </div>
            </div>
            <div class="button-align" style="margin-top: 0; justify-content: center;">
              <button type="button" id="continuarBtn" style="width: 80%; min-width: 180px; margin: 10px auto 0 auto; display: block;">
                Continuar <i class="fas fa-arrow-right seta-icon" style="margin-left: 8px;"></i>
              </button>
            </div>
          </div>
          <!-- Etapa 2 -->
          <div class="edit-fields hidden" id="formStep2">
            <h2>Informações do Jogo</h2>
            <div class="form-group">
              <label for="descricao">Descrição</label>
              <textarea name="descricao" rows="4"><?php echo htmlspecialchars($_POST['descricao'] ?? ''); ?></textarea>
            </div>
            <!-- Gêneros -->
            <div class="info-card">
              <h3>Gêneros:</h3>
              <select id="generos-select" name="generos[]" multiple>
                <?php foreach ($generos as $g): ?>
                  <option value="<?php echo (int)$g['id']; ?>" <?php echo in_array((int)$g['id'], $selGeneros ?? []) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($g['nome']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <!-- Plataformas -->
            <div class="info-card">
              <h3>Plataformas:</h3>
              <select id="plataformas-select" name="plataformas[]" multiple>
                <?php foreach ($plataformas as $p): ?>
                  <option value="<?php echo (int)$p['id']; ?>" <?php echo in_array((int)$p['id'], $selPlats ?? []) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($p['nome']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="button-align">
              <button type="button" id="voltarBtn">
                <i class="fas fa-arrow-left seta-icon" style="margin-right: 8px;"></i> Voltar
              </button>
              <button type="button" id="salvarBtn">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Salvar Jogo
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</main>

<!-- Modal de confirmação -->
<div id="confirmModal" class="modal hidden-force">
  <div class="modal-content">
    <h3><i class="fas fa-exclamation-triangle"></i> Tem certeza?</h3>
    <p>Você deseja cadastrar este jogo? Essa ação <strong>não poderá ser desfeita</strong>.</p>
    <div class="modal-buttons">
      <button id="cancelarConfirmacao">
        <i class="fas fa-times-circle"></i> Cancelar
      </button>
      <button id="confirmarBtn" class="btn-vermelho">
        <i class="fas fa-check-circle"></i> Confirmar
      </button>
    </div>
  </div>
</div>

<!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Preview da imagem
    const dropArea = document.getElementById("dropArea");
    const fileInput = document.getElementById("fileInput");
    const previewImg = document.getElementById("previewImg");

    dropArea?.addEventListener("click", () => fileInput.click());

    fileInput?.addEventListener("change", (e) => {
      const file = e.target.files[0];
      showPreview(file);
      resetDropStyle();
    });

    dropArea?.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropArea.classList.add("highlight-drop");
    });

    dropArea?.addEventListener("dragleave", () => {
      resetDropStyle();
    });

    dropArea?.addEventListener("drop", (e) => {
      e.preventDefault();
      const file = e.dataTransfer.files[0];
      fileInput.files = e.dataTransfer.files;
      showPreview(file);
      resetDropStyle();
    });

    function showPreview(file) {
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = () => {
          previewImg.src = reader.result;
        };
        reader.readAsDataURL(file);
      } else if (file) {
        alert("Por favor, selecione um arquivo de imagem válido.");
      }
    }

    function resetDropStyle() {
      dropArea.classList.remove("highlight-drop");
    }

    // Navegação entre etapas
    const continuarBtn = document.getElementById("continuarBtn");
    const voltarBtn = document.getElementById("voltarBtn");
    const formStep1 = document.getElementById("formStep1");
    const formStep2 = document.getElementById("formStep2");

    continuarBtn?.addEventListener("click", () => {
      formStep1.classList.add("hidden");
      formStep2.classList.remove("hidden");
      formStep2.classList.remove("slide-in-right");
      void formStep2.offsetWidth;
      formStep2.classList.add("slide-in-left");
    });

    voltarBtn?.addEventListener("click", () => {
      formStep2.classList.add("hidden");
      formStep1.classList.remove("hidden");
      formStep1.classList.remove("slide-in-right");
      void formStep1.offsetWidth;
      formStep1.classList.add("slide-in-left");
    });

    // Animação das setas
    function animarIconeSeta(botao, direcao = 'right') {
      const icone = botao?.querySelector('.seta-icon');
      if (!icone) return;
      botao.addEventListener('mouseenter', () => {
        icone.style.transform = `translateX(${direcao === 'right' ? '6px' : '-6px'})`;
      });
      botao.addEventListener('mouseleave', () => {
        icone.style.transform = 'translateX(0)';
      });
    }
    animarIconeSeta(continuarBtn, 'right');
    animarIconeSeta(voltarBtn, 'left');

    // Modal de confirmação
    const salvarBtn = document.getElementById("salvarBtn");
    const confirmModal = document.getElementById("confirmModal");
    const cancelarBtn = document.getElementById("cancelarConfirmacao");
    const confirmarBtn = document.getElementById("confirmarBtn");
    const cadastroForm = document.getElementById("cadastroForm");

    salvarBtn?.addEventListener("click", function (e) {
      e.preventDefault();
      confirmModal.classList.remove("hidden-force");
    });

    cancelarBtn?.addEventListener("click", function () {
      confirmModal.classList.add("hidden-force");
    });

    confirmarBtn?.addEventListener("click", function () {
      confirmModal.classList.add("hidden-force");
      if (cadastroForm) {
        cadastroForm.submit();
      } else {
        alert("Formulário principal não encontrado.");
      }
    });

    // Choices.js para selects múltiplos
    new Choices('#generos-select', {
      removeItemButton: true,
      placeholder: true,
      placeholderValue: 'Selecione gêneros...'
    });
    new Choices('#plataformas-select', {
      removeItemButton: true,
      placeholder: true,
      placeholderValue: 'Selecione plataformas...'
    });
  });
</script>
</body>
</html>
