<?php

class listlayoutModel{
    
    function readDirectoryLayout(){
        
        
        $files = scandir($GLOBALS['directory_layout']);
        $xml=array();
        
        foreach ($files as $file){
            $path_parts = pathinfo($file);
            if($path_parts['extension']=='xml'){
                $f=new StdClass();
                $f->name=$this->getNameLayout($GLOBALS['directory_layout']."/".$file);
                $f->file=$file;
                array_push($xml,$f);

            }
        }

        if($_SESSION['su']){
            $files = scandir($GLOBALS['directory_layout']."/system");
            foreach ($files as $file){
                $path_parts = pathinfo($file);
                if($path_parts['extension']=='xml'){
                    $f=new StdClass();
                    $f->name=$this->getNameLayout($GLOBALS['directory_layout']."/system/".$file);
                    $f->file="system/".$file;
                    array_push($xml,$f);

                }
            }
        }
        
        return $xml;
        
    }


    function getNameLayout($file){
        $myfile = fopen($file, "r") or die("Unable to open file!");
        $content= fread($myfile,filesize($file));
        fclose($myfile);
        $xml = simplexml_load_string($content);
        $layoutname=$xml[0]['name'];
        return $layoutname;
    }

	function runLiteScore2(){
		echo "Run";
		echo $GLOBALS['bin'];
		exec($GLOBALS['bin'],$out);
		var_dump($out);
	}
    
     function uploadFile(){
        
        $target_dir = $GLOBALS['directory_layout'];
        $target_file = $target_dir ."/". basename($_FILES["fileToUpload"]["name"]);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $GLOBALS['message']= "<div class='alert alert-success'>Upload complete</div>";
        } else {
            $GLOBALS['message']= "<div class='alert alert-error'>Error during upload</div>";
        }
        
    }
    
    function saveLayout(){
        
        $path=$GLOBALS['directory_layout']."/".$_REQUEST['file'];
      $txt=$_REQUEST['file_layout'];
        
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        
        
        $GLOBALS['message']= "<div class='alert alert-success'>File saved</div>";
        
    }
    
    function deleteFile(){
        
        $path=$GLOBALS['directory_layout']."/".$_REQUEST['file'];
        unlink($path);
        
        $GLOBALS['message']= "<div class='alert alert-success'>File deleted</div>";
        
    }
    
    
    
}

?>
