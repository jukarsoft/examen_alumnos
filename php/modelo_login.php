<?php 	
	session_start();
	//modelo login
	require 'modelo_conexion.php';

	//diseño clase Login
	class Login extends Conexion {

		public function __construct () {
			try {
				//llamar al constructor de la conexion
				parent::__construct();

			} catch (Exception $e){
				throw new Exception ($e->getMessage(),$e->getCode());
			}	
		}

		//validar el nif y el pass // validar los datos
		private function validar ($nif,$pass)	 {
			if (empty($nif) || empty($pass)) {
				throw new Exception ("NIF y Pass obligatorios", 10);
			}
		}

		//comprobar que el usuario es correcto
		public function comprobarLogin ($nif,$pass) {
			try {
				$this->validar($nif,$pass);

				//prepare de la sentencia sql para acceder al usuario por nif
				$stmt = $this->conexion->prepare("SELECT * FROM usuarios WHERE nif=:nif");
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt->bindParam(':nif', $nif);
				//Ejecutar la sentencia
				$stmt->execute();

				//validar que el nif existe
				if ($stmt->rowCount()==0) {
					throw new Exception ("NIF inexistente", 11);
				}

				//validar que la pass coincida
				$usuarios= $stmt->fetch();
				if ($pass != $usuarios['password']) {
					throw new Exception ("PASS incorrecta", 12);
				}		
					
				//guardar datos del usuario en la sesion					
				$_SESSION['mensajeria']['usuario'] = $usuarios['idpersona'];
				$_SESSION['mensajeria']['tipo'] = $usuarios['tipousuario'];
				//echo $_SESSION['mensajeria']['usuario'];
				//echo $_SESSION['mensajeria']['tipo'];

				$codigo='00';
				$info="OK";
				//$control=array('codigo'=>$codigo, 'mensaje'=> $info);
				$respuesta= (object) [
					'codigo'=> $codigo, 
					'mensaje'=> $info, 
					'tipousuario' => $usuarios['tipousuario']
				];
				//print_r($respuesta);
				return $respuesta;
				
			} catch (PDOException $e) {
				throw new Exception ($e->getMessage(), $e->getCode());	
			}
		}

		public function borrarSession () {
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
			return $respuesta;
		}
		
			
		public function altaUsuario ($nif, $nombre, $apellidos, $email, $password, $tipousuario) {
			try {
				//validar los datos
				$this->validarUsuario($nif, $nombre, $apellidos, $email, $password);
				//$sql="INSERT INTO usuarios VALUES(NULL, $nif, $nombre, $apellidos, $email, $password, NULL, $tipousuario)";
				$this->validarSiExisteUser($nif);
				$sql="INSERT INTO usuarios VALUES (NULL, '$nif', '$nombre', '$apellidos', '$email', '$password', NULL, '$tipousuario')";
				
				$stmt = $this->conexion->prepare($sql);

				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				$this->conexion->beginTransaction();
				//Ejecutar la sentencia
				$stmt->execute();
				$ultimoId=$this->conexion->lastInsertId();
				//commit a la transacción
				$this->conexion->commit();
				$numFilas=$stmt->rowCount();

				//recuperar el id asignada en el alta
				
				//echo $numFilas;
				//echo "<br>";
				//retorna codigo error + la lista de mensajes obtenida y el número de paginas a montar
				if ($numFilas==0) {
					$codigo='14';
					$mensaje='no se han insertado los mensajes, comprobar bbdd';
				} else {
					$codigo='00';
					$mensaje='Alta usuario realizada ';
				} 
				$respuesta= (object) ['codigo'=>'00', 'mensaje'=> $mensaje, 'numFilas' => $numFilas, 'ultimoId' => $ultimoId];
				
				$this->grabarFichero($ultimoId, $nif, $nombre, $apellidos);

				return $respuesta; 

			//print_r($respuesta);
			return $respuesta;
			} catch (PDOException $e){
				throw new Exception ($e->getMessage(), $e->getCode());	
			}
		}

		private function validarUsuario($nif, $nombre, $apellidos, $email, $password) {
			if (empty($nif) || empty($nombre)|| empty($apellidos) || empty($email) || empty($password)) {
				throw new Exception('NIF, Nombre, Apellidos, email y password son datos obligatorios', 20);
			}
		}


		public function validarSiExisteUser($nif) {
			try {
			$sql="SELECT * FROM usuarios WHERE nif = :nif";
								
				$stmt = $this->conexion->prepare($sql);
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt->bindParam(':nif', $nif);
				//Ejecutar la sentencia
				$stmt->execute();
				$array_usuarios = array();		
				while ($fila = $stmt->fetch()) {
					array_push($array_usuarios, $fila);
					//echo "<br>";
					//print_r($libros);
				}	
				$numFilas=$stmt->rowCount();
				if ($numFilas <> 0) {
					$mensaje='NIF ya existe en bbdd: '.$nif;
					throw new Exception($mensaje, 20);
				}

			} catch (PDOException $e){
				throw new Exception ($e->getMessage(), $e->getCode());	
			}

		}

		public function grabarFichero($ultimoId, $nif, $nombre, $apellidos) {

			try {
				//Abre fichero en modo lectura lectura/escritura
				$fichero = fopen('../files/log.txt','a+');
				$contenido=null;
				//leer el fichero
				$contenido = "$ultimoId, $nif, $nombre, $apellidos\n";
				fwrite($fichero,$contenido);
				fclose($fichero);
			} catch (Exception $e) {

			}
			

		}	

		public function obtenerUsuario ($user) {
			try {
				$sql="SELECT idpersona, nombre, apellidos, tipousuario FROM usuarios WHERE idpersona = :user ";
								
				$stmt = $this->conexion->prepare($sql);
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				$stmt->bindParam(':user', $user);
				//Ejecutar la sentencia
				$stmt->execute();
				$array_usuarios = array();		
				while ($fila = $stmt->fetch()) {
					array_push($array_usuarios, $fila);
					//echo "<br>";
					//print_r($libros);
				}	
				$numFilas=$stmt->rowCount();
				//echo $numFilas;
				//echo "<br>";
				//retorna codigo error + la lista de mensajes obtenida y el número de paginas a montar
				$codigo='00';
				$info="OK";
				
				$respuesta= (object) [
					'codigo'=> $codigo, 
					'mensaje'=> $info, 
					'lista' => $array_usuarios, 
					'numfilas'=> $numFilas
				];
			//print_r($respuesta);
			return $respuesta;
			} catch (PDOException $e){
				throw new Exception ($e->getMessage(), $e->getCode());	
			}
		}



	} //fin de clase

//////////////////////////////////////////////////////////////////////////////////
/*
	//provisionalmente para probar
	try {
		$login=new Login('43510039', 'admin');
	} catch (Exception $e) {
		echo $e->getMessage();

	}
*/
/*
		//provisionalmente para probar
	try {
		$login= new Login();
	} catch (Exception $e) {
		echo $e->getMessage();
	}	
/*
	}
	$login->altaUsuario ('38424012Z', 'juancarlos', 'moreno valero', 'jucamova@mail.com', 'juan', NULL);

*/
/*	
	try {
		$login->validarSiExisteUser('38424012M');
	} catch (Exception $e) {
		echo $e->getMessage();
	}
*/
/*
	try {
		$login->obtenerUsuario (64);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
*/
//////////////////////////////////////////////////////////////////////////////////
?>