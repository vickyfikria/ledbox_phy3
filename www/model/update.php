<?php

class updateModel{


    function runUpdate(){
        
        $result=new stdClass();
        $result->old_version=$GLOBALS['version'];


        $update=file_get_contents($GLOBALS['update_url']);
        $json=json_decode($update);
        file_put_contents("../update.zip", fopen($json->file, 'r'));
        exec('unzip -o ../update.zip -d ../');
		$GLOBALS['message']= "<div class='alert alert-success'>Aggiornamento effettuato correttamente. Riavviare il dispositivo per rendere effettive le modifiche</div>";
        sleep(5);
        $xml = simplexml_load_file('/home/pi/ledbox/manifest.xml');
        $result->new_version=floatval($xml[0]->version);


        //notifica ledbox management dell'avvenuta operazione
        $notify=file_get_contents($GLOBALS['notifyupdate_url']."&serialnumber=".$GLOBALS['serialnumber']."&version_sw=".$result->new_version);

        return $result;
    }

}