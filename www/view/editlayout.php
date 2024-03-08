<?php


$file=$_REQUEST['file'];
if($file<>"" && file_exists($GLOBALS['directory_layout']."/".$file)){
    $content=$model->readFile($file);

    $xml = simplexml_load_string($content);
    $layoutname=$xml[0]['name'];

    $isNew=false;
}
else{
    $isNew=true;
    $content="";
}
?>

<section class="content-header">
    <h1>Edit Layout</h1> 
</section>

<section class="content">
    <div class="row">
        
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    

                    <form id="formmain" action="index.php" method="post" />
                        <div class="form-group">
                            <label class="control-label" for="file_layout">File</label>
                            <input class="form-control" name="file" <?php if(!$isNew) echo 'readonly="readonly"';?> value="<?php echo $file;?>" />
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="visual-tab" data-toggle="tab" href="#visual" role="tab" aria-controls="visual" aria-selected="true">Design</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="code-tab" data-toggle="tab" href="#code" role="tab" aria-controls="code" aria-selected="false">Code</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="visual" role="tabpanel" aria-labelledby="visual-tab">
                            
                                <div class="card">
                                    <div class="card-header ui-sortable-handle">
                                        <label>Layout name</label>
                                        <input type="text" class="form-control" id="layout_name" placeholder="insert here layout name" onchange="build();openLayout();"/>
                                        <label>Layout type</label>
                                        <select id="layout_modifier" class="form-control" onchange="build()">
                                            <option value="">Normal</option>
                                            <option value="specular">Specular</option>
                                            <option value="extend1">Extended 1</option>
                                            <option value="extend2">Extended 2</option>
                                            <option value="extend3">Extended 3</option>
                                        </select>
                                    
                                    </div>
                                    <div class="card-body">
                                        
                                        <section id="layout" class="connectedSortable"></section>
                                        <a href="javascript:addSection();build();" class="btn btn-success"><i class="fas fa-plus"></i> Add section</a>
                                        <a href="javascript:build();" class="btn btn-primary"><i class="fas fa-sync"></i> Update</a>
                                    </div>
                                </div>
                                
                                
                            
                                
                            </div>
                            <div class="tab-pane fade" id="code" role="tabpanel" aria-labelledby="visual-code">
                                
                                <textarea class="form-control" id="file_layout" name="file_layout" cols="10" rows="20"><?php echo $content;?></textarea>
                                
                            </div>
                            <button type="submit" class="btn btn-success">Salva</button>
                            <a href="index.php?view=listlayout" class="btn btn-default">Annulla</a>
                            <input type="hidden" name="view" value="editlayout" />
                            <input type="hidden" name="task" value="saveLayout" />

                        </div>    
                    </form>
                </div>
            </div>
            
            
        </div>
        
        
    </div>
</section>

<div id="template_layout" style="display:none;">
    <div class="card card-primary bg-info">
        <div class="card-header  ">
           
            <h3 class="card-title">
                <input type="text" class="form-control field" field" id="field_name" placeholder="section name" value="" onchange="build()" />
            </h3>
            
            <div class="card-tools">
                <a href="#" onclick="deleteSection(this);" class="btn btn-tool"><i class="fas fa-trash"></i></a>
            </div>
        </div>
        <div class="card-body">
            
            <div class="row section" >
                <div class="col-md-3">
                    <label>Type</label>
                    <select class="form-control field" id="field_type" onchange="changeFieldType(this);build();">
                        <option value="text">Text</option>
                        <option value="image">Image</option>
                        <option value="rectangle">Rectangle</option>
                        <option value="circle">Circle</option>
                        <option value="counter">Counter</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label>Pos. X</label>
                    <input type="number" class="form-control field" id="field_x" max="192" min="0" placeholder="X" value="0" onchange="build()" />
                </div>
                <div class="col-md-1">
                    <label>Pos. Y</label>
                    <input type="number" class="form-control field" id="field_y" placeholder="Y" value="0" onchange="build()" />
                </div>
                <div class="col-md-1">
                    <label>Visible</label>
                    <select class="form-control field" id="field_visible" onchange="build()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label>Private</label>
                    <select class="form-control field" id="field_private" onchange="build()">
                        <option value="false">No</option>
                        <option value="true">Yes</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label>Enable</label>
                    <select class="form-control field" id="field_enable" onchange="build()">
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Value</label>
                    <input class="form-control field" id="field_value" onchange="build()" value="new section" />
                </div>
            </div>
            <hr/>
            <div class="row" id="params">
                
            </div>

        </div>

    </div>

</div>

<div style="display:none" id="fieldlist"> 
    <div class="col-md-2" id="fontsize">
        <label>Font Size</label>
        <input class="form-control field" type="number" min="1" max="200" id="field_fontsize" value="10" onchange="build()" />
    </div>

    <div class="col-md-2" id="fontsize">
        <label>Max Lenght</label>
        <input class="form-control field" type="number" min="1" max="200" id="field_maxlength" value="10" onchange="build()" />
    </div>
    <div class="col-md-2" id="align">
        <label>Horizontal</label>
        <select class="form-control field" id="field_align" onchange="build()">
            <option value="left">Left</option>
            <option value="center">Center</option>
            <option value="right">Right</option>
            
        </select>
    </div>
    <div class="col-md-2" id="valign">
        <label>Vertical</label>
        <select class="form-control field" id="field_valign" onchange="build()">
            <option value="top">Top</option>
            <option value="center">Center</option>
            <option value="bottom">Bottom</option>
            
        </select>
    </div>
    <div class="col-md-2" id="animation">
        <label>Animation</label>
        <div class="input-group">
            <select class="form-control field" id="field_animation" onchange="build()">
                <option value="">None</option>
                <option value="scroller_x">Scroller X</option>
                <option value="scroller_y">Scroller Y</option>
                <option value="blinking">Blinking Fill</option>
                <option value="blinkingborder">Blinking Border</option>
            </select>
            <span class="input-group-append">
                <a href="#" class="btn btn-sm btn-default" onclick="editParameter(this,'template_animation_parameters');"><i class="fas fa-pencil-alt"></i></a>
                <input class="field fieldjson" type="text" id="field_animation_params" style="display:none;" />
            </span>
        </div>
        
        
    </div>
    <div class="col-md-3" id="color">
        <label>Color</label>
        <div class="input-group my-colorpicker">
            <input class="form-control" type="text"/>
            <span class="input-group-append">
                <span class="input-group-text  colorpicker-input-addon"><i></i></span>
            </span>
        </div>
        <input class="field" style="display:none;" type="text"  id="field_color" value="255,255,255"  />
    </div>
    <div class="col-md-3" id="bordercolor">
        <label>Border Color</label>
        <div class="input-group my-colorpicker">
            <input class="form-control" type="text"/>
            <span class="input-group-append">
                <span class="input-group-text  colorpicker-input-addon"><i></i></span>
            </span>
        </div>
        <input class="field" style="display:none;" type="text"  id="field_bordercolor" value="255,255,255"  />
    </div>
    <div class="col-md-1" id="width">
        <label>Width</label>
        <input class="form-control field" type="number" min="1" max="200" id="field_width" value="10" onchange="build()" />
    </div>
    <div class="col-md-1" id="height">
        <label>Height</label>
        <input class="form-control field" type="number" min="1" max="200" id="field_height" value="10" onchange="build()" />
    </div>

   

    <div class="col-md-3" id="src">
        <label>Source</label>
        <input class="form-control field" type="text" id="field_src" value="" onchange="build()" placeholder="local path of image" />
    </div>
    <div class="col-md-3" id="parameter">
        <label>Counter Parameters</label>
        <a href="#" class="form-control btn btn-sm btn-default" onclick="editParameter(this,'template_counter_parameters');">Edit Parameter</a>
        <input class="field fieldjson" type="text" id="field_parameter" style="display:none;"  />
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalParameters" tabindex="-1" role="dialog" aria-labelledby="modalParametersLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalParametersLabel">Parameters</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <table class="table table-striped" id="listParams"></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="saveParameters()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<table style="display:none;">
    <tbody id="template_counter_parameters">
        <tr>
            <td>type</td>
            <td>
                <select class="parameter_field form-control" id="parameter_type" >
                    <option value="countup">Count Up</option>
                    <option value="countdown">Count Down</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>start (second)</td>
            <td><input class="parameter_field form-control" type="number" min="0" id="parameter_start" value="0" /></td>
        </tr>
        <tr>
            <td>stop (second)</td>
            <td><input class="parameter_field form-control" type="number" min="0" id="parameter_stop" value="3600" /></td>
        </tr>
        <tr>
            <td>format</td>
            <td><input class="parameter_field form-control" type="text" min="0" id="parameter_format" value="%M:%S" /></td>
        </tr>
    </tbody>

    <tbody id="template_animation_parameters">
        <tr>
            <td>count (0=infinity)</td>
            <td><input class="parameter_field form-control" type="number" min="1" id="parameter_count" value="1" /></td>
        </tr>
        <tr>
            <td>pause (ms)</td>
            <td><input class="parameter_field form-control" type="number" min="1" id="parameter_pause" value="1000" /></td>
        </tr>
        <tr>
            <td>color 1</td>
            <td><input class="parameter_field form-control" type="text" id="parameter_color1" value="255,255,255" /></td>
        </tr>
        <tr>
            <td>color 2</td>
            <td><input class="parameter_field form-control" type="text" id="parameter_color2" value="0,0,0" /></td>
        </tr>
    </tbody>
</table>





<script type="text/javascript">

function parseLayout(){
    
    var parser = new DOMParser();
    xmlDoc = parser.parseFromString($("#file_layout").val(),"text/xml");
    var layout=xmlDoc.getElementsByTagName("layout")[0];
    $("#layout_name").val(layout.getAttribute("name"));
    $("#layout_modifier").val(layout.getAttribute("modifier"));


    var sections=xmlDoc.getElementsByTagName("section");
    var fieldtype="text";
    for (var i=0;i<sections.length;i++){
        item=sections[i];
        values=[];
        var attributes=item.attributes;
        for(var j=0;j<attributes.length;j++){
            attrib={};
            attrib.name=attributes[j].name;
            attrib.value=attributes[j].value;
            values.push(attrib);

            if(attributes[j].name=="type")
                fieldtype=attributes[j].value;
        }
        var data_value="";
        if(item.childNodes.length>0)
            data_value=item.childNodes[0].nodeValue;
        addSection(values,fieldtype,data_value);


    }

    
      
}

function build(){
    var doc = document.implementation.createDocument("", "", null);
    var layoutElem = doc.createElement("layout");
    layoutElem.setAttribute("name",$("#layout_name").val());
    layoutElem.setAttribute("modifier",$("#layout_modifier").val());
    
    $("#layout").children().each(function(id){
        var section = doc.createElement("section");
        $(this).find(".field").each(function(idx){
            
            var field_type=$(this).attr("id").replace("field_","");
            if(field_type=="value"){
                var newText=xmlDoc.createTextNode($(this).val());
                section.appendChild(newText);
            }
            else
                section.setAttribute(field_type,$(this).val());
            

            layoutElem.appendChild(section);
        });
    });
    
    doc.appendChild(layoutElem);
    var oSerializer = new XMLSerializer();
    var sXML = oSerializer.serializeToString(doc);
    $("#file_layout").val("<?xml version='1.0'?>"+sXML);
    save();
}


function save(){
        
        var form = $("#formmain");
        var url = form.attr('action');
       
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            success: function(data)
            {
                openLayout();
            }
        });
}

function deleteSection(obj){
    $(obj).parent().parent().parent().remove();
    build();
}

function addSection(values=null,fieldtype="text",data_value=""){
    var t=$("#template_layout").children().clone();
    var testvalue="section"+($("#layout").children().length+1);
    if(data_value=="")
        data_value=testvalue;

    t.find("#field_value").val(data_value);

    t.find("#field_name").val(testvalue);


    $("#layout").append(t);
    _changeFieldset(fieldtype,t.find("#params"));

    if(values!=null){
        for(var i=0;i<values.length;i++)
        {
            item=values[i];
            var f=t.find("#field_"+item.name);
            f.val(item.value);
        }
    }

    updateColorPicker();
    
}

function changeFieldType(obj){
    var t=$(obj).val();
    var c=$(obj).parent().parent();
    var s=$(obj).parent().parent().parent().find("#params");
    s.empty();
    _changeFieldset(t,s);
    
}
function _changeFieldset(type_field,section_params){
    switch(type_field){
        case "text":
            addField("fontsize",section_params);
            addField("maxlength",section_params);
            addField("color",section_params);
            addField("align",section_params);
            addField("animation",section_params);
            break;
        case "image":
            addField("src",section_params);
            addField("align",section_params);
            addField("valign",section_params);
            addField("width",section_params);
            addField("height",section_params);
            break;
        case "rectangle":
            addField("color",section_params);
            addField("bordercolor",section_params);
            addField("width",section_params);
            addField("height",section_params);
            addField("animation",section_params);
            break;
        case "circle":
            addField("color",section_params);
            addField("bordercolor",section_params);
            addField("width",section_params);
            addField("height",section_params);
            addField("animation",section_params);
            break;
        case "counter":
            addField("fontsize",section_params);
            addField("color",section_params);
            addField("align",section_params);
            addField("animation",section_params);
            addField("parameter",section_params);
            break;
        
    }

    updateColorPicker();
}


function addField(name,section){
    var f=$("#fieldlist #"+name).clone();
    
    section.append(f);
}


function openLayout(){
    var message={};
    message.cmd="ReloadLayout";
    message.value=$("#layout_name").val();
    sendCommand(JSON.stringify(message));
}

ws.onmessage = function(event){
    obj=JSON.parse(event.data);
    
    switch(obj.sender){
        case "Connect":
            openLayout();
            break;
        
    }
    
    
    
}

function updateColorPicker(){
    $('.my-colorpicker').colorpicker({
        format: 'rgb',
        
    }).on('colorpickerChange', function (e) {
        var f=e.colorpicker.element.parent().find('.field');
        f.val(e.color.toRgbString().replace("rgb(","").replace(")",""));
       
    }).on('colorpickerCreate', function (e) {
        var f=e.colorpicker.element.parent().find('.field');
        e.colorpicker.setValue("rgb("+f.val()+")");
    });
}

var params_modal;

function editParameter(obj,id_template){
    params_modal=$(obj).parent().find(".fieldjson");
    var listParams=$("#modalParameters #listParams");
    listParams.empty();
    var t=$("#"+id_template).clone();

    //update values
    var v=params_modal.val();
    var json=replaceAll(v,"'",'"');
    var values={};
    try{
        values=JSON.parse(json);
    }catch{
        values={};
    }
    var keys=Object.keys(values);

    for(var i=0;i<keys.length;i++){
        var field=keys[i];
        var value=values[field];
        t.find("#parameter_"+field).val(value);
    }

    listParams.append(t);
    $("#modalParameters").modal("show");
    
}

function saveParameters(){
    var listParams=$("#modalParameters #listParams .parameter_field");
    var params={};

    listParams.each(function(idx){
        var key=$(this).attr("id").replace("parameter_","");
        var value=$(this).val();
        if($(this).attr("type")=="number")
            params[key]=parseInt(value);
        else
            params[key]=value;
    });
    params_modal.val(replaceAll(JSON.stringify(params),'"',"'"));
    $("#modalParameters").modal("hide");
    build();
}

// creo una funzione per fare l'escape degli eventuali caratteri speciali
function escapeRegExp(str) {
  return str.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

// utilizzo RegExp per effettuare una sostituzione globale
function replaceAll(str, cerca, sostituisci) {
  return str.replace(new RegExp(escapeRegExp(cerca), 'g'), sostituisci);
}

$(function () {

    'use strict'

    $('.connectedSortable').sortable({
        placeholder         : 'sort-highlight',
        connectWith         : '.connectedSortable',
        handle              : '.card-header, .nav-tabs',
        forcePlaceholderSize: true,
        zIndex              : 999999,
        stop: function(){build();}
    })
    $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

    //Colorpicker
    
    
});
parseLayout();



</script>