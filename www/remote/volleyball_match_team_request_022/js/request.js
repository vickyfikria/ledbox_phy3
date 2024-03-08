/* Copyright (C) 2019 - CODING SRLS */
/* build 022 */
	

interface_name="volleyball_matchscore_request";
default_layout=""; 													//layout che viene aperto quando si avvia l'interfaccia
layout_matchscore="volleyball_matchscore_02"; 						//layout MatchScore a cui si deve interfacciare questa interfaccia
layout_escoresheet_indoor="escoresheet_matchscore_indoor_03"; 		//layout eScoreSheet Indoor a cui si deve interfacciare questa interfaccia
layout_escoresheet_outdoor="escoresheet_matchscore_outdoor_03"; 	//layout eScoreSheet Outdoor a cui si deve interfacciare questa interfaccia

var teamname_selected=""

var value_ledbox;
var lock=false;

// Eventi
$(document).on('afterLoad', function(e, eventInfo) { 
	changeColorTeam();
	//requestValues();
	getLayout();
});


function getLayout(){
	var message={};
	message.cmd="GetLayout";
	message.value="";
	sendCommand(JSON.stringify(message));
}


$(document).on('messageReceived', function(e, eventInfo) { 
	


	switch(e.message.sender){

		case "GetLayout":
			layout=e.message.value;
				
			if(	layout == layout_matchscore 			||
				layout == layout_escoresheet_indoor		||
				layout == layout_escoresheet_outdoor	){
				requestValues();
				$("#btns_team").show();
				$("#alert_no_matchscore").hide();
			}
			else{
				$("#btns_team").hide();
				$("#alert_no_matchscore").show();
			}
			break;

		case "GetSections":
			
			value_ledbox=e.message.value;
			
			
			$("#team1_ledbox").html(getSectionValue(value_ledbox,"team1","text"));
			$("#team2_ledbox").html(getSectionValue(value_ledbox,"team2","text"));
			$("#score1_ledbox").html(getSectionValue(value_ledbox,"score1","text"));
			$("#score2_ledbox").html(getSectionValue(value_ledbox,"score2","text"));
			$("#colorteam1_ledbox").html(getSectionValue(value_ledbox,"team1","color"));
			$("#colorteam2_ledbox").html(getSectionValue(value_ledbox,"team2","color"));

			$("#team1_ledbox").css("color","rgb("+getSectionValue(value_ledbox,"team1","color")+")");
			$("#team2_ledbox").css("color","rgb("+getSectionValue(value_ledbox,"team2","color")+")");
			$("#score1_ledbox").css("color","rgb("+getSectionValue(value_ledbox,"score1","color")+")");
			$("#score2_ledbox").css("color","rgb("+getSectionValue(value_ledbox,"score2","color")+")");

			//$("#selectOrigin").show();
			getOriginFromLedBox();
			break;
	}

	switch(e.message.cmd){
		case "SetLayout":
		case "SetSections":
			changeColorTeam();
			break;
	}
	
});


//Cambia il colore del team sull'HTML dell'interfaccia
function changeColorTeam(){
	$("#colorStyle").empty();
	
	colorteam1=$('#team1[layoutattrib="color"]').val();
	colorteam1=convertRGBColorToHex(colorteam1);
	colorteam2=$('#team2[layoutattrib="color"]').val();
	colorteam2=convertRGBColorToHex(colorteam2);

	$("#colorStyle").append("<style>:root{--color1:"+colorteam1+"} :root{--color2:"+colorteam2+"}</style>");	
	
}


//Chiude la dialog per la selezione del team
function choiceTeam(obj){


	teamname_selected=$(obj).text();

	
	$("#button_request").show();
	$("#button_request #teamname_selected").html(teamname_selected);

	$("#choiceTeamDialog").hide();
	
}


// richiedi 
function Request() {
	var team=1;
	Horn();	
	//verifica quale team appartiene il teamname
	if($('#team1[layoutattrib="text"]').val()==teamname_selected)
		team=1;
	else
		team=2;


	if(team==1)
		colorteam=$('#team1[layoutattrib="color"]').val();
	else
		colorteam=$('#team2[layoutattrib="color"]').val();
	

	

	message={}
	message.cmd="SetSections";
	message.value=[];
	message.value.push(createSectionValue("team"+team,"animation","blinking"));
	message.value.push(createSectionValue("team"+team,"animation_params","{'count':16,'color1':'"+colorteam+"','color2':'0,0,0','pause':400}"));
	message.value.push(createSectionValue("mode","text","teamrequest"));
	
	sendCommand(JSON.stringify(message));	
	$("#button_request").css('background-color','#e2a81b');
	$("#button_request").addClass('disable');
	
	var timer=setTimeout(function(){
		$("#button_request").css('background-color','#585951');
		$("#button_request").removeClass('disable');
		message={}
		message.cmd="SetSections";
		message.value=[];
		message.value.push(createSectionValue("team"+team,"animation",""));
		message.value.push(createSectionValue("mode","text",""));

		sendCommand(JSON.stringify(message));
	},10000);

}

// imposta il colore nelle variabiali che vengono inviati al LEDbox
function setColor(team,layoutcolor) {
	
	
	$('#team'+team+'[layoutattrib="color"]').val(layoutcolor);
	$("#set"+team+'[layoutattrib="color"]').val(layoutcolor);
	$("#bg"+team+'[layoutattrib="bordercolor"]').val(layoutcolor);
	$("#score"+team+'[layoutattrib="color"]').val(layoutcolor);
	
}

function getOriginFromLedBox(){
	sections=value_ledbox;
	updateValues(sections);
	
	setColor(1,getSectionValue(sections,"team1","color"));
	setColor(2,getSectionValue(sections,"team2","color"));

	changeColorTeam();
	// $("#selectOrigin").hide();
	save(sections);
}

//override della funzione Back
function Back(){
	var message={};
    message.cmd="local";
    message.value="back";
    sendCommand(JSON.stringify(message));
    
    for(var i=0;i<10000;i++){
        clearInterval(i);
        clearTimeout(i);
    }
}

