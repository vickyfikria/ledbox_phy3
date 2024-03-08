<?php

class wsModel{
	
	function get(){
		
		$task=$_REQUEST['param_task'];
		
		
		//carica il file model
		require "model/".$_REQUEST['param_model'].".php";
		$modelname=$_REQUEST['param_model']."Model";
		
		$model=new $modelname();
		
		
		if($_REQUEST['param_task'])
		    $data=$model->$task();
		
		$json="";
		$json->value=$data;
		
		header('Content-type: application/json');
		echo json_encode( $json );
	}

}

?>

