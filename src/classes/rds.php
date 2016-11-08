<?php

class Rds {

	private $id;
	private $nome;
 	private $url;
 	private $port;


function __construct(){

 		
 		$this->conexao = new Banco('rds');
 	}


	function setNome($nome){

 		$this->nome = $nome;
 		}

 	function setUrl($url){

 		$this->url = $url;
 		}
 		
 	function setPort($port){

 		$this->port = $port;
 		}		


public function cadastrarRds(){
 		
 		$adddate = date('Y-m-d H:i:s');
 		
 		$campos = array(
 			
 			'nome' => "'".$this->nome."'",
 			'url' =>"'".$this->url."'",
 			'port' => "'".$this->port."'",
 			'adddate' => "'".$adddate."'"
 			);

 		$query = $this->conexao->cadastrar($campos);

 		

 		if($query){

 			return (array('success' => true, 'msg' => "RDS: ". $this->nome ." cadastrada com sucesso"));

 		} else {

 			return (array('success' => false, 'msg' => "Cadastro de RDS falhou"));

 		}



 	}



	function listRds(){


	$queryRds = $this->conexao->query(
				"	SELECT 	id,
							nome,url,port 
					FROM 	rds");
 
	$fetch = $queryRds->fetchAll(PDO::FETCH_ASSOC);

	return $fetch;
	
	}
}


?>