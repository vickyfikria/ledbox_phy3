<?php
session_start();
require "application.php";


function getLoginPage(){
  echo $GLOBALS['template_login'];
  exit();
}
 

 
  if(!isset($_SESSION['login']) || $_SESSION['login']==false)
  {
    $_SESSION['login']=false;
    
    if($_REQUEST['username']==$GLOBALS['username'] && $_REQUEST['password']==$GLOBALS['password']){
      $_SESSION['login']=true;
      $_SESSION['su']=false;
      
    }

    if($_REQUEST['username']==$GLOBALS['su_username'] && $_REQUEST['password']==$GLOBALS['su_password']){
      $_SESSION['login']=true;
      $_SESSION['su']=true;
    }
    

    if($_SESSION['login']==false){
      getLoginPage();
    }

    /*

    if( !isset( $_SERVER['PHP_AUTH_USER'] ) || !isset( $_SERVER['PHP_AUTH_PW'] ) )
    {
      header("HTTP/1.0 401 Unauthorized");
      header("WWW-authenticate: Basic realm=\"LEDbox Administration\"");
      header("Content-type: text/html");
      // Print HTML that a password is required
      header("location:template/login.html");
      exit;
    }
    else
    {
      // Validate the $_SERVER['PHP_AUTH_USER'] & $_SERVER['PHP_AUTH_PW']
      if( ($_SERVER['PHP_AUTH_USER']!=$GLOBALS['username'] || $_SERVER['PHP_AUTH_PW']!=$GLOBALS['password']) &&  ($_SERVER['PHP_AUTH_USER']!=$GLOBALS['su_username'] || $_SERVER['PHP_AUTH_PW']!=$GLOBALS['su_password']))
      {
        // Invalid: 401 Error & Exit
        header("HTTP/1.0 401 Unauthorized");
        header("WWW-authenticate: Basic realm=\"LEDbox Administration\"");
        header("Content-type: text/html");
        header("location:template/login.html");
        // Print HTML that a username or password is not valid
        exit;
      }
      else
      {
        // Valid
        $_SESSION['login']=true;
        $_SESSION['su']=false;
        if($_SERVER['PHP_AUTH_USER']==$GLOBALS['su_username'] && $_SERVER['PHP_AUTH_PW']==$GLOBALS['su_password'])
          $_SESSION['su']=true;

      }
    }*/

  }



if(!$_REQUEST['view'] && !$_REQUEST['model']){
	$_REQUEST['view']="dashboard";

}

if($_REQUEST['view']){
	$file_view="view/".$_REQUEST['view'].".php";
	//read all querystring
	if(!file_exists($file_view)){
	    $_REQUEST['view']="remotes";
	    $file_view="view/remotes.php";
	}
	require "model/".$_REQUEST['view'].".php";
	$modelname=$_REQUEST['view']."Model";

}
if($_REQUEST['model']){
	require "model/".$_REQUEST['model'].".php";
	$modelname=$_REQUEST['model']."Model";
}






$model=new $modelname();

$task=$_REQUEST['task'];


if($_REQUEST['task']){
    $model->$task();
}



if($_REQUEST['view']){

	ob_start ();
	include $file_view;
	$html=ob_get_contents();
	ob_end_clean();
	render($html,$_REQUEST['view']);

}

