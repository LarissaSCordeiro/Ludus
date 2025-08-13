<!-------------------------------------------------------------------------------- PHP -------------------------------------------------------------------------------------------->
<?php
ob_start(); 
session_start();
require_once "config.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (!empty($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enviar_comentario'])) {
        $comentario = trim($_POST['comentario']);

        $stmt = $mysqli->prepare("SELECT id FROM avaliacao WHERE id_usuario = ? AND id_jogo = ?");
        $stmt->bind_param("ii", $user_id, $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($avaliacao = $resultado->fetch_assoc()) {
            $id_avaliacao = $avaliacao['id'];

            $inserir = $mysqli->prepare("INSERT INTO comentario (id_usuario, texto, id_avaliacao) VALUES (?, ?, ?)");
            $inserir->bind_param("isi", $user_id, $comentario, $id_avaliacao);

            if ($inserir->execute()) {
                header("Location: dashboard.php?id=" . $id);
                exit(); 
            } else {
                $msg_erro  = "Não foi possível enviar seu comentário.";
            }

            $inserir->close();
        } else {
            unset($_POST['comentario']);
            unset($_POST['enviar_comentario']);
            $msg_erro = "Você precisa avaliar o jogo antes de comentar...";
        }

        $stmt->close();
    }
}

$stmt = $mysqli->prepare("SELECT nome, email, foto_perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

$consulta = $mysqli->prepare("SELECT jogo.id AS id_jogo, jogo.nome, jogo.descricao, jogo.imagem, jogo.data_lancamento, jogo.estudio, jogo.desenvolvedor, GROUP_CONCAT(DISTINCT 
genero.nome ORDER BY genero.nome SEPARATOR ', ') AS genero, GROUP_CONCAT(DISTINCT plataforma.nome ORDER BY plataforma.nome SEPARATOR ', ') AS plataforma FROM jogo LEFT JOIN
jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo LEFT JOIN genero ON genero.id = jogo_possui_genero.id_genero LEFT JOIN jogo_possui_plataforma ON jogo.id = jogo_possui_plataforma.id_jogo 
LEFT JOIN plataforma ON plataforma.id = jogo_possui_plataforma.id_plataforma WHERE jogo.id = ? GROUP BY jogo.id");
$consulta->bind_param("i", $id);
$consulta->execute();
$resultado = $consulta->get_result();
$jogo = $resultado->fetch_assoc();



$consulta = $mysqli->prepare("SELECT comentario.data_comentario, comentario.texto, usuario.nome AS nome_usuario, usuario.email, usuario.foto_perfil, avaliacao.nota FROM comentario
INNER JOIN usuario ON comentario.id_usuario = usuario.id INNER JOIN avaliacao ON comentario.id_avaliacao = avaliacao.id WHERE avaliacao.id_jogo = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$resultado = $consulta->get_result();

$comentarios = [];
while ($linha = $resultado->fetch_assoc()) {
    $comentarios[] = $linha;
}

// avaliações do jogo
// Busca a nota do usuário logado para este jogo
$nota_usuario = 0.0;

if (!empty($user_id)) {
    $stmt = $mysqli->prepare("SELECT nota FROM avaliacao WHERE id_jogo = ? AND id_usuario = ?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($avaliacao = $resultado->fetch_assoc()) {
        $nota_usuario = floatval($avaliacao['nota']);
    } else {
        $nota_usuario = 0.0; // Usuário ainda não avaliou
    }

    $stmt->close();
}



// Calcula a média das avaliações da comunidade
$media_comunidade = null;

$stmt = $mysqli->prepare("SELECT AVG(nota) FROM avaliacao WHERE id_jogo = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($media_comunidade);
$stmt->fetch();
$stmt->close();


// Busca o número de avaliações dadas no jogo
$stmtCountAvaliacoes = $mysqli->prepare("SELECT COUNT(*) FROM avaliacao WHERE id_jogo = ?");
$stmtCountAvaliacoes->bind_param("i", $id);
$stmtCountAvaliacoes->execute();
$stmtCountAvaliacoes->bind_result($totalAvaliacoes);
$stmtCountAvaliacoes->fetch();
$stmtCountAvaliacoes->close();


?>
<!-------------------------------------------------------------------------------- HTML ------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/dash.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <script defer src="./js/script.js"> </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!--------- Interface --------->
    <header>
        <section class="logo">
            <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
            <?php } else { ?>
            <a href="index.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a>
            <?php } ?>
        </section>

        <nav id="nav" class="nav-links">
            <?php if (isset($_SESSION['user_id'])) { ?>
            <a href="perfil.php"><img src="img/usuarios/default.png" alt="Perfil do usuário" class="user-avatar"></a>
            <?php } else { ?>
            <a href="login.php" class="a-Button">Entrar</a>
			 <a href="cadastro.php" class="a-Button">Criar uma conta</a>
            <?php } ?>
            <a href="filtragem.php">Games</a>
        </nav>

        <div class="search-container">
            <form action="filtragem.php" method="GET">
                <input type="text" name="pesquisa" placeholder="Pesquisar..." required>
                <i class="fas fa-search icon"></i>
            </form>
        </div>

        <div class="hamburger" onclick="toggleMenu()">
            ☰
        </div>
    </header>
    <!--------- Informações dos jogos --------->

    <main id="dash_main">

        <div>
            <!-- Coluna da imagem -->
            <div class="game-card-full info-card">

                <!-- Título e favorito -->
                <div class="card-header">
                    <h1 class="titulo-jogo"><?= $jogo["nome"]; ?></h1>
                </div>

                <!-- Imagem -->
                <div class="card-image-wrapper">
                    <img src="<?= $jogo["imagem"]; ?>" alt="Capa do jogo" />
                </div>

                <!-- Informações principais -->
                <div class="card-info-grid">

                    <div class="info-bloco">
                        <h3>Descrição:</h3>
                        <p><?= $jogo["descricao"]; ?></p>
                    </div>

                    <div class="info-bloco">
                        <h3>Estúdio:</h3>
                        <p><?= $jogo["estudio"]; ?></p>
                    </div>

                    <div class="info-bloco">
                        <h3>Desenvolvedor:</h3>
                        <p><?= $jogo["desenvolvedor"]; ?></p>
                    </div>

                    <div class="info-bloco">
                        <h3>Data de Lançamento:</h3>
                        <p><?= date("d/m/Y", strtotime($jogo["data_lancamento"])); ?></p>
                    </div>

                    <div class="info-bloco">
                        <h3>Plataformas:</h3>
                        <div class="icon-tags">
                            <?php
                            $plataformas = explode(", ", $jogo["plataforma"]);
                            foreach ($plataformas as $plataforma) {
                                $icon = match (strtolower($plataforma)) {
                                    "pc" => '<i class="fas fa-desktop"></i>',
                                    "playstation" => '<i class="fab fa-playstation"></i>',
                                    "xbox" => '<i class="fab fa-xbox"></i>',
                                    "nintendo" => '<i class="fas fa-gamepad"></i>',
                                    default => '<i class="fas fa-laptop"></i>',
                                };
                                echo "<span class='tag'>$icon $plataforma</span>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="info-bloco">
                        <h3>Gêneros:</h3>
                        <div class="icon-tags">
                            <?php
                            $generos = explode(", ", $jogo["genero"]);
                            foreach ($generos as $genero) {
                                $icon = match (strtolower($genero)) {
                                    "terror" => '<i class="fas fa-skull-crossbones"></i>',
                                    "ação" => '<i class="fas fa-fist-raised"></i>',
                                    "aventura" => '<i class="fas fa-hiking"></i>',
                                    "estratégia" => '<i class="fas fa-chess-board"></i>',
                                    "corrida" => '<i class="fas fa-flag-checkered"></i>',
                                    default => '<i class="fas fa-puzzle-piece"></i>',
                                };
                                echo "<span class='tag'>$icon $genero</span>";
                            }
                            ?>
                        </div>
                    </div>

                </div>


                <div>
                    <div class="avaliacoes-gerais">
                        <!-- Avaliação da comunidade -->
                        <div class="avaliacao-card nova">
                            <div class="orbe media" id="orbe-comunidade">
                                <span
                                    id="nota-comunidade"><?= number_format(floatval($media_comunidade ?? 0), 1) ?></span>
                            </div>
                            <h4>Média da Comunidade</h4>
                            <small id="total-avaliacoes">Baseada em <?= $totalAvaliacoes ?? 0 ?> avaliações</small>
                        </div>

                        <?php
                        // Garante que $nota_usuario está definido como float ou 0.0
                        $nota_usuario = isset($nota_usuario) ? floatval($nota_usuario) : 0.0;
                        ?>

                        <div class="avaliacao-card nova">
                            <div id="label-nota" class="label-nota">Em progresso</div>
                            <div class="orbe usuario" id="orbe"><span id="nota-texto">0.0</span></div>
                            <h4>Dê sua nota</h4>
                            <div class="avaliacao-container">
                                <div class="estrelas" id="estrelas" data-jogo-id="<?= $jogo['id_jogo'] ?>"
                                    data-usuario-id="<?= $user_id ?? '' ?>">
                                    <i class="fa-regular fa-star" data-value="1"></i>
                                    <i class="fa-regular fa-star" data-value="2"></i>
                                    <i class="fa-regular fa-star" data-value="3"></i>
                                    <i class="fa-regular fa-star" data-value="4"></i>
                                    <i class="fa-regular fa-star" data-value="5"></i>
                                </div>
                            </div>
                            <small id="user-avaliacao-text">
                                <?= $nota_usuario > 0 ? "Sua nota: " . number_format($nota_usuario, 1) : "Você ainda não avaliou" ?>
                            </small>
                        </div>


                        <div class="avaliacao-card nova">
                            <h4>⭐ Marcar como lendário!</h4>
                            <small>Curtiu esse jogo? marque-o como um favorito digno do trono gamer!</small>

                            <?php
                            $favoritado = false;
                            if (isset($_SESSION['user_id'])) {
                                $stmt = $mysqli->prepare("SELECT 1 FROM usuario_favorita_jogo WHERE id_usuario = ? AND id_jogo = ?");
                                $stmt->bind_param("ii", $_SESSION['user_id'], $jogo['id_jogo']);
                                $stmt->execute();
                                $stmt->store_result();
                                $favoritado = $stmt->num_rows > 0;
                            }
                            ?>

                            <button class="btn-favorito <?= $favoritado ? 'favoritado' : '' ?>"
                                data-jogo-id="<?= $jogo['id_jogo'] ?>"
                                data-usuario-id="<?= $_SESSION['user_id'] ?? '' ?>">
                                <i class="fa-heart <?= $favoritado ? 'fas' : 'far' ?> icone-coracao"></i>
                                <span class="label-favorito"><?= $favoritado ? 'Desfavoritar' : 'Favoritar' ?></span>
                            </button>



                        </div>
                    </div>
                </div>
            </div>
        </div>

<!-------------------------------------------------------------------------------- Comentarios ------------------------------------------------------------------------------------>
        <article class="p2">
            <?php 
            $count = count($comentarios);
            echo "<h2>Comentários ($count)</h2> ";
			 if (!empty($msg_erro)) { ?>
            <div class="mensagem-alerta">
              <?php echo htmlspecialchars($msg_erro); ?>
			 </div> <?php }
            if (isset($_SESSION['user_id'])){ ?>
                <section class="coment_usu">
                    <figure class="usu_foto">
                        <img src="<?php echo $usuario["foto_perfil"]; ?>" alt="img" class="img_coment">
                        <h4><?php echo $usuario["nome"]; ?></h4>
                    </figure>
                    <div class="form_com">
                        <p><?php echo $usuario["email"]; ?></p>
                        <form method="POST" id="comentarioForm">
                             <textarea name="comentario" placeholder="Adicione seu comentário..." required></textarea><br>
							 <input type="hidden" name="id" value="<?php echo $id; ?>">
                             <input type="hidden" name="enviar_comentario" value="1">
                             <button type="button" id="btn_comentario">Enviar</button>
                        </form>
                    </div>
                </section>
            <?php } else { ?>
                <section class="coment_usu">
                    <p>Faça <a href="login.php">login</a> para comentar ou <a
                            href="cadastro.php">cadastre-se</a>
                    </p>
                </section> <?php }?>
			 <div class="coment_usu_pb">	
            <?php 
           foreach ($comentarios as $coment) { ?>
		  
                <section class="coment_usu">
                    <figure class="usu_foto">
                        <img src="<?php echo $coment["foto_perfil"]; ?>" alt="img" class="img_coment">
                        <h4><?php echo $coment["nome_usuario"]; ?></h4>

                        <p><?php
                        $data_comentario = new DateTime($coment["data_comentario"]);
                        $data = $data_comentario->format('d/m \à\s H\hi');
                        echo $data; ?></p>

                        <p><?php echo "nota " . $coment["nota"]; ?></p>
                    </figure>
                        <p class="form_com" ><?php echo $coment["texto"]; ?></p> 
                </section> 
            <?php } ?>  </div>
			<?php if ($count == 0) { ?>
                <section class="coment_usu">
                    <p>Ninguém comentou aqui ainda, seja o primeiro a comentar !</p>
                </section> <?php }?>
        </article>
    </main>
<!-------------------------------------------------------------------------------- Contatos --------------------------------------------------------------------------------------->
    <footer class="footer-nav">
        <div class="social-icons">
            <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
            <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i
                    class="fab fa-github"></i></a>
        </div>
        <span>Ludus • v0.1</span>
    </footer>

</body>
<script>
    const usuarioLogado = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;
    const notaUsuario = <?= json_encode($nota_usuario ?? 0) ?>;
    const mediaComunidad = <?= json_encode(round($media_comunidade ?? 0, 1)) ?>;
    const totalAvaliacoes = <?= json_encode($totalAvaliacoes ?? 0) ?>; // valor inicial vindo do PHP
    const estrelas = document.querySelectorAll('.estrelas i');
    const orbe = document.getElementById('orbe');
    const notaTexto = document.getElementById('nota-texto');
    const labelNota = document.getElementById('label-nota');
    const orbeComunidade = document.getElementById('orbe-comunidade');
    const notaTextoComunidade = document.getElementById('nota-comunidade');
    const totalAvaliacoesElemento = document.getElementById('total-avaliacoes'); // elemento para o texto "Baseada em x avaliações"

    let notaAtual = 0;
    let cooldown = false;

    const divEstrelas = document.getElementById('estrelas');
    const jogoID = divEstrelas ? divEstrelas.getAttribute('data-jogo-id') : null;
    const usuarioID = divEstrelas ? divEstrelas.getAttribute('data-usuario-id') : null;

    const cores = [
        "#5e5e5e", "#da5959ff", "#4fff3fff", "#00ffffff", "#00b3e0ff", "#d15cffff"
    ];

    // Função para pluralizar a palavra "avaliação"
    function pluralizarAvaliacao(qtd) {
        const texto = qtd === 1 ? 'avaliação' : 'avaliações';
        return `Baseada em ${qtd} ${texto}`;
    }


    function atualizarOrbe(nota, orbeElement = orbe, notaElement = notaTexto) {
        if (!orbeElement || !notaElement) return;
        notaElement.textContent = nota.toFixed(1);
        const corIndex = Math.floor(nota);
        const corFinal = cores[corIndex] || cores[0];
        orbeElement.style.background = `radial-gradient(circle at center, ${corFinal}, #222)`;
        orbeElement.style.boxShadow = `0 0 25px ${corFinal}`;

        if (orbeElement === orbe) {
            let texto;
            if (nota >= 4.9) texto = "Lenda viva!";
            else if (nota >= 4.5) texto = "Um mito nasce";
            else if (nota >= 3.5) texto = "Rumo ao sucesso";
            else if (nota >= 2.5) texto = "Potencial oculto";
            else if (nota >= 1.5) texto = "Iniciante";
            else texto = "Start";
            labelNota.textContent = texto;
        }
    }

    function preencherEstrelas(nota) {
        estrelas.forEach((estrela, index) => {
            const valorEstrela = index + 1;
            estrela.classList.remove('filled', 'half-filled');
            if (valorEstrela <= Math.floor(nota)) {
                estrela.classList.add('filled');
            } else if (valorEstrela - 0.5 <= nota) {
                estrela.classList.add('half-filled');
            }
        });
    }

    // Atualiza a orbe da comunidade e o texto "Baseada em X avaliações" ao carregar
    if (orbeComunidade && notaTextoComunidade) {
        atualizarOrbe(parseFloat(mediaComunidad) || 0, orbeComunidade, notaTextoComunidade);
    }
    if (totalAvaliacoesElemento) {
        totalAvaliacoesElemento.textContent = pluralizarAvaliacao(totalAvaliacoes);
    }

    // Inicialização do estado visual do usuário
    if (usuarioLogado) {
        notaAtual = notaUsuario; // Pode ser 0, o que indica "ainda não avaliou"
        if (notaAtual > 0) {
            atualizarOrbe(notaAtual);
            preencherEstrelas(notaAtual);
        } else {
            atualizarOrbe(0);
            preencherEstrelas(0);
        }
    } else {
        notaAtual = mediaComunidad;
        atualizarOrbe(mediaComunidad);
        preencherEstrelas(mediaComunidad);
    }

    estrelas.forEach((estrela) => {
        estrela.addEventListener('mousemove', (e) => {
            if (cooldown) return;
            const rect = estrela.getBoundingClientRect();
            const offsetX = e.clientX - rect.left;
            const perc = offsetX / rect.width;
            const baseValue = parseInt(estrela.dataset.value);
            const nota = perc > 0.5 ? baseValue : baseValue - 0.5;
            preencherEstrelas(nota);
            atualizarOrbe(nota);
        });

        estrela.addEventListener('click', (e) => {
            if (!usuarioLogado) {
                mostrarToast("Você precisa estar logado para avaliar. Crie uma conta ou faça login.");
                return;
            }

            if (cooldown) {
                mostrarToast("Aguarde um momento antes de avaliar novamente.");
                return;
            }

            const rect = estrela.getBoundingClientRect();
            const offsetX = e.clientX - rect.left;
            const perc = offsetX / rect.width;
            const baseValue = parseInt(estrela.dataset.value);
            notaAtual = perc > 0.5 ? baseValue : baseValue - 0.5;
            preencherEstrelas(notaAtual);
            atualizarOrbe(notaAtual);

            enviarAvaliacao(notaAtual);
        });
    });

    document.getElementById('estrelas').addEventListener('mouseleave', () => {
        preencherEstrelas(notaAtual);
        atualizarOrbe(notaAtual);
    });

    // Envio da avaliação via AJAX
    function enviarAvaliacao(nota) {
        cooldown = true;
        mostrarToast("Enviando avaliação...", "info");

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "salvar_avaliacao.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                cooldown = false;
                if (xhr.status === 200) {
                    const resposta = JSON.parse(xhr.responseText);
                    if (resposta.status === "ok") {
                        mostrarToast("Avaliação salva com sucesso!", "success");

                        // Atualiza orbe da comunidade (nota e cor)
                        if (resposta.media_comunidade && orbeComunidade && notaTextoComunidade) {
                            atualizarOrbe(parseFloat(resposta.media_comunidade), orbeComunidade, notaTextoComunidade);
                        }
                        // Atualiza texto "Baseada em X avaliações"
                        // Atualiza texto "Baseada em X avaliações"
                        const totalAvaliacoesElemento = document.getElementById('total-avaliacoes');
                        if (resposta.total_avaliacoes !== undefined && totalAvaliacoesElemento) {
                            totalAvaliacoesElemento.textContent = pluralizarAvaliacao(resposta.total_avaliacoes);
                        }


                    } else {
                        mostrarToast("Erro ao salvar avaliação: " + resposta.mensagem, "error");
                    }
                } else {
                    mostrarToast("Erro na comunicação com o servidor.", "error");
                }
            }
        };

        xhr.send("nota=" + nota + "&id_jogo=" + jogoID + "&user_id=" + usuarioID);
    }

    // Toast para mensagens na tela
    let toastAtivo = false;

    function mostrarToast(mensagem, tipo = 'error') {
        if (toastAtivo) return;
        toastAtivo = true;

        const toast = document.createElement('div');
        toast.className = `toast ${tipo}`;
        const conteudo = document.createElement('div');
        conteudo.className = 'toast-content';

        const icone = document.createElement('span');
        icone.className = 'toast-icon';
        icone.innerHTML = tipo === 'error' ? '⚠️' : tipo === 'info' ? '⏳' : '✅';

        const texto = document.createElement('span');
        texto.textContent = mensagem;

        conteudo.appendChild(icone);
        conteudo.appendChild(texto);
        toast.appendChild(conteudo);
        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
                toastAtivo = false;
            }, 300);
        }, 3000);
    }

    // Botão Favoritar
    document.querySelectorAll('.btn-favorito').forEach(btn => {
        btn.addEventListener('click', () => {
            const jogoId = btn.dataset.jogoId;
            const usuarioId = btn.dataset.usuarioId;

            const label = btn.querySelector('.label-favorito');
            const icone = btn.querySelector('.icone-coracao');

            fetch('favoritar.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `jogo_id=${encodeURIComponent(jogoId)}&usuario_id=${encodeURIComponent(usuarioId)}`
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'favoritado') {
                        btn.classList.add('favoritado');
                        if (label) label.textContent = 'Desfavoritar';
                        if (icone) {
                            icone.classList.remove('far');
                            icone.classList.add('fas');
                        }
                    } else if (data.status === 'desfavoritado') {
                        btn.classList.remove('favoritado');
                        if (label) label.textContent = 'Favoritar';
                        if (icone) {
                            icone.classList.remove('fas');
                            icone.classList.add('far');
                        }
                    } else {
                        console.error('Resposta inesperada:', data);
                    }
                })
                .catch(err => {
                    console.error('Erro na requisição:', err);
                });
        });
    });
</script>

</html>