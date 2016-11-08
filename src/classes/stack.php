<?php

class Stack {

	private $id;
	private $nome;
 	private $endereco;
 	

 	function __construct(){

		$this->nome = $nome;
		$this->endereco = $endereco;
		$this->conexao = new Banco('stack');
 	}


 	function setNome($nome){

 		$this->nome = $nome;
 	}

 	function setEndereco($endereco){

 		$this->endereco = $endereco;
 	}

/// 

		public function cadastrarStack(){
	 		
	 		$adddate = date('Y-m-d H:i:s');
	 		
	 		$campos = array(
	 			
	 			'nome' => "'".$this->nome."'",
	 			'endereco' =>"'".$this->endereco."'",
	 			'adddate' => "'".$adddate."'"
	 			);

	 		$query = $this->conexao->cadastrar($campos);

	 		

	 		if($query){

	 			return (array('success' => true, 'msg' => "Stack: ". $this->nome ." cadastrada com sucesso"));

	 		} else {

	 			return (array('success' => false, 'msg' => "Cadastro de Stack falhou"));

	 		}



 	}



 	function listStack(){


	$db = new PDO("mysql:host=localhost;dbname=projeto","root","iset");

	$queryStack = $db->query(
				"	SELECT 	id,
							nome,endereco 
					FROM 	stack");
 
	$fetch = $queryStack->fetchAll(PDO::FETCH_ASSOC);

	return $fetch;
	
	}
}


?>