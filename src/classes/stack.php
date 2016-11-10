<?php


class Stack {

	private $id;
	private $nome;
	private $endereco;

	function __construct(){
		
		$this->conexao = new Banco('stack');
	}

	function setNome($nome){

		$this->nome = $nome;
	}

	function setEndereco($endereco){

		$this->endereco = $endereco;
	}

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

		$queryStack = $this->conexao->query(
				"SELECT id,
							nome,endereco 
							FROM stack");


		$fetch = $queryStack->fetch(PDO::FETCH_ASSOC);

		return $fetch;

	}

	function buscaStack($busca){

		$queryBusca = "select * from stack where id = $busca";
		$query      = $this->conexao->query($queryBusca);
		$fetch      = $query->fetch(PDO::FETCH_ASSOC);
		return $fetch;
	}

	function verificarId($idUrl){
		$this->id = $idUrl;
		$result = $this->conexao->query("SELECT * FROM stack WHERE id = $this->id ");
		$rowCount = $result->rowCount();
		return $rowCount;	
	}
}