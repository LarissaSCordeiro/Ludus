<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="Ludus">
<title>Ludus | Site de avaliação de jogos Indie BR</title>
<link rel="icon" href="./img/Ludus_Favicon (1).png">
<link rel="stylesheet" href="./css/style.css"/>
</head>
<body>
<!--Interface-->
<header>
<a href="index.html"><img class="logo" src="./img/logo.png" alt="logo" width="170" height="70"></a>
<input type="checkbox" id="toggle">
<nav>
<menu>
<a href="#"><strong>Catalogo</strong></a>
<!--<a href="#"><strong></strong></a>-->
<!--<a href="#"><strong></strong></a>-->
</menu></nav>
</header>
<!--apresentacao do site-->
<main>
<section class= "cad">
<article id = "cad">
<h2 id="titulo">Cadastro</h2>
<form action="Cadastrar.php" method="post">
<input type="email" name="email" id="email"  placeholder="Digite seu Email" required>
<br><br>
<input type="text" name="usuario" id="usuario" placeholder="Digite seu Nome" required>
<br><br>
<input type="text" name="senha" id="senha" min="6" placeholder="Digite sua Senha" required>
<br><br>
<input type="text" name="senha" id="senha" min="6" placeholder="Confirmar Senha" required>
<br><br>
<?php
if(isset($_POST['cadastro'])){
$email = $_POST['email'];
$senha = $_POST['senha'];
$usuario = $_POST['usuario'];
require_once("conexao.php");
$hash = password_hash($_POST['senha'], PASSWORD_BCRYPT);
$consulta = $mysqli->prepare("INSERT INTO ludus(email, senha, usuario) VALUES (?, ?, ?)");
$consulta->bind_param("sss", $email, $senha, $usuario);
$consulta->execute();
$consulta->close();	
} ?>
<button type="submit" name="cadastro" id="btn" > <strong>Enviar</strong> </button>
<br><br>
<a href="login.php">Fazer Login</a>
</form>
</article></section></main>
<!--Contatos-->
<footer>
<h3>Desenvolvedores</h3>
</footer>
</body>
</html>