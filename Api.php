<?php
# Se utilizarán los métodos de la clase Rest creada anteriormente
require_once("Rest.php");

class Api extends Rest{
	# valores fijos del servicio
	const servidor = "localhost";
	const usuario_db = "postgress";
	const port = "5432";
	const pwd_db = "";
	const nombre_db = "autorizacion";
	#variables globales
	private $_conn = NULL;
	private $_metodo;
	private $argumentos;

	//metodo principal que hace llamado a la funcionalidad padre y conecta a la bd
	public function constructor(){
		parent::constructor();
		$this->conectarDB();
	}

	private function conectarDB(){
		$dsn = "host=".servidor." port=".port." user=".usuario_db." pass=".pwd_db." dbname=".nombre_db;
    	try{
    		$this->_conn = pg_connect(
    	}
    $connect = pg_connect("host=$host, port=$port, user=$user, 
pass=$pass, dbname=$dbname");

    if(!$connect)
        echo "<p><i>No me conecte</i></p>";
    else
        echo "<p><i>Me conecte</i></p>";

    pg_close($connect);
	}
}

?>