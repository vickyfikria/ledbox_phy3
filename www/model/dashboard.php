<?php

class dashboardModel{
    
    
	function testService(){

		exec("pgrep -f 'python ledbox.py'",$pids);
		if($pids[1])
			return true;
		else
			return false;
	 

	}

	function getTemperature(){
		$output=shell_exec("vcgencmd measure_temp");
		$o=explode("=",$output);
		return str_replace("'C","",$o[1]);
	}

	function getOutput(){
		$path=$GLOBALS['output_ledbox'];
		$myfile = fopen($path, "r");
		$value= fread($myfile,filesize($path));
		fclose($myfile);

		return $value;
	}


	function getCurrentUsers(){
		$path=$GLOBALS['output_current_users'];
		$myfile = fopen($path, "r");
		$value= fread($myfile,filesize($path));
		fclose($myfile);

		return $value;
	}

	function restartDHCP(){
		exec($GLOBALS['bin_dhcp']);
		
	}

	function runService(){
		exec($GLOBALS['bin_startservice']);
		
	}
    
     function closeService(){
        exec($GLOBALS['bin_stopservice']);
        
	}
	
	function runLED(){
		exec($GLOBALS['bin_startled']);
		
	}
    
     function closeLED(){
        exec($GLOBALS['bin_stopled']);
        
    }

    function restartLED(){
    	exec($GLOBALS['bin_startled']);
    	exec($GLOBALS['bin_stopled']);
    }
	
	function closeServiceTest(){
        exec($GLOBALS['bin_stopservice']);
        
	}
	

	function reboot(){
		exec($GLOBALS['bin_reboot']);
		header("location:index.php");
	}

	function disconnect(){
		
		$_SESSION['login']=false;
		header("location:index.php");
		return True;
	}

    function checkUpdate(){
		
		$update=file_get_contents($GLOBALS['update_url'].$GLOBALS['update_filename_url']);
		
		$json=json_decode($update);
		$json->isupdate=false;
		if($GLOBALS['version']<$json->version)
			$json->isupdate=true;
		

		return $json;

	}
    
    
}

?>
