

<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'header.php';
/**
 * Recebe as requisições dos formularios
 */
echo '<div class="areaCursista">';
function __autoload($classe){
    include_once '../classes/DAO/'.$classe.'.class.php';
}
if(isset($_POST['verifica'])){
    $dao=new CursoDAO();
    $controle=new Controle();
    
    $codigo=$_POST['verifica'];
    echo '<div class="aviso">';
    $arrayCertificado=$dao->verificaAutenticidade($codigo);
    if($arrayCertificado!=FALSE){
     
        echo '<h3>NÚMERO DE CERTIFICADO VALIDADO COM SUCESSO!</H3><br>';
        $matricula=$arrayCertificado['matri'];
        $arrayCursoAprovado=$dao->listaCursoAprovado($matricula);
        $arrayCursoCod=$controle->exibeCursoCodigo($codigo, $arrayCursoAprovado);
        $arrayCursista=$dao->listaCursista($matricula);
        echo '<p><strong>Cursista</strong>: '.$arrayCursista['nome'].'</p><br>'
                . '<p><strong>Curso(s) realizado(s):</strong></p><ul>';
        
        foreach ($arrayCursoCod as $value) {
            echo '<li>'.$value.' (aprovado)</li>';
        }
        echo '</ul><br><p><strong>Total de horas cursadas: '
        . '</strong>'.$arrayCertificado["horas"].' horas</p></div>';
    }else{
        echo '<p>O código <strong>'.$_POST['verifica'].'<strong> não foi '
                . 'encontrado em nosso sistema!</p><br><br><br>';
    }
    echo '<p><a href="validar.php">Voltar</a></p></div>';
    include_once './footer.php';
}
