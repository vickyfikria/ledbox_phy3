<?php


$files=$model->readDirectoryRemotes();


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section class="content-header">
	<h1>Interfacce</h1> 
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
						<i class="fas fa-list mr-1"></i>
						List
                    </h3>
                </div>
                <table class="table">
                    <?php foreach ($files as $file):?>

                       
                    <tr>
                        <td><?php echo $file;?></td>
                        <td class="text-right">
                            <button onclick="runInterface('remote/<?php echo $file;?>');" class="btn btn-primary">Open</button>
                            <?php if($file!="test"):?>
                                <button onclick="deleteDirectory('<?php echo $file;?>');" class="btn btn-danger">Delete</button>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                    <i class="fas fa-mobile mr-1"></i>
						Remote
                    </h3>
                </div>
                <iframe id="iframe" style="border:none;min-height:600px;"></iframe>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function runInterface(url){
        $("#iframe").attr("src",url);
    }

    function deleteDirectory(dir){
        if(confirm("Are you sure to delete remote?"))
            document.location="index.php?view=remotes&task=deleteFile&file="+dir
    }
</script>