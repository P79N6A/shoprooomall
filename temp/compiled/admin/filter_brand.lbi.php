<div class="brand-top">
	<div class="letter">
		<ul>
			<li><a href="javascript:void(0);" data-letter=""><?php echo $this->_var['lang']['all_brand']; ?></a></li>
			<?php $_from = $this->_var['letter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'letter_0_78352600_1553747973');if (count($_from)):
    foreach ($_from AS $this->_var['letter_0_78352600_1553747973']):
?>
            <li><a href="javascript:void(0);" data-letter="<?php echo $this->_var['letter_0_78352600_1553747973']; ?>"><?php echo $this->_var['letter_0_78352600_1553747973']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			<li><a href="javascript:void(0);" data-letter="QT"><?php echo $this->_var['lang']['other']; ?></a></li>
		</ul>
	</div>
	<div class="b_search">
		<input name="search_brand_keyword" id="search_brand_keyword" type="text" class="b_text" placeholder="<?php echo $this->_var['lang']['brand_name_keywords_search']; ?>" autocomplete="off" />
		<a href="javascript:void(0);" class="btn-mini"><i class="icon icon-search"></i></a>
	</div>
</div>
<div class="brand-list">
	<?php echo $this->fetch('library/search_brand_list.lbi'); ?>
</div>
<div class="brand-not" style="display:none;"><?php echo $this->_var['lang']['no_accord_with']; ?>"<strong><?php echo $this->_var['lang']['other']; ?></strong>"<?php echo $this->_var['lang']['condition_brand']; ?></div>