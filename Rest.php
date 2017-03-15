<?php
#clase Rest, se encarga de:
#-Devolver cabeceras y resultados
#-Filtrar/procesar los Datos

class Rest{
	#tipo de dato que se aceptaran
	public $tipoDato = "application/json";
	#Array con los datos solicitados
	public $datosPeticion = array();
	#Estado por defecto de respuesta Ok HTTP
	private $_codEstado = 200;

	#metodo principal para tratar los datos de entrada
	public function constructor(){
		$this->tratarEntrada();
	}

	#metodo encargado de mostrar la respuesta al usuario
	#recibe como entrada los parametros $data, que contiene la respuesta JSON a enviar
	# y  $estado que especifica el código de estado HTTP
	public function respuesta($data, $estado){
		$this->_codEstado = ($estado)? $estado:200; #si estado esta vacio toma el valor por defecto = 200
		#se obtiene la cabecera HTTP
		$this->setCabecera();
		echo $data;
		exit;
	}

	#metodo encargado de crear dos cabeceras de respuesta dependiendo del estado
	public function setCabecera(){
		#ejemplo = HTTP/1.1 201 Created
		header("HTTP/1.1" . $this->_codEstado . $this->getCodEstado());
		header("Content-Type:" . $this->tipoDato . ';charset=utf-8');
		#Content-Type: application/x-www-form-urlencoded ??
		#Accept: application/x-www-form-urlencoded;application/json;charset=UTF-8;q=0.5
	}

	#Lista con los codigos HTTP con su respectiva descripcion
	private function getCodEstado(){
		$estado = array(
			#Success
			200 => 'OK', 
			201 => 'Created',
			202 => 'Accepted',
			202 => 'Non-Authoritative Information',
			204 => 'No Content',
			#Redirection
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			#Client Error
			400 => 'Bad Request',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			408 => 'Request Timeout',
			409 => 'Conflict',
			429 => 'Too Many Requests',
			# Server Error
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
			);

		$resp = ($estado[$this->_codEstado])? $estado[$this->_codEstado]:$estado[500]; //por defecto se envia el estado 500
		return $resp;
	}

	# Metodo que se encarga de procesar el array de entrada y devuelve los datos solicitados segun get o post  
	private function tratarEntrada(){
		$metodo = $_SERVER['REQUEST_METHOD'];

		# se trataran tres casos post, get, delete y put (update se tratara despues)
		switch ($metodo) {
			case "GET":
				$this->datosPeticion = $this->limpiarEntrada($_GET); 
				break;	
			case "POST":
				$this->datosPeticion = $this->limpiarEntrada($_POST); 
				break;
			case "DELETE": //"falling though". Se ejecutará el case siguiente  
			case "PUT":
				//php no tiene un método propiamente dicho para leer una petición PUT o DELETE por lo que se usa un "truco":  
		        //leer el stream de entrada file_get_contents("php://input") que transfiere un fichero a una cadena.  
		        //Con ello obtenemos una cadena de pares clave valor de variables (variable1=dato1&variable2=data2...)
		        //que evidentemente tendremos que transformarla a un array asociativo.  
		        //Con parse_str meteremos la cadena en un array donde cada par de elementos es un componente del array.  
				parse_str(file_get_contents("php://input"), $this->datosPeticion);  
         		$this->datosPeticion = $this->limpiarEntrada($this->datosPeticion); 
				break;
			default:
				$this->response('',404);
				break;
		}
	}

	//metodo encargado de parsear los datos que se pasen por prámetro
	public function limpiarEntrada($data){
		$entrada = array(); //datos de la entrada

		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$entrada[$key] = $this->limpiarEntrada($value);
			}
		} else{
			if (get_magic_quotes_gpc()) {
				//Quitamos las barras de un string con comillas escapadas  
		        //Aunque actualmente se desaconseja su uso, muchos servidores tienen activada la extensión magic_quotes_gpc.   
		        //Cuando esta extensión está activada, PHP añade automáticamente caracteres de escape (\) delante de las comillas que se escriban en un campo de formulario.   
		        $data = trim(stripslashes($data));
			}

			#eliminamos etiquetas html y php
			$data = strip_tags($data);
			#se convierten los caracteres a entidades HTML
			$data = htmlentities($data);
			$entrada = trim($data);
		}
		return $entrada;
	}
}

?>