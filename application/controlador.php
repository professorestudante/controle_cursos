<?php

session_start();
/**
 * Recebe as requisições dos formularios, cria as variáveis de sessão correspondentes
 * e as envia para o arquivo areaCursista.php
 */

function __autoload($classe){
    include_once '../classes/DAO/'.$classe.'.class.php';
}
if ((isset($_POST['email']) and (isset($_POST['matricula'])))) {
$_SESSION["email"]=$_POST["email"];
$_SESSION["matricula"]=$_POST["matricula"];
header('Location:areaCursista.php');
}else{
    echo 'Erro!';
}
