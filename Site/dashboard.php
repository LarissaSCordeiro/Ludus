<?php
require_once("config.php");
session_start();

$user_id = $_SESSION['user_id'];
$id = isset($_POST['id']) ? (int) $_POST['id'] : (isset($_GET['id']) ? (int) $_GET['id'] : 0);

if (isset($_POST['enviar_comentario']) && isset($_SESSION['user_id'])) {

    $comentario = trim($_POST['comentario']);
    $id_usuario = $_SESSION['user_id'];
    $id_avaliacao = isset($_POST['id_avaliacao']) ? (int) $_POST['id_avaliacao'] : 0;

    $inserir = $mysqli->prepare("INSERT INTO comentario (id_usuario, texto, id_avaliacao) VALUES (?, ?, ?)");
    $inserir->bind_param("isi", $id_usuario, $comentario, $id_avaliacao);

    if (!$inserir->execute()) {

    } else {
        header("Location: dashboard.php?id=" . $id);
        exit();
    }
}

$stmt = $mysqli->prepare("SELECT nome, email, foto_perfil FROM usuario WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

$consulta = $mysqli->prepare("SELECT jogo.nome, jogo.descricao, jogo.imagem, jogo.data_lancamento, jogo.estudio, jogo.desenvolvedor, GROUP_CONCAT(DISTINCT genero.nome ORDER BY genero.nome SEPARATOR ', ') AS genero, GROUP_CONCAT(DISTINCT plataforma.nome ORDER BY plataforma.nome SEPARATOR ', ') AS plataforma FROM jogo LEFT JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo LEFT JOIN genero ON genero.id = jogo_possui_genero.id_genero LEFT JOIN jogo_possui_plataforma ON jogo.id = jogo_possui_plataforma.id_jogo LEFT JOIN plataforma ON plataforma.id = jogo_possui_plataforma.id_plataforma WHERE jogo.id = ? GROUP BY jogo.id");
$consulta->bind_param("i", $id);
$consulta->execute();
$resultado = $consulta->get_result();
$jogo = $resultado->fetch_assoc();

$consulta = $mysqli->prepare("SELECT comentario.data_comentario, comentario.texto, usuario.nome AS nome_usuario, usuario.email, usuario.foto_perfil, avaliacao.nota FROM comentario INNER JOIN usuario ON comentario.id_usuario = usuario.id INNER JOIN avaliacao ON comentario.id_avaliacao = avaliacao.id WHERE avaliacao.id_jogo = ?");
$consulta->bind_param("i", $id);
$consulta->execute();
$resultado = $consulta->get_result();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <script src="./js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Alinhamento visual do container principal */
        #dash_main {
            display: flex;
            flex-wrap: wrap;
            padding: 40px;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        /* Alinha verticalmente a imagem */
        .dash_img img {
            width: 100%;
            max-width: 300px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            flex: 1 1 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Efeito glow no título do jogo */
        .titulo-jogo {
            grid-column: 1 / -1;
            font-weight: bold;
            color: transparent;
            background: linear-gradient(90deg, #ffe066, #ffbb33);
            -webkit-background-clip: text;
            background-clip: text;
            text-shadow: 0 0 8px rgba(255, 224, 102, 0.3), 0 0 12px rgba(255, 153, 0, 0.2);
            margin-bottom: 20px;
        }

        /* Grid de informações do jogo */
        .info-grid {
            flex: 2 1 600px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
        }

        .titulo-jogo {
            grid-column: 1 / -1;
            font-size: 2rem;
            color: #ffe066;
            margin-bottom: 10px;
        }

        /* Cards individuais */
        .info-card {
            background: linear-gradient(145deg, rgba(43, 43, 68, 0.9), rgba(30, 30, 48, 0.95));
            padding: 16px;
            border-radius: 12px;
            border: 1px solid #2f2f4f;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        /* Animação de brilho passando dentro do card */
        .info-card::after {
            content: "";
            position: absolute;
            top: -100%;
            left: -100%;
            width: 200%;
            height: 200%;
            background: linear-gradient(120deg, transparent 0%, rgba(216, 140, 229, 0.05) 50%, transparent 100%);
            transform: rotate(25deg);
            animation: shine 5s infinite;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%) rotate(25deg);
                opacity: 0;
            }

            40% {
                opacity: 1;
            }

            60% {
                opacity: 1;
            }

            100% {
                transform: translateX(100%) rotate(25deg);
                opacity: 0;
            }
        }

        .info-card:hover {
            transform: translateY(-4px);
        }

        /* textos do card */
        .info-card h3 {
            color: #a6a6f2;
            margin-bottom: 8px;
        }

        .info-card p {
            color: #e0e0ff;
            font-size: 0.95rem;
        }

        /* ícones personalizados das plataformas e gêneros dos jogos*/
        .icon-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 8px;
        }

        .tag {
            background-color: #3a3a5c;
            padding: 6px 12px;
            border-radius: 8px;
            color: #f0f0f0;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        }

        .tag i {
            font-size: 1rem;
            color: #ffe066;
        }

        /* Efeito de hover de transformação de escala nos ícones */
        .tag:hover i {
            transform: scale(1.2) rotate(-5deg);
            transition: transform 0.3s ease;
            color: #f4961e;
        }

        /*OBS: por enquanto, se tentar colocar esse CSS
         em um arquivo externo ele não irá funcionar!*/
    </style>

</head>

<body>

    <!-- Interface -->
    <header>
        <section class="logo">
            <a href="index.php">
                <img src="img/NewLudusLogo.png" alt="Logotipo">
            </a>
        </section>

        <nav id="nav" class="nav-links">
            <a href="cadastro.php" class="a-Button">Criar uma conta</a>
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

    <main id="dash_main">
        <figure class="dash_img">
            <img src="<?php echo $jogo["imagem"]; ?>" alt="Capa Jogo">
        </figure>


        <article class="info-grid">
            <h1 class="titulo-jogo"><?php echo $jogo["nome"]; ?></h1>

            <div class="info-card">
                <h3>Estúdio:</h3>
                <p><?php echo $jogo["estudio"]; ?></p>
            </div>

            <div class="info-card">
                <h3>Desenvolvedor:</h3>
                <p><?php echo $jogo["desenvolvedor"]; ?></p>
            </div>

            <div class="info-card">
                <h3>Data de Lançamento:</h3>
                <p><?php echo date("d/m/Y", strtotime($jogo['data_lancamento'])); ?></p>
            </div>

            <div class="info-card">
                <h3>Descrição:</h3>
                <p><?php echo $jogo["descricao"]; ?></p>
            </div>

            <div class="info-card">
                <h3>Plataformas:</h3>
                <div class="icon-tags">
                    <?php
                    $plataformas = explode(", ", $jogo["plataforma"]);
                    foreach ($plataformas as $plataforma) {
                        $icon = "";
                        switch (strtolower($plataforma)) {
                            case "pc":
                                $icon = '<i class="fas fa-desktop"></i>';
                                break;
                            case "playstation":
                                $icon = '<i class="fab fa-playstation"></i>';
                                break;
                            case "xbox":
                                $icon = '<i class="fab fa-xbox"></i>';
                                break;
                            case "nintendo":
                                $icon = '<i class="fas fa-gamepad"></i>';
                                break;
                            default:
                                $icon = '<i class="fas fa-laptop"></i>';
                        }
                        echo "<span class='tag'>$icon $plataforma</span>";
                    }
                    ?>
                </div>
            </div>

            <div class="info-card">
                <h3>Gêneros:</h3>
                <div class="icon-tags">
                    <?php
                    $generos = explode(", ", $jogo["genero"]);
                    foreach ($generos as $genero) {
                        $icon = "";
                        switch (strtolower($genero)) {
                            case "terror":
                                $icon = '<i class="fas fa-skull-crossbones"></i>';
                                break;
                            case "ação":
                                $icon = '<i class="fas fa-fist-raised"></i>';
                                break;
                            case "aventura":
                                $icon = '<i class="fas fa-hiking"></i>';
                                break;
                            case "estratégia":
                                $icon = '<i class="fas fa-chess-board"></i>';
                                break;
                            case "corrida":
                                $icon = '<i class="fas fa-flag-checkered"></i>';
                                break;
                            default:
                                $icon = '<i class="fas fa-puzzle-piece"></i>';
                        }
                        echo "<span class='tag'>$icon $genero</span>";
                    }
                    ?>
                </div>

            </div>
        </article>



        <!-- Parte dos Comentarios -->
        <article class="p2">
            <?php
			$count = $resultado->num_rows;
			echo "<h2>Comentários ($count)</h2> ";
			if ($count == 0) { ?>
		         <section class="coment_usu">
			          <p>Ninguém comentou aqui ainda, seja o primeiro a comentar !</p>
			     </section> 
			<?php } while ($coment = $resultado->fetch_assoc()) { ?>
                <section class="coment_usu">
                    <img src="<?php echo $usuario["foto_perfil"]; ?>" alt="img">
                    <h4><?php echo $coment["nome_usuario"]; ?></h4>
                    <p><?php echo $coment["data_comentario"]; ?>     <?php echo $coment["nota"]; ?></p>
                    <p class="caixa_texto"><?php echo $coment["email"]; ?></p>
                    <p><?php echo $coment["texto"]; ?></p>
                </section>
            <?php } if (isset($_SESSION['user_id']) && $usuario): ?>
                <section class="coment_usu">
				<figure class="usu_foto">
                    <img src="<?php echo $usuario["foto_perfil"]; ?>" alt="img" class= "img_coment">
                    <h4><?php echo $usuario["nome"]; ?></h4></figure>
					<div class="form_com">
                    <p><?php echo $usuario["email"]; ?></p>
                    <form method="POST" id="comentarioForm">
                        <textarea name="comentario" placeholder="Adicione seu comentário..." required></textarea><br>
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit"  id="btn_comentario" >Enviar</button>
                    </form> </div>
                    <div id="msg-feedback"></div>
                </section>
            <?php else: ?>
                <section class="coment_usu">
                    <p class="msg-erro">Faça <a href="login.php">login</a> para comentar ou <a
                            href="cadastro.php">cadastre-se</a></p>
                </section>
            <?php endif; ?>
        </article>




    </main>

    <footer class="footer-nav">
        <div class="social-icons">
            <a href="mailto:exemplo@email.com" title="Email"><i class="fas fa-envelope"></i></a>
            <a href="https://github.com/LarissaSCordeiro/Ludus" target="_blank" title="GitHub"><i
                    class="fab fa-github"></i></a>
        </div>
        <span>Ludus • v0.1</span>
    </footer>

</body>

</html>