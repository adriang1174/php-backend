<?php

include('ip2locationlite.class.php');

class NotIPLocationException extends Exception{}
class Ip_Location{

	public static function getLocation($ip){

		$errors = array();
		$respuesta = new Ftl_Response();
		$respuesta->state = 0;

		if ($_SERVER['REMOTE_ADDR'] !== "127.0.0.1")
		{
			$ipLite = new ip2location_lite;
			$ipLite->setKey(IP_KEY);
			 
			//Get errors and locations
			$locations = $ipLite->getCity($ip);
			$errors = $ipLite->getError();

			if (!empty($locations) && is_array($locations)) {

				if ($locations["statusCode"] != "ERROR"){
					$respuesta->state = 1;
					$respuesta->data = $locations;
				}else{
					$respuesta->state = -2;
					$respuesta->error = $locations;
				}

			}		

			if (!empty($errors) && is_array($errors)) {
					$respuesta->state = -2;
					$respuesta->error = $errors;
			}		

		}
		else
		{
			$respuesta->state = 1;
			$respuesta->data = array(
				"countryCode" => "AR",
				"countryName" => "ARGENTINA",
				"cityName" => "BUENOS AIRES"
			); 	
		}
			


		return $respuesta;



	}
	
} 