<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><a href="javascript:;" ectype="goback" class="s-back"></a><?php echo $this->_var['lang']['19_merchants_store']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
			<?php echo $this->fetch('library/seller_manage_tab.lbi'); ?>
            <div class="flexilist">
                <div class="common-content">
                    <div class="mian-info">
						<form method="POST" action="merchants_users_list.php" name="theFrom" >
                    	<div class="switch_info business_info" style="background:none;">
                            <div class="step">
								<div class="items">
									<div class="item">
										<div class="label"><?php echo $this->_var['lang']['login_name']; ?>：</div>
										<div class="value"><input name="login_name" type="text" value="<?php echo $this->_var['user_name']; ?>" class="text" /></div>
									</div>
									<div class="item">
										<div class="label"><?php echo $this->_var['lang']['login_passwarp']; ?>：</div>
										<div class="value"><input type="password"   style="display:none"/> <input name="login_password" type="password" id="password_val" value="<?php echo $this->_var['password']; ?>" class="text" /></div>
									</div>		
								</div>
                            </div>
                            <?php $_from = $this->_var['priv_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'priv');if (count($_from)):
    foreach ($_from AS $this->_var['priv']):
?>
                            <?php if ($this->_var['priv']['priv']): ?>
                            <div class="step">
                            	<div class="tit">
                                    <div class="checkbox_items">
                                    	<div class="checkbox_item">
                                            <input type="checkbox" name="chkGroup" value="checkbox" class="ui-checkbox" id="chkGroup_<?php echo $this->_var['priv']['action_id']; ?>" />
                                            <label for="chkGroup_<?php echo $this->_var['priv']['action_id']; ?>" class="ui-label blod"><?php echo $this->_var['lang'][$this->_var['priv']['action_code']]; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="qx_items">
                                	<div class="qx_item">
                                        <div class="checkbox_items">
                                            <?php $_from = $this->_var['priv']['priv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('priv_list', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['priv_list'] => $this->_var['list']):
?>
                                            <div class="checkbox_item">
                                                <input type="checkbox" value="<?php echo $this->_var['priv_list']; ?>" name="action_code[]" class="ui-checkbox" id="<?php echo $this->_var['priv_list']; ?>_<?php echo $this->_var['priv']['action_id']; ?>" date="<?php echo $this->_var['priv']['action_code']; ?>" <?php if ($this->_var['list']['cando'] == 1): ?> checked="true" <?php endif; ?> title="<?php echo $this->_var['list']['relevance']; ?>"/>
                                                <label for="<?php echo $this->_var['priv_list']; ?>_<?php echo $this->_var['priv']['action_id']; ?>" class="ui-label"><?php echo $this->_var['lang'][$this->_var['list']['action_code']]; ?></label>
                                            </div>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <div class="steplast">
                            	<div class="checkbox_items">
                                	<div class="checkbox_item">
                                    	<input type="checkbox" name="checkall" value="checkbox" class="ui-checkbox" id="checkall" />
                                        <label for="checkall" class="ui-label"><?php echo $this->_var['lang']['check_all']; ?></label>
                                    </div>
                                </div>
                                <div class="info_btn">
								    <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" class="button" />
								    <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
								    <input type="hidden" name="admin_id" value="<?php echo $this->_var['admin_id']; ?>" />
								    <input type="hidden" name="user_id" value="<?php echo $this->_var['user_id']; ?>" />
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
	<script language="javascript">
	$("#checkall").click(function(){
		var checkbox = $(this).parents(".switch_info").find('input:checkbox[type="checkbox"]');
		if($(this).prop("checked") == true){
			checkbox.prop("checked",true);
		}else{
			checkbox.prop("checked",false);
		}
	});
	
	$("input[name='chkGroup']").click(function(){
		var checkbox = $(this).parents(".tit").next(".qx_items").find('input:checkbox[type="checkbox"]');
		if($(this).prop("checked") == true){
			checkbox.prop("checked",true);
		}else{
			checkbox.prop("checked",false);
		}
	});
	
	$("input[name='action_code[]']").click(function(){    
		var qx_items = $(this).parents(".qx_items");
		var length = qx_items.find("input[name='action_code[]']").length;
		var length2 =  qx_items.find("input[name='action_code[]']:checked").length;
		if(length == length2){
			qx_items.prev().find("input[name='chkGroup']").prop("checked",true);
		}else{
			qx_items.prev().find("input[name='chkGroup']").prop("checked",false);
		}
	});
	
	$(".qx_items").each(function(index, element) {
		var length = $(this).find("input[name='action_code[]']").length;
		var length2 = $(this).find("input[name='action_code[]']:checked").length;
		
		if(length == length2){
			$(this).prev().find("input[name='chkGroup']").prop("checked",true);
		}else{
			$(this).prev().find("input[name='chkGroup']").prop("checked",false);
		}
	});
    </script>
</body>
</html>
