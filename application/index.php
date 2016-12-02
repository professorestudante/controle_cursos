<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <!--meta charset="UTF-8"-->
        <title></title>
        <link href="../css/styles.css" rel="stylesheet">
    </head>
    <body>
        <!--div class='logo'>
            <nav class="social">
            <ul>
                
                <li><a href="http://cefopsefor.com.br/index.php/feed/rss/">
                    <img class="rss" src="../img/rss.png"></a></li>
                <li><a href="https://www.facebook.com/profile.php?id=100010225684741&fref=ts">
                    <img class="rss" src="../img/face.png"></a></li>         
            </ul>
            </nav>
        <div class='logo_link'><a href=http://cefopsefor.com.br>
                <img id='logo-image' class='img-responsive' alt='CEFOP SEFOR' 
                     src=http://cefopsefor.com.br/wp-content/uploads/2016/05/customLogo-ok2222222-1.jpg />
            </a></div>  
        </div>
                    <nav>
              <ul class="menu">
                            <li><a href="http://cefopsefor.com.br/">HOME</a></li>
                            <li><a href="http://cefopsefor.com.br/index.php/cefop/">CEFOP</a></li>
                                    <li><a href="#">FORMAÇÃO</a>
                                    <ul>
                                        <li><a href="#">CORDENADORES ESCOLARES</a>
                                          <ul><li><a href="http://cefopsefor.com.br/index.php/inscricoes/">
                                                      INSCRIÇÕES</a></li></ul>
                                      </li>
                                      
                                      <li><a href="http://ead.cefopsefor.com.br/">EAD CEFOP</a></li>
                                                         
                                    </ul>                                 
                                    </li>
                            
                            <li><a href="http://cefopsefor.com.br/index.php/noticias/">NOTÍCIAS</a></li>
                            <li><a href="http://cefopsefor.com.br/index.php/nte/">NTE</a></li> 
                            <li><a href="http://cefopsefor.com.br/index.php/nossa-equipe/">NOSSA EQUIPE</a></li>
                            <li><a href="http://cefopsefor.com.br/index.php/contact-us/">CONTATO</a></li>
            </ul>
            </nav>
        <div class="tewste"></div-->
        <?php
        
        function __autoload($classe){
            include_once '../classes/DAO/'.$classe.'.class.php';
            
        }
/*
        $array=array();
        $array[0]='GEL';
        $array[1]='TRD';
        $array[2]='L1C';
        $array[3]='';
        $array[4]='';*/
        //$matricula='001';
        $cod=".";
        $dao= new CursoDAO(); 
        //$arrayEmitidos=$dao->listarCodCertificadosEmitidos($matricula);
       // $cursosArray=$dao->listaCursoAprovado($matricula);
       // $codAprovacao=$dao->retornaCodigoAprovacaoTotal($matricula);
       $verifica=new Controle();
       $codigo='CEFOP-04112016-GL1CT-1';
        $arrayCertificado=$dao->verificaAutenticidade($codigo);
        
        //$array=$dao->listarCodCertificadosEmitidos($matricula);
       // $i=$verifica->verificaEmitidos($array);
        
       // $array=$dao->reimprimeTotal($matricula);
       /*foreach ($array as $value) {
            echo $value.'<br>';
        }*/
        $matricula=$arrayCertificado['matri'];
        $arrayCursoAprovado=$dao->listaCursoAprovado($matricula);
        $arrayCursoCod=$verifica->exibeCursoCodigo($codigo, $arrayCursoAprovado);
        $arrayCursista=$dao->listaCursista($matricula);
        echo 'Nome:'.$arrayCursista['nome'].'<br>';
        foreach ($arrayCursoCod as $value) {
            echo $value.'<br>';
        }
        
        
        
       
        ?>
    </body>
</html>
