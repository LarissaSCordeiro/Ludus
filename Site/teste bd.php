<?php
// 1. Conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'ludus';

$conn = new mysqli($host, $user, $pass, $db);

// Verifica conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// 2. Consulta os jogos com INNER JOIN para os gêneros
$sql = "
    SELECT 
        jogo.id AS jogo_id,
        jogo.nome AS nome_jogo,
        jogo.imagem,
        GROUP_CONCAT(genero.nome SEPARATOR ', ') AS generos
    FROM jogo
    LEFT JOIN jogo_possui_genero ON jogo.id = jogo_possui_genero.id_jogo
    LEFT JOIN genero ON genero.id = jogo_possui_genero.id_genero
    GROUP BY jogo.id
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Catálogo de Jogos - Ludus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0e0a1a;
            color: #fff;
            padding: 20px;
        }
        .jogo {
            background: #1b1629;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .jogo img {
            width: 100px;
            height: auto;
            border-radius: 8px;
        }
        .info {
            display: flex;
            flex-direction: column;
        }
        .info h2 {
            margin: 0;
            color: #f4961e;
        }
        .info span {
            font-size: 14px;
            color: #ccc;
        }
    </style>
</head>
<body>

<h1>🎮 Catálogo de Jogos</h1>

<?php if ($result && $result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="jogo">
            <img src="<?= htmlspecialchars($row['imagem']) ?>" alt="Capa do jogo">
            <div class="info">
                <h2><?= htmlspecialchars($row['nome_jogo']) ?></h2>
                <span>Gêneros: <?= htmlspecialchars($row['generos'] ?? 'Nenhum') ?></span>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Nenhum jogo encontrado.</p>
<?php endif; ?>

<?php $conn->close(); ?>

</body>
</html>
