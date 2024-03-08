<?php
    $update=$model->runUpdate();
?>

<div class="section">
    <div class="row">
        <div class="col-md-12">
            <h1>Aggiornamento</h1>
            <table class="table">
                <tr>
                    <td>Versione precedente</td>
                    <td><?php echo $update->old_version;?>
                </tr>
                <tr>
                    <td>Versione successiva</td>
                    <td><?php echo $update->new_version;?>
                </tr>

            </table>
            <div class="text-center">
                <a href="javascript:reboot()" class="btn btn-danger">Riavvia</a>
            </div>
        
    </div>
</div>

<script type="text/javascript">

function reboot(){
       document.location="index.php?view=dashboard&task=reboot";

}

</script>