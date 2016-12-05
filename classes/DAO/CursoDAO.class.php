<?php

/**
 * Contém os métodos de acesso ao banco de dados curso_coordenadores
 *
 * @author Francisco Filho - prof.francisco2014@gmail.com
 */


include('../classes/PDO/PDOConnectionFactory.class.php');
include_once '../classes/DAO/Certificados.class.php';

class CursoDAO extends PDOConnectionFactory{
    
    public $conex = null;
    
    public function __construct() {
        $this->conex=  PDOConnectionFactory::getConnection();
    }

    public function listaCursoAprovado($matricula){
        /**
         * Retorna um array com os dados (titulo, codigo) dos cursos aprovados referentes a
         * matricula inserida
         */
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
           /**
            * retorna o total de horas cursadas referente à todos os cursos
            * cursados e aprovados
            */
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
        /**
         * Retorna a data mais recente de conclusão do curso referente ao código
         * inserido. Se não for inserido o código do curso, retorna a data mais
         * recente dos total de cursos realizados.
         * 
         */
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
         * retorna um array tendo como indice o codigo do curso e um boleano 
         * indicando se foi aprovado (TRUE) ou não (FALSE).
         * O retono desse método deve ser inserido na funcao retornaCodigoAprovacaoTotal()
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
           
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }
    }

    public function retornaCodigoAprovacaoTotal($matricula){
            
             /**
              * Retorna um código de aprovação todos os cursos aprovados e que ainda
              * não aparecem na tabela controle_certificados.
             */
            $aprovacao=  $this->retornaAprovacao($matricula);//retorno do método retornaAprovacao
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
         /**
          * retorna o id mais recente da tabela controle_certificado
          */
       
       $stmt=  $this->conex->query( "SELECT MAX(id) FROM `controle_certificados`"
               . " WHERE 1"); 
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
       return $row['MAX(id)'];
    }
    function gerarIdCertificado($matricula, $cod_aprovacao){
        /* @var $ultimoId type */
        /**
         * verifica se já existe referencias a matricula eo codigo inseridos na 
         * tabela controle de certificados, caso não exista, retorna o
         * o retorno da funçao retornaUltimoId() adicionado a 1. caso exista, 
         * retorna o id referente a matrícula e o cod_aprovacao na tabela controle
         * _certificados.
         */
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
         * recebe como parametros o código dos cursos realizados, e a id do 
         * códgo de certificado (retorno da funcao exibeIdCerticado). Verifica se
         * o id já existe na tabela controle_certificado e, caso não exista,
         * insere os dados do objeto $certificados no base de dados.
         */
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
        /**
         * retorna um array com os dados do campo cod_curso da tabela controle_certificados
         * referentes a matricula inserida
         */
        try{
            $codigos=array();
            $codigos[0]='';
            $stmt=  $this->conex->query("SELECT cod_curso FROM "
                    . "`controle_certificados` WHERE matri='".$matricula."'");
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                array_push($codigos, $row['cod_curso']);
                
               
            } 
           return $codigos;
            
        } catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();

        }    
    }
    function listarCodAutenticacao($matricula,$cod_curso){
        /**
         * retorna um array com os dados do campo cod_certificado da tabela controle_certificados
         * referentes a matricula e o código do curso inseridos.
         */
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
         * retorna os dados dados da tabela controle_certificados referentes a
         * matricula inserida
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
    
}
