<?php

require_once '../class/banco.php';

class Usuario extends Banco {
 
 private $login;
 private $senha;

function __construct(){
 		 		
 		$this->conexao = new Banco('clientes');
 		
 	}

 	function setLogin($login){

 		$this->login = $login;

 	}

 	function setSenha($senha){

 		$this->senha = $senha;
 	}

 		function getLogin(){

 		return $this->login;
 	}


 		function getSenha(){

 		return $this->senha;
 	}


// if abaixo verifica se o campo de login ou senha está setado e retorna mensagem de campo vazio 

	function verificaLogin($login,$senha){

		if($this->login == null or $this->senha == null){
			echo json_encode(array('success' => false, 'msg' => "Campo vazio"));
		} else {
			
			$senha = md5($this->senha);

		// query de consulta do login no banco de dadoss 

			$result = $this->conexao->query("SELECT * FROM usuario 
											 WHERE login = '$login' 
											 AND senha = '$senha'");

			$rowCount = $result->rowCount();

			
		// if abaixo verifica se foi encontrado algo no banco com a query $result e retorna logado ou login  falhou 

			if($rowCount>0){

				$_SESSION['usuario'] = $this->login;

				echo json_encode(array('success' => true, 'msg' => "Logado"));
			} else {
				echo json_encode(array('success' => false, 'msg' => "Login Falhou"));
			  }
		  }

	}


	public function verificaSessao(){

	 	if(isset($_SESSION['usuario'])){

	 		echo json_encode(array('success' => true, 'msg' => "Logado", 'usuario' => $_SESSION['usuario']));

	 	} else {

	 		echo json_encode(array('success' => false, 'msg' => "Não logado"));
	 	}
	 }


	 function logout(){
	 	session_destroy();

	 	

	 }

}

?>