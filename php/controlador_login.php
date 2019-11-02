<?php 

	//controlador login
	//modelo
	require 'modelo_login.php';

	if (isset($_SESSION['mensajeria'])) {
		$user = $_SESSION['mensajeria']['usuario'];
		$tipo = $_SESSION['mensajeria']['tipo'];
	} 

	try {
		//instancia de la clase Login
		$login1 = new Login();

		$opcion=$_POST['opcion'];
		//$opcion='consulta';
		switch ($opcion) {
		case 'logon':
			$nif = $_POST['nif'];
			$pass = $_POST['password'];
			//$nif = '123456789';
			//$pass = 'pepe';
			$login1->comprobarLogin($nif, $pass);

			$codigo='00';
			$info='logado';
			if (isset($_SESSION['mensajeria'])) {
				$user = $_SESSION['mensajeria']['usuario'];
				$tipo = $_SESSION['mensajeria']['tipo'];
			}
			$respuesta= (object) [
					'codigo'=> $codigo, 
					'mensaje'=> $info,
					'tipousuario' => $tipo
			];
			echo json_encode($respuesta);
			break;
		case 'logoff':
			$login1->borrarSession();
			if (isset($_SESSION['mensajeria']))	 {
				//borrado de la variable de sesion
				unset($_SESSION['mensajeria']);
			}	
			$codigo='00';
			$info="OK";
			//$control=array('codigo'=>$codigo, 'mensaje'=> $info);
			$respuesta= (object) [
				'codigo'=> $codigo, 
				'mensaje'=> $info
				];
				//print_r($respuesta);
			echo json_encode($respuesta);
			break;
			case 'alta':
				$nif = $_POST['nif'];
				$nombre = $_POST['nombre'];
				$apellidos = $_POST['apellidos'];
				$email = $_POST['email'];
				$password = $_POST['password'];
				$tipousuario = $_POST['tipousuario'];
				$respuesta=$login1->altaUsuario($nif, $nombre, $apellidos, $email, $password, $tipousuario);
			//$respuesta=$login1->altaUsuario('38424012L', 'paco', 'ganso', 'jjjj@gmail.com', 'pass', NULL);
				echo json_encode($respuesta);
			break;
			case 'consulta':
				$respuesta=$login1->obtenerUsuario ($user);
				//print_r($respuesta);
				echo json_encode($respuesta);
			break;
			case 'alta':
				$nif = $_POST['nif'];
				$nombre = $_POST['nombre'];
				$apellidos = $_POST['apellidos'];
				$email = $_POST['email'];
				$password = $_POST['password'];
				$tipousuario = $_POST['tipousuario'];
				$respuesta=$login1->altaUsuario($nif, $nombre, $apellidos, $email, $password, $tipousuario);
			//$respuesta=$login1->altaUsuario('38424012L', 'paco', 'ganso', 'jjjj@gmail.com', 'pass', NULL);
				echo json_encode($respuesta);
			break;
		default:
			break;
		}

	
	} catch (Exception $e) {
		$respuesta= (object) [
					'codigo'=> $e->getCode(), 
					'mensaje'=> $e->getMessage()
				];
		//$respuesta=array($e->getCode(),$e->getMessage());
		$error=json_encode($respuesta);
		echo $error;
		
	}





?>