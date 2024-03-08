<?php

class editpluginModel{
    

    function readFile($file){

            $path=$GLOBALS['directory_plugin']."/".$file;

            $value = parse_ini_file($path,true) or die("Unable to open file!");


            return $value;

        }
    
   

    function savePlugin(){
        
        $path=$GLOBALS['directory_plugin']."/".$_REQUEST['file'];
        $fields=$_POST['field'];
        

        if($fields){

            $ini=$this->build_ini_string($fields);
            
            $inifile = fopen($path, "w");

            fwrite($inifile, $ini);
            fclose($inifile);
        }

        
        
        $GLOBALS['message']= "<div class='alert alert-success'>File salvato correttamente</div>";
        header("location:index.php?view=listplugin");
        
        
        
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