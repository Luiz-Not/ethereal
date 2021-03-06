<?php

require_once 'banco.php';

class Cliente extends Banco {

	private $id;
	private $idRds;
	private $idStack;
	private $nome;
	private $email;
	private $dominio;

	function __construct(){
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

	public function cadastrarNoBanco(){
		$adddate = date('Y-m-d H:i:s');

		$campos = array(
			'rds_id' => $this->idRds,
			'stack_id' => $this->idStack,
			'nome' => "'".$this->nome."'",
			'email' =>"'".$this->email."'",
			'dominio' => "'".$this->dominio."'",
			'adddate' => "'".$adddate."'"
		);

		$query = $this->conexao->cadastrar($campos);

		if($query){
			return (array('success' => true, 'msg' => "Cliente ". $this->nome ." cadastrado com sucesso"));
		} else {
			return (array('success' => false, 'msg' => "Cadastro falhou"));

		}
	}

	function verificarEmail(){
		$result = $this->conexao->query("SELECT * FROM clientes WHERE email = '$this->email'");
		$rowCount = $result->rowCount();

		return $rowCount;	
	}

	function verificarId($idUrl){
		$this->id = $idUrl;
		$result = $this->conexao->query("SELECT * FROM clientes WHERE id = $this->id ");
		$rowCount = $result->rowCount();
		
		return $rowCount;	
	}

	function verificarCampo($campo){

		if($campo == null){

			echo $response = json_encode("Campo ".$campo." vazio");die;
			
			die;
		}
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

	function buscaCliente($busca){
		
		$queryBusca = "select * from clientes where id = $busca";
		$query      = $this->conexao->query($queryBusca);

		$fetch      = $query->fetch(PDO::FETCH_ASSOC);

		return $fetch;
		
	}

	function alterarCliente() {
		$alterdate = date('Y-m-d H:i:s');
		$idAlter = $this->id;

		$campos = array(
			'SET rds_id = '=> $this->idRds,
			'stack_id =' => $this->idStack,
			'nome =' => "'".$this->nome."'",
			'email =' =>"'".$this->email."'",
			'dominio =' => "'".$this->dominio."'",
			'alterdate =' => "'".$alterdate."'"
		);

		$query = $this->conexao->alterar($campos,$idAlter);

		if($query){
			return (array('success' => true, 'msg' => " Cliente ". $this->nome ." alterado com sucesso"));
		} else {
			return (array('success' => false, 'msg' => "Alteração falhou"));
		}
	}

	function excluirCliente(){
		$queryDelete = "DELETE FROM clientes WHERE id = '$this->id';" ;
		$query = $this->conexao->query($queryDelete);

		if($query){

			return (array('success' => true, 'msg' => "Cliente excluído com sucesso"));

		} else {

			return (array('success' => false, 'msg' => "Processo falhou"));
		}

	}

	function graficoClientesStack(){

	$query = " 
			SELECT count(c.stack_id) as stackQuant, s.nome as nomeStack 
			FROM clientes c  
			inner join stack s 
			on c.stack_id = s.id 	
			group by c.stack_id";	

			$result = $this->conexao->query($query);

			$fetch = $result->fetchAll(PDO::FETCH_ASSOC);

			return $fetch;
		}

		function graficoClientesRds(){

	$query = " 
			SELECT count(c.rds_id) as rdsQuant, c.rds_id, r.nome as nomeRds
			from clientes c
			inner join rds r on c.rds_id = r.id
			group by c.rds_id";

			$result = $this->conexao->query($query);

			$fetch = $result->fetchAll(PDO::FETCH_ASSOC);

			return $fetch;
		}
	}