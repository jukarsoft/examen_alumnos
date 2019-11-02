<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script type="text/javascript" src='https://code.jquery.com/jquery-3.3.1.min.js'></script>
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

	<script>
		window.onload = function() {
		 	//boton enviar mensajes  - activar listener
            document.getElementById('alta').addEventListener('click', altaUsuario);

        }   

         function altaUsuario () {
        	//alert ('altaUsuario');

        	nif = document.getElementById('nif').value;
        	nombre = document.getElementById('nombre').value;
        	apellidos = document.getElementById('apellidos').value;
            //
        	email = document.getElementById('email').value;
            //validar email

        	password = document.getElementById('password').value;
        	tipousuario = "";

            if (nif.trim() == '' || nombre.trim() == '' || apellidos.trim() == '' || email.trim() == '' || password.trim() == '' ) {
                alert('NIF, Nombre, Apellidos, Email, Password, son campos obligatorios');
                return
            }
            if (!isValidEmail(email)) {
                alert('Formato email Incorrecto');
                return
            }
            valor=validarNIF(nif);
            if (!valor) {
            alert('NIF no válido');  
            return;
        }

        	var datos = new FormData();
            datos.append('opcion','alta');  
            datos.append('nif',nif);  
            datos.append('nombre', nombre);
            datos.append('apellidos', apellidos);  
            datos.append('email', email);  
            datos.append('password', password); 
            datos.append('tipousuario', tipousuario); 

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
                                	
                	alert (`alta OK: ${datos.codigo} << ${datos.mensaje} >>`);
                    validarLogon();
                    document.getElementById('nif').value = "";
                    document.getElementById('nombre').value = "";
                    document.getElementById('apellidos').value = "";
                    document.getElementById('email').value = "";
                    document.getElementById('password').value = "";
                    
                    
                }      
             }) 
             .catch(function(error) {
                alert (error);
            }) 
        } 

        function isValidEmail(mail) { 
            return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(mail); 
        }

        //valida el NIF
        function validarNIF(nif) {
            var lockup = 'TRWAGMYFPDXBNJZSQVHLCKE';
            var valueNif=nif.substr(0,nif.length-1);
            var letra=nif.substr(nif.length-1,1).toUpperCase();
     
            if(lockup.charAt(valueNif % 23)==letra) {
                return true;
            } else {return false;}
            
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
		<h2>ALTA FORO</h2>
		<span></span><br><br>
		<form> 
			<label>NIF Usuario: </label><input type="text" name="nif" id="nif"><br>
			<label>Nombre: </label><input type="text" name="nombre" id="nombre"><br>
			<label>Apellidos: </label><input type="text" name="apellidos" id="apellidos"><br>
			<label>Email: </label><input type="email" name="email" id="email"><br>
			<label>Password: </label><input type="password" name="password" id="password"><br><br>
			<input type="button" name="alta" id="alta" value="Alta usuario" >
		</form><br><br>
		
		<a href="examen_login.php">Volver a login</a>
	</div><br>
</body>
</html>