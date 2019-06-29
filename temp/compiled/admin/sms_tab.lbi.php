<div class="tabs_info">
    <ul>
    	<li <?php if ($this->_var['menu_select']['current'] == '01_sms_setting'): ?>class="curr"<?php endif; ?>><a href="sms_setting.php?act=step_up"><?php echo $this->_var['lang']['01_sms_setting']; ?></a></li>
        <li <?php if ($this->_var['menu_select']['current'] == 'dscsms_configure'): ?>class="curr"<?php endif; ?>><a href="dscsms_configure.php?act=list"><?php echo $this->_var['lang']['dscsms_configure']; ?></a></li>
        <li <?php if ($this->_var['menu_select']['current'] == 'alidayu_configure'): ?>class="curr"<?php endif; ?>><a href="alidayu_configure.php?act=list"><?php echo $this->_var['lang']['alidayu_configure']; ?></a></li>
        <li <?php if ($this->_var['menu_select']['current'] == 'alitongxin_configure'): ?>class="curr"<?php endif; ?>><a href="alitongxin_configure.php?act=list"><?php echo $this->_var['lang']['alitongxin_configure']; ?></a></li>
        <li <?php if ($this->_var['menu_select']['current'] == 'huyi_configure'): ?>class="curr"<?php endif; ?>><a href="huyi_configure.php?act=list"><?php echo $this->_var['lang']['huyi_configure']; ?></a></li>
    </ul>
</div>