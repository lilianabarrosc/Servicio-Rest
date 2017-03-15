<?php
//clase Rest, se encarga de:
//-Devolver cabeceras y resultados
//-Filtrar/procesar los Datos

class Rest{
	//tipo de dato que se aceptaran
	public $tipoDato = "application/json";
	//Array con los datos solicitados
	public $datosPeticion = arra();
	//Estado por defecto de respuesta Ok HTTP
	private $_codEstado = 200;

	//metodo principal para tratar los datos de entrada
	public function constructor(){
		$this->tratarEntrada();
	}

	//metodo encargado de mostrar la respuesta al usuario
	//recibe como entrada los parametros $data, que contiene la respuesta JSON a enviar
	// y  $estado que especifica el código de estado HTTP
	public function respuesta($data, $estado){
		$this->_codEstado = ($estado)? $estado:200; //si estado esta vacio toma el valor por defecto = 200
		//se obtiene la cabecera HTTP
		$this->setCabecera();
		echo $data;
		exit;
	}

	//metodo encargado de crear dos cabeceras de respuesta dependiendo del estado
	public function setCabecera(){
		//ejemplo = HTTP/1.1 201 Created
		header("HTTP/1.1" . $this->_codEstado . $this->getCodEstado());
		header("Content-Type:" . $this->tipoDato . ';charset=utf-8');
		//Content-Type: application/x-www-form-urlencoded ??
		//Accept: application/x-www-form-urlencoded;application/json;charset=UTF-8;q=0.5
	}

	//Lista con los codigos HTTP con su respectiva descripcion
	private function getCodEstado(){
		$estado = array(
			//Success
			200 => 'OK', 
			201 => 'Created',
			202 => 'Accepted',
			202 => 'Non-Authoritative Information',
			204 => 'No Content',
			//Redirection
			301 => 'Moved Permanently',
			302 => 'Found',
			303 => 'See Other',
			304 => 'Not Modified',
			//Client Error
			400 => 'Bad Request',
			401 => 'Unauthorized',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			408 => 'Request Timeout',
			409 => 'Conflict',
			429 => 'Too Many Requests',
			//Server Error
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported'
			);

		$resp = ($estado[$this->_codEstado])? $estado[$this->_codEstado]:$estado[500]; //por defecto se envia el estado 500
		return $resp;
	}
}

?>