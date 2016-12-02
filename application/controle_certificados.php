<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

        <?php
        session_start();
        include_once 'header.php';
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
        $solicitacao=TRUE;
        $modulos="";
        
        $arrayMensagem=array();
        $contato='</br><p>Em caso de dúvida entre em contato conosco:</p>'
                        . '<p>Célula de Formação, Programas e Projetos</p></br>'
                        . '<p>(85) 3101-2061 </p></br><p> (85) 3218-1213  </p></br>'
                        . '<p><a href="mailto:cefop@ct.seduc.ce.gov.br">cefop@ct.seduc.ce.gov.br</a></p>';
        
        
        $arrayMensagem[0]='<h3>Este certificado não pode ser impresso!</h3><br><br><div class="aviso">'
                        .$contato.'</div>'
                        . '<a href="areaCursista.php">Voltar</a>'
                        . '</br></br><a href="acessocursista.php">Sair</a>';
        
        $arrayMensagem[1]='<div class="aviso"><h3>ATENÇÃO!</h3><p>Ao imprimir o certificado com o total '
                                . 'de módulos cursados, você NÃO poderá imprimir separadamente certificados '
                                . 'individuais '
                                . 'dos módulos selecionados.</p>'
                                . $contato.'</div>'
                                . '<p><a href="certificados_total.php">SIM,</a> eu quero o certificado com o '
                                . 'total de módulos cursados</p></br>'
                                .'<p><a href="areaCursista.php">NÃO,</a> eu  quero imprimir certificados '
                                . ' individuais'
                                . ' para cada módulo separadamente.</p>'
                                . '</br></br><a href="acessocursista.php">Sair</a>';
        
        $arrayMensagem[2]='<h3><a href="certificados_total.php">Reimprimir certificado de ';
        $arrayMensagem[3]=' horas</a></h3><br><br><br>'
                        . '<p><div class="aviso">ATENÇÃO! Havendo módulos cursados e não listados neste certificado, os mesmos deverão '
                        . 'ser impressos individualmente na <a href="areaCursista.php"> '
                . 'área do cursista</a></p>'
                        .$contato.'</div>'
                        .'<a href="areaCursista.php">Voltar</a>'
                . '</br></br><a href="acessocursista.php">Sair</a>';
        $arrayMensagem[4]='<div class="aviso"><h3>ATENÇÃO!</h3><br><p>Ao imprimir separadamemente o certificado deste módulo, '
                . 'você NÃO poderá imprimir o certificado de maior carga horária com o total '
                . 'de módulos cursados <p>'.$contato.'</div>'
                        . '<p><a href="certificados_individual.php">SIM,</a> eu quero '
                        . 'imprimir o certificado deste módulo '
                        . ' com carga horária de 60 horas</p></br>'
                        .'<p><a href="areaCursista.php">NÃO,</a> eu quero imprimir '
                        . ' um único certificado com maior carga horária e contendo'
                        . ' todos os módulos cursados</p>'
                        . '</br></br><a href="acessocursista.php">Sair</a>';
        $arrayMensagem[5]='<h3><a href="certificados_total.php">Imprimir certificado de ';

        if(isset($_POST['cert_solicitado'])){
            
            $cert_solicitado=$_POST['cert_solicitado'];  
            if($cert_solicitado==="total"){
                $cursosArray=$dao->listaCursoAprovado($matricula);
             
                if($emitido==0){
                    $codCurso=$controle->exibeCodTotal($arrayEmitidos);
                    $_SESSION['emitido']=FALSE;
                    $_SESSION['cursos_total']=$cursosArray;
                    $_SESSION['horas']=$controle->exibeHorasCod($codAprovacao);
                    echo $arrayMensagem[1];

                }elseif ($emitido===1 or $emitido==3) {
                    $arrayCeritificado=$dao->reimprimeTotal($matricula);
                    $i=$controle->exibeHorasCod($arrayCeritificado['cod_curso']);
                    $arrayc=$controle->imprimeTotal($arrayEmitidos,$arrayAprovados);
                    $_SESSION['horas']=$controle->exibeHorasCod($arrayCeritificado['cod_curso']);
                    $_SESSION['cursos_total']=$arrayc;
                    $_SESSION['emitido']=TRUE;
                    $_SESSION['cod_autenticacao']=$arrayCeritificado['cod_certificado'];
                    echo $arrayMensagem[2].$i.$arrayMensagem[3];
                    
                }elseif($emitido==3){}
                elseif($emitido==2 and ($controle->exibeHorasCod($codAprovacao)>1) ){
                    $i=$controle->exibeHorasCod($codAprovacao);
                    $arrayCursos=$controle->exibeCursoCodigo($codAprovacao,$arrayAprovados);
                    $_SESSION['horas']=$controle->exibeHorasCod($codAprovacao);
                    $_SESSION['cursos_total']=$controle->imprimeTotal($arrayCursos,$arrayAprovados);
                    $_SESSION['emitido']=FALSE;
                    echo $arrayMensagem[5].$i.$arrayMensagem[3];
                }else {
                    echo $arrayMensagem[0];
                }
                

            }else{
                $codCurso='';
                $listaCodEmitidos=$dao->listarCodCertificadosEmitidos($matricula);
                switch ($cert_solicitado) {
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
                    $_SESSION['cod_aprovacao']=$codCurso;
                    $_SESSION['curso']=$cert_solicitado;
                    echo $arrayMensagem[4];
                }
                elseif($controle->verificaIdividual($listaCodEmitidos,$codCurso)){ 
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
      
