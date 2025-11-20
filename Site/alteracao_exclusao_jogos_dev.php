<?php
session_start();
require_once("config.php");

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$stmtTipo = $mysqli->prepare("SELECT tipo FROM usuario WHERE id = ?");
$stmtTipo->bind_param("i", $id_usuario);
$stmtTipo->execute();
$resTipo = $stmtTipo->get_result();
$tipoRow = $resTipo->fetch_assoc();
$tipoUsuario = $tipoRow['tipo'] ?? '';
$stmtTipo->close();

if ($tipoUsuario !== 'desenvolvedor' && $tipoUsuario !== 'administrador') {
    echo "Acesso negado.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meus Jogos | Ludus</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
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
            max-width: 255px;
            width: 100%;
        }

        .card-img-wrapper {
            width: 100%;
            aspect-ratio: 3 / 4;
            overflow: hidden;
            border-radius: 8px;
            margin-bottom: 12px;
            background-color: #222;
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

    <!-- Cabeçalho -->
    <?php include __DIR__ . '/headers/header_selector.php'; ?>

    <main>
        <div class="container">
            <?php
            $stmt = $mysqli->prepare("SELECT id, nome, imagem, desenvolvedor FROM jogo WHERE id_usuario = ? ORDER BY id DESC");
            $stmt->bind_param("i", $id_usuario);
            $stmt->execute();
            $result = $stmt->get_result();

            if (!$result) {
                die("Erro na consulta: " . $mysqli->error);
            }

            if ($result->num_rows > 0) {
                while ($jogo = $result->fetch_assoc()) {
                    echo '<div class="card" data-id-jogo="' . $jogo["id"] . '">';

                    echo '<div class="card-img-wrapper">';
                    echo '<img src="' . htmlspecialchars($jogo["imagem"]) . '" alt="Capa do Jogo">';
                    echo '</div>';

                    echo '<div class="card-content">';
                    echo '<h2>' . htmlspecialchars($jogo["nome"]) . '</h2>';
                    echo '<p>Criador: ' . htmlspecialchars($jogo["desenvolvedor"]) . '</p>';

                    echo '<div class="card-buttons">';

                    echo '<button class="btn-excluir" data-id="' . $jogo["id"] . '">';
                    echo '<i class="fas fa-trash-alt"></i> Excluir</button>';

                    echo '<form action="editar.php" method="GET" style="margin: 0;">';
                    echo '<input type="hidden" name="id" value="' . $jogo["id"] . '">';
                    echo '<button class="btn-editar" type="submit">';
                    echo '<i class="fas fa-pencil-alt"></i> Editar</button>';
                    echo '</form>';

                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhum jogo cadastrado por você.</p>";
            }
            $stmt->close();
            ?>

        </div>
    </main>

    <!-- Rodapé -->
    <?php include __DIR__ . '/footers/footer.php'; ?>

</body>

<!-- Modal de confirmação -->
<div id="confirmModal" class="modal hidden-force">
    <div class="modal-content">
        <h3><i class="fas fa-exclamation-triangle"></i> Tem certeza?</h3>
        <p>Você deseja excluir esse jogo? Essa ação <strong>não poderá ser desfeita</strong>.</p>
        <div class="modal-buttons">
            <button id="cancelarConfirmacao" class="btn-vermelho">
                <i class="fas fa-times-circle"></i> Cancelar
            </button>
            <button id="confirmarBtn" class="btn-verde">
                <i class="fas fa-check-circle"></i> Confirmar
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("confirmModal");
        const cancelarBtn = document.getElementById("cancelarConfirmacao");
        const confirmarBtn = document.getElementById("confirmarBtn");
        let jogoIdParaExcluir = null;

        document.querySelectorAll(".btn-excluir").forEach(button => {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                jogoIdParaExcluir = this.getAttribute("data-id");
                if (jogoIdParaExcluir) {
                    modal.classList.remove("hidden-force");
                }
            });
        });

        cancelarBtn?.addEventListener("click", () => {
            modal.classList.add("hidden-force");
            jogoIdParaExcluir = null;
        });

        confirmarBtn?.addEventListener("click", function(event) {
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
                        LudusToast("Jogo excluído com sucesso!");
                        const card = document.querySelector(`[data-id-jogo="${jogoIdParaExcluir}"]`);
                        if (card) card.remove();
                    } else {
                        LudusToast(data.message || "Erro ao excluir jogo.", true);
                    }
                })
                .catch(() => {
                    LudusToast("Erro na comunicação com o servidor.", true);
                });
            jogoIdParaExcluir = null;
        });
    });
</script>

<script src="./js/script.js"></script>
<script src="./js/toast.js"></script>

</html>