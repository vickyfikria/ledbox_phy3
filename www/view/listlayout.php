<?php


$files=$model->readDirectoryLayout();

?>

<section class="content-header">
	<h1>Layout</h1> 
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
                    <div class="card-tools">
                        <a href="#" class="btn btn-tool" data-toggle="modal" data-target="#modalNewLayout"><i class="fas fa-plus"></i></a>
                    </div>
				</div><!-- /.card-header -->
				<div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <th scope="col">Layout</th>
                            <th scope="col" class="d-none d-lg-table-cell">File</th>
                            <th scope="col"></th>
                        </thead>
                        <?php foreach ($files as $file):?>
                        <tr>
                            <td><?php echo $file->name;?></td>
                            <td class="d-none d-lg-table-cell"><?php echo $file->file;?></td>
                            <td class="text-right"><a href="index.php?view=editlayout&file=<?php echo $file->file;?>" class="btn btn-primary">Edit</a> <a href="index.php?view=listlayout&task=deleteFile&file=<?php echo $file->file;?>" class="btn btn-danger">Delete</a></td>
                        </tr>
                        <?php endforeach;?>
                    </table>
				</div><!-- /.card-body -->
			</div>
        </div>
         

       
    </div>
</section>

<div class="modal" tabindex="-1" role="dialog" id="modalNewLayout">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New layout</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="index.php">
            <div class="form-group">
                <label>Layout name</label>
                <input type="text" required="required" class="form-control" name="layoutname" placeholder="insert here the layout name" />
            </div>
            <input type="submit" class="btn btn-success" value="Create" />
            <input type="hidden" name="view" value="editlayout" />
            <input type="hidden" name="task" value="newLayout" />
            
        </form>
      </div>
    </div>
  </div>
</div>