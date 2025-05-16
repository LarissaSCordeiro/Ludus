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
<a href="index.html"><img class="logo" src="./img/logo.png" alt="logo" width="170" height="70"></a>
<nav>
<label for="toggle">&#9776;</label>
<input type="checkbox" id="toggle">
<menu>
<a class="btn" href="#"><strong>Catalogo</strong></a>
<!--<a href="#"><strong></strong></a>-->
<!--<a href="#"><strong></strong></a>-->
</menu>
</nav>
</header>
<!--apresentacao do site-->
<main>
<section class = "log">
<article id = "log">
<h2>Login</h2>
<form action="verificacao.php" method="post">
<input type="email" name="email" id="email" placeholder="Digite seu Email" required>
<br><br>
<input type="text" name="senha" id="senha" min="6" placeholder="Digite sua Senha" required>
<br><br>
<button type="submit" name="login" id="btn" > <strong>Enviar</strong> </button>
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