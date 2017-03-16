<?php
# Se utilizarán los métodos de la clase Rest creada anteriormente
require_once("Rest.php");

class Api extends Rest{
	# valores fijos del servicio
	const servidor = "localhost";
	const usuario_db = "postgress";
	const pwd_db = "";
	const nombre_db = "autorizacion";
	#variables globales
	private $_conn = NULL;
	private $_metodo;
	private $argumentos;

}

?>