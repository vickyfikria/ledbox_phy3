<?php

class remotesModel{
    
    function readDirectoryRemotes(){
        
        
        $directories = scandir($GLOBALS['directory_remote']);
        $html=array();
        
        foreach ($directories as $directory){
            $path_parts = pathinfo($directory);
            if($directory!="." && $directory!=".."){
                if($path_parts['extension']=='')
                    array_push($html,$directory);
            }
        }
        
        return $html;
        
    }
    
    function uploadFile(){
        
        $target_dir = $GLOBALS['directory_remote'];
        $target_file = $target_dir ."/". basename($_FILES["fileToUpload"]["name"]);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $GLOBALS['message']= "<div class='alert alert-success'>File deleted!</div>";
        } else {
            $GLOBALS['message']= "<div class='alert alert-error'>Errore durante l'upload del file</div>";
        }
        
    }
    
    
    
    function deleteFile(){
        
        $path=$GLOBALS['directory_remote']."/".$_REQUEST['file'];
        $this->delete_directory($path);
        unlink($path.".zip");
        $GLOBALS['message']= "<div class='alert alert-success'>File eliminato correttamente</div>";
        
    }


    function delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
         if (!$dir_handle)
              return false;
         while($file = readdir($dir_handle)) {
               if ($file != "." && $file != "..") {
                    if (!is_dir($dirname."/".$file))
                         unlink($dirname."/".$file);
                    else
                         $this->delete_directory($dirname.'/'.$file);
               }
         }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}

    
    
    function saveRemote(){
        
        $path=$GLOBALS['directory_remote']."/".$_REQUEST['file'];
        $txt=$_REQUEST['file_remote'];
        
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        
        
        $GLOBALS['message']= "<div class='alert alert-success'>File salvato correttamente</div>";
        
    }
    
    
    
}

?>