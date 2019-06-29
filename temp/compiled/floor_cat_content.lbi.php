
<?php if ($this->_var['temp'] == 'floor_temp'): ?>
<ul class="p-list">
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['foo']['iteration']++;
?>
<li class="opacity_img">
    <div class="product">
        <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" width="140" height="140"></a></div>
        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></div>
        <div class="p-price">
            <span class="shop-price">
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                    <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                    <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
            </span>
            <span class="original-price"><?php echo $this->_var['goods']['market_price']; ?></span>
        </div>
    </div>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<?php elseif ($this->_var['temp'] == 'floor_temp_expand'): ?>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['foo']['iteration']++;
?>
<div class="p-list-item">
	<div class="product">
        <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" width="140" height="140"></a></div>
        <div class="p-name"><a href="<?php echo $this->_var['goods']['url']; ?>" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></a></div>
        <div class="p-price">
            <span class="shop-price">
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                    <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                    <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
            </span>
            <span class="original-price"><?php echo $this->_var['goods']['market_price']; ?></span>
        </div>
    </div>
</div>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
