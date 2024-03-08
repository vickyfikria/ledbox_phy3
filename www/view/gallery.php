<?php


$tree=$model->readDirectoryGallery();


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<section class="content-header">
	<h1>Media</h1> 
</section>

<div class="section">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
						<i class="fas fa-list mr-1"></i>Filesystem media folder
					</h3>
                </div><!-- /.card-header -->
            <div class="card-body">
               <div id="tree"></div>
               
                <div>
                    <a href="#" onclick="delete_selected();" class="btn btn-danger">Delete</a>
                </div>
              
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var data=JSON.parse('<?php echo $tree;?>');
    
    $('#tree').treeview({
        data: data,
        expandIcon: "far fa-plus-square",
        collapseIcon: "far fa-minus-square",
        state:{
            checked: true,
            disabled: true,
            expanded: false,
            selected: true
        },
        levels:1,
        showBorder: false,
        
    });

    function delete_selected(){
        var selected=$('#tree').treeview('getSelected');
        if(selected.length>0)
            if(confirm("Are you sure to delete element?"))
                document.location="index.php?view=gallery&task=delete&path="+selected[0].path+"&type="+selected[0].type;
    }
</script>