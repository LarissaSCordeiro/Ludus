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
        .container {
            margin: 26px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background: linear-gradient(145deg, rgba(43, 43, 68, 0.9), rgba(30, 30, 48, 0.95));
            padding: 16px;
            border: 1px solid #2f2f4f;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            max-width: 300px;
            width: 100%;
        }

        .card::after {
            content: "";
            position: absolute;
            top: -100%;
            left: -100%;
            width: 200%;
            height: 200%;
            background: linear-gradient(120deg, transparent 0%, rgba(216, 140, 229, 0.05) 50%, transparent 100%);
            transform: rotate(25deg);
            animation: shine 5s infinite;
            z-index: 0;
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

        .card-img-wrapper {
            width: 100%;
            aspect-ratio: 3 / 4;
            /* Proporção ideal pra capa de jogo */
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 12px;
            background-color: #222;
            /* fallback se imagem quebrar */
            z-index: 1;
        }

        .card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .card-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 1;
        }

        .card-content h2 {
            text-align: center;
            font-size: 20px;
            margin: 0;
            color: #f1f1f1;
        }

        .card-content p {
            text-align: center;
            font-size: 14px;
            color: #ccc;
            margin: 0;
        }

        .card-buttons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
            z-index: 1;
        }

        .card-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s ease;
        }

        .btn-excluir {
            background-color: #e74c3c;
        }

        .btn-excluir:hover {
            background-color: #852a20ff;
        }

        .btn-editar {
            background-color: #f4961e;
            color: #000;
        }

        .btn-editar:hover {
            background-color: #925200;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .card {
                flex-direction: row;
                /* 👉 card horizontal */
                width: 100%;
                max-width: 100%;
                align-items: flex-start;
                text-align: left;
                padding: 12px;
                gap: 16px;
            }

            .card-img-wrapper {
                width: 50%;
                aspect-ratio: auto;
                height: auto;
            }

            .card-img-wrapper img {
                width: 100%;
                height: auto;
                object-fit: cover;
            }

            .card-content {
                width: 60%;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                align-items: flex-start;
                padding-top: 10px;
            }

            .card-content h2,
            .card-content p {
                text-align: left;
            }

            .card-buttons {
                flex-direction: column;
                flex-wrap: wrap;
                gap: 10px;
                width: 100%;
                margin-top: 70px;
                justify-content: flex-start;
            }

            .card-buttons form {
                width: 100%;
                flex: 1 1 auto;
            }

            .card-buttons button {
                width: 100%;
                justify-content: center;
            }
        }

        .toast {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #2ecc71;
            color: white;
            padding: 14px 24px;
            border-radius: 12px;
            font-size: 16px;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease-in-out, transform 0.4s ease;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
        }

        .toast.show {
            opacity: 1;
            pointer-events: auto;
            transform: translateX(-50%) translateY(-10px);
        }

        .toast.error {
            background-color: #e74c3c;
        }

        .toast-content {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-icon {
            font-size: 20px;
            animation: pop 0.3s ease;
        }

        /* Animação do ícone */
        @keyframes pop {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
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
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal.hidden-force {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
    </style>
</head>

<body>

    <header>
        <figure class="logo">
            <a href="index.html">
                <img src="img/NewLudusLogo.png" alt="Logotipo">
            </a>
        </figure>

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

    <main>
        <div class="container">
            <?php
            require_once("config.php");

            $sql = "SELECT nome, desenvolvedor, imagem, id FROM jogo";
            $result = mysqli_query($mysqli, $sql);

            if (!$result) {
                die("Erro na consulta: " . mysqli_error($mysqli));
            }

            if (mysqli_num_rows($result) > 0) {
                while ($jogo = mysqli_fetch_assoc($result)) {
                    echo '<div class="card" data-id-jogo="' . $jogo["id"] . '">';

                    // Wrapper da imagem pra manter o tamanho padronizado
                    echo '<div class="card-img-wrapper">';
                    echo '<img src="' . htmlspecialchars($jogo["imagem"]) . '" alt="Capa do Jogo">';
                    echo '</div>';

                    // Conteúdo do card
                    echo '<div class="card-content">';
                    echo '<h2>' . htmlspecialchars($jogo["nome"]) . '</h2>';
                    echo '<p>Criado por: ' . htmlspecialchars($jogo["desenvolvedor"]) . '</p>';

                    // Botões
                    echo '<div class="card-buttons">';

                    // Botão excluir (NÃO dentro de form)
                    echo '<button class="btn-excluir" data-id="' . $jogo["id"] . '">';
                    echo '<i class="fas fa-trash-alt"></i> Excluir</button>';

                    // Botão editar
                    echo '<form action="editar.php" method="GET" style="margin: 0;">';
                    echo '<input type="hidden" name="id" value="' . $jogo["id"] . '">';
                    echo '<button class="btn-editar" type="submit">';
                    echo '<i class="fas fa-pencil-alt"></i> Editar</button>';
                    echo '</form>';

                    echo '</div>'; // .card-buttons
                    echo '</div>'; // .card-content
                    echo '</div>'; // .card
                }
            } else {
                echo "<p>Nenhum jogo cadastrado.</p>";
            }
            ?>

        </div>
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

<!-- Janelinha de confirmação de alteração -->
<div id="confirmModal" class="modal hidden-force">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle"></i> Tem certeza?</h3>
        <p>Você deseja excluir esse jogo? Essa ação <strong>não poderá ser desfeita</strong>.</p>
        <div class="modal-buttons">
            <button id="cancelarConfirmacao" class="btn-verde">
                <i class="fas fa-times-circle"></i> Cancelar
            </button>
            <button id="confirmarBtn" class="btn-vermelho">
                <i class="fas fa-check-circle"></i> Confirmar
            </button>
        </div>
    </div>
</div>


<!-- Toast -->
<div id="toast" class="toast hidden">
    <div class="toast-content">
        <span id="toast-icon" class="toast-icon">✔️</span>
        <p id="toast-message">Mensagem</p>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("confirmModal");
        const cancelarBtn = document.getElementById("cancelarConfirmacao");
        const confirmarBtn = document.getElementById("confirmarBtn");
        let jogoIdParaExcluir = null;

        // Botões de exclusão
        document.querySelectorAll(".btn-excluir").forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();

                jogoIdParaExcluir = this.getAttribute("data-id");
                if (jogoIdParaExcluir) {
                    modal.classList.remove("hidden-force");
                }
            });
        });

        // Botão cancelar no modal
        cancelarBtn?.addEventListener("click", () => {
            modal.classList.add("hidden-force");
            jogoIdParaExcluir = null;
        });

        // Botão confirmar exclusão no modal
        confirmarBtn?.addEventListener("click", function (event) {
            event.preventDefault();

            if (!jogoIdParaExcluir) return;

            modal.classList.add("hidden-force");

            fetch("excluir.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-Requested-With": "XMLHttpRequest"
                },
                body: `id=${encodeURIComponent(jogoIdParaExcluir)}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast("Jogo excluído com sucesso!");

                        const card = document.querySelector(`[data-id-jogo="${jogoIdParaExcluir}"]`);
                        if (card) card.remove();
                    } else {
                        showToast(data.message || "Erro ao excluir jogo.", true);
                    }
                })
                .catch(() => {
                    showToast("Erro na comunicação com o servidor.", true);
                });

            jogoIdParaExcluir = null;
        });

        // Função de toast visual
        function showToast(msg, isError = false) {
            const toast = document.getElementById("toast");
            const icon = document.getElementById("toast-icon");
            const text = document.getElementById("toast-message");

            text.textContent = msg;
            toast.classList.remove("hidden");
            toast.classList.add("show");

            if (isError) {
                toast.classList.add("error");
                icon.textContent = "❌";
            } else {
                toast.classList.remove("error");
                icon.textContent = "✔️";
            }

            // Reinicia animação
            icon.style.animation = "none";
            void icon.offsetWidth;
            icon.style.animation = null;

            setTimeout(() => {
                toast.classList.remove("show");
                // Remove os parâmetros da URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 3000);
        }

        // Verifica parâmetros da URL (ex: ?sucesso=1) para mostrar toast
        const urlParams = new URLSearchParams(window.location.search);
        const sucesso = urlParams.get('sucesso');
        const erro = urlParams.get('erro');

        if (sucesso === '1') {
            showToast("Jogo atualizado com sucesso!");
        } else if (sucesso === '2') {
            showToast("Jogo excluído com sucesso!");
        } else if (erro === '1') {
            showToast("Erro ao atualizar o jogo.", true);
        }
    });
</script>




</html>