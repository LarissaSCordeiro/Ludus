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
<h1><a href="index.html">Ludus</a></h1>
<nav>
<label for="toggle">&#9776;</label>
<input type="checkbox" id="toggle">
<menu>
<a href="perfil.php"><strong>Perfil</strong></a>
<a href="#"><strong>Sobre nós</strong></a>
<a href="#"><strong>Categorias</strong></a>
</menu>
</nav></header>
<!--apresentacao do site-->
<main>
<section class= "log_cad">
<article id = "cad">
<h1>Cadastro</h1>
<h2>Insira os dados a seguir :</h2>
<form action="Cadastrar.php" method="post">
<input type="email" name="email" id="email"  placeholder="Digite seu Email" required>
<br><br>
<input type="text" name="senha" id="senha" min="6" placeholder="Digite sua Senha" required>
<br><br>
<input type="text" name="usuario" id="usuario" placeholder="Digite seu Nome" required>
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
<input type="submit" name="cadastro" value="Enviar"/>
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