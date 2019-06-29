<div class="select">
    <?php if ($this->_var['common_tabs']['info']): ?>
	<div class="fl" id="steps_shop_name"><?php echo $this->_var['lang']['steps_shop_name']; ?>：</div>
	<div id="" class="imitate_select select_w170">
		<div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
		<ul>
			<li><a href="javascript:get_store_search(-1);" data-value="-1" class="ftx-01"><?php echo $this->_var['lang']['select_please']; ?></a></li>
			<!--<li id="store_0"><a href="javascript:get_store_search(0);" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['platform_self']; ?></a></li>-->
			<li><a href="javascript:get_store_search(1);" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['s_shop_name']; ?></a></li>
			<li><a href="javascript:get_store_search(2);" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['s_qw_shop_name']; ?></a></li>
			<li><a href="javascript:get_store_search(3);" data-value="3" class="ftx-01"><?php echo $this->_var['lang']['s_brand_type']; ?></a></li>
		</ul>
		<input name="store_search" type="hidden" value="-1" id="">
	</div>
    <?php endif; ?>
	<div id="" class="imitate_select select_w170" style="display:none">
		<div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
		<ul>
			<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
			<?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
			<li><a href="javascript:;" data-value="<?php echo $this->_var['store']['ru_id']; ?>" class="ftx-01"><?php echo $this->_var['store']['store_name']; ?></a></li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<input name="merchant_id" type="hidden" value="0" id="">
	</div>
	<input name="store_keyword" type="text" style="display:none" class="text text_2"/>
	<div id="" class="imitate_select select_w170" style="display:none">
		<div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
		<ul>
			<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['steps_shop_type']; ?></a></li>
			<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['flagship_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['flagship_store']; ?></a></li>
			<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['exclusive_shop']; ?>" class="ftx-01"><?php echo $this->_var['lang']['exclusive_shop']; ?></a></li>
			<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['franchised_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['franchised_store']; ?></a></li>
			<li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['shop_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['shop_store']; ?></a></li>
		</ul>
		<input name="store_type" type="hidden" value="0" id="">
	</div>							
</div>

<script>
<?php if ($this->_var['priv_ru'] == 1): ?>
function get_store_search(val){
	if(val == 1){
		$("input[name=merchant_id]").parent(".imitate_select").show();
		$("input[name=store_keyword]").hide();
		$("input[name=store_type]").parent(".imitate_select").hide();
	}else if(val == 2){
		$("input[name=merchant_id]").parent(".imitate_select").hide();
		$("input[name=store_keyword]").show();
		$("input[name=store_type]").parent(".imitate_select").hide();			
	}else if(val == 3){
		$("input[name=merchant_id]").parent(".imitate_select").hide();
		$("input[name=store_keyword]").show();
		$("input[name=store_type]").parent(".imitate_select").show();			
	}else{
		$("input[name=merchant_id]").parent(".imitate_select").hide();
		$("input[name=store_keyword]").hide();
		$("input[name=store_type]").parent(".imitate_select").hide();			
	}
}
<?php endif; ?>
</script>