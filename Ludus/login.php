<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="Ludus">
<title>Ludus | Site de avaliação de jogos Indie BR</title>
<link rel="icon" href="./img/Ludus_Favicon (1).png">
</head>
<body>
<!--Interface-->
<header>
<h1>Cadastro</h1>
</header>
<!--apresentacao do site-->
<nav>
</nav>
<article>
<form action="Login.php" method="post">
<label for="email">Email:</label>
<input type="email" name="email" id="email"/>
<br><br>
<label for="senha">Senha:</label>
<input type="text" name="senha" id="senha"/>
<br><br>
<?php
if(isset($_POST['cadastro'])){
$email = $_POST['email'];
$senha = $_POST['senha'];
require_once("conexao.php");
$hash = password_hash($_POST['senha'], PASSWORD_BCRYPT);
$consulta = $mysqli->prepare("INSERT INTO pessoas(email, senha) VALUES (?, ?)");
$consulta->bind_param("ss", $email, $senha);
$consulta->execute();
$consulta->close();	
} ?>
<input type="submit" name="login" value="Login"/>
<br><br>
</form>
</article>
<!--Contatos-->
<footer>
<h3>Desenvolvedores</h3>
</footer>
</body>
</html>