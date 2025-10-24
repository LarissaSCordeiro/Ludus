<!-------------------------------------------------------------------------------- PHP -------------------------------------------------------------------------------------------->
<?php
session_start();
require_once("config.php");

$query_usu = "SELECT * FROM usuario";
$query_coment = "SELECT comentario.id AS id_comentario, comentario.data_comentario, comentario.texto, usuario.nome AS nome_usuario, usuario.email, usuario.foto_perfil, avaliacao.nota, jogo.nome AS nome_jogo FROM
 comentario INNER JOIN usuario ON comentario.id_usuario = usuario.id INNER JOIN avaliacao ON comentario.id_avaliacao = avaliacao.id INNER JOIN jogo ON avaliacao.id_jogo = jogo.id";
 

if ($_SESSION['tipo_usuario'] != 'administrador') {
	header('Location: paginainicial.php');
    exit();
}

function excluirComentario($mysqli, $id_coment) {
    $query_u = "DELETE FROM comentario WHERE id = $id_coment";
    if ($mysqli->query($query_u)) {
        echo "<p>Comentário excluído</p>";
    } else {
        echo $mysqli->error;
    }
}

if (isset($_POST['excluir_usu']) && is_numeric($_POST['excluir_usu'])) {
    $id_usu = intval($_POST['excluir_usu']);

    $mysqli->query("DELETE FROM comentario WHERE id_usuario = $id_usu");
    $mysqli->query("DELETE FROM avaliacao WHERE id_usuario = $id_usu");
    $mysqli->query("DELETE FROM usuario_favorita_jogo WHERE id_usuario = $id_usu");
    $mysqli->query("DELETE FROM usuario_curte_avaliacao WHERE id_usuario = $id_usu");

    $jogos = $mysqli->query("SELECT id FROM jogo WHERE id_usuario = $id_usu");
    while ($jogo = $jogos->fetch_assoc()) {
        $id_jogo = $jogo['id'];
        $mysqli->query("DELETE FROM jogo_possui_genero WHERE id_jogo = $id_jogo");
        $mysqli->query("DELETE FROM jogo_possui_plataforma WHERE id_jogo = $id_jogo");
        $mysqli->query("DELETE FROM usuario_favorita_jogo WHERE id_jogo = $id_jogo");
    }

    $mysqli->query("DELETE FROM jogo WHERE id_usuario = $id_usu");

    $query_u = "DELETE FROM usuario WHERE id = $id_usu";
    if ($mysqli->query($query_u)) {
        session_destroy();
        echo "<p>Usuário excluído</p>";
    } else {
        echo $mysqli->error;
    }
}


elseif (isset($_POST['excluir_coment']) && is_numeric($_POST['excluir_coment'])) {
    $id_coment = intval($_POST['excluir_coment']);
    excluirComentario($mysqli, $id_coment);
}


$usuario = $mysqli->query($query_usu);
$comentario = $mysqli->query($query_coment);

$count_usu = $usuario->num_rows;
$count_coment = $comentario->num_rows;
?>

<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/gerenc.css" />
	<script defer src="./js/script.js"> </script>
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <!-------------------------------------------------------------------------------- Interface -------------------------------------------------------------------------------------->
    
    <!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_admin.php'; ?>
  <div class="main_aside">
    <main id="main_gerencia">
        <?php
        function usuarios()
        {
            global $usuario, $count_usu; ?>

            <?php echo "<h2> Usuários - $count_usu </h2>";

            if ($usuario->num_rows > 0) {
                while ($dados_usu = $usuario->fetch_assoc()) { ?>
                    <div class="usuario-card">
                        <div class="usuario-info">
                            <div class="avatar">
                                <img src="<?php echo $dados_usu["foto_perfil"]; ?>" alt="img">
                            </div>
                            <div class="detalhes">
                                <p><strong>ID:</strong> <?php echo $dados_usu["id"]; ?></p>
                                <p><strong>Nome:</strong> <?php echo $dados_usu["nome"]; ?></p>
                                <p><strong>Email:</strong> <?php echo $dados_usu["email"]; ?></p>
                                <p><strong>Tipo:</strong> <?php echo $dados_usu["tipo"]; ?></p>
                                <p><strong>Cadastro:</strong> <?php echo $dados_usu["data_cadastro"]; ?></p>
                            </div>
                        </div>
                        <form method="POST" class="form-excluir">
                            <input type="hidden" name="excluir_usu" value="<?php echo $dados_usu['id']; ?>">
                            <button type="button" class="abrir-modal" data-id="<?php echo $dados_usu['id']; ?>" data-tipo="usuario">
                                Excluir Usuário
                            </button>
                        </form>
                    </div>
                <?php }
            } else {
                echo "<h4>Nenhum usuário foi cadastrado</h4>";
            }
        }

        function comentarios()
        {
            global $comentario, $count_coment; ?>

            <?php
            echo "<h2> Comentários - $count_coment</h2>";

            if ($comentario->num_rows > 0) {
                while ($comentarios = $comentario->fetch_assoc()) { ?>
                    <section class="coment_usu">
                        <h1 class="nome-jogo"><?php echo $comentarios["nome_jogo"]; ?></h1>

                        <figure class="dados_u">
                            <img src="<?php echo $comentarios["foto_perfil"]; ?>"
                                alt="Foto de perfil de <?php echo htmlspecialchars($comentarios['nome_usuario']); ?>"
                                class="img_coment">

                            <figcaption class="perfil-coment">
                                <div>
                                    <h4><?php echo $comentarios["nome_usuario"]; ?></h4>
                                    <p><?php echo $comentarios["data_comentario"]; ?></p>
                                    <p><?php echo "Nota: " . $comentarios["nota"]; ?></p>
                                </div>

                                <form method="POST" class="form-excluir-comentario">
                                    <input type="hidden" name="excluir_coment" value="<?php echo $comentarios['id_comentario']; ?>">
                                    <!-- Para exclusão de comentário -->
                                    <button type="button" class="abrir-modal btn-excluir-coment"
                                        data-id="<?php echo $comentarios['id_comentario']; ?>" data-tipo="comentario"
                                        aria-label="Excluir comentário de <?php echo htmlspecialchars($comentarios['nome_usuario']); ?>">
                                        Excluir Comentário
                                    </button>
                                </form>
                            </figcaption>
                        </figure>

                        <p class="form_com"><?php echo $comentarios["texto"]; ?></p>
                    </section>
                <?php }
            } else {
                echo "<h4>Nenhum usuário comentou ainda</h4>";
            }
        }

        if (isset($_POST['button_coment'])) {
            comentarios();
        } elseif (isset($_POST['button_usu'])) {
            usuarios();
        } else {
            usuarios();
        }
        ?>
    </main>
	 <aside id="aside_gerencia" >
	 <h2>Gerenciar</h2>
            <form method="POST">
                <input type="submit" name="button_coment" class="button_gen" value="Comentários">
                <input type="submit" name="button_usu" class="button_gen" value="Usuários">
            </form>
        </aside>
</div>
    <div id="modal-confirmacao" class="modal hidden-force">
        <div class="modal-content">
            <h3><i class="fas fa-exclamation-triangle"></i> Tem certeza?</h3>
            <p>Você deseja excluir esse item? Essa ação <strong>não poderá ser desfeita</strong>.</p>
            <form id="form-confirmacao" method="POST">
                <input type="hidden" name="excluir_usu" id="input-excluir-usu">
                <input type="hidden" name="excluir_coment" id="input-excluir-coment">
                <div class="modal-buttons">
                    <button type="button" class="btn-verde" onclick="fecharModal()">
                        <i class="fas fa-times-circle"></i>Cancelar</button>
                    <button type="submit" class="btn-vermelho">
                        <i class="fas fa-check-circle"></i>Confirmar</button>
                </div>
            </form>
        </div>
    </div>

<!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>

</body>

</html>