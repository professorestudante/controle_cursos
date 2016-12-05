
<?php 
/**
 * recebe as variaveis de sessão enviadas de controle_certiticados.php gera em PDF o 
 * certificado total de módulos cursados e insere os dados na tabela controle_certificados
 * se o certificado ainda não foi emitido.
 */
session_start();
require_once("../classes/dompdf/autoload.inc.php");
include_once '../classes/DAO/CursoDAO.class.php';
include_once '../classes/DAO/Certificados.class.php';

       
        $matricula=$_SESSION["matricula"];
      
        $dao=new CursoDAO();
        $codAprovacao=$dao->retornaCodigoAprovacaoTotal($matricula);
        $nome=$dao->listaCursista($matricula);
        $data=$dao->listaData($matricula, null);
        $dataArray=  explode('-', $data);   
        $dia=$dataArray[2];
        $mes=$dataArray[1];
        $ano=$dataArray[0];
        $mes_ext=array('01' => "janeiro",'02' => "fevereiro",'03' => "março",
            '04' => "abril",'05' => "maio",'06' => "junho",'07' => "julho",'08' => "agosto",
            '09' => "setembro",'10' => "outubro",'11' => "novembro",'12' => "dezembro");
        $horas=$_SESSION['horas'];    
        $cursosArray=$_SESSION['cursos_total'];
        $cursos='';

        foreach ($cursosArray as $value) {
            if($value!=""){
                $cursos.="<li>".$value.'</li>';
                
            }
        }
        $codigo='';         
        //INSERINDO O REGISTRO NA TABELA CONTROLE DE CERTIFICADOS
        if(!$_SESSION['emitido']){
            $id=$dao->gerarIdCertificado($matricula, $codAprovacao);
            $codigo="CEFOP-".$dia.$mes.$ano."-".$codAprovacao."-".$id;
            $certificados=new Certificados();
            $certificados->cod_certificado=$codigo;
            $certificados->cod_aprovacao=$codAprovacao;
            $certificados->matricula=$matricula;
            $certificados->data=$dia.'/'.$mes.'/'.$ano;
            $certificados->horas=$horas;
            $dao->IserirCodigo($id, $certificados);
            
        }else{
            $codigo=$_SESSION['cod_autenticacao'];
        }

        

        
        /*  */
$html= '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link href="../css/stylesC.css" rel="stylesheet">
        <title></title>
    </head>
    <body>
       <img class="frente" src="../img/frente.JPG" width="950">
        <div class="inicio">
            <p> Certificamos que <span class="nome">'.$nome["nome"].'</span>
        participou do Programa de Formação de Coordenadores Escolares da Superintendência das 
        Escolas Estaduais de Fortaleza, concluindo com êxito o(s) Módulos(s):</p></div>
        <div class="cursos">  
       <ul>'.$cursos.'</ul>
       </div>
       <div class ="horas"><p>Perfazendo uma carga horária de '.$horas.' horas </p></div>
       <div class="data"> <p>Fortaleza, '.$dia.' de '.$mes_ext[$mes].' de '.$ano.'</p> </div> 
        <div class="quebrapagina"></div>
        <div class="verso">
        <img src="../img/verso.jpg" width="950"> 
        </div>
        <div class="codigo">'.$codigo.'</div>
        

</html>';

        use Dompdf\Dompdf;
	$dompdf=new Dompdf();
        $dompdf->load_html($html);
        $dompdf->set_paper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("saida10", //Nome do arquivo de saída 
            array(
                "Attachment" => true //Para download, altere para true 
            ));
 

//echo $html;
?>
        

