<?php

/*
	// TODO
		- Clip löschen ?

*/

class rex_xform_value_pz_address_image_screen extends rex_xform_value_abstract
{

	function enterObject()
	{
		$value_ids = explode(",",$this->getValue());
		
		$default_image_path = $this->getElement(3);
		
		$clip_ids = "";
		$clips = array();
		
		$output = '	<div class="rex-form-row">
						<label></label>
						<div id="pz_multiupload_'.$this->getId().'"></div>
					</div>
					<script>
					function pz_createUploader_'.$this->getId().'(){            
						var uploader = new qq.FileUploader({
							element: document.getElementById(\'pz_multiupload_'.$this->getId().'\'),
							action: \''.pz::url("screen", "clipboard", "upload", array( "mode"=>"file" ) ).'\',
							
							template: \'<div class="qq-uploader"><div class="qq-upload-drop-area"><span>'.rex_i18n::msg("file_for_upload").'</span></div><div class="qq-upload-button">'.rex_i18n::msg("dragdrop_file_for_upload").'</div><ul class="qq-uploaded-list"></ul><ul class="qq-upload-list"></ul></div>\',
							
							fileTemplate: \'<li><span class="qq-upload-file"></span><span class="qq-upload-spinner"></span><span class="qq-upload-size"></span><a class="qq-upload-cancel" href="javascript:void(0);">'.rex_i18n::msg("dragdrop_file_exit").'</a><span class="qq-upload-failed-text">'.rex_i18n::msg("dragdrop_files_upload_failed").'</span></li>\',
							
							removeTemplate: \'\',
							
							// remove();
							
							classes: {
					            button: "qq-upload-button",
					            drop: "qq-upload-drop-area",
					            dropActive: "qq-upload-drop-area-active",
					            list: "qq-upload-list",
					            file: "qq-upload-file",
					            spinner: "qq-upload-spinner",
					            size: "qq-upload-size",
					            cancel: "qq-upload-cancel",
					            success: "qq-upload-success",
					            fail: "qq-upload-fail"
					        },
							sizeLimit: 0, // max size   
							minSizeLimit: 0, // min size
							onSubmit: function() {
							},
							onComplete: function(id, fileName, result) {
								pz_hidden = $("#'.$this->getHTMLId().' #'.$this->getFieldId().'");
								pz_image = $("#'.$this->getHTMLId().' label img");
								if(result.clipdata.id) {
					    			link = "'.pz::url("screen", "clipboard", "get", array( "mode"=>"image_src_raw", "image_size" => "xl", "image_type" => "image/jpg" )).'"+"&clip_id="+result.clipdata.id;
					    			$.post(link, "", function(data) {
					    				if(data != "") {
					    					pz_hidden.val("PHOTO;ENCODING=b;TYPE=JPEG:"+data);
											
											link = "'.pz::url("screen", "clipboard", "get", array( "mode"=>"image_inline", "image_size" => "m")).'"+"&clip_id="+result.clipdata.id;

											$.post(link, "", function(data) {
												if(data != "")
													pz_image.attr("src",data);		
											});
					    					
					    					
					    				}
								     });
					    			
						    	}
						    	window.setTimeout(function(){
									clearUploadListSuccess();
								}, 3000);
								
							},
							maxConnections: 1
							
						});
						
						uploaded_list = $("#pz_multiupload_'.$this->getId().' .qq-uploaded-list");
						uploader._filesInProgress = 0;           
					}
					jQuery(document).ready(function(){
						pz_createUploader_'.$this->getId().'();
					});
					</script>';

		$class = $this->getHTMLClass();
		$classes = $class;
		if ($this->getElement(5) != '') 
	  		$classes .= ' '.$this->getElement(5);
		if (isset($this->params["warning"][$this->getId()]))
			$classes .= ' '.$this->params["warning"][$this->getId()];
		
		$img = $this->getValue();
		if($img == "") 
			$img = $default_image_path;
		else
			$img = pz_address::makeInlineImage($img);
		
		$classes = (trim($classes) != '') ? ' class="'.trim($classes).'"' : '';
		$label = ($this->getElement(2) != '') ? '<label'.$classes.' for="' . $this->getFieldId() . '">'.
			'<img src="'.$img.'" title="' . rex_i18n::translate($this->getElement(2)) . '" width=40 height=40 />'.
			'</label>' : '';	
		$field = '<input id="'.$this->getFieldId().'" type="hidden" style="height:100px" name="'.$this->getFieldName().'" value="'.htmlspecialchars(stripslashes($this->getValue())).'" />'.$output;
		$html_id = $this->getHTMLId();
		$name = $this->getName();
		
		
		$f = new rex_fragment();
		$f->setVar('label', $label, false);
		$f->setVar('field', $field, false);
		$f->setVar('html_id', $html_id, false);
		$f->setVar('name', $name, false);
		$f->setVar('class', $class, false);
		
		$fragment = $this->params['fragment'];
		$this->params["form_output"][$this->getId()] = $f->parse($fragment);

		$this->params["value_pool"]["email"][$this->getElement(1)] = stripslashes($this->getValue());
		$this->params["value_pool"]["sql"][$this->getElement(1)] = $this->getValue();

		return;

	}

	function getDescription()
	{
		return "pz_image_screen -> Beispiel: pz_image_screen|label|Bezeichnung|";
	}

}

?>