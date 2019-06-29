<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php echo $this->_var['lang']['19_merchants_store']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<?php echo $this->fetch('library/seller_step_tab.lbi'); ?>	
            
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
									<input name="type" type="hidden" value="seller_setup">
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
</body>
</html>
