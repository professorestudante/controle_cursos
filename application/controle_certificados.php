
<?php
/**
 * recebe os dados enviados pela pagina areaCursista.php e verifica requisitos
 * para a emissão de certificados. Exibe avisos ao usuário e o direciona para a area
 * de geração de certificados caso os requisitos estejam ok 
 */
session_start();
include_once 'header.php';
include_once './mensagens.php';
function __autoload($classe){
    include_once '../classes/DAO/'.$classe.'.class.php';
}
echo '<div class="areaCursista">';
$matricula =$_SESSION['matricula'];
$dao= new CursoDAO();
$arrayEmitidos=$dao->listarCodCertificadosEmitidos($matricula);
$arrayAprovados=$dao->listaCursoAprovado($matricula);
$codAprovacao=$dao->retornaCodigoAprovacaoTotal($matricula);
$controle=new Controle();
$emitido=$controle->verificaEmitidos($arrayEmitidos);

if(isset($_POST['cert_solicitado'])){

    $cert_solicitado=$_POST['cert_solicitado'];//recebe o tipo de certificado solicitado  
    if($cert_solicitado==="total"){//se foi solicitado um certificado total
        $cursosArray=$dao->listaCursoAprovado($matricula);

        if($emitido==0){
            /**
             * caso não foi emitido nenhum certificado para o usuário, um novo código é gerado
             * e enviado á página certificado_total.php
             */
            $codCurso=$controle->exibeCodTotal($arrayEmitidos);
            $_SESSION['emitido']=FALSE;
            $_SESSION['cursos_total']=$cursosArray;
            $_SESSION['horas']=$controle->exibeHorasCod($codAprovacao);
            echo $arrayMensagem[1];

        }elseif ($emitido===1 or $emitido==3) { 
            /**
             * se foi emitido algum certificado individual, o mesmo será reimpresso
             * utilizando dos dados da tabela controle_certiticado  
             */
            $arrayCeritificado=$dao->reimprimeTotal($matricula);
            $i=$controle->exibeHorasCod($arrayCeritificado['cod_curso']);
            $arrayc=$controle->imprimeTotal($arrayEmitidos,$arrayAprovados);
            $_SESSION['horas']=$controle->exibeHorasCod($arrayCeritificado['cod_curso']);
            $_SESSION['cursos_total']=$arrayc;
            $_SESSION['emitido']=TRUE;
            $_SESSION['cod_autenticacao']=$arrayCeritificado['cod_certificado'];
            echo $arrayMensagem[2].$i.$arrayMensagem[3];

        }elseif($emitido==2 and ($controle->exibeHorasCod($codAprovacao)>1) ){
            /**
             * se foi impresso somente um certificado individual
             */
            $i=$controle->exibeHorasCod($codAprovacao);
            $arrayCursos=$controle->exibeCursoCodigo($codAprovacao,$arrayAprovados);
            $_SESSION['horas']=$controle->exibeHorasCod($codAprovacao);
            $_SESSION['cursos_total']=$controle->imprimeTotal($arrayCursos,$arrayAprovados);
            $_SESSION['emitido']=FALSE;
            echo $arrayMensagem[5].$i.$arrayMensagem[3];
        }else {
            echo $arrayMensagem[0];
        }


    }else{//se não,  foi solicitado um certificado individual
        $codCurso='';
        $listaCodEmitidos=$dao->listarCodCertificadosEmitidos($matricula);
        switch ($cert_solicitado) {//indicao o codigo do curso conforme o curso escolhido pelo usuário
                case "Gestão e Liderança":
                    $codCurso='GEL';
                    break;
                case "Legislação, Programas e Projetos":
                    $codCurso='LPP';
                    break;
                case "Currículo, Planejamento e Avaliação":
                    $codCurso='CPA';
                    break;
                case "Tecnologia e Recursos Didáticos":
                    $codCurso='TRD';
                    break;

                default:
                    break;
            }
        if($emitido===0){
            /**
             * se não foi emitido nenhum certificado, os dados do novo certificados são
             * enviados para a página certificados_individual.php. 
             */
            $_SESSION['cod_aprovacao']=$codCurso;
            $_SESSION['curso']=$cert_solicitado;
            echo $arrayMensagem[4];
        }
        elseif($controle->verificaIdividual($listaCodEmitidos,$codCurso)){ 
            /**
             * se já foi emitido algum certificado, verifica se o certificado solicitado já foi
             * emitido em um certificado total, se o resultado for negativo, envia os dados
             * para a página certificados_individual.php.
             */
            $_SESSION['cod_aprovacao']=$codCurso;
            $_SESSION['curso']=$cert_solicitado;
            echo $arrayMensagem[4];
        }  
        else {
            echo $arrayMensagem[0];

        }
    }
}  else {
    echo '<script>alert("Escolha o certificado a ser impresso!!);</script>';
    header("Location: areaCursista.php");
}

echo '</div>';
include_once 'footer.php';
?>
      
