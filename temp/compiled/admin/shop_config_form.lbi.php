<div class="item <?php echo $this->_var['var']['code']; ?>"  data-val="<?php echo $this->_var['var']['id']; ?>">
    <div class="label"><?php echo $this->_var['var']['name']; ?>：</div>
    <?php if ($this->_var['var']['type'] == "text"): ?>
    <div class="label_value">
    	<input type="text" name="value[<?php echo $this->_var['var']['id']; ?>]" class="text <?php echo $this->_var['var']['code']; ?>" value="<?php echo $this->_var['var']['value']; ?>" autocomplete="off" />
        <div class="form_prompt"></div>
    	<?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
    </div>
    
    <?php elseif ($this->_var['var']['type'] == "password"): ?>
    <div class="label_value">
        <input type="password"  style="display:none"/> 
        <input type="password" name="value[<?php echo $this->_var['var']['id']; ?>]" class="text" value="<?php echo $this->_var['var']['value']; ?>" autocomplete="off" />
        <div class="form_prompt"></div>
        <?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
    </div>
    <?php elseif ($this->_var['var']['type'] == "textarea"): ?>
    <div class="label_value">
        <textarea class="textarea" name="value[<?php echo $this->_var['var']['id']; ?>]" id="role_describe"><?php echo $this->_var['var']['value']; ?></textarea>
        <div class="form_prompt"></div>
        <?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
    </div>
    <?php elseif ($this->_var['var']['type'] == "select"): ?>
    <div class="label_value">
        <div class="checkbox_items">
            <?php $_from = $this->_var['var']['store_options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'opt');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['opt']):
?>
                <div class="checkbox_item">
                    <input type="radio" name="value[<?php echo $this->_var['var']['id']; ?>]" class="ui-radio evnet_<?php echo $this->_var['var']['code']; ?>" id="value_<?php echo $this->_var['var']['id']; ?>_<?php echo $this->_var['k']; ?>" value="<?php echo $this->_var['opt']; ?>"
                    <?php if ($this->_var['var']['value'] == $this->_var['opt']): ?>checked="true"<?php endif; ?>
                    <?php if ($this->_var['var']['code'] == 'rewrite'): ?>
                    onclick="return ReWriterConfirm(this);"
                    <?php endif; ?>
                    <?php if ($this->_var['var']['code'] == 'smtp_ssl' && $this->_var['opt'] == 1): ?>
                    onclick="return confirm('<?php echo $this->_var['lang']['smtp_ssl_confirm']; ?>');"
                    <?php endif; ?>
                    <?php if ($this->_var['var']['code'] == 'enable_gzip' && $this->_var['opt'] == 1): ?>
                    onclick="return confirm('<?php echo $this->_var['lang']['gzip_confirm']; ?>');"
                    <?php endif; ?>
                    <?php if ($this->_var['var']['code'] == 'retain_original_img' && $this->_var['opt'] == 0): ?>
                    onclick="return confirm('<?php echo $this->_var['lang']['retain_original_confirm']; ?>');"
                    <?php endif; ?> />
                    <label for="value_<?php echo $this->_var['var']['id']; ?>_<?php echo $this->_var['k']; ?>" class="ui-radio-label"><?php echo $this->_var['var']['display_options'][$this->_var['k']]; ?></label>
                </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <div class="form_prompt"></div>
        <?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
    </div>
    <?php elseif ($this->_var['var']['type'] == "options"): ?>
    <div class="label_value">
        <div id="select<?php echo $this->_var['var']['id']; ?>_<?php echo $this->_var['k']; ?>" class="imitate_select select_w320">
          <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
          <ul>
             <?php $_from = $this->_var['lang']['cfg_range'][$this->_var['var']['code']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'options');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['options']):
?>
             <li><a href="javascript:;" data-value="<?php echo $this->_var['k']; ?>" class="ftx-01"><?php echo $this->_var['options']; ?></a></li>
             <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
          </ul>
          <input name="value[<?php echo $this->_var['var']['id']; ?>]" type="hidden" value="<?php echo $this->_var['var']['value']; ?>" id="<?php echo $this->_var['var']['id']; ?>_<?php echo $this->_var['k']; ?>_val">
        </div>
        <div class="form_prompt"></div>
        <?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
    </div>
    <?php elseif ($this->_var['var']['type'] == "file"): ?>
    <div class="label_value">
        <div class="type-file-box">
            <input type="button" name="button" id="button" class="type-file-button" value="" />
            <input type="file" class="type-file-file"  name="<?php echo $this->_var['var']['code']; ?>" size="30" data-state="imgfile" hidefocus="true" value="" />
            <?php if ($this->_var['var']['value']): ?>
            <span class="show">
                <a href="<?php echo $this->_var['var']['value']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="<?php echo $this->_var['var']['value']; ?>" ectype="tooltip" title="tooltip"></i></a>
            </span>
            <?php endif; ?>
            <input type="text" name="textfile" class="type-file-text" id="textfield" readonly />
        </div>
        <?php if ($this->_var['var']['del_img']): ?>
            <a href="shop_config.php?act=del&code=<?php echo $this->_var['var']['code']; ?>" class="btn red_btn h30 mr10 fl" style="line-height:30px;"><?php echo $this->_var['lang']['drop']; ?></a>
        <?php else: ?>
            <?php if ($this->_var['var']['value'] != ""): ?>
            <img src="images/yes.gif" alt="yes" class="fl mt10" />
            <?php else: ?>
            <img src="images/no.gif" alt="no" class="fl mt10" />
            <?php endif; ?>
        <?php endif; ?>
        <div class="form_prompt"></div>
        <?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
    </div>
    <?php elseif ($this->_var['var']['type'] == "manual"): ?>
        <?php if ($this->_var['var']['code'] == "shop_country"): ?>
            <div class="ui-dropdown smartdropdown alien">
                <input type="hidden" value="<?php echo $this->_var['cfg']['shop_country']; ?>" name="value[<?php echo $this->_var['var']['id']; ?>]" id="selProvinces">
                <div class="txt"><?php echo $this->_var['lang']['country']; ?></div>
                <i class="down u-dropdown-icon"></i>
                <div class="options clearfix" style="max-height:300px;">
                    <?php $_from = $this->_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="1" data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            </div>
        <?php elseif ($this->_var['var']['code'] == "shop_province"): ?>
        <div class="ui-dropdown smartdropdown alien">
            <input type="hidden" value="<?php echo $this->_var['cfg']['shop_province']; ?>" name="value[<?php echo $this->_var['var']['id']; ?>]" id="selProvinces">
            <div class="txt"><?php echo $this->_var['lang']['province_alt']; ?></div>
            <i class="down u-dropdown-icon"></i>
            <div class="options clearfix" style="max-height:300px;">
                <?php $_from = $this->_var['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="2" data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
        <?php elseif ($this->_var['var']['code'] == "shop_city"): ?>
        <div id="dlCity" class="ui-dropdown smartdropdown alien">
            <input type="hidden" value="<?php echo $this->_var['cfg']['shop_city']; ?>" name="value[<?php echo $this->_var['var']['id']; ?>]" id="selCities">
            <div class="txt"><?php echo $this->_var['lang']['city']; ?></div>
            <i class="down u-dropdown-icon"></i>
            <div class="options clearfix" style="max-height:300px;">
                <span class="liv hide" data-text="<?php echo $this->_var['lang']['city']; ?>" data-value="0"><?php echo $this->_var['lang']['city']; ?></span>
                <?php $_from = $this->_var['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                 <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="3" data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
        <?php elseif ($this->_var['var']['code'] == "lang"): ?>
        <div class="label_value">
            <div id="select<?php echo $this->_var['var']['id']; ?>_<?php echo $this->_var['k']; ?>" class="imitate_select select_w320" >
              <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
              <ul>
                 <?php $_from = $this->_var['lang_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'options');if (count($_from)):
    foreach ($_from AS $this->_var['k'] => $this->_var['options']):
?>
                 <li><a href="javascript:;" data-value="<?php echo $this->_var['options']; ?>" class="ftx-01"><?php echo $this->_var['options']; ?></a></li>
                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
              </ul>
              <input name="value[<?php echo $this->_var['var']['id']; ?>]" type="hidden" value="<?php echo $this->_var['var']['value']; ?>" id="<?php echo $this->_var['var']['id']; ?>_<?php echo $this->_var['k']; ?>_val">
            </div>
            <div class="form_prompt"></div>
            <?php if ($this->_var['var']['desc']): ?><div class="notic"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
        </div>
        <?php elseif ($this->_var['var']['code'] == "invoice_type"): ?>
        <div class="label_value">
            <table>
                <tr>
                    <td colspan="2">
                         <div id="consumtable">
                            <p>
                                <label class="fl mr10"><?php echo $this->_var['lang']['invoice_type']; ?></label>
                                <input type="text" class="text w120" name="invoice_type[]" size="10" autocomplete="off"/>
                                <label class="fl mr10"><?php echo $this->_var['lang']['invoice_rate']; ?></label>
                                <input type="text" class="text w120" name="invoice_rate[]" size="10" />
                                <input type="button" onclick="addCon_amount(this)" class="button fl" value="<?php echo $this->_var['lang']['add']; ?>" autocomplete="off"/>
                                <span class="form_prompt ml10 fl"></span>
                            </p>
                            <?php if ($this->_var['invoice_list']): ?>
                            <?php $_from = $this->_var['invoice_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'invoice');if (count($_from)):
    foreach ($_from AS $this->_var['invoice']):
?>
                                <?php if ($this->_var['invoice']['type']): ?>
                                <p class="mt10">
                                    <label class="fl mr10"><?php echo $this->_var['lang']['invoice_type']; ?></label>
                                    <input type="text" name="invoice_type[]" value="<?php echo $this->_var['invoice']['type']; ?>" class="text w120" size="10" autocomplete="off"/>
                                    <label class="fl mr10"><?php echo $this->_var['lang']['invoice_rate']; ?></label>
                                    <input type="text" name="invoice_rate[]" value="<?php echo $this->_var['invoice']['rate']; ?>" size="10" class="text w120" autocomplete="off"/>
                                	<a href='javascript:;' class='removeV' onclick='removeCon_amount(this)'><img src='images/no.gif' title='<?php echo $this->_var['lang']['drop']; ?>'></a>
                                </p>  
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <?php endif; ?>
                        </div>
                	</td>
                </tr>
            </table>
            <div class="form_prompt"></div>
            <?php if ($this->_var['var']['desc']): ?><div class="notic" style="padding:0px;"><?php echo nl2br($this->_var['var']['desc']); ?></div><?php endif; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
