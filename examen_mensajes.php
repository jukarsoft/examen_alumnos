<?php
	session_start();
	
	if (isset($_SESSION['mensajeria'])) {
		$user = $_SESSION['mensajeria']['usuario'];
		$tipo = $_SESSION['mensajeria']['tipo'];
	} else {
		header('Location: examen_login.php');
	}
	
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

        img {
            width: 80%;
        }
        #paginas {cursor:pointer;}


	</style>
	
	<script type="text/javascript">
        var tipoUsuario="";
        var idpersona="";
        var pagina=1;
        var paginas=0;
		window.onload = function() {
		 	//boton enviar mensajes  - activar listener
            document.getElementById('logoff').addEventListener('click', desconectaUsuario);
            document.getElementById('enviar').addEventListener('click', altaMensaje);

            cargarMensajes();

        }   

        function altaMensaje() {
            //alert ('altaMensaje');
            //alert (idpersona);
            //alert (tipoUsuario);
            
            ///comprobar foto
            var archivo = document.getElementById('foto').files;
            //alert (archivo.length);
            if (archivo.length!=0) {
                var datos = new FormData()
            
                 datos.append('archivo', archivo[0])
                
                datos.append('dato','ejemplo subir archivo')
                fetch('php/recibir_archivos.php', {
                    method: 'POST',
                    body: datos
                })
                .then(function(respuesta) {
                    if (respuesta.ok) {
                        return respuesta.json()
                    } else {
                        console.log(respuesta)
                    }
                })
                .then(function(datos) {
                    //alert(datos)
                    console.log(datos)

                    if (datos.codigo=='78' ) {
                        alert (`error: ${datos.codigo} - ${datos.mensaje}`)
                        document.getElementById('formu').reset(); 
                        return
                    }  
                    //alert (datos.mensaje)
                    nombreFoto=datos.name
                    registroMensaje(nombreFoto)

                })
                .catch(function(error) {
                    console.log(error)
                    alert(error)
                })           
            } else {
                nombreFoto=""
                registroMensaje(nombreFoto)
            }
        }   

        function registroMensaje(nombreFoto) {
            //alert ('registroMensaje')
            texto=document.getElementById('texto').value;
            if (texto.trim() == '') {
                alert('texto obligatorio');
                return
            }
            var datos = new FormData();
            datos.append('opcion', 'A');
            datos.append('idpersona', idpersona);
            datos.append('texto', texto);
            datos.append('foto', nombreFoto);
            
            fetch ('php/controlador_mensajes.php', {
                method: 'POST',
                body: datos
            })
            .then(function(respuesta) {
                if (respuesta.ok) {
                    //cambiar el json a text, si queremos ver el error
                    return respuesta.json();
                } else {
                    throw "error en la petición AJAX",88;
                }
            })
            .then(function(datos) {
                console.log(datos);
                //alert(datos);

                codigo=datos.codigo;
                mensaje=datos.mensaje;
                
                //datos es un array js
                if (codigo!='00') {
                    throw mensaje, codigo;
                } else {
                    alert (`creado nuevos mensajes - ${codigo} - ${mensaje} - id: ${datos.ultimoId}`);
                }
                document.getElementById('texto').value = "";
               document.getElementById('formu').reset(); 
                cargarMensajes();
            })
            .catch(function (error) {
                alert (error);
                if (error!='00') {
                    alert (error);
                }   
            })    



    }
            
        function desconectaUsuario () {
        	//alert ('desconectaUsuario');
        	 var datos = new FormData();
             datos.append('opcion','logoff');           
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

                if (datos.codigo != "00")  {
                	error = `error: ${datos.codigo} << ${datos.mensaje} >>`;
                	throw "mensaje error", error;
                	//alert (`error: ${datos.codigo} - ${datos.mensaje}`);
                } else {
                	window.location.href = 'examen_login.php';
                }      
             }) 
             .catch(function(error) {
                alert (error);
            }) 
        } 

        //carga los mensajes recibidos por usuario logado	
        function cargarMensajes() {
        	//alert ('cargarMensajes');
        	var datos = new FormData();
			datos.append('opcion','C');
            datos.append('pagina', pagina);
			fetch('php/controlador_mensajes.php', {
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

                if (datos.codigo!='00')  {
                	throw datos.mensaje, datos.codigo;
                	return
                }
                paginas=datos.paginas;
                obtenerUsuarioConectado(datos.lista);
                
             }) 
             .catch(function(error) {
                alert (error);
            }) 

        }

        function obtenerUsuarioConectado(mensajes) {
            //alert ('obtenerUsuarioConectado');

            var datos = new FormData();
            datos.append('opcion','consulta');
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

                if (datos.codigo!='00')  {
                    throw datos.mensaje, datos.codigo;
                    return
                } 
                document.getElementById('usuario').innerHTML="";
                    lista=datos.lista;
                    //alert (lista);

                    linea="";

                    for (i in lista) {
                        autor=lista[0].nombre + ' ' + lista[0].apellidos;
                        linea+="<p class='user'>";
                        linea+='Usuario conectado: ' + autor;
                        linea+="</p>";
                        
                    }
                    document.getElementById('usuario').innerHTML=linea; 
                    tipoUsuario=lista[0].tipousuario;
                    idpersona=lista[0].idpersona;
                    mostrarMensajes(mensajes);

             }) 
             .catch(function(error) {
                alert (error);
            }) 

        }

        //Montar la salida a pantalla de los mensajes 
        function mostrarMensajes(mensajes) {
        	//alert ('mostrarMensajes');
        	//alert (mensajes);
        	console.log(mensajes);

            document.getElementById('mensajes').innerHTML="";
            linea="";
            for (i in mensajes) {
                autor=mensajes[i]['nombre'] + ' ' + mensajes[i]['apellidos'];
                fecha=mensajes[i]['fecha'];
                idcomentario=mensajes[i]['idcomentario'];
                comentario=mensajes[i]['comentario'];
                foto=mensajes[i]['foto'];

                linea+="<p class='linea'>";
                linea+=fecha + ' por ' + autor;
                linea+="</p>";
                linea+="<p class='linea'>";
                linea+=comentario;
                linea+=`</p>`;
               
                if (foto!=null && foto!='') {
                    linea+=`<div class='divfoto'>`;
                    linea+=`<p class='linea'><img src=img/${foto}></a></p>`;
                    linea+=`</div>`;
                }   
                linea+=`<hr>`;

            }
            document.getElementById('mensajes').innerHTML=linea;
            mostrarPaginas();
        }	

         //montar los listener 
        function mostrarPaginas() {
            var enlaces = '';
            for (i=1; i <= paginas; i++) {
                if (i==pagina) {
                    enlaces+= "<span style='font-weight:bold; font-size:large;'>" + i + "</span>&nbsp&nbsp&nbsp ";
                } else {
                    enlaces+= "<span> " + i + "</span>&nbsp&nbsp&nbsp ";
                }
                
            }
            document.getElementById('paginas').innerHTML = enlaces;
            //activar los listener para la paginación (id + span)
            var span=document.querySelectorAll('#paginas span');

            for (i=0; i<span.length; i++) {
                span[i].addEventListener('click', function() {
                    //recuperar el número de página 
                    pagina=this.innerText;
                    //invoca a la función para la carga de los mensajes
                    cargarMensajes();
                })
            }
        } 


	</script>
</head>
<body>
	<div class='wraper'> 
		<h2>MENSAJES FORO</h2>
		<span id='usuario'></span><br>
		<form   id="formu" enctype="multipart/form-data">
			<textarea style="width:300px; height:50px" id="texto"></textarea><br>
			<label>Subir foto: </label><input type="file" id="foto" /><br><br>
			<input type="button" id="enviar" value="Comenta" >
			<input type="button" id="logoff" value='logoff'>
		</form><br><br>
		<span id='mensajes'></span><br><br>
        <center id='paginas'></center><br>
	</div><br>
</body>
</html>