<?php

class listpluginModel{
    
    function readPlugins(){
        
        
        $files = scandir($GLOBALS['directory_plugin']);
        $xml=array();
        
        foreach ($files as $file){
            $path_parts = pathinfo($file);
            
            if($path_parts['extension']=='ini'){

                $f=new StdClass();
                $f->name=$this->getNamePLugin($GLOBALS['directory_plugin']."/".$file);
                $f->file=$file;
                array_push($xml,$f);

            }
        }
        
        return $xml;
        
    }


    function getNamePLugin($file){
        
        // Parse without sections
        $ini_array = parse_ini_file($file,true) or die("Unable to open file!");
        $pluginname=$ini_array['GENERAL']['name'];
        return $pluginname;
    }

	
    
     function uploadFile(){
        
        $target_dir = $GLOBALS['directory_plugin'];
        $target_file = $target_dir ."/". basename($_FILES["fileToUpload"]["name"]);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $GLOBALS['message']= "<div class='alert alert-success'>Upload complete</div>";
        } else {
            $GLOBALS['message']= "<div class='alert alert-error'>Error during upload</div>";
        }
        
    }
    
    function savePlugin(){
        
        $path=$GLOBALS['directory_plugin']."/".$_REQUEST['file'];
      $txt=$_REQUEST['file_layout'];
        
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        
        
        $GLOBALS['message']= "<div class='alert alert-success'>File saved</div>";
        
    }
    
    function deletePlugin(){
        
        $path=$GLOBALS['directory_plugin']."/".$_REQUEST['file'];
        unlink($path);
        
        $GLOBALS['message']= "<div class='alert alert-success'>File deleted</div>";
        
    }
    
    
    
}

?>
