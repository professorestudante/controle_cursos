<?php
/**
 * Description of PDOConnectionFactory
 *
 * @author cefop 9
 */
class PDOConnectionFactory {
// recebe a conexão
	public $con = null;
	// qual o banco de dados?
        
	public $dbType 	= "mysql";
	// parâmetros de conexão
	// quando não for necessário deixe em branco apenas com as aspas duplas ""
	public $host 	= "localhost";
	public $user 	= "root";
	public $senha 	= "123456";
	public $db      = "curso_coordenadores";
        public $set = "UTF-8";
	// seta a persistência da conexão
	public $persistent = false;
	// new PDOConnectionFactory( true ) <--- conexão persistente
	// new PDOConnectionFactory()       <--- conexao não persistente
	public function PDOConnectionFactory( $persistent=false ){
		// verifico a persistência da conexao
		if( $persistent != false){
			$this->persistent = true;
		}
	}
	public function getConnection(){
		try{
			// realiza a conexão
                        $dsn = $this->dbType.":host=".$this->host.";dbname=".$this->db;"charset=utf8";//Garante a codificacao UTF-8
			$this->con = new PDO($dsn,  $this->user, $this->senha,
					array( PDO::ATTR_PERSISTENT => $this->persistent ,
                                            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8') );
			// realizado com sucesso, retorna conectado
			return $this->con;
			// caso ocorra um erro, retorna o erro;
		}catch ( PDOException $ex ){
			echo "Erro: ".$ex->getMessage();
		}
	}
	// desconecta
	public function Close(){
		if( $this->con != null )
			$this->con = null;
	}
}
