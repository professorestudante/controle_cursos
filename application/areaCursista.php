<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

        
        <?php
        echo '<div class="areaCursista">';
        include_once 'header.php';
        session_start();
        function __autoload($classe){
            include_once '../classes/DAO/'.$classe.'.class.php';
        }
        $mens=array();
        $string="";
        
        try {
            if(isset($_SESSION["matricula"])){
                $dao=new CursoDAO();
                $matricula=$_SESSION["matricula"];
                $cursista=$dao->listaCursista($matricula);
                if($_SESSION["email"]===$cursista['email']){
                    echo 'Cursista: <h4>'.$cursista['nome'].'<br>Matrícula: '.$cursista['matri']
                    .'</h4><br></br><form action="controle_certificados.php" method="POST">';
              
                
                $cursosDao= $dao->listaCursoAprovado($matricula);
                $cursos='';
                $i=0;
                $mensTotal="";
                foreach ($cursosDao as $value) {
                    $cursos.='<p><input type="radio" name="cert_solicitado" value="'.$value.'"> '.$value.' '
                            . '( 60 horas )</p>';
                    $i++;
                }
                if($i>1){
                    $mensTotal='</br><input type="radio"  name="cert_solicitado" value="total">'
                . ' Todos os módulos cursados em um único certificado </br>';
                }
                if($cursos!==''){
                echo '<h3>Módulos cursados</h3><p>Selecione o certificado a ser impresso:</p><br>';
                echo '<div id="listaCursos">'.$cursos.'</div>'.$mensTotal.'</br><input type="reset" value="Limpar" id="reset2">
                    <input type="submit" value="Imprimir certificado" id="submit2">
                    </br></br><a href="acessocursista.php">Sair</a>
                    </form>';
                }else{
                    echo '<h3>Não há certificados disponíveis.</h3><br><br>'
                    . '</br></br><a href="acessocursista.php">Sair</a>';
                }
            }
            else{
                unset($_SESSION["email"]);
                unset($_SESSION["matricula"]);
                echo '<h3>Erro de autenticação!</h3>'
                . '<p>Usuário ou senha inválido.</p>'
                . '</br></br><a href="acessocursista.php">Sair</a>';
            }
            
             }else {
                header('acessocursista.php');
            }
            
            
        } catch (Exception $ex) {
            echo 'Erro de acesso! '.$ex;
            
        }
        echo '</div>';
        include_once 'footer.php';
        ?>
           