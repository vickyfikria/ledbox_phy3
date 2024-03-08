<?php

require 'dashboard.php';


class settingModel{
    
    
	function getConfig(){
	
		$config=parse_ini_file($GLOBALS['file_config'],true);
		return $config;
	}

	function getUserConfig(){
	
		$config=parse_ini_file($GLOBALS['file_user_config'],true);
		return $config;
	}

	function getCoverImage(){
		$path="../bin/banner.jpg";
		$type=pathinfo($path,PATHINFO_EXTENSION);
		$data=file_get_contents($path);
		$base64="data:image/".$type.';base64,'.base64_encode($data);
		return $base64;
	}


	function getNetworkConfig(){
		$path="/etc/network/interfaces";
		$file=fopen($path,"r");
		$config=fread($file,filesize($path));
		fclose($file);
		
		return $config;
	}

	function scanNetwork(){
		exec($GLOBALS['bin_scanwifi'],$result);
		sleep(5);
		return $result;
	}

	function saveConfig(){
		$fields=$_POST['field'];
		$user_fields=$_POST['user_field'];

		
		$image_cover=$_FILES['file_cover'];
		echo $image_cover;
		if($image_cover!=""){
			  $check = getimagesize($_FILES["file_cover"]["tmp_name"]);
			  if($check !== false) {
			    //l'immagine è corretta	
			  	$target_file = "../bin/banner.jpg";
			  	if (move_uploaded_file($_FILES["file_cover"]["tmp_name"], $target_file)) {
			  		copy($target_file,"../media/banner.jpg");
			  	}
  
	
			  } 
		}

		
		if($fields){
			$ini=$this->build_ini_string($fields);
			$inifile = fopen($GLOBALS['file_config'], "w");
			fwrite($inifile, $ini);
			fclose($inifile);
		}

		if($user_fields){
			$ini=$this->build_ini_string($user_fields);
			$inifile = fopen($GLOBALS['file_user_config'], "w");
			fwrite($inifile, $ini);
			fclose($inifile);
		}


		//riavvia il servizio LED
		$model_dashboard=new dashboardModel();
		$model_dashboard->restartLED();
		

		$GLOBALS['message']= "<div class='alert alert-success'>Configurazione salvate correttamente. Riavviare il servizio per renderle effettive.</div>";
	   
		


	}




	function build_ini_string(array $a) {
		$ini="";

		foreach($a as $key=>$field){
			$ini.="[".$key."]\n";
			foreach($field as $k=>$v){
				$ini.=$k."=".$v."\n";
			}
			$ini.="\n";
		}

		return $ini;
	}
    
    
}

?>
