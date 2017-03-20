<?php
# Se utilizarán los métodos de la clase Rest creada anteriormente
require_once("Rest.php");

class Api extends Rest{
	# valores fijos del servicio
	const servidor = "localhost";
	const usuario_db = "root";
	#const port = "5432";
	const pwd_db = "liliana10";
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

	//metodo que se conecta con la base de datos
	private function conectarDB(){
		$dsn = 'mysql:dbname='.self::nombre_db.' ;host='.self::servidor;
		try {
			#metodo abstracto para la conexion a la bd
			$this->_conn = new PDO($dsn, self::usuario_db, self::pwd_db);
		 	
		 } catch (PDOException $e) {
		 	echo "Falló la conexión: ".$e->getMessage();
		 } 
	}

	//metodo que devuelve un error y estado
	private function devolverError($id){
		//array de errores
		$errores = array(
			array('estado' => "error", "msg" => "Petición no encontrada"),
			array('estado' => "error", "msg" => "Petición no aceptada"),
			array('estado' => "error", "msg" => "Petición sin contenido"),
			array('estado' => "error", "msg" => "Email o password incorrectos"),
			array('estado' => "error", "msg" => "Error borrando usuarios"),
			array('estado' => "error", "msg" => "Error actualizando nombre de usuario"),
			array('estado' => "error", "msg" => "error buscando usuario por email"),
			array('estado' => "error", "msg" => "Error creando usuario"),
			array('estado' => "error", "msg" => "Usuario ya existe")
			);
		return $errores[$id];
	}
}

?>