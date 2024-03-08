/* IMPOSTAZIONI */


//The magic code to add show/hide custom event triggers
(function ($) {
	  $.each(['show', 'hide'], function (i, ev) {
	    var el = $.fn[ev];
	    $.fn[ev] = function () {
	      this.trigger(ev);
	      return el.apply(this, arguments);
	    };
	  });
	})(jQuery);


var htmlSetting=`

<div id="{WINDOWNAME}" class="messageDialog" style="display: none;">
	<p><language>YOU_MUST_LOAD_IMAGES</language></p>
	<div id="listImage"></div>
    <center>
		<button class="message_button" onclick="SelectImage()"><language>ADD_IMAGE</language></button>
        <button class="message_button" onclick="closeSetting()"><language>CLOSE</language></button>    
    </center>
	<div style="display:none;">
		<div id="templateImage">
			<div class="rowImage"  >
				<div class="body" onclick="openChoice(this);">
					<img id="img" height="64" src="{LOCAL_FILE}" />
					<input type="hidden" id="image" value="{FILE}" />
				</div>
				<div class="footer">
					<div id="time_space"><span id="time">00:00</span></div>
					<div id="stepper">
						<button onclick="changeDuration(this,-1);">-</button>
						<button onclick="changeDuration(this,1);">+</button>
						<input type="hidden" id="duration" value="{DURATION}" />
					</div>
				</div>
			</div>

			
		</div>

	</div>
</div>
`;

var contextualMenu=`
<div id="contextualMenu" title="" style="display:none;">
		<h4><language>SELECT_COMMAND</language></h4>
		<ul>
			<li><button onclick="moveChoice(2)"><language>MOVE_UP</language></button></li>
			<li><button onclick="moveChoice(1)"><language>MOVE_DOWN</language></button></li>
			<li><button onclick="deleteChoice()"><language>DELETE</language></button></li>
		</ul>
	</div>
`;


$(document).on('messageReceived', function(e, eventInfo) { 
    switch(e.message.cmd){
		case "fileImageSelected":
			if(e.message.value.filename!=""){
				var item={};
				file_uploaded=e.message;
				item.file="media/"+file_uploaded.alias+"/"+file_uploaded.sport+"/"+file_uploaded.value.filename;
	            item.file_local=file_uploaded.value.filepath;
	            item.duration=5;
				addImage(item);
				updateListImages();
			}
			break;
	}
});

var rowSelected;
var id_window;
var field_setting;
function openSettingImage(id,field){
	id_window=id;
	field_setting=field;
	$("#"+id_window+" #listImage").empty();
	try{
		images=JSON.parse($("#"+field_setting).val());
		for(i=0;i<images.length;i++){
			addImage(images[i]);
		}
	}catch{

	}	

	$("#"+id ).on("hide",function(){
   		$.event.trigger({
        	type: "settingClose",
        	message: id_window
    	});
   	});

	$( "#"+id ).show();
	
}

function closeSetting(){
    $( "#"+id_window ).hide();
    
}

function SelectImage(){
	selectImageFile(field_setting);
}

function deleteChoice(){
	$(rowSelected).parent().remove();
	$( "#contextualMenu" ).dialog( "close" );
	updateListImages();
}

function moveChoice(direction){
	var item=$(rowSelected).parent();
	if(direction==1){
		var next=$(rowSelected).parent().next();
		if(next) next.after(item);
	 }else{
		var prev=$(rowSelected).parent().prev();
		if(prev) prev.before(item);
	 }
	 updateListImages();
	$( "#contextualMenu" ).dialog( "close" );
	
}

function updateListImages(){
	var l=[];
	$("#"+id_window+" #listImage .rowImage").each(function(){
		var t={};
		t.file_local=$(this).find("#img").attr("src");
		t.file=$(this).find("#image").val();
		t.duration=$(this).find("#duration").val();
		l.push(t);
		
	});
	
	$("#"+field_setting).val(JSON.stringify(l));

}

function openChoice(obj){
	rowSelected=obj;
	$('#contextualMenu').dialog({
		dialogClass: "no-close",
		buttons: [
			{
			  text: "ANNULLA",
			  click: function() {
				$( this ).dialog( "close" );
			  }
			}
		  ]
	});
	
}

function addImage(item){
	var tr=$("#"+id_window+" #templateImage").clone().html();

	if(typeof(item)!="undefined"){
		tr=tr.replace("{LOCAL_FILE}",item.file_local);
		tr=tr.replace("{FILE}",item.file);
		tr=tr.replace("{DURATION}",item.duration);
	}

	$("#"+id_window+" #listImage").append(tr);
	updateDuration();
}

function changeDuration(obj,delta){
	var d=$(obj).parent().find("#duration").val();
	if(delta<0 && d==1)
		return;

	$(obj).parent().find("#duration").val(parseInt($(obj).parent().find("#duration").val())+delta);
	updateDuration();
	updateListImages();

}

function updateDuration(){

	$("#"+id_window+" #listImage #duration").each(function(){
		var d=parseInt($(this).val());
		var l= String(Math.floor(d / 60)).padStart(2,'0') + ":" + String(d % 60 ? d % 60 : '00').padStart(2,'0');

		$(this).parent().parent().find("#time").html(l);

	});
	
}


$( document ).ready(function() {
    $(".settingWindow").each(function(){
        var html=htmlSetting;
        var winname=$(this).attr("name");
        html=html.replace("{WINDOWNAME}",winname);
        $(this).html(html);
    });



    $("body").append(contextualMenu);
	applyLanguage();
});

