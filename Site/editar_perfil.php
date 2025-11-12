<?php
session_start();
require_once("config.php");

$id_usuario = $_SESSION['id_usuario'];

// Pega os dados do usuário
$stmt = $mysqli->prepare("SELECT nome, email, foto_perfil, tipo FROM usuario WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Usuário não encontrado.";
    exit();
}

$usuario = $resultado->fetch_assoc();
$nomeUsuario = $usuario['nome'];
$emailUsuario = $usuario['email'];
$foto_perfilPerfil = $usuario['foto_perfil'] ?: 'img/usuarios/default.png';
$tipoUsuario = $usuario['tipo'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/editar_perfil.css">
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <?php include __DIR__ . '/headers/header_selector.php'; ?>

    <main class="edit-perfil-container">
        <div class="edit-perfil-card">

            <form id="editProfileForm" enctype="multipart/form-data" method="post" action="salvar_editar_perfil.php">
                <div class="edit-perfil-left">
                    <img src="<?php echo htmlspecialchars($foto_perfilPerfil); ?>" alt="Foto de perfil" class="foto-perfil">

                    <div class="drop-area" id="dropArea">
                        Clique ou arraste nova foto
                        <input type="file" id="fileInput" name="foto" accept="image/*" hidden>
                    </div>
                </div>

                <div class="edit-perfil-right">
                    <h2>Editar Perfil</h2>

                    <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nomeUsuario); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($emailUsuario); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="senha_atual">Senha atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>

                    <div class="form-group">
                        <label for="nova_senha">Nova senha (opcional)</label>
                        <input type="password" id="nova_senha" name="nova_senha" minlength="6">
                    </div>

                    <div class="form-group">
                        <label for="confirmar_senha">Confirmar nova senha</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="6">
                    </div>

                    <div class="button-align">
                        <button type="submit"><i class="fas fa-save"></i> Salvar alterações</button>
                        <a href="perfil.php" class="btn-cancelar"><i class="fas fa-arrow-left"></i> Cancelar</a>
                    </div>

                    <?php if ($tipoUsuario === 'jogador'): ?>
                    <div class="button-align" style="margin-top: 20px; border-top: 1px solid #444; padding-top: 20px;">
                        <button type="button" id="btnTornarDesenvolvedor" class="btn-developer">
                            <i class="fas fa-code"></i> Tornar-se Desenvolvedor
                        </button>
                        <p style="font-size: 0.85rem; color: #aaa; margin-top: 10px;">
                            Confirme sua identidade como um desenvolvedor de jogos. Você receberá um email de verificação.
                        </p>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/footers/footer.php'; ?>

    <script>
        const dropArea = document.getElementById("dropArea");
        const fileInput = document.getElementById("fileInput");
        const previewImg = document.querySelector(".edit-perfil-left img");

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

    <!-- Global toast (load before any inline scripts that may call LudusToast) -->
    <script src="js/toast.js"></script>

    <?php if (isset($_GET['erro'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const erro = "<?= $_GET['erro'] ?>";
                switch (erro) {
                    case "senha_incorreta":
                        LudusToast("Senha atual incorreta!", true);
                        break;
                    case "senha_nao_coincide":
                        LudusToast("As senhas não coincidem!", true);
                        break;
                    case "usuario_nao_encontrado":
                        LudusToast("Usuário não encontrado!", true);
                        break;
                }
            });
        </script>
    <?php endif; ?>

    <!-- Modal para solicitação de desenvolvedor -->
    <div id="developerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-code"></i> Solicitação de Desenvolvedor</h2>
                <button type="button" class="modal-close" id="modalClose">&times;</button>
            </div>
            
            <form id="developerForm" method="post">
                <div class="form-group">
                    <label for="motivo">Por que deseja ser desenvolvedor?</label>
                    <textarea 
                        id="motivo" 
                        name="motivo" 
                        placeholder="Conte-nos sobre seus jogos, portfólio, ou projetos que está desenvolvendo. Mínimo 10 caracteres..."
                        minlength="10"
                        maxlength="1000"
                        required
                        rows="6"
                    ></textarea>
                    <small id="charCount">0/1000</small>
                </div>
                
                <div class="modal-info">
                    <p><i class="fas fa-info-circle"></i> Você receberá um email com um link de verificação. O link expira em 48 horas.</p>
                </div>
                
                <div class="modal-buttons">
                    <button type="submit" class="btn-submit">Enviar</button>
                    <button type="button" class="btn-cancel" id="modalCancel">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/developer-request.js"></script>

</body>

</html>