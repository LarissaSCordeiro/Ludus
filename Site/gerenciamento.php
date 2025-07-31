<!-------------------------------------------------------------------------------- PHP -------------------------------------------------------------------------------------------->
<?php
require_once("config.php");
session_start();

$query_usu = "SELECT * FROM usuario";
$query_coment = "SELECT comentario.id AS id_comentario,comentario.data_comentario, comentario.texto, usuario.nome AS nome_usuario, usuario.email, usuario.foto_perfil, avaliacao.nota, jogo.nome AS nome_jogo FROM
 comentario INNER JOIN usuario ON comentario.id_usuario = usuario.id INNER JOIN avaliacao ON comentario.id_avaliacao = avaliacao.id INNER JOIN jogo ON avaliacao.id_jogo = jogo.id";

if (isset($_POST['excluir_usu'])) {
    $id_usu = $_POST['excluir_usu'];

    $mysqli->query("DELETE FROM avaliacao WHERE id_usuario = $id_usu");
    $mysqli->query("DELETE FROM comentario WHERE id_usuario = $id_usu");
    $mysqli->query("DELETE FROM usuario_favorita_jogo WHERE id_usuario = $id_usu");
    $mysqli->query("DELETE FROM usuario_curte_avaliacao WHERE id_usuario = $id_usu");

    $jogos = $mysqli->query("SELECT id FROM jogo WHERE id_usuario = $id_usu");
    while ($jogo = $jogos->fetch_assoc()) {
        $id_jogo = $jogo['id'];
        $mysqli->query("DELETE FROM jogo_possui_genero WHERE id_jogo = $id_jogo");
        $mysqli->query("DELETE FROM jogo_possui_plataforma WHERE id_jogo = $id_jogo");
        $mysqli->query("DELETE FROM usuario_favorita_jogo WHERE id_jogo = $id_jogo");
    }

    $mysqli->query("DELETE FROM jogo WHERE id_usuario = $id_usu");

    $query_u = "DELETE FROM usuario WHERE id = $id_usu";
    if ($mysqli->query($query_u)) {
		session_destroy();
        echo "<p>Usuario excluido</p>";
    } else {
        echo $mysqli->error ;
    }
}

if (isset($_POST['excluir_coment'])) {
    $id_coment = $_POST['excluir_coment']; 
	
    $query_u = "DELETE FROM comentario WHERE id = $id_coment";
    if ($mysqli->query($query_u)) {
        echo "<p>Comentario excluido</p>";
    } else {
        echo $mysqli->error ;
    }
	
}
    $usuario = $mysqli->query($query_usu);
	$comentario = $mysqli->query($query_coment);
	
	$count_usu = $usuario->num_rows;
	$count_coment = $comentario->num_rows;	
?>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ludus | Jogos Indie BR</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="icon" href="img/Ludus_Favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
<!-------------------------------------------------------------------------------- Interface -------------------------------------------------------------------------------------->
 <header>
        <section class="logo">
         <a href="paginainicial.php"><img src="img/NewLudusLogo.png" alt="Logotipo"></a> 
       
         </section>
		 
		 <nav class="nav_buttons">
         <form method="POST">
         <input type="submit" name="button_coment" value="Comentários">
         <input type="submit" name="button_usu" value="Usuários">
         </form>  </nav>

        <div class="hamburger" onclick="toggleMenu()">
            ☰
        </div>
		
    </header>
<main id="main_gerencia">
<?php 
	function usuarios() {
	    global $usuario, $count_usu; ?>
		   
	<?php	echo "<h2> Usuários - $count_usu </h2> "; 
	
    if ($usuario->num_rows > 0) {
        while ($dados_usu = $usuario->fetch_assoc()) { ?>
		<section class="section_gerencia">
		    <h5><?php echo $dados_usu["id"]; ?></h5>
            <img src="<?php echo $dados_usu["foto_perfil"]; ?>" alt="img" class= "img_coment">
			<h4><?php echo $dados_usu["nome"]; ?></h4>
			<h4><?php echo $dados_usu["email"]; ?></h4>
			<h4><?php echo $dados_usu["tipo"]; ?></h4>
			<h4><?php echo $dados_usu["data_cadastro"]; ?></h4>
			<form method="POST">
            <input type="hidden" name="excluir_usu" value="<?php echo $dados_usu['id']; ?>">
            <input type="submit" id="bt_excluir" value="Excluir Usuario">
            </form>
		</section>
	<?php } 
	} else {
		echo "<h4>Nenhum usuario foi cadastado</h4>";
	} 
	}
	function comentarios() {
		global $comentario, $count_coment;  ?>
		
	    <?php
		echo "<h2> Comentários - $count_coment</h2> ";
	   
	  if ($comentario->num_rows > 0) {
		while ($comentarios = $comentario->fetch_assoc()) {?>
		 <section class="coment_usu">
			  <h1 id="nome_jogo"><?php echo  $comentarios["nome_jogo"]; ?></h1>
			  <figure class="dados_u">
              <img src="<?php echo $comentarios["foto_perfil"]; ?>" alt="img" class="img_coment">
              <h4><?php echo $comentarios["nome_usuario"]; ?></h4>
			  <p><?php echo $comentarios["data_comentario"]; ?></p>
              <p><?php echo "nota " . $comentarios["nota"]; ?></p>
			  <form method="POST">
              <input type="hidden" name="excluir_coment" value="<?php echo $comentarios['id_comentario']; ?>">
              <input type="submit" id="bt_excluir" value="Excluir Comentário">
              </form>
            </figure>
	         <p class="form_com"><?php echo $comentarios["texto"]; ?></p> 
        </section>
      <?php  }
    }  else {
		echo "<h4>Nenhum usuario comentou ainda</h4>";
	} }	

    if(isset($_POST['button_coment'])){
    comentarios();
    } elseif(isset($_POST['button_usu'])){
    usuarios();
    } else {
    usuarios(); 
    }
?>
</main>
 
</body>
</html>
