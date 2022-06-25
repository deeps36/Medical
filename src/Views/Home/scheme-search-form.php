<?php

	//var_dump($connect);
	$outputFormFields = json_decode($fields);
	/*echo "<pre>";
	print_r($outputFormFields->text);
	echo"</pre>"; exit;*/
	if($outputFormFields->responseType === '1'){
		
		foreach($outputFormFields->text as $broadHead){
			//echo $broadHead;
			//echo"{$broadHead->subject_head_name}\t";
			$head = $broadHead->subject_head_name;
			$headId = $broadHead->subject_head_bh_id;
			$attrID = explode("delimit","{$broadHead->attributes_master_id}");
			$attrName = explode("delimit","{$broadHead->attributes_master_name}");
			$uiElement = explode("delimit","{$broadHead->attributes_master_ui_element}");
			$dataType = explode("delimit","{$broadHead->attributes_master_data_type}");
			$possibleValues = explode("delimit","{$broadHead->attributes_master_possible_values}");
			if(isset($broadHead->attributes_master_possible_values_en))
				$possibleValues_en = explode("delimit","{$broadHead->attributes_master_possible_values_en}");
			
			if(strtolower($headId) == 9){
?>
					<div class="row">
						<div class="medium-4 small-12 columns">
							<label><?php echo $GLOBALS['lang_state'];?>
								<select id="<?php echo $headId; ?>_state" required>
								<option value="" disabled selected><?php echo str_replace('{attr}', $GLOBALS['lang_state'], $GLOBALS['lang_select_attr']);?></option>
								<?php 
									$outputStates = json_decode($allStatesList);
									//print_r($outputStates->text);
									if($outputStates->responseType === '1'){
										foreach($outputStates->text as $statesList){
								?>			
											<option value="<?php echo $statesList->mc; ?>"><?php echo $statesList->name; ?></option>
								<?php	}
									}
								?>
								</select>
							</label>
						</div>
							<?php
								//echo sizeof($attrID);
								//print_r($attrName);
								$k =1;
								for($i=0;$i<sizeof($attrID);$i++){
									if($k == 3){
										$k =1;
										?> </div> <div class="row">
										<?php
									} else{
										$k++;
									}
									$uiElement[$i] = preg_replace('/\s+/', '', $uiElement[$i]);
									$dataType[$i] = preg_replace('/\s+/', '', $dataType[$i]);
									$attrID[$i] = preg_replace('/\s+/', '', $attrID[$i]);
									$uiElement[$i] = strtolower($uiElement[$i]);
									if($uiElement[$i] == 'textfield'){
										//case 'textfield':
							?>				<div class="medium-4 small-12 columns attribute-<?php echo $attrID[$i];?>">
												<label><?php echo ucfirst(trim($attrName[$i]));?>
													<input id="<?php echo $headId."_".$attrID[$i]; ?>" type="<?php echo $dataType[$i]; ?>" placeholder="<?php echo str_replace('{attr}', strtolower(trim($attrName[$i])), $GLOBALS['lang_type_attr']);?>" <?php if($dataType[$i] == "number"){ echo "min=\"1\"";} ?> required />
												</label>
											</div>
							<?php	} elseif($uiElement[$i] == 'select'){ ?>
											<div class="medium-4 small-12 columns attribute-<?php echo $attrID[$i];?>">
												<label><?php echo ucfirst(trim($attrName[$i])); ?>
													<select id="<?php echo $headId."_".$attrID[$i]; ?>" required>
													<option value="" disabled selected><?php echo str_replace('{attr}', strtolower(trim($attrName[$i])), $GLOBALS['lang_select_attr']);?></option>
													<?php 
														$options = explode(",",$possibleValues[$i]);
														if(isset($possibleValues_en)){
															$options_en = explode(",",$possibleValues_en[$i]);
															foreach($options as $index=>$option){
													?>			<option value="<?php echo trim($options_en[$index]); ?>"><?php echo trim($option); ?></option>
													<?php	} 
														} else{
															foreach($options as $option){
													?>			<option value="<?php echo trim($option); ?>"><?php echo trim($option); ?></option>
													<?php	}
														}
													?>
													</select>
												</label>
											</div>
							<?php	} elseif($uiElement[$i] == 'radio'){ ?>
											<fieldset class="medium-4 small-12 columns attribute-<?php echo $attrID[$i];?>">
												<label><?php echo ucfirst(trim($attrName[$i])); ?><legend></legend></label>
												<?php 
													$options = explode(",",$possibleValues[$i]);
													if(isset($possibleValues_en)){
														$options_en = explode(",",$possibleValues_en[$i]);
														foreach($options as $index=>$option){ ?>
															<input id="<?php echo $headId."_".$attrID[$i]."_".$index; ?>" name="<?php echo $headId."_".$attrID[$i]; ?>" type="radio" value="<?php echo trim($options_en[$index]);?>" required /><label for="<?php echo $headId."_".$attrID[$i]."_".$index; ?>"><?php echo ucfirst(trim($option)); ?></label>
												<?php	} 
													} else{
														foreach($options as $index=>$option){ ?>
															<input id="<?php echo $headId."_".$attrID[$i]."_".$index; ?>" name="<?php echo $headId."_".$attrID[$i]; ?>" type="radio" value="<?php echo trim($option);?>" required /><label for="<?php echo $headId."_".$attrID[$i]."_".$index; ?>"><?php echo ucfirst(trim($option)); ?></label>
												<?php	} 
													}
												?>
											</fieldset>
							<?php	} elseif($uiElement[$i] == 'textarea'){ ?>
										
							<?php	} elseif($uiElement[$i] == 'checkbox'){ ?>
											
							<?php	}
								} // for loop end
								//exit;
							?>
					</div>
			
<?php	
			} // if (general) END 
		} // foreach(broadhead) END
	} else{
		echo $outputFormFields->text;
	}
?>