<div class="tabs_info">
    <ul>
        <li <?php if ($this->_var['menu_select']['current'] == '01_seller_stepup'): ?>class="curr"<?php endif; ?>>
            <a href="merchants_steps.php?act=step_up"><?php echo $this->_var['lang']['01_seller_stepup']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == '01_merchants_steps_list'): ?>class="curr"<?php endif; ?>>
            <a href="merchants_steps.php?act=list"><?php echo $this->_var['lang']['01_merchants_steps_list']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == '03_users_merchants_priv'): ?>class="curr"<?php endif; ?>>
            <a href="merchants_privilege.php?act=allot"><?php echo $this->_var['lang']['enter_init_power']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == '10_seller_grade'): ?>class="curr"<?php endif; ?>>
            <a href="seller_grade.php?act=list"><?php echo $this->_var['lang']['shop_level']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == 'template_mall'): ?>class="curr"<?php endif; ?>>
            <a href="template_mall.php?act=list"><?php echo $this->_var['lang']['template_mall']; ?></a>
        </li>
        <li <?php if ($this->_var['menu_select']['current'] == 'template_apply_list'): ?>class="curr"<?php endif; ?>>
            <a href="template_mall.php?act=template_apply_list"><?php echo $this->_var['lang']['template_order']; ?></a>
        </li>
    </ul>
</div>	