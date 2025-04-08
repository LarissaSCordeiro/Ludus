<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="description" content="Ludus">
<title>Ludus | Site de avaliação de jogos Indie BR</title>
<link rel="icon" href="./img/Ludus_Favicon (1).png">
<link rel="stylesheet" href="./css/style.css"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, max-scale=1.0">
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
</nav>
</header>
<!--apresentacao do site-->
<main>
<section class = "log_cad">
<article id = "log">
<h1>Login</h1>
<form action="verificacao.php" method="post">
<input type="email" name="email" id="email" placeholder="Digite seu Email" required>
<br><br>
<input type="text" name="senha" id="senha" min="6" placeholder="Digite sua Senha" required>
<br><br>
<input type="submit" name="login" value="Enviar"/>
<p>Não tem uma conta ? <a href="cadastro.php">Clique aqui</a></p>
<br><br>
</form>
</article></section></main>
<!--Contatos-->
<footer>
<h3>Desenvolvedores</h3>
</footer>
</body>
</html>