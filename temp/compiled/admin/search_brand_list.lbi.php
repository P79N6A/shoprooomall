<?php if ($this->_var['filter_brand_list']): ?>
<ul>
	<li data-id="0" data-name="<?php echo $this->_var['lang']['select_barnd']; ?>" class="blue"><?php echo $this->_var['lang']['cancel_select']; ?></li>
	<?php $_from = $this->_var['filter_brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'brands');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['brands']):
?>
	<li data-id="<?php echo $this->_var['brands']['brand_id']; ?>" data-name="<?php echo $this->_var['brands']['brand_name']; ?>"><em><?php echo $this->_var['brands']['letter']; ?></em><?php echo $this->_var['brands']['brand_name']; ?></li>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php endif; ?>