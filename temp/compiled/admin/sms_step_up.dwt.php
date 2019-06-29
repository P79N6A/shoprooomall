<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php echo $this->_var['lang']['14_sms']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<?php echo $this->fetch('library/sms_tab.lbi'); ?>	
            
            <div class="flexilist">
                <div class="mian-info">
                    <form enctype="multipart/form-data" name="theForm" action="shop_config.php?act=post" method="post" id="shopConfigForm">
                        <div class="switch_info">
                            <?php $_from = $this->_var['group_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'var');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['var']):
?>
                                <?php echo $this->fetch('library/shop_config_form.lbi'); ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <div class="item">
                                <div class="label">&nbsp;</div>
                                <div class="label_value info_btn">
									<input name="type" type="hidden" value="sms_setup">
                                    <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" ectype="btnSubmit" class="button" >	
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>	
		</div>
	</div>

	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
    
    <?php echo $this->smarty_insert_scripts(array('files'=>'jquery.purebox.js')); ?>
    
    
	<script type="text/javascript">
	$(function(){
		/*控制短信接口显示*/
		var id = $(".sms_type").data('val');
		$("form[name='theForm'] :input[name='value[" + id + "]']").each(function(index, element) {
			if($(element).is(':checked')){
				if($(element).val() == 0){
					$(".ali_appkey").hide();
					$(".ali_secretkey").hide();
					
					$(".access_key_id").hide();
					$(".access_key_secret").hide();
					
					$(".dsc_appkey").hide();
					$(".dsc_appsecret").hide();
				}else if($(element).val() == 1){
					$(".sms_ecmoban_password").hide();
					$(".sms_ecmoban_user").hide();
					
					$(".access_key_id").hide();
					$(".access_key_secret").hide();
					
					$(".dsc_appkey").hide();
					$(".dsc_appsecret").hide();
				}else if($(element).val() == 2){
					$(".ali_appkey").hide();
					$(".ali_secretkey").hide();
					
					$(".sms_ecmoban_password").hide();
					$(".sms_ecmoban_user").hide();
					
					$(".dsc_appkey").hide();
					$(".dsc_appsecret").hide();
				}else if($(element).val() == 3){
					$(".ali_appkey").hide();
					$(".ali_secretkey").hide();
					
					$(".sms_ecmoban_password").hide();
					$(".sms_ecmoban_user").hide();
					
					$(".access_key_id").hide();
					$(".access_key_secret").hide();
				}
			}
		});

		$(".evnet_sms_type").change(function(){
			var T = $(this);
			var val = T.val();
			if(val == 1){
				$(".sms_ecmoban_password").hide();
				$(".sms_ecmoban_user").hide();
				$(".access_key_id").hide();
				$(".access_key_secret").hide();
				$(".dsc_appkey").hide();
				$(".dsc_appsecret").hide();
				
				$(".ali_appkey").show();
				$(".ali_secretkey").show();
			}else if(val == 2){
				$(".sms_ecmoban_password").hide();
				$(".sms_ecmoban_user").hide();
				$(".ali_appkey").hide();
				$(".ali_secretkey").hide();
				$(".dsc_appkey").hide();
				$(".dsc_appsecret").hide();
				
				$(".access_key_id").show();
				$(".access_key_secret").show();
			}else if(val == 3){
				$(".sms_ecmoban_password").hide();
				$(".sms_ecmoban_user").hide();
				$(".ali_appkey").hide();
				$(".ali_secretkey").hide();
				$(".access_key_id").hide();
				$(".access_key_secret").hide();
				
				$(".dsc_appkey").show();
				$(".dsc_appsecret").show();
			}else{
				$(".ali_appkey").hide();
				$(".ali_secretkey").hide();
				$(".access_key_id").hide();
				$(".access_key_secret").hide();
				$(".dsc_appkey").hide();
				$(".dsc_appsecret").hide();
				
				$(".sms_ecmoban_password").show();
				$(".sms_ecmoban_user").show();
			}
		});
	});
	</script>
    
</body>
</html>
