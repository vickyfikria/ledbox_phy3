<?php


$files=$model->readPlugins();

?>

<section class="content-header">
	<h1>Plugins</h1> 
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
			    <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
						<i class="fas fa-list mr-1"></i>
						List
					</h3>
                </div><!-- /.card-header -->
				<div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </thead>
                        <?php foreach ($files as $file):?>
                        <tr>
                            <td><?php echo $file->name;?></td>
                            <td class="d-none d-lg-table-cell"><?php echo $file->file;?></td>
                            <td class="text-right"><a href="index.php?view=editplugin&file=<?php echo $file->file;?>" class="btn btn-primary">Configure</a></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
				</div><!-- /.card-body -->
			</div>
        </div>
         

       
    </div>
</section>