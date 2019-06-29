<?php if ($this->_var['seckill_goods']): ?>
<?php if ($this->_var['ajax_seckill'] != 1): ?>
<div class="seckill-channel" id="h-seckill">
	<div class="box-hd clearfix">
    	<i class="box_hd_arrow"></i>
    	<i class="box_hd_dec"></i>
		<i class="box-hd-icon"></i>
		<div class="sk-time-cd">
			<div class="sk-cd-tit"><?php if ($this->_var['sec_begin_time']): ?><?php echo $this->_var['lang']['ju_start']; ?><?php else: ?><?php echo $this->_var['lang']['ju_end']; ?><?php endif; ?></div>
			<div class="cd-time fl" ectype="time" data-time='<?php if ($this->_var['sec_begin_time']): ?><?php echo $this->_var['sec_begin_time']; ?><?php else: ?><?php echo $this->_var['sec_end_time']; ?><?php endif; ?>'>
				<div class="days hide"></div>
				<span class="split hide"><?php echo $this->_var['lang']['day']; ?></span>
				<div class="hours"></div>
				<span class="split"><?php echo $this->_var['lang']['goods_js']['time']; ?></span>
				<div class="minutes"></div>
				<span class="split"><?php echo $this->_var['lang']['goods_js']['branch']; ?></span>
				<div class="seconds"></div>
				<span class="split"><?php echo $this->_var['lang']['goods_js']['second']; ?></span>
			</div>
		</div>
        <div class="sk-more"><a href="<?php echo $this->_var['url_seckill']; ?>" target="_blank"><?php echo $this->_var['lang']['more_secKill']; ?></a> <i class="arrow"></i></div>
	</div>
	<div class="box-bd clearfix">
    	<?php endif; ?>
		<ul>
			<?php $_from = $this->_var['seckill_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
            <?php if ($this->_var['temp'] == 'backup_festival_1'): ?>
            <li class="opacity_img">
                <div class="p-img"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></a></div>
                <div class="p-name"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
                <div class="p-price">
                    <span class="shop-price"><?php echo $this->_var['goods']['sec_price']; ?></span>
                    <span class="original-price"><?php echo $this->_var['goods']['market_price']; ?></span>
                </div>
            </li>
            <?php else: ?>
			<li class="opacity_img">
				<div class="p-img"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" class="img-lazyload"></a></div>
				<div class="p-name"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
				<div class="p-over">
					<div class="p-info">
						<div class="p-price">
							<span class="shop-price"><?php echo $this->_var['goods']['sec_price']; ?></span>
							<span class="original-price"><?php echo $this->_var['goods']['market_price']; ?></span>
						</div>
					</div>
					<div class="p-btn">
					<?php if ($this->_var['sec_begin_time']): ?>
					<a href="<?php echo $this->_var['goods']['url']; ?>"  target="_blank"><?php echo $this->_var['lang']['begin_minute']; ?></a>
					<?php else: ?>
					<?php if ($this->_var['goods']['sec_num'] <= 0): ?>
					<a href="javascript:;"><?php echo $this->_var['lang']['has_been_snatched']; ?></a>
					<?php else: ?>
					<a href="<?php echo $this->_var['goods']['url']; ?>"  target="_blank"><?php echo $this->_var['lang']['immediate_rush_buy']; ?></a>
					<?php endif; ?>
					<?php endif; ?>
					</div>
				</div>
			</li>
            <?php endif; ?>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
        <a href="javascript:void(0);" class="prev"><i class="iconfont icon-left"></i></a>
        <a href="javascript:void(0);" class="next"><i class="iconfont icon-right"></i></a>
        <input type="hidden" name="sec_begin_time" value="<?php echo $this->_var['sec_begin_time']; ?>">
        <input type="hidden" name="sec_end_time" value="<?php echo $this->_var['sec_end_time']; ?>">
        <?php if ($this->_var['ajax_seckill'] != 1): ?>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($this->_var['ajax_seckill'] != 1): ?>
<input type="hidden" value="<?php if ($this->_var['seckill_goods']): ?>1<?php else: ?>0<?php endif; ?>" name="seckill_goods"/>
<?php endif; ?>