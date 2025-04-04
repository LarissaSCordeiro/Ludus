<?php
session_start();
function protegePagina(){
$email = $_SESSION['email'];
$senha = $_SESSION['senha'];
require_once("conexao.php");
$consulta = $mysqli->prepare("SELECT email, senha FROM usuario where email = ?");
$consulta->bind_param("s", $email);
$consulta->execute();
$resultado = $consulta->get_result();
$resultadoFormatado = $resultado->fetch_all(MYSQLI_ASSOC);
if(empty($resultadoFormatado)== true){header("Location: ./login.php");}
}
?>