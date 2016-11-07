<?php



class Cliente extends Banco {

	private $id;
 	private $idRds;
 	private $idStack;
 	private $nome;
 	private $email;
 	private $dominio;


function __construct(){

 		$this->id = $id;
 		$this->idRds = $idRds;
 		$this->idStack = $idStack;
 		$this->nome = $nome;
 		$this->email = $email;
 		$this->dominio = $dominio;

 		
 		$this->conexao = new Banco('clientes');
 		
 	}


 	public function setId($id){

 		$this->id = $id;

 	}

 	public function getId(){

 		return $this->id;

 	}


 	public function setIdRds($idRds){

 		$this->idRds = $idRds;

 	}

 	public function setIdStack($idStack){

 		$this->idStack = $idStack;

 	}


 	public function setNome($nome){

 		$this->nome = $nome;

 	}

 	public function setEmail($email){

 		$this->email = $email;

 	}

 	public function setDominio($dominio){

 		$this->dominio = $dominio;

 	}


 	public function getNome(){

 		return $this->nome;
 	}	


function dashboard(){
	 		 		
 		$queryDashboard =(
 			"	SELECT 
 			c.id as id,
 			c.nome as cliente, 
 			c.email as email, 
 			c.dominio as dominio,
 			r.nome as rds, 
 			s.nome as stack  
 			FROM clientes c 
 			INNER JOIN rds r ON c.rds_id = r.id 
 			INNER JOIN stack s ON c.stack_id = s.id;"); 


 		$execute = $this->conexao->buscarJoin($queryDashboard);

 		$fetch = $execute->fetchAll(PDO::FETCH_ASSOC);

 		return $fetch;
 		
 	}

}


?>