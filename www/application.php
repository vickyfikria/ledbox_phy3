<?php

$su_username='su';
$su_password='F0qDTcXwMnH8nzn3';

$file_config        = '../setting.ini';
$file_user_config        = '../user_setting.ini';
$file_manifest        = '../manifest.xml';
$directory_base     = '../ledbox';
$directory_layout    = '../layout';
$directory_plugin    = '../plugin';
$directory_gallery    = '../media';
$directory_remote    = 'remote';
$extension_gallery=array("jpg","gif","png","jpeg","bmp","mp4");
$output_ledbox ='../log.txt';
$output_current_users ='../current_users.txt';
$bin_startservice='../bin/startledbox > /dev/null 2>/dev/null &';
$bin_stopservice='../bin/stopledbox > /dev/null 2>/dev/null &';
$bin_startled='../bin/startled > /dev/null 2>/dev/null &';
$bin_stopled='../bin/stopled > /dev/null 2>/dev/null &';

$bin_reboot='sh ../bin/reboot.sh > /dev/null 2>/dev/null &';
$bin_scanwifi='sh ../bin/scanWifi.sh';
$bin_dhcp='../bin/dhcp';

$update_url="http://ledbox.tech4sport.com/service.php?auth=bGVkYm94OkYwcURUY1h3TW5IOG56bjg=&task=getUpdates";
$notifyupdate_url="http://ledbox.tech4sport.com/service.php?auth=bGVkYm94OkYwcURUY1h3TW5IOG56bjg=&task=setUpgrade";

$port_service=8889;

$template=file_get_contents("template/index.html");
$template_login=file_get_contents("template/login.html");
$message="";

$xml = simplexml_load_file('/home/pi/ledbox/manifest.xml');
$version=floatval($xml[0]->version);

$serialnumber=parse_ini_file($file_user_config,true)['GENERAL']['device'];
$username=parse_ini_file($file_user_config,true)['ADMINISTRATION']['username'];
$password=parse_ini_file($file_user_config,true)['ADMINISTRATION']['password'];
$menus=[];

function addMenu($label,$url,$icon,$view){
    
    $menu->label=$label;
    $menu->url=$url;
    $menu->icon=$icon;
    $menu->view=$view;
    
    array_push($GLOBALS['menus'], $menu);
}

function htmlMenu($current_view){
    $html="";
    foreach ($GLOBALS['menus'] as $menu){
        $active="";
        if(strpos($menu->view,$current_view)!==false){
            $active="active";
        }
        if($menu->label=="-"){
           $html.='
            <li class="nav-item">
                <hr/>
              </li>
              ';
        }
        elseif($menu->view==""){
                $html.='<li class="nav-header">'.$menu->label.'</li>';    
        }else{
        
            $html.='

            
            <li class="nav-item '.$active.'">
                <a class="nav-link" href="'.$menu->url.'">
                  <i class="nav-icon '.$menu->icon.'"></i>
                  <p>'.$menu->label.'</p></a>
              </li>
              ';
        }
    }
    return $html;
}

addMenu("MENU","","","");
addMenu("Dashboard","index.php?view=dashboard","nav-icon fas fa-tachometer-alt","dashboard");
addMenu("Settings","index.php?view=setting","nav-icon fas fa-cog","setting");
addMenu("Layouts","index.php?view=listlayout","nav-icon fas fa-layer-group","listlayout,editlayout");
addMenu("Media","index.php?view=gallery","nav-icon fas fa-images","gallery");
addMenu("Remotes","index.php?view=remotes","nav-icon fas fa-mobile","remotes");
addMenu("Plugins","index.php?view=listplugin","nav-icon fas fa-puzzle-piece","plugins");

function render($html,$current_view,$title=""){
    
    
    $t= str_replace("<bodycontent/>", $html, $GLOBALS['template']);
    if($GLOBALS['message']){
        $t= str_replace("<messagecontent/>", $GLOBALS['message'], $t);
        $GLOBALS['message']="";
    }
    
    if($_REQUEST['message_success']){
        $t= str_replace("<messagecontent/>", "<div class=\"alert alert-success\">".base64_decode($_REQUEST['message_success'])."</div>", $t);
    }
    
    $t= str_replace("<bodyheader/>", $title, $t);
    $t= str_replace("<serialnumber/>", $GLOBALS['serialnumber'],$t);
    $t= str_replace("<menu/>", htmlMenu($current_view), $t);
    
    $t= str_replace("<operator/>", $_SESSION['operator'], $t);
    
    echo $t;
    
}

