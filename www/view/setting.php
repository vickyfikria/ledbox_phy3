<?php
$config= $model->getConfig();
$user_config= $model->getUserConfig();
$img_cover=$model->getCoverImage();
?>

<section class="content-header">
	<h1>Settings</h1> 
</section>





<section class="content">
    <div class="row">
        <div class="col-md-12">
        <?php if($_SESSION['su']): ?>   
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="simple-tab" data-toggle="tab" href="#simple" role="tab" aria-controls="simple" aria-selected="true">Basic</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="advanced-tab" data-toggle="tab" href="#advanced" role="tab" aria-controls="advanced" aria-selected="false">Advanced</a>
            </li>
        </ul>
        <?php endif;?>
        <form action="index.php" method="post" enctype="multipart/form-data">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="simple" role="tabpanel" aria-labelledby="simple-tab">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">General</h3>
                                </div>
                            
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Cover image</label>
                                        <img src="<?php echo $img_cover;?>" />
                                        <input type="file" name="file_cover" value="Change" /> 
                                    </div>

                                    <div class="form-group">
                                        <label>Brightness</label>
                                         <select id="DISPLAY_brightness" class="form-control" onchange="updateField(this);refreshFields();">
                                            <option value="10" <?php if($config['DISPLAY']['brightness']=="10") echo "selected='selected'";?>>10%</option>
                                            <option value="20" <?php if($config['DISPLAY']['brightness']=="20") echo "selected='selected'";?>>20%</option>
                                            <option value="30" <?php if($config['DISPLAY']['brightness']=="30") echo "selected='selected'";?>>30%</option>
                                            <option value="40" <?php if($config['DISPLAY']['brightness']=="40") echo "selected='selected'";?>>40%</option>
                                            <option value="50" <?php if($config['DISPLAY']['brightness']=="50") echo "selected='selected'";?>>50%</option>
                                            <option value="60" <?php if($config['DISPLAY']['brightness']=="60") echo "selected='selected'";?>>60%</option>
                                            <option value="70" <?php if($config['DISPLAY']['brightness']=="70") echo "selected='selected'";?>>70%</option>
                                            <option value="80" <?php if($config['DISPLAY']['brightness']=="80") echo "selected='selected'";?>>80%</option>
                                            <option value="90" <?php if($config['DISPLAY']['brightness']=="90") echo "selected='selected'";?>>90%</option>
                                            <option value="100" <?php if($config['DISPLAY']['brightness']=="100") echo "selected='selected'";?>>100%</option>
                                        </select>
                                        
                                    </div>


                                    <div class="form-group">
                                        <label>Layout modifier</label>
                                        <select id="LAYOUT_modifier" class="form-control" onchange="updateField(this);refreshFields();">
                                            <option value="" <?php if($user_config['LAYOUT']['modifier']=="") echo "selected='selected'";?>>None</option>
                                            <option value="specular"  <?php if($user_config['LAYOUT']['modifier']=="specular") echo "selected='selected'";?>>Specular</option>
                                            <option value="extended1"  <?php if($user_config['LAYOUT']['modifier']=="extended1") echo "selected='selected'";?>>Extended (first)</option>
                                            <option value="extended2"  <?php if($user_config['LAYOUT']['modifier']=="extended2") echo "selected='selected'";?>>Extended (second)</option>
                                            <option value="extended3"  <?php if($user_config['LAYOUT']['modifier']=="extended3") echo "selected='selected'";?>>Extended (third)</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Mode</label>
                                        <select id="GENERAL_mode" class="form-control" onchange="updateField(this);refreshFields();">
                                            <option value="master" <?php if($user_config['GENERAL']['device']=="master") echo "selected='selected'";?>>Master</option>
                                            <option value="slave"  <?php if($user_config['GENERAL']['device']=="slave") echo "selected='selected'";?>>Slave</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="panel_ip_master" style="display:none">
                                        <label>IP Master</label>
                                        <input type="text" class="form-control" id="GENERAL_ip_master" onKeyUp="updateField(this);" value="<?php echo $user_config['GENERAL']['ip_master'];?>">
                                    </div>





                                </div>

                                
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Administration</h3>
                                </div>
                            
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" class="form-control" id="ADMINISTRATION_username" onKeyUp="updateField(this);" value="<?php echo $user_config['ADMINISTRATION']['username'];?>">
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control" id="ADMINISTRATION_password" onKeyUp="updateField(this);" value="<?php echo $user_config['ADMINISTRATION']['password'];?>">
                                    </div>
                                </div>

                                
                            </div>
                        </div>
                        
                        <div class="col-md-6 mt-2">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Wifi</h3>
                                </div>
                            
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Mode</label>
                                        <select id="WIFI_mode" class="form-control" onchange="updateField(this);refreshFields();">
                                            <option value="ap" <?php if($user_config['WIFI']['mode']=="ap") echo "selected='selected'";?>>Access Point</option>
                                            <option value="client"  <?php if($user_config['WIFI']['mode']=="client") echo "selected='selected'";?>>Wifi Client</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="panel_wifi_ssid" style="display:none">
                                        <label>SSID Wifi</label>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" id="WIFI_ssid" onKeyUp="updateField(this);" value="<?php echo $user_config['WIFI']['ssid'];?>">
                                            <div class="input-group-append">
                                            <a href="#" class="btn btn-primary btn-sm" onclick="scanWifi()" data-toggle="modal" data-target="#ScanWifiModal">Scan</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="panel_wifi_psk" style="display:none">
                                        <label>Password Wifi</label>
                                        <input type="text" class="form-control" id="WIFI_psk" onKeyUp="updateField(this);" value="<?php echo $user_config['WIFI']['psk'];?>">
                                    </div>


                                </div>
                            </div>

                            
                        
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">LAN</h3>
                                </div>
                            
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Mode</label>
                                        <select id="NETWORK_mode" class="form-control" onchange="updateField(this);refreshFields();">
                                            <option value="dhcp" <?php if($user_config['NETWORK']['mode']=="dhcp") echo "selected='selected'";?>>DHCP</option>
                                            <option value="static"  <?php if($user_config['NETWORK']['mode']=="static") echo "selected='selected'";?>>IP static</option>
                                        </select>
                                    </div>
                                    <div class="form-group" id="panel_network_ip" style="display:none">
                                        <label>IP</label>
                                        <input type="text" class="form-control" id="NETWORK_ip" onKeyUp="updateField(this);" value="<?php echo $user_config['NETWORK']['ip'];?>">
                                    </div>
                                    <div class="form-group" id="panel_network_subnet" style="display:none">
                                        <label>Subnet</label>
                                        <input type="text" class="form-control" id="NETWORK_subnet" onKeyUp="updateField(this);" value="<?php echo $user_config['NETWORK']['subnet'];?>">
                                    </div>
                                    <div class="form-group" id="panel_network_gateway" style="display:none">
                                        <label>Gateway</label>
                                        <input type="text" class="form-control" id="NETWORK_gateway" onKeyUp="updateField(this);" value="<?php echo $user_config['NETWORK']['gateway'];?>">
                                    </div>
                                    

                                </div>
                            </div>
                        </div>
                    </div>
                
                    
                </div>
                <div class="tab-pane fade" id="advanced" role="tabpanel" aria-labelledby="advanced-tab">
                    <table class="table">
                        <?php foreach($user_config as $key=>$value):?>
                        
                            <tr class="bg-info">
                                <td colspan="2"><?php echo $key;?></td>
                            </tr>
                        
                            <?php foreach($value as $fieldname=>$fieldvalue):?>
                                <tr>
                                    <td><?php echo $fieldname;?></td>
                                    <td><input type="text" id="field_<?php echo $key;?>_<?php echo $fieldname;?>" name="user_field[<?php echo $key;?>][<?php echo $fieldname;?>]" value="<?php echo $fieldvalue;?>" />
                                </tr>
                            <?php endforeach;?>
                        <?php endforeach;?>
                        <?php foreach($config as $key=>$value):?>
                            <tr class="bg-info">
                                <td colspan="2"><?php echo $key;?></td>
                            </tr>
                        
                            <?php foreach($value as $fieldname=>$fieldvalue):?>
                                <tr>
                                    <td><?php echo $fieldname;?></td>
                                    <td><input type="text" id="field_<?php echo $key;?>_<?php echo $fieldname;?>" name="field[<?php echo $key;?>][<?php echo $fieldname;?>]" value="<?php echo $fieldvalue;?>" />
                                </tr>
                            <?php endforeach;?>
                        
                        <?php endforeach;?>
                       
                    </table>

                </div>
           
                <input type="hidden" name="task" value="saveConfig" />
                <input type="hidden" name="view" value="setting" />
                <input type="submit" class="btn btn-success" value="Salva" />
            
                </div>
            </form>

            


            
    </div>
</div>

<!-- ScanWifiModal -->
<div id="ScanWifiModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Scansione rete</h4>
      </div>
      <div class="modal-body">
        <p id="lbl_scan_waiting" style="display: none;">Scansione in corso ...</p>
        <table class="table table-striped">
            <tbody id="tablebody-wifi">
                

            </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</section>


<script type="text/javascript">


function refreshFields(){
    if($("#GENERAL_mode").val()=="master")
        $("#panel_ip_master").hide();
    else
        $("#panel_ip_master").show();

    if($("#WIFI_mode").val()=="ap"){
        $("#panel_wifi_ssid").hide();
        $("#panel_wifi_psk").hide();
    }
    else
    {
        $("#panel_wifi_ssid").show();
        $("#panel_wifi_psk").show();
    }

    if($("#NETWORK_mode").val()=="dhcp"){
        $("#panel_network_ip").hide();
        $("#panel_network_subnet").hide();
        $("#panel_network_gateway").hide();
        
    }
    else
    {
        $("#panel_network_ip").show();
        $("#panel_network_subnet").show();
        $("#panel_network_gateway").show();
    }
    /*
    if($("#typeledbox").val()=="indoor"){
        $("#field_DISPLAY_hardware_mapping").val("applicon");
        $("#field_DISPLAY_pwm_lsb_nanoseconds").val("200");
        $("#field_DISPLAY_slowdown_gpio").val("5");
        $("#field_DISPLAY_pwm_bits").val("5");
        $("#field_DISPLAY_multiplexing").val("0");
        $("#field_LEDBOX_version_hw").val("0.44");
        
    }

    if($("#typeledbox").val()=="outdoor"){
        $("#field_DISPLAY_hardware_mapping").val("applicon");
        $("#field_DISPLAY_pwm_lsb_nanoseconds").val("200");
        $("#field_DISPLAY_slowdown_gpio").val("5");
        $("#field_DISPLAY_pwm_bits").val("5");
        $("#field_DISPLAY_multiplexing").val("1");
        $("#field_LEDBOX_version_hw").val("0.45");
    }
    */


}

function updateField(obj){
    var id=$(obj).attr('id');
    console.log(id);
    $("#field_"+id).val($(obj).val());
}

function selectWifi(obj){
    $("#field_WIFI_ssid").val(obj);
    $("#WIFI_ssid").val(obj);
    
}


function scanWifi(){
    $("#lbl_scan_waiting").show();
    $("#tablebody-wifi").empty();
    getWs("setting", "scanNetwork",function (data){
        $("#lbl_scan_waiting").hide();
        
        data.value.forEach(function(entry) {
            wifi=entry.replace("\tSSID: ","");

            html="<tr><td>"+wifi+"</td><td><button data-dismiss='modal' class='btn btn-default' onclick='selectWifi(\""+wifi+"\");'>Seleziona</button></td></tr>";
            $("#tablebody-wifi").append(html);
        });
    });
}

refreshFields();

</script>
