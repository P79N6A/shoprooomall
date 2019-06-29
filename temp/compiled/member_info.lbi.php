
<?php if ($this->_var['user_info']): ?>
<span><?php echo $this->_var['lang']['hello']; ?> &nbsp;<a href="<?php echo $this->_var['site_domain']; ?>user.php"><?php echo $this->_var['user_info']['nick_name']; ?></a></span>
<span>，<?php echo $this->_var['lang']['Welcome_to']; ?>&nbsp;<a alt="<?php echo $this->_var['lang']['home']; ?>" title="<?php echo $this->_var['lang']['home']; ?>" href="index.php"><?php echo $this->_var['shop_name']; ?></a></span>
<span>[<a href="<?php echo $this->_var['site_domain']; ?>user.php?act=logout"><?php echo $this->_var['lang']['user_logout']; ?></a>]</span>
<?php else: ?>
	<a href="<?php echo $this->_var['site_domain']; ?>user.php" class="link-login red"><?php echo $this->_var['lang']['please_login']; ?></a>
	<?php if ($this->_var['shop_reg_closed'] != 1): ?>
    <a href="user.php?act=register" class="link-regist"><?php echo $this->_var['lang']['zhuce']; ?></a>
    <?php endif; ?>
<?php endif; ?>