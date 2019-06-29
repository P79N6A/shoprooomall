<div class="admin-main-left">
    <div class="admincj_nav">
        <?php $_from = $this->_var['nav_top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_48862400_1553749888');if (count($_from)):
    foreach ($_from AS $this->_var['nav_0_48862400_1553749888']):
?>
        <div class="navLeftTab" id="adminNavTabs_<?php echo $this->_var['nav_0_48862400_1553749888']['type']; ?>" style="display:<?php if ($this->_var['nav_0_48862400_1553749888']['type'] == 'home'): ?>block<?php else: ?>none<?php endif; ?>;">
            <?php $_from = $this->_var['nav_0_48862400_1553749888']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'menu');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['menu']):
        $this->_foreach['foo']['iteration']++;
?>
            <?php if (! $this->_var['menu']['action']): ?>
            <div class="item<?php if ($this->_var['k'] == '02_promotion'): ?> fold_item<?php endif; ?>">
                <div class="tit"><a href="javascript:void(0)"><i class="nav_icon icon_<?php echo $this->_var['k']; ?>"></i><h4<?php if ($this->_var['k'] == '24_wxapp'): ?> class="txt_tx"<?php endif; ?>><?php echo $this->_var['menu']['label']; ?></h4></a></div>
                <?php if ($this->_var['menu']['children']): ?>
                <div class="sub-menu"<?php if ($this->_var['nav_0_48862400_1553749888']['type'] == 'home'): ?> style="display:block;"<?php endif; ?>>
                    <ul>
                        <?php $_from = $this->_var['menu']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'child');$this->_foreach['childfoo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['childfoo']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['child']):
        $this->_foreach['childfoo']['iteration']++;
?>
                        <li <?php if (($this->_foreach['childfoo']['iteration'] <= 1)): ?>class="curr"<?php endif; ?>><s></s><?php if ($this->_var['key'] == '03_visualnews'): ?><a href="<?php echo $this->_var['child']['action']; ?>" target="_blank" ><?php echo $this->_var['child']['label']; ?></a><?php else: ?><a href="javascript:void(0);" data-url="<?php echo $this->_var['child']['action']; ?>" data-param="<?php echo $this->_var['nav_0_48862400_1553749888']['type']; ?>|<?php echo $this->_var['key']; ?>" target="workspace" ><?php echo $this->_var['child']['label']; ?></a><?php endif; ?></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
</div>