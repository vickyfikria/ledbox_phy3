<?php

class galleryModel{
    
    
    function scanDir($path_directory,&$tree){
        $dirs = scandir($path_directory);
        foreach ($dirs as $dir) {
            if($dir=="." || $dir==".." || substr($dir,0,1)==".")
                continue;
           
            $path=$path_directory."/".$dir;
            
            $node=new stdClass();
            $node->name=$dir;
            $node->text=$dir;
            $node->nodes=[];
            

            if(is_dir($path)){
                
                $node->type="directory";
                $node->nodes=$this->scanDir($path,$node->nodes);
                $node->icon="far fa-folder";
                $node->size=$this->calculateSize($node->nodes);
            }
            if(count($node->nodes)==0)
                unset($node->nodes);
            
            if(is_file($path)){
                if($path_directory==$GLOBALS['directory_gallery']) //escludi tutti i file di sistema
                    continue;
                
                    $node->type="file";
                    $node->icon="far fa-file";
                    $node->size=filesize($path);
            }
            $node->path=$path;
            $node->text.=" (".$this->renderSize($node->size).")";
            
            array_push($tree,$node);
        }

        return $tree;

    }


    function renderSize($size){
        if($size<=1000){
            return round($size)." bytes";
        }
        if($size>1000 && $size<1000000){
            return round($size/1000,2)." kB";
        }

        if($size>=1000000){
            return round($size/1000000,2)." MB";
        }




    }
    function calculateSize($nodes){
        $total=0;
        foreach($nodes as $node){
            if(isset($node->size)){
                $total+=$node->size;
            }    
        }
        return $total;
    }

    
    function readDirectoryGallery(){
            $tree=[];
            $tree= $this->scanDir($GLOBALS['directory_gallery'],$tree);
           
            //escludi file di sistema
            $json=json_encode($tree);
            
            return $json;
    }
        
        
        
    
    
    function uploadFile(){
        
        $target_dir = $GLOBALS['directory_gallery'];
        $target_file = $target_dir ."/". basename($_FILES["fileToUpload"]["name"]);
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $GLOBALS['message']= "<div class='alert alert-success'>File caricato correttamente</div>";
        } else {
            $GLOBALS['message']= "<div class='alert alert-error'>Errore durante l'upload del file</div>";
        }
        
    }
    
    function delete(){
        $path=$_REQUEST['path'];
        $type=$_REQUEST['type'];

        if($type=="file"){
            unlink($path);
        }else{
            $this->delete_directory($path);
        }


        header("location:index.php?view=gallery");
    }
    
    function delete_directory( $dir )
    {
       
        if( is_dir( $dir ) )
        {
            $files = glob( $dir . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned
        
            foreach( $files as $file )
            {
                
                $this->delete_directory( $file );      
            }
        
            rmdir( $dir );
        } 
        elseif( is_file( $dir ) ) 
        {
            unlink( $dir );  
        }
    }
    
    
    
    
    
}

?>