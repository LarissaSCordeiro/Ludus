<!DOCTYPE html>
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
<section class = "log_cad">
<article id = "log_cad">
<h1>Login</h1>
<form action="verificacao.php" method="post">
<label for="email">Email:</label>
<input type="email" name="email" id="email"/>
<br><br>
<label for="senha">Senha:</label>
<input type="text" name="senha" id="senha"/>
<br><br>
<input type="submit" name="login" value="Enviar"/>
<p>Não tem uma conta ? <a href="cadastro.php">Clique aqui</a></p>
<br><br>
</form>
</article></section>
<!--Contatos-->
<footer>
<h3>Desenvolvedores</h3>
</footer>
</body>
</html>