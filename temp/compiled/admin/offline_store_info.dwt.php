<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><a href="<?php echo $this->_var['action_link']['href']; ?>" class="s-back"><?php echo $this->_var['lang']['back']; ?></a><?php echo $this->_var['lang']['19_self_support']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span></div>
                <ul>
                	<li><?php echo $this->_var['lang']['operation_prompt_content_common']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['info']['0']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['info']['1']; ?></li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="common-content">
                    <div class="mian-info">
                        <form method="post" action="offline_store.php" name="theForm" id="offline_store_form" enctype="multipart/form-data">
                            <div class="switch_info">
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_user']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text" name="stores_user" maxlength="60" size = "30" value="<?php echo $this->_var['offline_store']['stores_user']; ?>" />
                                    	<div class="form_prompt"></div>
                                    </div>
                                </div>
								<?php if ($this->_var['act'] == "insert"): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_pwd']; ?>：</div>
                                    <div class="label_value">
										<input type="password" style="display:none"/><input type="password" class="text" name="stores_pwd" id="stores_pwd" maxlength="60" size = "30"/>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['confirm_pwd']; ?>：</div>
                                    <div class="label_value">
										<input type="password" style="display:none"/><input type="password" class="text" name="confirm_pwd" id="confirm_pwd" maxlength="60" size = "30" />
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>								
								<?php elseif ($this->_var['act'] == "update"): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['newpass']; ?>：</div>
                                    <div class="label_value">
										<input type="password" style="display:none"/><input type="password" name="newpass" id="newpass" maxlength="20" size="20" class="text ignore" />
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['confirm_pwd']; ?>：</div>
                                    <div class="label_value">
										<input type="password" style="display:none"/><input type="password" name="newconfirm_pwd" id="newconfirm_pwd" maxlength="20" size="20" class="text ignore" />
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>								
								<?php endif; ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_name']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text" name="stores_name" maxlength="60" size = "30" value="<?php echo $this->_var['offline_store']['stores_name']; ?>" />
                                    	<div class="form_prompt"></div>
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['region_select']; ?>：</div>
                                    <div class="label_value">
										<div id="dlcountry" class="ui-dropdown smartdropdown alien mr15">
                                            <input type="hidden" value="<?php echo empty($this->_var['offline_store']['country']) ? '0' : $this->_var['offline_store']['country']; ?>" name="country" id="selcountry">
                                            <div class="txt"><?php echo $this->_var['lang']['country']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="1"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                        <div id="dlProvinces" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['offline_store']['province']; ?>" name="province" id="selProvinces">
                                            <div class="txt"><?php echo $this->_var['lang']['province_alt']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="2"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                        <div id="dlCity" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['offline_store']['city']; ?>" name="city" id="selCities">
                                            <div class="txt"><?php echo $this->_var['lang']['city']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="3"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                        <div id="dlRegion" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['offline_store']['district']; ?>" name="district" id="selDistricts">
                                            <div class="txt"><?php echo $this->_var['lang']['area_alt']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['districts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="4"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>	
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_address']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text" name="stores_address" maxlength="60" size = "30" value="<?php echo $this->_var['offline_store']['stores_address']; ?>" />
										<div class="notic"><?php echo $this->_var['lang']['stores_address_dsc']; ?></div>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_tel']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text" name="stores_tel" maxlength="60" size = "30" value="<?php echo $this->_var['offline_store']['stores_tel']; ?>" />
										<div class="notic"><?php echo $this->_var['lang']['stores_tel_dsc']; ?></div>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_email']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text" name="stores_email" maxlength="60" size = "30" value="<?php echo $this->_var['offline_store']['stores_email']; ?>" />
										<div class="notic"><?php echo $this->_var['lang']['stores_email_dsc']; ?></div>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['stores_opening_hours']; ?>：</div>
                                    <div class="label_value">
										<input type="text" class="text" name="stores_opening_hours" maxlength="60" size = "30" value="<?php echo $this->_var['offline_store']['stores_opening_hours']; ?>" />
										<div class="notic"><?php echo $this->_var['lang']['stores_opening_hours_dsc']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['stores_traffic_line']; ?>：</div>
                                    <div class="label_value">
										<textarea class="textarea" maxlength="50" rows="2" name="stores_traffic_line"><?php echo $this->_var['offline_store']['stores_traffic_line']; ?></textarea>
										<div class="notic"><?php echo $this->_var['lang']['stores_traffic_line_dsc']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['stores_img']; ?>：</div>
                                    <div class="label_value">
                                    	<div class="type-file-box">
                                            <input type="button" name="button" id="button" class="type-file-button" value=""/>
                                            <input type="file" class="type-file-file" id="stores_img" name="stores_img" data-state="imgfile" size="30" hidefocus="true" value="" />
                                            <?php if ($this->_var['offline_store']['stores_img']): ?>
                                            <span class="show">
                                                <a href="<?php echo htmlspecialchars($this->_var['offline_store']['stores_img']); ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="<?php echo htmlspecialchars($this->_var['offline_store']['stores_img']); ?>" ectype="tooltip" title="tooltip"></i></a>
                                            </span>
                                            <?php endif; ?>
                                            <input type="text" name="textfile" class="type-file-text" id="textfield" value="<?php if ($this->_var['offline_store']['stores_img']): ?><?php echo htmlspecialchars($this->_var['offline_store']['stores_img']); ?><?php endif; ?>" autocomplete="off" readonly />
                                        </div>
                                        <div class="notic"><?php echo $this->_var['lang']['stores_img_dsc']; ?></div>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['is_confirm']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_confirm" id="is_confirm_0" value="0" <?php if ($this->_var['offline_store']['is_confirm'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_confirm_0" class="ui-radio-label"><?php echo $this->_var['lang']['close']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="is_confirm" id="is_confirm_1" value="1" <?php if ($this->_var['offline_store']['is_confirm'] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="is_confirm_1" class="ui-radio-label"><?php echo $this->_var['lang']['open']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
										<input type="submit" class="button" name="sub" value="<?php echo $this->_var['lang']['button_submit']; ?>" id="submitBtn" />
										<input type="reset" class="button button_reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
										<input type="hidden" name="act" value="<?php echo $this->_var['act']; ?>"/>
										<input type="hidden" name="id" value="<?php echo $this->_var['offline_store']['id']; ?>"/>
										<input type="hidden" name="file_url" value="<?php echo $this->_var['offline_store']['stores_img']; ?>"/>
                                    </div>
                                </div>								
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
    </div>
 	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
	<script type="text/javascript" src="js/region.js"></script>
	<script type="text/javascript">
	var action = '<?php echo $this->_var['act']; ?>';
	$(function(){
		$.levelLink();
		$('.nyroModal').nyroModal();
		
		//表单验证
		$("#submitBtn").click(function(){
			if($("#offline_store_form").valid()){
				$("#offline_store_form").submit();
			}
		});
		
		$('#offline_store_form').validate({
			errorPlacement:function(error, element){
				var error_div = element.parents('div.label_value').find('div.form_prompt');
				element.parents('div.label_value').find(".notic").hide();
				error_div.html("").append(error);
			},
			ignore: ".ignore",
			rules:{
				stores_user :{
					required : true
				},
				stores_pwd:{
					required : true,
					minlength:6
				},
				confirm_pwd:{
					required : true,
					equalTo:"#stores_pwd"
				},
				stores_name:{
					required : true
				},
				stores_address:{
					required : true
				},
				textfile:{
					required : true
				},
				stores_tel : {
					required : true,
					isTel : true
				},
				stores_email:{
					required : true,
					email : true
				},
				country : {
					min : 1
				},
				province : {
					min : 1
				},
				city : {
					min : 1
				},
				district : {
					min : 1
				}
			},
			messages:{
				stores_user:{
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_user_null
				},
				stores_pwd:{
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_pwd_null,
					minlength : '<i class="icon icon-exclamation-sign"></i>'+pwd_not_six
				},
				confirm_pwd:{
					required : '<i class="icon icon-exclamation-sign"></i>'+confirm_pwd_null,
					equalTo:'<i class="icon icon-exclamation-sign"></i>'+confirm_pwd_equal
				},
				stores_name:{
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_name_null
				},
				stores_address:{
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_address_null
				},
				textfile:{
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_img_null
				},
				stores_tel : {
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_tel_null,
					isTel : '<i class="icon icon-exclamation-sign"></i>'+stores_tel_error,
				},
				stores_email:{
					required : '<i class="icon icon-exclamation-sign"></i>'+stores_email_null,
					email : '<i class="icon icon-exclamation-sign"></i>'+stores_email_error
				},
				country : {
					min : '<i class="icon icon-exclamation-sign"></i>'+country_null
				},
				province : {
					min : '<i class="icon icon-exclamation-sign"></i>'+province_null
				},
				city : {
					min : '<i class="icon icon-exclamation-sign"></i>'+city_null
				},
				district : {
					min : '<i class="icon icon-exclamation-sign"></i>'+district_null
				}
			}			
		});
	});
	</script>
</body>
</html>
