<section class="content-header">
	<h1>Dashboard</h1> 
</section>
<section class="content">
	<div class="row">
		<div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $GLOBALS['serialnumber'];?></h3>
                <p>Serial Number</p>
              </div>
              <div class="icon">
                <i class="ion ion-card"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo $GLOBALS['version'];?></h3>

                <p>Version</p>
              </div>
              <div class="icon">
                <i class="ion ion-information"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="online_update"> ...</h3>
				<a id="online_update_btn" style="display:none;" href="index.php?view=update" class="btn btn-small btn-success">Aggiorna</a>

                <p>Online Update</p>
              </div>
              <div class="icon">
                <i class="ion ion-cloud"></i>
              </div>
            </div>
          </div>
		  <!-- ./col -->
		  <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="cpu_temp">nd</h3>

                <p>CPU Temp</p>
              </div>
              <div class="icon">
                <i class="ion ion-thermometer"></i>
              </div>
            </div>
          </div>
	</div>    


	 

    <div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header ui-sortable-handle">
					<h3 class="card-title">
						<i class="fas fa-list mr-1"></i>
						Logs
					</h3>
					<div class="card-tools">
						<label>Log</label><input type="checkbox" id="enableLog" checked="checked" onclick="updateLog=!updateLog;stateLog();"/>
                        <label>Auto scroll </label><input type="checkbox" id="enableAutoScroll" checked="checked" />
                    </div>
					
				</div><!-- /.card-header -->
				<div class="card-body">
					<div id="output" style="overflow-y:scroll;height:500px;">
						<table class="table table-striped">
							<thead style="position: sticky; top: 0;background:white; ">
								<th>Date</th>
								<th>Message</th>
								<th>Options</th>
							</thead>
							<tbody id="outputlog" style="top:50px;" >
								<tr>
									<td colspan="3">Loading ... </td>
								</tr>
							</tbody>
						</table>
					</div>
				</div><!-- /.card-body -->
			</div>
		</div>
		
		<div class="col-md-12">
			<div class="card">
				<div class="card-header ui-sortable-handle" style="cursor: move;">
					<h3 class="card-title">
						<i class="fas fa-users mr-1"></i>
						Users
					</h3>
				</div><!-- /.card-header -->
				<div class="card-body">
				<table class="table">
					<thead>
						<th>ID</th>
						<th>Connection</th>
						<th class="d-none d-lg-table-cell">Address</th>
						<th class="d-none d-lg-table-cell">Alias</th>
						<th class="d-none d-lg-table-cell" >Sport</th>
						<th class="d-none d-lg-table-cell">Role</th>
						<th>Client</th>
						
					</thead>
					<tbody id="current_users"></tbody>
				</table>
				</div><!-- /.card-body -->
			</div>
		</div>

    </div>
</section>

<script type="text/javascript">
var updateLog=true;
var interval_log=null;
function startLog(){

	interval_log = setInterval(function() {
	
		getOutput();
		getCurrentUsers();
		getTemperature();

	}, 2000);
}

function stopLog(){
	if(interval_log!=null)
		clearInterval(interval_log);
}

function stateLog(){
	if(updateLog)
		startLog();
	else
		stopLog();
}


function getOutput(){
	getWs("dashboard", "getOutput",function (data){
		if(data.value.length==0)
			return;
		var lines=data.value.split("\n");
		$("#outputlog").empty();
		lines.forEach(function(item){
			var params=item.split("|");
			var option=["",""]
			if(params.length>1)
				option=params[1].split("=");
				if(option.length<2)
					option[1]=""
				else
					option[1]=Prism.highlight(option[1], Prism.languages.json, 'json');
			var html="<tr><td>"+params[0]+"</td><td>"+option[0]+"</td><td>"+option[1]+"</td></tr>";
			$("#outputlog").append(html);
		});
		
		

		if($("#enableAutoScroll").is(':checked'))
			$('#output').scrollTop($('#output')[0].scrollHeight);
	});
}
function checkUpdate(){
	getWs("dashboard","checkUpdate",function(data){
		if(data.value){
			var version=data.value.version;
			if(version=="")
				version="nd";
			if(data.value.isupdate){
				$("#online_update_btn").show();
			}
			$("#online_update").html(version);
		}
	})
}

function getCurrentUsers(){
	getWs("dashboard", "getCurrentUsers",function (data){
		if(data.value.length==0)
			return;
		$("#current_users").empty();
		rows=data.value.split("\n");
		for(var i=0;i<rows.length;i++){
			fields=rows[i].split(";");
			tr="<tr>";
			var classtd="";
			for(var j=0;j<fields.length;j++){
				if(j>1)
				classtd="d-none d-lg-table-cell"
				tr+="<td class='"+classtd	+"'>"+fields[j]+"</td>";
			}
			tr+="</tr>";
			$("#current_users").append(tr);
		}

		
		
		
	});
}


function getTemperature(){
	getWs("dashboard", "getTemperature",function (data){
		$("#cpu_temp").html(data.value+"<small> °C</small>");
	});
}
stateLog();
checkUpdate();



</script>
