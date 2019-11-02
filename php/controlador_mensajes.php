<?php 
	//controlador Mensajes
	session_start();
	//controlador login
	require 'modelo_mensajes.php';
	//validar si existe la sesion // indica que esta logado previamente
	if (!isset($_SESSION['mensajeria'])){
		header('Location: mensajeria_login.php');
	} else {
		$user = $_SESSION['mensajeria']['usuario']; 
		$tipo = $_SESSION['mensajeria']['tipo'];
	}

	
	//instancia de la clase Mensaje
	$mensajes = new Mensaje();
	$opcion=$_POST['opcion'];
	
	switch ($opcion) {
		
		case 'C':
				$pagina = $_POST['pagina'];
				$array=$mensajes->cargarMensajes($pagina);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($array);
				echo $respuesta;
				break;	
		case 'A':
				$idpersona=$_POST['idpersona'];
				$texto=$_POST['texto'];
				$foto=$_POST['foto'];
				$array=$mensajes->altaMensaje($idpersona, $texto, $foto);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($array);
				echo $respuesta;
				break;	
		case 'B':
				$array=$_POST['array'];
				$datos=$mensajes->borrarMensajes($array);
				//retorna array: control, array_mensajes, arrayfilas
				$respuesta=json_encode($datos);
				echo $respuesta;
				break;					
		default:
				break;
		}



?>