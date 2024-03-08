<?php

class editlayoutModel{
    

    function readFile($file){

            $path=$GLOBALS['directory_layout']."/".$file;

            $myfile = fopen($path, "r") or die("Unable to open file!");
            $value= fread($myfile,filesize($path));
            fclose($myfile);

            return $value;

        }
    
    function newLayout(){
        
        $layoutname=$_REQUEST['layoutname'];
        //create file
        $file=$this->slugify($layoutname).".xml";
        $path=$GLOBALS['directory_layout']."/".$file;
        $txt="<?xml version='1.0'?><layout name='".$layoutname."'></layout>";
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        header("location:index.php?view=editlayout&file=".$file);

    }

    function saveLayout(){
        
        $path=$GLOBALS['directory_layout']."/".$_REQUEST['file'];
        $txt=$_REQUEST['file_layout'];
        
        $myfile = fopen($path, "w") or die("Unable to open file!");
        fwrite($myfile, $txt);
        fclose($myfile);
        
        
        $GLOBALS['message']= "<div class='alert alert-success'>File salvato correttamente</div>";
        header("location:index.php?view=listlayout");
        
        
        
    }
    
    function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}