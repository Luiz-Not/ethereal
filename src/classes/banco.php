<?php 

class Banco {

private $conexao;
private $tabela;

function __construct($tabela){

$this->tabela = $tabela;

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "iset";
$config['db']['dbname'] = "projeto";

try {
    $this->conexao = new PDO("mysql:host=" . $config['db']['host'] . ";dbname=" . $config['db']['dbname'],
        $config['db']['user'], $config['db']['pass']);

    $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
  
}

catch(PDOException $e)
    {
    echo "Connection failed: " . $e->getMessage();

    
    }
	
}

function cadastrar($campos){



	$insert = " INSERT INTO {$this->tabela} ";	

	foreach($campos as $key => $value) {
			$chaves[] = $key;
			$valores[] = $value;
		};

	var_dump($this->conexao);
	die();

	$result = $this->conexao->query($insert .= "(".implode(',',$chaves).") VALUES (".implode(',',$valores).")");


		return $result;
	}



	function listar($campos){

	$result = $this->conexao->query("SELECT ".implode(',',$campos) ." FROM '{$this->tabela}'");	

	return $result;

	}


	function buscarJoin($dados){

		$result = $this->conexao->query($dados);

		return $result;

	}

	function alterar($campos){


	$update = "UPDATE {$this->tabela} ";	

		foreach ($campos as $key => $value) {
			$chaves[] = $key;
			$valores[] = $value;
		}


	$result = $this->conexao->query($update .= "(".implode(',',$chaves).") VALUES (".implode(',',$valores).")");

	
		return $result;


	}


	function excluir($id){

		$queryDelete = "DELETE FROM $this->tabela WHERE $id";


		$result = $this->conexao->query($queryDelete);

		return $result;

	}

	function query($query){

	$result = $this->conexao->query($query);	
	return $result;

	}

	

};