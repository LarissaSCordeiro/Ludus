<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="Ludus">
<title>Ludus | Site de avaliação de jogos Indie BR</title>
<link rel="icon" href="./img/Ludus_Favicon (1).png">
<link rel="stylesheet" href="./css/style.css" />
</head>
<body>
<!--Interface-->
<header>
<h1><a href="index.html">Ludus</a></h1>
<nav class="menu">
<ul>
<li><h3><a href="perfil.php">Perfil</a></h3></li>
<li><h3><a href="#">Sobre nós</a></h3></li>
</ul>
</nav>
</header>
<!--apresentacao do site-->
<section class= "log_cad">
<article id = "log_cad">
<h1>Cadastro</h1>
<h2>Insira os dados a seguir :</h2>
<form action="Cadastrar.php" method="post">
<label for="email">Email:</label>
<input type="email" name="email" id="email"/>
<br><br>
<label for="senha">Senha:</label>
<input type="text" name="senha" id="senha"/>
<br><br>
<label for="usuario">Nome:</label>
<input type="text" name="usuario" id="usuario"/>
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
</article></section>
<!--Contatos-->
<footer>
<h3>Desenvolvedores</h3>
</footer>
</body>
</html>