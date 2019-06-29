<?php $_from = $this->_var['priv_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'priv');if (count($_from)):
    foreach ($_from AS $this->_var['priv']):
?>
<?php if ($this->_var['priv']['priv']): ?>
<div class="step">
    <div class="tit">
        <div class="checkbox_items">
            <div class="checkbox_item">
                <input type="checkbox" name="chkGroup" value="checkbox" class="ui-checkbox" id="chkGroup_<?php echo $this->_var['priv']['action_id']; ?>"/>
                <label for="chkGroup_<?php echo $this->_var['priv']['action_id']; ?>" class="ui-label blod"><?php echo $this->_var['lang'][$this->_var['priv']['action_code']]; ?></label>
            </div>
        </div>
    </div>
    <div class="qx_items">
            <div class="qx_item">
            <div class="checkbox_items">
                <?php $_from = $this->_var['priv']['priv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('priv_list', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['priv_list'] => $this->_var['list']):
?>
                <div class="checkbox_item">
                    <input type="checkbox" value="<?php echo $this->_var['priv_list']; ?>" name="action_code[]" class="ui-checkbox" id="<?php echo $this->_var['priv_list']; ?>_<?php echo $this->_var['priv']['action_id']; ?>" date="<?php echo $this->_var['priv']['action_code']; ?>" <?php if ($this->_var['list']['cando'] == 1): ?> checked="true" <?php endif; ?> title="<?php echo $this->_var['list']['relevance']; ?>"/>
                    <label for="<?php echo $this->_var['priv_list']; ?>_<?php echo $this->_var['priv']['action_id']; ?>" class="ui-label"><?php echo $this->_var['lang'][$this->_var['list']['action_code']]; ?></label>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<div class="steplast">
    <div class="checkbox_items">
            <div class="checkbox_item">
            <input type="checkbox" name="checkall" value="checkbox" class="ui-checkbox" id="checkall" />
            <label for="checkall" class="ui-label"><?php echo $this->_var['lang']['check_all']; ?></label>
        </div>
    </div>
    <div class="info_btn">
        <input type="submit" value="<?php echo $this->_var['lang']['button_save']; ?>" class="button">
        <input type="hidden"   name="id"    value="<?php echo $this->_var['user_id']; ?>" />
        <input type="hidden" name="token" value="<?php echo $this->_var['token']; ?>" />
        <input type="hidden"   name="act"   value="<?php echo $this->_var['form_act']; ?>" />
    </div>
</div>
