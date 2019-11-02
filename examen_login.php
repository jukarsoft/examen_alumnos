<?php 
	session_start();

	//$_SESSION['mensajeria']['usuario'] = $usuarios['idpersona'];
	//$_SESSION['mensajeria']['tipo'] = $usuarios['tipousuario'];

	//validar si existe la sesion // indica que esta logado previamente
	if (isset($_SESSION['mensajeria'])){
		if ($_SESSION['mensajeria']['tipo']=='AD') {
			header('Location: examen_mensajes_admin.php');
		} else {
			header('Location: examen_mensajes.php');
		}
	}	
		//$_SESSION['mensajeria']['usuario'] = $usuarios['idpersona'];
		//$_SESSION['mensajeria']['tipo'] = $usuarios['tipousuario'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style type="text/css">
		div.wraper {
			width:500px; 
			border:2px solid black; 
			border-radius:5px; 
			text-align:justify; padding:20px; 
			margin:auto;
			background-color: palegreen;
		}
		label {
			width: 120px;
			display: inline-block;
		}
	</style>
	
	<script type="text/javascript">
		//activar listener
		 window.onload = function() {
            document.getElementById('logon').addEventListener('click', validarLogon);
        }    

        function validarLogon() {
        	//alert ('validarLogon');
			var nif=document.getElementById('nif').value;
			var pass=document.getElementById('password').value;
			if (nif.trim() == '' || pass.trim() == '') {
                alert('NIF y PASSWORD son obligatorios');
                return
            }


            var datos = new FormData();
            datos.append('opcion', 'logon');
            datos.append('nif', nif);
            datos.append('password', pass);
            fetch('php/controlador_login.php', {
                method: 'POST',
                body: datos
            })
            .then (function(respuesta) {
               //primera respuesta del servidor como que ha recibido la petición
                if (respuesta.ok) {
                    return respuesta.json();
                } else {
                    console.log(respuesta);
                    throw "error en la llamada AJAX", 88;
                }
             })
             .then (function (datos) {
             	console.log(datos);
             	//alert (datos);
                if (datos.codigo!='00')  {
                	error = `error: ${datos.codigo} << ${datos.mensaje} >>`;
                	throw "mensaje error", error;
                	//alert (`error: ${datos.codigo} - ${datos.mensaje}`);
                } else {
                	if (datos.tipousuario=='AD') {
                		window.location.href = 'examen_mensajes_admin.php';   
                	} else {
                		window.location.href = 'examen_mensajes.php'; 
                	}
                }      
             }) 
             .catch(function(error) {
                alert (error);
            }) 

		}

		

	</script>



</head>
<body>
	<div class='wraper'> 
		<h2>LOGIN FORO</h2>
		<span></span><br><br>
		<form> 
			<label>NIF Usuario: </label><input type="text" id="nif"><br>
			<label>Password: </label><input type="password" id="password"><br><br>
			<input type="button" id="logon" value="Log in" >
		</form><br><br>

		<a href="examen_alta.php">Darse de alta como usuario</a><br><br>
	</div><br>
</body>
</html>