<?php 
	//conexión a base de datos mensajeria

	class Conexion {
		protected $conexion;
			//conexión PDO
			public function __construct() {
				try { 
					$dsn= "mysql:host=localhost;dbname=examen_usuarios;charset=UTF8";
						$this->conexion=new PDO ($dsn,'root','');
						$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$this->conexion->exec("SET CHARACTER SET utf8");
				} catch (DPOException $e) {
						throw new Exception ('conexion: '.$e->getMessage(), $e->getCode().$e->getLine());
				}		

			}
	}	




 ?>