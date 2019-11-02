<?php

	$arrayExt = ['jpg', 'gif', 'png'];
	//recuperar los ficheros
	$fichero = $_FILES['archivo'];
	$dato = $_POST['dato'];
	//que queremos hacer con el archivo
	//echo "$fichero[name] $fichero[size] $fichero[tmp_name] $fichero[type]";
	if ($fichero['size']<300000 && validarExt($fichero,$arrayExt)) {
		//guardar el archivo en una carpeta del servidor
		if (move_uploaded_file($fichero['tmp_name'], "../img/$fichero[name]")) {
			$codigo = '00';
			$mensaje = "Fichero subido correctamente";
		} else {
			$codigo = '77';
			$mensaje= "Fichero no subido";
		}

	} else {
		$codigo = '78';
		$mensaje = "fichero excede tamaño permitido o es un formato no permitido";
	}
	
	//echo "$fichero[name] $fichero[size] $fichero[tmp_name] $fichero[type]";
	$respuesta = (object) ['codigo'=> $codigo, 'mensaje'=> $mensaje, 'name' => $fichero['name'], 'size' => $fichero['size'], 'type' => $fichero['type']];
	 echo json_encode($respuesta);
	


	//validar una extensión de archivo
	function validarExt ($file,$arrayExt) {
		$pos=strrpos($file['name'], '.');
		$ext=substr($file['name'], $pos+1);
		if (!in_array($ext, $arrayExt)) {
			return false;
		} else  return true;
	}	

?>