<?php 
	//modelo login
	require 'modelo_conexion.php';
	
	//diseño clase Mensaje
	class Mensaje extends Conexion {
		public function cargarMensajes($pagina) {
			//variables de paginación // inicialización variables de paginación
			$filaInicial=0;
			$numFilasMostrar=5;
			try {
				$filaInicial=($pagina-1)*$numFilasMostrar;
				$sql="SELECT idcomentario, comentario, idalta, foto, fecha, nombre, apellidos FROM comentarios INNER JOIN usuarios ON idpersona = idalta LIMIT $filaInicial,$numFilasMostrar";
				 //prepare de la sentencia sql para acceder al usuario por nif
				$stmt = $this->conexion->prepare($sql);
				// Especificar como se quieren devolver los datos
				$stmt->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				//$stmt->bindParam(':idpersona', $idpersona);
				//Ejecutar la sentencia
				$stmt->execute();
				$array_mensajes = array();		
				while ($fila = $stmt->fetch()) {
					array_push($array_mensajes, $fila);
				}	
				//recupera el numero de filas de la seleccion
				$numFilas=$stmt->rowCount();

				//averiguar el número de páginas a montar
				$sql2 = "SELECT COUNT(*) as numeroFilas FROM comentarios";
				//prepare de la sentencia sql para acceder al usuario por nif
				$stmt2 = $this->conexion->prepare($sql2);
				// Especificar como se quieren devolver los datos
				$stmt2->setFetchMode(PDO::FETCH_ASSOC);
				//bind de los parámetros
				//$stmt2->bindParam(':destinatario', $destinatario);
				//Ejecutar la sentencia
				$stmt2->execute();
				
				$fila = $stmt2->fetch();
				//print_r($fila['numeroFilas']);	
				//recupera el numero de filas totales
				$numFilasTotales=$stmt2->rowCount();
				//calcular el número de páginas 
				$paginas=ceil($fila['numeroFilas'] / $numFilasMostrar);
				//echo "<br>";
			

				///////////////////

				//retorna codigo error + la lista de mensajes obtenida y el número de paginas a montar
				$codigo='00';
				$info="OK";
				//$control=array('codigo'=>$codigo, 'mensaje'=> $info);
				$respuesta= (object) [
					'codigo'=> $codigo, 
					'mensaje'=> $info, 
					'lista' => $array_mensajes, 
					'numfilas' => $numFilas,
					'paginas' => $paginas
				];
				//print_r($respuesta);
				return $respuesta;
			} catch (PDOException $e) {
				throw new Exception ($e->getMessage(), $e->getCode());	
			}

		}

		public function altaMensaje ($idalta, $texto, $foto) {

			try {
				$sql="INSERT INTO comentarios VALUES (NULL,'$texto','$idalta', '$foto', current_timestamp())";
				$stmt=$this->conexion->PREPARE($sql);
				//inicia una transaction
				$this->conexion->beginTransaction();
				//Ejecutar la sentencia
				$stmt->execute();
				$ultimoId=$this->conexion->lastInsertId();
				//commit a la transacción
				$this->conexion->commit();
				//numero de filas modificadas
				$numFilas=$stmt->rowCount();
				if ($numFilas==0) {
					$codigo='14';
					$mensaje='no se han insertado los mensajes, comprobar bbdd';
				} else {
					$codigo='00';
					$mensaje='Alta mensaje realizada con éxito';
				} 
				$respuesta= (object) ['codigo'=>'00', 'mensaje'=> $mensaje, 'ultimoId' => $ultimoId];
				return $respuesta; 

			} catch (PDOException $e) {
					throw new Exception ($e->getMessage(), $e->getCode()); 
			}


		}

		//función acceso a bbdd mensajes para el borrado de los mensajes seleccionados
		public function borrarMensajes ($array) {
			try {
				$sql="DELETE FROM comentarios WHERE idcomentario IN ($array)";
				//prepare de la sentencia sql para acceder al usuario por nif
				$stmt=$this->conexion->PREPARE($sql);
				
				//inicia una transaction
				$this->conexion->beginTransaction();
				//Ejecutar la sentencia
				$stmt->execute();
				//commit a la transacción
				$this->conexion->commit();
				//numero de filas modificadas
				$numFilas=$stmt->rowCount();
				if ($numFilas==0) {
					$codigo='14';
					$mensaje='no se relizado ninguna baja de registros, comprobar bbdd';
				} else {
					$codigo='00';
					$mensaje='petición de BORRADO mensajes: '.$array.', realizada';
				} 
				$respuesta= (object) ['codigo'=>'00', 'mensaje'=> $mensaje];
				//print_r($respuesta);
				return $respuesta; 

			} catch (PDOException $e) {
				throw new Exception ($e->getMessage(), $e->getCode());	

			}
		}	


	}	//fin clase

/*
//provisionalmente para probar
	try {
		$conexion = new Mensaje();
	} catch (Exception $e) {
		echo $e->getMessage();

	}
*/
/*	
		//provisionalmente para probar
	try {
		$conexion->cargarMensajes();
	} catch (Exception $e) {
		echo $e->getMessage();
	}
*/
/*
	try {
		$conexion->altaMensaje(64,'hola','bici.jpg');
	} catch (Exception $e) {
		echo $e->getMessage();
	}
*/	

/*
	try {
		$conexion->borrarMensajes(38);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
*/	

?>