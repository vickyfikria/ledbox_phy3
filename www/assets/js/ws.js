function getWs(model,task,result){
	$.getJSON( "index.php?model=ws&task=get&param_model="+model+"&param_task="+task, function( data ) {
		result(data);

    	});

}