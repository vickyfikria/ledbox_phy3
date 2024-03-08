<?php


$file=$_REQUEST['file'];
if($file<>"" && file_exists($GLOBALS['directory_plugin']."/".$file)){
    $content=$model->readFile($file);
    $isNew=false;
}
else{
    $isNew=true;
    $content="";
}
?>

<section class="content-header">
    <h1>Configure Plugin</h1> 
</section>

<section class="content">
    <div class="row">
        
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    

                    <form id="formmain" action="index.php" method="post" />
                            
                        <div class="card">
                            <div class="card-header ui-sortable-handle">
                                <label>Name</label>
                                <input type="text" class="form-control" readonly="readonly" name="field[GENERAL][name]" value="<?php echo $content['GENERAL']['name'];?>" />
                           
                                <label>Version</label>
                                <input type="text" class="form-control" readonly="readonly" name="field[GENERAL][version]" value="<?php echo $content['GENERAL']['version'];?>" />


                            </div>
                            <div class="card-body">
                                <table class="table">
                                <?php foreach ($content['PARAMETERS'] as $key => $value):?>
                                    <tr>
                                        <td><?php echo $key;?></td>
                                        <td>
                                            <input type="text" class="form-control" name="field[PARAMETERS][<?php echo $key;?>]" value="<?php echo $value;?>" />
                                        </td>
                                    </tr>

                                <?php endforeach?>
                                </table>
                                
                            </div>
                        </div>
                                
                                
                            
                                
                          
                            <button type="submit" class="btn btn-success">Salva</button>
                            <a href="index.php?view=listplugin" class="btn btn-default">Annulla</a>
                            <input type="hidden" name="file" value="<?php echo $file;?>" />
                            <input type="hidden" name="view" value="editplugin" />
                            <input type="hidden" name="task" value="savePlugin" />

                        </div>    
                    </form>
                </div>
            </div>
            
            
        </div>
        
        
    </div>
</section>
