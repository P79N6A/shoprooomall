
<?php if ($this->_var['type'] == 'topic'): ?>  
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
<li>
    <div class="img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_img']; ?>"></a></div>
    <div class="info">
        <div class="name"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
            <div class="price">
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                    <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                    <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
            </div>
        <div class="btn_hover"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['lang']['button_buy']; ?></a></div>
    </div>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

<?php elseif ($this->_var['type'] == 'seller'): ?>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
<li>
        <div class="img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_img']; ?>"></a></div>
        <div class="info">
            <div class="name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
            <div class="price">
                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                        <?php echo $this->_var['goods']['promote_price']; ?>
                    <?php else: ?>
                        <?php echo $this->_var['goods']['shop_price']; ?>
                    <?php endif; ?>
            </div>
            <div class="btn_hover"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['lang']['button_buy']; ?></a></div>
        </div>
    </li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php elseif ($this->_var['type'] == 'home_rank'): ?>

<div class="com-ul" >
    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['list']['iteration']++;
?>
    <?php if ($this->_foreach['list']['iteration'] < 4): ?>
    <div class="com-li">
        <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
            <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
            <div class="p-name"><?php echo $this->_var['goods']['goods_name']; ?></div>
            <div class="p-price">
                <?php if ($this->_var['activitytype'] == 'exchange'): ?>
                <?php echo $this->_var['goods']['exchange_integral']; ?>
                <?php else: ?>
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <i class="ph-icon ph-icon<?php echo $this->_foreach['list']['iteration']; ?>"><?php echo $this->_foreach['list']['iteration']; ?></i>
        </a>
    </div>
    <?php endif; ?>
    <?php endforeach; else: ?>
    <div class="com-li">
        <a href="#" target="_blank">
            <div class="p-img"><img src="data/gallery_album/visualDefault/zhanwei.png"></div>
            <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
            <div class="p-price"><em>¥</em>8388.00</div>
            <i class="ph-icon ph-icon2">2</i>
        </a>
    </div>
    <div class="com-li">
        <a href="#" target="_blank">
            <div class="p-img"><img src="data/gallery_album/visualDefault/zhanwei.png"></div>
            <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
            <div class="p-price"><em>¥</em>8388.00</div>
            <i class="ph-icon ph-icon2">2</i>
        </a>
    </div>
    <div class="com-li">
        <a href="#" target="_blank">
            <div class="p-img"><img src="data/gallery_album/visualDefault/zhanwei.png"></div>
            <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
            <div class="p-price"><em>¥</em>8388.00</div>
            <i class="ph-icon ph-icon3">3</i>
        </a>
    </div>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<div class="com-ul">
    <?php if ($this->_var['goods_list']): ?>
    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['list']['iteration']++;
?>
    <?php if ($this->_foreach['list']['iteration'] > 3): ?>
    <div class="com-li">
        <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
            <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
            <div class="p-name"><?php echo $this->_var['goods']['goods_name']; ?></div>
            <div class="p-price">
                <?php if ($this->_var['activitytype'] == 'exchange'): ?>
                <?php echo $this->_var['goods']['exchange_integral']; ?>
                <?php else: ?>
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <i class="ph-icon ph-icon<?php echo $this->_foreach['list']['iteration']; ?>"><?php echo $this->_foreach['list']['iteration']; ?></i>
        </a>
    </div>
    <?php endif; ?>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php else: ?>
    <div class="com-li">
        <a href="#" target="_blank">
            <div class="p-img"><img src="data/gallery_album/visualDefault/zhanwei.png"></div>
            <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
            <div class="p-price"><em>¥</em>8388.00</div>
            <i class="ph-icon ph-icon4">4</i>
        </a>
    </div>
    <div class="com-li">
        <a href="#" target="_blank">
            <div class="p-img"><img src="data/gallery_album/visualDefault/zhanwei.png"></div>
            <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
            <div class="p-price"><em>¥</em>8388.00</div>
            <i class="ph-icon ph-icon5">5</i>
        </a>
    </div>
    <div class="com-li">
        <a href="#" target="_blank">
            <div class="p-img"><img src="data/gallery_album/visualDefault/zhanwei.png"></div>
            <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
            <div class="p-price"><em>¥</em>8388.00</div>
            <i class="ph-icon ph-icon6">6</i>
        </a>
    </div>
    <?php endif; ?>
</div>

<?php else: ?>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
<li class="opacity_img">
    <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
        <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
        <div class="p-name" title="<?php echo $this->_var['goods']['goods_name']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></div>
        <div class="p-price">
            <div class="shop-price">
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
            </div>
            <div class="original-price"><?php echo $this->_var['goods']['market_price']; ?></div>
        </div>
    </a>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
