<?php

/**
 * Contém os métodos de acesso ao banco de dados curso_coordenadores
 *
 * @author cefop 9
 */


include('../classes/PDO/PDOConnectionFactory.class.php');
include_once '../classes/DAO/Certificados.class.php';

class CursoDAO extends PDOConnectionFactory{
    
    public $conex = null;
    
    public function __construct() {
        $this->conex=  PDOConnectionFactory::getConnection();
    }

    public function listaCursoAprovado($matricula){
        $cursos=array();
         try {
            $stmt= $this->conex->query("SELECT curso.titulo, curso.cod_curso FROM curso INNER 
            JOIN controle_curso ON controle_curso.cod_curso=curso.cod_curso 
            AND controle_curso.matri='".$matricula."'"
                    . "AND controle_curso.Situacao=2;");
            $i=0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
                $cursos[$row['cod_curso']]=$row['titulo'];
                $i++;
            }
            return $cursos;
           
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
        
    }
       public function listaHoras($matricula){
        $horas=0;
         try {
            $stmt= $this->conex->query("SELECT curso.horas FROM curso INNER 
            JOIN controle_curso ON controle_curso.cod_curso=curso.cod_curso 
            AND controle_curso.matri='".$matricula."'"
                    . "AND controle_curso.Situacao=2;");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){  
                $horas+=$row['horas'];
            }
            return $horas;
           
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
        
    } 


    public function listaCursista($matricula){
        /**
         * retonar uma array com o nome, email e matricula
         */
        $cursista=array();
        $cursista['nome']=NULL;
        $cursista['email']=NULL;
        $cursista['matri']=NULL;
         try {
            $stmt= $this->conex->query("SELECT Nome, email,matri FROM cursista "
                    . "WHERE matri='".$matricula."' ;");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){;   
                if ($row['Nome'] != null and $row['Nome'] != '') {
                    $cursista['nome']=$row['Nome'];
                    $cursista['email']=$row['email'];
                    $cursista['matri']=$row['matri'];
                    
                }
                else{
                    return 0;
                }
            }
            return $cursista;
           
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
        
    }
    public function listaData($matricula,$cod_curso){
        $data = array();
         try {
             if(strlen($cod_curso)==null){
                 $stmt= $this->conex->query("SELECT MAX( data) as 'data' FROM controle_curso "
                         . "WHERE controle_curso.Situacao=2 AND "
                         . "controle_curso.matri='".$matricula."' ORDER BY data DESC ");   
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $data = $row['data'];
             }
             else{

                 $stmt= $this->conex->query("SELECT controle_curso.data FROM "
                    . "controle_curso WHERE controle_curso.Situacao=2;"
                     . "AND controle_curso.matri='".$matricula."'"
                    . "AND controle_curso.cod_curso='".$cod_curso."';");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $data = $row['data'];
             }
            
             return $data;
         
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
        
    }
    
    public function retornaAprovacao($matricula){
        /**
         * retorna um array tendo como indice o codigo do curso e um boleano indicando se foi aprovado
         * deve ser inserido na funcao retornaCodigoAprovacao()
         */
       $aprovado=array();// 
       $aprovado['LPP']=false;
       $aprovado['GEL']=false;
       $aprovado['CPA']=false;
       $aprovado['TRD']=false;
  
        try{
            
            $stmt=  $this->conex->query("SELECT cod_curso, Matri FROM "
                    . "`controle_curso` WHERE matri='".$matricula."' AND Situacao=2");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                
                if($row['cod_curso']=='LPP'){
                    $aprovado['LPP']=TRUE;
                }elseif ($row['cod_curso']=='GEL') {
                    $aprovado['GEL']=TRUE;
                }
                elseif ($row['cod_curso']=='CPA') {
                    $aprovado['CPA']=TRUE;
                }
                elseif ($row['cod_curso']=='TRD') {
                    $aprovado['TRD']=TRUE;
                } 
            } 
           return $aprovado;
           /* 
            foreach ($aprovado as $value) {
                echo $value;
            }*/
         
            
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }
    }
    
    
    public function retornaCodigoAprovacaoTotal($matricula){
            $aprovacao=  $this->retornaAprovacao($matricula);
        /**
             * o parâmetro $aprovacao é o retorno do método retornaAprovacao
             */
            $certificado=array();
            $certificado['GEL']=TRUE;
            $certificado['LPP']=TRUE;
            $certificado['CPA']=TRUE;
            $certificado['TRD']=TRUE;
            $codAprovado='';
            
            try{
            
            $stmt=  $this->conex->query("SELECT id, cod_curso  FROM `controle_certificados`"
                    . " WHERE matri='".$matricula."';");
            //veririfica se já foi solicitado algum certetificado individual na tabela
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                
                 if($row['cod_curso']==='LPP'){
                    $certificado['LPP']=FALSE;
                }elseif ($row['cod_curso']==='GEL') {
                    $certificado['GEL']=FALSE;
                }
                elseif ($row['cod_curso']==='CPA') {
                     $certificado['CPA']=FALSE;
                }
                elseif ($row['cod_curso']==='TRD') {
                     $certificado['TRD']=FALSE;
                }
                
            }
          
                 if  ( $aprovacao['GEL'] and $certificado['GEL']){
                     
                     $codAprovado.='G';                     
                 }
                 if($aprovacao['LPP'] and $certificado['LPP']){
                     
                     $codAprovado.='L1';
                 }
                 if ($aprovacao['CPA'] and $certificado['CPA']) {
                     $codAprovado.='C';
                         }
                if ($aprovacao['TRD'] and $certificado['TRD']) {
                     $codAprovado.='T';
                         }
                         return $codAprovado;

        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }
    }
     public function retornaUltimoId(){
       
       $stmt=  $this->conex->query( "SELECT MAX(id) FROM `controle_certificados`"
               . " WHERE 1"); 
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       return $row['MAX(id)'];
    }
    function gerarIdCertificado($matricula, $cod_aprovacao){
        /* @var $ultimoId type */
        $ultimoId=$this->retornaUltimoId();
                $id=0;  
                try{
            
            $stmt=  $this->conex->query("SELECT id, matri  FROM "
                    . "`controle_certificados` WHERE matri='".$matricula."' AND cod_curso='".$cod_aprovacao."'");
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['id']!=null and $row['id']!=0) {
                    $id=$row['id'];
                    return $id;
                }
                else{
                   return ++$ultimoId;
                }    
            
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }
        
    }
   
            
    function IserirCodigo($id,$certificados){
        /**
         * recebe como parametros o ano da conclusao do certificado, o código dos
         * cursos realizados, e a id do códgo de certificado (retorno da funcao exibeIdCerticado
         */
           $teste=0;
          try{
               $stmt=  $this->conex->query("SELECT id, matri  FROM "
                    . "`controle_certificados` WHERE id=".$id);
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
              $teste=$row['id'];
       
              if($teste=='' or $teste==NULL){
                  
                $stmt = $this->conex->prepare("INSERT INTO controle_certificados"
                        . "(cod_curso,cod_certificado,matri,data_curso,horas) "
                           . "VALUES (?,?,?,?,?)");   
                
                $stmt->bindValue(1,$certificados->cod_aprovacao);
                $stmt->bindValue(2,$certificados->cod_certificado);
                $stmt->bindValue(3,$certificados->matricula);
                $stmt->bindValue(4,$certificados->data);
                $stmt->bindValue(5,$certificados->horas);
            
            /*
             * executar query
             */
            $stmt->execute();
            
            /**
             * fechar conexao
             */
            $this->conex=null;
                  
             }
              

        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }

    }
    
    public function verificaAutenticidade($codigo){
        /**
         * retorna um array com os dados da tabela controle certificado ou retorna
         * FALSE se o o código não for encontrado.
         */
        $array=array();
        try{
              $stmt=  $this->conex->query("SELECT * FROM"
                      . " `controle_certificados` WHERE cod_certificado='".$codigo."'");
              $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
              if(strcmp($row['cod_certificado'],$codigo)==0){
                  $array['cod_curso']=$row['cod_curso'];
                  $array['matri']=$row['matri'];
                  $array['data']=$row['data'];
                  $array['cod_certificado']=$row['cod_certificado'];
                  $array['id']=$row['id'];
                  $array['data_curso']=$row['data_curso'];
                  $array['horas']=$row['horas'];
                  return $array;
             }else{
                 return FALSE;
             }
              
             $this->conex=null;
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
        
  
    }
    
    function listarCodCertificadosEmitidos($matricula){
        try{
            $codigos=array();
            $codigos[0]='';
            $stmt=  $this->conex->query("SELECT cod_curso FROM "
                    . "`controle_certificados` WHERE matri='".$matricula."'");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                //$codigos[$i]=$row['cod_curso'];
                array_push($codigos, $row['cod_curso']);
                
               
            } 
           return $codigos;
            
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }    
    }
    function listarCodAutenticacao($matricula,$cod_curso){
        try{
            $codigo="";

            $stmt=  $this->conex->query("SELECT cod_certificado, Matri FROM "
                    . "`controle_certificados` WHERE matri='".$matricula."' AND "
                    . "cod_curso='{$cod_curso}'");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $codigo=$row['cod_certificado'];

            } 
           return $codigo;
            
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }    
    }
    
    function reimprimeTotal($matricula){
        /**
         * retorna os dados
         */
        try{
            $certificado= array();
            $certificado['cod_curso']='';
   
            $stmt=  $this->conex->query("SELECT * FROM "
                    . "`controle_certificados` WHERE matri='".$matricula."'"
                    . "AND cod_curso!='TRD'"
                    . "AND cod_curso!='LPP'"
                    . "AND cod_curso!='CPA'"
                    . "AND cod_curso!='GEL';");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
               $certificado['cod_curso']=$row['cod_curso'];
               $certificado['data']=$row['data'];
                $certificado['cod_certificado']=$row['cod_certificado'];
                $certificado['id']=$row['id'];

            } 
            if ($certificado['cod_curso']!=''){
                return $certificado;
            }else{
                return 0;
            }
           
            
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        } 
    }
    
    public function dadosCertificadoEmitido($arrayCodigo){
        /**recebe o array de retorno da funcao verificaAutenticacao e 
         * e retorna um array com o nome do cursista, código do curso e a data de 
         * conclusao.
         */
        $arrayCursista=  $this->listaCursista($arrayCodigo['matri']);
        $nome=$arrayCursista['nome'];
        
    }
    

}
