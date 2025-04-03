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
<form action="Cadastrar.php" method="post">
<label for="email">Email:</label>
<input type="email" name="email" id="email"/>
<br><br>
<label for="senha">Senha:</label>
<input type="text" name="senha" id="senha"/>
<br><br>
<label for="nome">Nome:</label>
<input type="text" name="nome" id="nome"/>
<br><br>
<?php
if(isset($_POST['cadastro'])){
$email = $_POST['email'];
$senha = $_POST['senha'];
$nome = $_POST['nome'];
require_once("conexao.php");
$hash = password_hash($_POST['senha'], PASSWORD_BCRYPT);
$consulta = $mysqli->prepare("INSERT INTO pessoas(email, senha, nome) VALUES (?, ?, ?)");
$consulta->bind_param("sss", $email, $senha, $nome);
$consulta->execute();
$consulta->close();	
} ?>
<input type="submit" name="cadastro" value="Cadastrar"/>
<br><br>
<a href="login.php">Fazer Login</a>
</form>
</article>
<!--Contatos-->
<footer>
<h3>Desenvolvedores</h3>
</footer>
</body>
</html>