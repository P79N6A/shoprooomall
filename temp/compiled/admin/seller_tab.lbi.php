<div class="tabs_info">
    <ul>
        <li <?php if ($this->_var['menu_select']['current'] == '02_merchants_users_list'): ?>class="curr"<?php endif; ?>>
            <a href="merchants_users_list.php?act=list"><?php echo $this->_var['lang']['02_merchants_users_list']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == '16_seller_users_real'): ?>class="curr"<?php endif; ?>>
            <a href="user_real.php?act=list&user_type=1"><?php echo $this->_var['lang']['16_users_real']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == '11_seller_apply'): ?>class="curr"<?php endif; ?>>
            <a href="seller_apply.php?act=list"><?php echo $this->_var['lang']['11_seller_apply']; ?></a>
        </li>
    </ul>
</div>	