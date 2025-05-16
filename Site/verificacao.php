<?php
include("seguranca.php");
$_SESSION['senha'] = $_POST['senha'];
$_SESSION['email'] = $_POST['email'];
protegePagina();
?>