<?php

/**
 * contem os metodos de controle de emissao de certificado.
 *
 * @author Francisco Filho - prof.francisco2014@gmail.com
 */

class Controle {

    public function verificaEmitidos($arrayEmitidos){
        
        /**
         * verifica se foi emitido o certificado retornando um inteiro
         * onde 0->nenhum certificado emitido; 1 -> certificado total emitido, 
         * 2-> certificados individuais emitidos; 3-> total e individual
         */
       try{
        $stringT="";
        $stringI='';
        $individual=FALSE;
        foreach ($arrayEmitidos as $value) {
            if(!($value==="TRD" or $value==="LPP" or $value==="GEL" or $value==='CPA')){
                $stringT.=$value;
            } else{
               $stringI=$value;      
            }
        }
//echo 'string: '.$stringT ." - individual: ".$individual.' - string i: '.$stringI.'<br>';
        if($stringT!=='' and $stringI ===""){//foi impresso somente 1 cert total
            return 1;
        }elseif ($stringT===''  and $stringI !=="") {//foi impresso somente individual
            return 2;
        }elseif ($stringT!=='' and $stringI !=="") {//foi impresso individual e total
            return 3;
        }elseif($stringT ==='' and $stringI===""){//não foi impresso nenhum certiticado
            return 0;
            
        }else{
           return -1; 
        }
       }  catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }

    }//fim do metodo verificaEmitidos
    
    
    public function imprimeTotal($array_emitidos, $cursosArray){
        /**
         * recebe como parametros o retorno das função listarCodCertificadosEmitidos e
         * o retorno da função listaCursoAprovado(), retorna uma array com os títulos dos cursos
         * aprovados, excluindo os cursos curso codigo seja do tipo individual
         * (GEL, TRD, LPP ou CPA)
         */
        try{
        $cursos=array();
        $cursos[0]="";
        $cod="";
        foreach ($array_emitidos as $value) {
            $aux=!(($value==="TRD") or ($value==="LPP") or ($value==="GEL") or ($value==='CPA'));
          if($aux){
              $cod.=$value;
              } 
          }//fim do foreach

          if (strpos($cod, 'G') !== FALSE) {
              array_push($cursos, $cursosArray["GEL"]);


          }
          if (strpos($cod, 'L1') !== FALSE ){
              array_push($cursos, $cursosArray["LPP"]);


          }
          if (strpos($cod, 'C') !== FALSE ) {
              array_push($cursos, $cursosArray["CPA"]);


          }
          if (strpos($cod, 'T') !== FALSE ) {
              array_push($cursos, $cursosArray["TRD"]);

          } 
          return $cursos;
        }catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
    }//fim do método imprimeTotal
    
    public function exibeHorasCod($cod_curso){
        /**
         * exibe o total de horas cursadas conforme o código inserido
         */
        try{
        $horas=0;
        if (strpos($cod_curso, 'G') !== FALSE) {
            $horas+=60;     
        }
        if (strpos($cod_curso, 'L1') !== FALSE ){
            $horas+=60;  

        }
        if (strpos($cod_curso, 'C') !== FALSE ) {
            $horas+=60;  

        }
        if (strpos($cod_curso, 'T') !== FALSE ) {
            $horas+=60;  
        }
        return $horas;
        
        }catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
        }
    }//fim do método exibeHorasCod
    
    public function exibeCodTotal($arrayEmitidos){
       /**
        * recebe como parametro o retorno da funcao listarCodCertificadosEmitidos()
        * e retorna somente o código de aprovação total.
        */
      try{
      $codigo="";
      foreach ($arrayEmitidos as $value) {
          $aux=!(($value==="TRD") or ($value==="LPP") or ($value==="GEL") or ($value==='CPA'));
          if($aux){
              $codigo.=$value;
              } 
          }
          return $codigo;  
          
      }catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
      }
          
    }//fim do método exibeCodTotal
    
    
    public function exibeCursoCodigo($codAprovacao,$cursosArray){
        /**
         * recebe como parametros o retorno da funcao retornaCodigoAprovacaoTotal() e o retorno da
         * funcao listaCursoAprovado() e retorna um array com o código individual o o título corres
         * pondente, sendo o código individual o indice do array.
         */
        try{
        $array=$cursosArray;
        $cursos=array();
                   
        if (strpos($codAprovacao, 'G') !== FALSE) {
            array_push($cursos, $array["GEL"]);

        }
        if (strpos($codAprovacao, 'L1') !== FALSE ){
            array_push($cursos, $array["LPP"]);


        }
        if (strpos($codAprovacao, 'C') !== FALSE ) {
            array_push($cursos, $array["CPA"]);


        }
        if (strpos($codAprovacao, 'T') !== FALSE ) {
            array_push($cursos, $array["TRD"]);

        } 
        return $cursos;
        }catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
      }
    }//fim do método exibeCursoCodigo
    

    public function verificaIdividual($arrayEmitidos,$codCurso){
        /**
         * verifica se já foi emitido um módulo individual em um certificado total
         * retorna FALSE se já foi emitido. 
         */
        try{
        $substr=$this->exibeSubstringCodTotal($codCurso);
        $codtotal=''.  $this->exibeCodTotal($arrayEmitidos);
        //echo '<br>CodTotal: '.$codtotal.'<br>substring: '.$substr;
        if($codtotal===""){
            return TRUE;
        }elseif (strpos($codtotal, $substr)!==FALSE) {
            return FALSE;
        }else{
            return TRUE;
        }
        }catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
      }
        
    }//fim do método verificaIndividual
    
    public function exibeSubstringCodTotal($cod_individual){
        /**
         * recebe o código individual e retorna a sua substring correspondente
         * no código total
         */
        try{
        switch ($cod_individual) {
            case "GEL":
                return 'G';
                break;
            case "LPP":
                return 'L1';
                break;
            case "CPA":
                return 'C';
                break;
            case "TRD":
                return 'T';
                break;
            default:
                return 0;
                break;
        }
        }catch (Exception $ex) {
            echo 'Erro : '.$ex->getMessage();
      }
    }//fim do método exibeSubstringCodTotal
    
    
}
