
<?php if ($this->_var['user_id']): ?>
	<a href="<?php echo $this->_var['site_domain']; ?>user.php" class="nick_name"><?php echo $this->_var['info']['nick_name']; ?></a>
<?php else: ?>
	<a href="<?php echo $this->_var['site_domain']; ?>user.php"><?php echo $this->_var['lang']['login']; ?></a>
    <?php if ($this->_var['shop_reg_closed'] != 1): ?>|
    <a href="user.php?act=register"><?php echo $this->_var['lang']['register']; ?></a>
    <?php endif; ?>
<?php endif; ?>

    