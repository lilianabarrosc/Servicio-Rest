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

	//metodo encargado de procesar la URL de la peticion y llamar al metodo que lo resolvera
	public function procesarLlamada(){
		if(isset($_REQUEST['url'])){ //si trae un respuesta !null
			//si por ejemplo pasamos explode('/','////controller///method////args///') el resultado es un array con elem vacios;
	        //Array ( [0] => [1] => [2] => [3] => [4] => controller [5] => [6] => [7] => method [8] => [9] => [10] => [11] => args [12] => [13] => [14] => )
	        $url = explode('/', trim($_REQUEST['url']));
	        //con array_filter() filtramos elementos de un array pasando función callback, que es opcional.
	        //si no le pasamos función callback, los elementos false o vacios del array serán borrados 
 	        //por lo tanto entre la anterior función (explode) y esta eliminamos los '/' sobrantes de la URL -> devuelve indice/valor en un array
	        $url = array_filter($url); 
	        $this->_metodo = strtolower(array_shift($url)); //transforma a minusculas y se quita el primer elemento del array
       		$this->_argumentos = $url;  
       		$func = $this->_metodo;
 
       		if((int) method_exists($this, $func) > 0){
       			//Si la URL tiene argumentos ej borrarUsuario/1 (1->argumento)
       			if (count($this->_argumentos) > 0) {
       				call_user_func_array(array($this, $this->_metodo), $this->_argumentos);
       			} else {//si no, lo llamamos sin argumentos, al metodo del controlador ej http://localhost/login_rest/usuarios 
           			call_user_func(array($this, $this->_metodo));  
         		}  
       		} else // se envia el error en formato json
       			$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
		}
		//se envia el error en formato json
		$this->mostrarRespuesta($this->convertirJson($this->devolverError(0)), 404);
	}

	//metodo que devuelve un json
	private function convertirJson($data){
		return json_encode($data);
	}

	//metodo encargado de procesar la peticion y devuelve un json con los datos de los usuarios
	public function usuarios(){
		if ($_SERVER['REQUEST_METHOD'] != "GET") {
			# no se envian los usuarios, se envia un error
			$this->mostrarRespuesta($this->convertirJson($this->devolverError(1)), 405);
		}//es una petición GET
		//se realiza la consulta a la bd
		$query = $this->_conn->query("SELECT id, nombre, email FROM usuario");
		//cantidad de usuarios
		$filas = $query->fetchAll(PDO::FETCH_ASSOC);  
     	$num = count($filas);
     	//si devolvio un resultado, se envia al cliente
     	if ($num > 0) {  
	       $respuesta['estado'] = 'correcto';  
	       $respuesta['usuarios'] = $filas;  
	       $this->mostrarRespuesta($this->convertirJson($respuesta), 200);  
	     } //se envia un error  
	     $this->mostrarRespuesta($this->devolverError(2), 204);
	}
}

?>