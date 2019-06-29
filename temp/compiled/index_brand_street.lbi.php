
<?php if ($this->_var['recommend_brands']): ?>
<?php if ($this->_var['temp'] == 'backup_festival_1'): ?>
	<ul>
		<?php $_from = $this->_var['recommend_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['brand']):
?>
		<li>
			<div class="brand-img"><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>"></a></div>
			<div class="brand-mash">
				<div data-bid="<?php echo $this->_var['brand']['brand_id']; ?>" ectype="coll_brand"><i class="iconfont <?php if ($this->_var['brand']['is_collect']): ?>icon-zan-alts<?php else: ?>icon-zan-alt<?php endif; ?>"></i></div>
				<div class="coupon"><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><span><?php echo $this->_var['lang']['follow_number']; ?><em id="collect_count_<?php echo $this->_var['brand']['brand_id']; ?>"><?php echo empty($this->_var['brand']['collect_count']) ? '0' : $this->_var['brand']['collect_count']; ?></em></span><div class="click-enter"><?php echo $this->_var['lang']['click_getinfo']; ?></div></div></a></div>
			</div>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</ul>
	<input type="hidden" name="user_id" value="<?php echo $this->_var['user_id']; ?>">
	<a href="javascript:void(0);" ectype="changeBrand" class="refresh-btn"><i class="iconfont icon-rotate-alt"></i><span><?php echo $this->_var['lang']['change_a_lot']; ?></span></a>
<?php else: ?>
<div class="brand-list" id="recommend_brands">
	<ul>
		<?php $_from = $this->_var['recommend_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['brand']):
?>
		<li>
			<div class="brand-img"><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>"></a></div>
			<div class="brand-mash">
				<div data-bid="<?php echo $this->_var['brand']['brand_id']; ?>" ectype="coll_brand"><i class="iconfont <?php if ($this->_var['brand']['is_collect']): ?>icon-zan-alts<?php else: ?>icon-zan-alt<?php endif; ?>"></i></div>
				<div class="coupon"><a href="<?php echo $this->_var['brand']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['follow_number']; ?><br><div id="collect_count_<?php echo $this->_var['brand']['brand_id']; ?>"><?php echo empty($this->_var['brand']['collect_count']) ? '0' : $this->_var['brand']['collect_count']; ?></div></a></div>
			</div>
		</li>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	</ul>
	<input type="hidden" name="user_id" value="<?php echo $this->_var['user_id']; ?>">
	<a href="javascript:void(0);" ectype="changeBrand" class="refresh-btn"><i class="iconfont icon-rotate-alt"></i><span><?php echo $this->_var['lang']['change_a_lot']; ?></span></a>
</div>
<?php endif; ?>
<?php endif; ?>