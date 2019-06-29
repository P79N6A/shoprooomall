<div class="footer-new">
    <div class="footer-new-top">
    	<div class="w w1200">
            <div class="service-list">
                <div class="service-item">
                    <i class="f-icon f-icon-qi"></i>
                    <span><?php echo $this->_var['lang']['7_days_return']; ?></span>
                </div>
                <div class="service-item">
                    <i class="f-icon f-icon-zheng"></i>
                    <span><?php echo $this->_var['lang']['Authentic_guarantee']; ?></span>
                </div>
                <div class="service-item">
                    <i class="f-icon f-icon-hao"></i>
                    <span><?php echo $this->_var['lang']['Rave_reviews']; ?></span>
                </div>
                <div class="service-item">
                    <i class="f-icon f-icon-shan"></i>
                    <span><?php echo $this->_var['lang']['Lightning_delivery']; ?></span>
                </div>
                <div class="service-item">
                    <i class="f-icon f-icon-quan"></i>
                    <span><?php echo $this->_var['lang']['Authority_of_honor']; ?></span>
                </div>
            </div>
            <div class="contact">
                <div class="contact-item contact-item-first"><i class="f-icon f-icon-tel"></i><span><?php echo $this->_var['service_phone']; ?></span></div>
                <div class="contact-item">
                	<?php if ($this->_var['kf_im_switch']): ?>
                    <a id="IM" IM_type="dsc" onclick="openWin(this)" href="javascript:;" class="btn-ctn"><i class="f-icon f-icon-kefu"></i><span><?php echo $this->_var['lang']['consulting_service']; ?></span></a>
                    <?php else: ?>
                        <?php if ($this->_var['basic_info']['kf_type'] == 1): ?>
                        <a href="http://www.taobao.com/webww/ww.php?ver=3&touid=<?php echo $this->_var['basic_info']['kf_ww']; ?>&siteid=cntaobao&status=1&charset=utf-8" class="btn-ctn" target="_blank"><i class="f-icon f-icon-kefu"></i><span><?php echo $this->_var['lang']['consulting_service']; ?></span></a>
                        <?php else: ?>
                        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $this->_var['basic_info']['kf_qq']; ?>&site=qq&menu=yes" class="btn-ctn" target="_blank"><i class="f-icon f-icon-kefu"></i><span><?php echo $this->_var['lang']['consulting_service']; ?></span></a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-new-con">
    	<div class="fnc-warp">
            <div class="help-list">
                <?php $_from = $this->_var['helps']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'help_cat');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['help_cat']):
        $this->_foreach['no']['iteration']++;
?>
                <?php if ($this->_foreach['no']['iteration'] < 6): ?>
                <div class="help-item">
                    <h3><?php echo $this->_var['help_cat']['cat_name']; ?></h3>
                    <ul>
                    <?php $_from = $this->_var['help_cat']['article']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');$this->_foreach['help_cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['help_cat']['total'] > 0):
    foreach ($_from AS $this->_var['item']):
        $this->_foreach['help_cat']['iteration']++;
?>
                    <?php if ($this->_foreach['help_cat']['iteration'] < 4): ?>
                    <li><a href="<?php echo $this->_var['item']['url']; ?>"  title="<?php echo htmlspecialchars($this->_var['item']['title']); ?>" target="_blank"><?php echo $this->_var['item']['short_title']; ?></a></li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </dl>
                </div>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  
            </div>
            <div class="qr-code">
                <div class="qr-item qr-item-first">
                    <div class="code_img"><img src="<?php echo $this->_var['site_domain']; ?><?php echo $this->_var['ecjia_qrcode']; ?>"></div>
                    <div class="code_txt">ECJia</div>
                </div>
                <div class="qr-item">
                    <div class="code_img"><img src="<?php echo $this->_var['site_domain']; ?><?php echo $this->_var['ectouch_qrcode']; ?>"></div>
                    <div class="code_txt">ECTouch</div>
                </div>
            </div>
    	</div>
    </div>
    <div class="footer-new-bot">
    	<div class="w w1200">
            <?php if ($this->_var['navigator_list']['bottom']): ?> 
            <p class="copyright_links">
                <?php $_from = $this->_var['navigator_list']['bottom']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav_0_72485400_1553749812');$this->_foreach['nav_bottom_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_bottom_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav_0_72485400_1553749812']):
        $this->_foreach['nav_bottom_list']['iteration']++;
?>
                <a href="<?php echo $this->_var['nav_0_72485400_1553749812']['url']; ?>"<?php if ($this->_var['nav_0_72485400_1553749812']['opennew'] == 1): ?> target="_blank" <?php endif; ?>><?php echo $this->_var['nav_0_72485400_1553749812']['name']; ?></a>
                <?php if (! ($this->_foreach['nav_bottom_list']['iteration'] == $this->_foreach['nav_bottom_list']['total'])): ?> 
                <span class="spacer"></span>
                <?php endif; ?> 
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
            </p>
            <?php endif; ?>
            
            <?php if ($this->_var['img_links'] || $this->_var['txt_links']): ?>
            <p class="copyright_links">
                <?php $_from = $this->_var['img_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
                    <a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><img src="<?php echo $this->_var['link']['logo']; ?>" alt="<?php echo $this->_var['link']['name']; ?>" border="0" /></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    
                <?php if ($this->_var['txt_links']): ?>
                <?php $_from = $this->_var['txt_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');$this->_foreach['nolink'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nolink']['total'] > 0):
    foreach ($_from AS $this->_var['link']):
        $this->_foreach['nolink']['iteration']++;
?>
                    <a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><?php echo $this->_var['link']['name']; ?></a>
                    <?php if (! ($this->_foreach['nolink']['iteration'] == $this->_foreach['nolink']['total'])): ?> 
                    <span class="spacer"></span>
                    <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </p>
            <?php endif; ?>
            
            <?php if ($this->_var['icp_number']): ?>
            <p><span>©&nbsp;2015-2018&nbsp;shoprooo.com.au&nbsp;版权所有&nbsp;&nbsp;</span><span><?php echo $this->_var['lang']['icp_number']; ?>:</span><a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo $this->_var['icp_number']; ?></a>&nbsp;POWERED by<a href="http://www.shoprooo.com.au/" target="_blank">shoprooo</a>2.0</p>
            <?php endif; ?>
            
            <?php if ($this->_var['partner_img_links'] || $this->_var['partner_txt_links']): ?>
            <p class="copyright_auth">
                <?php $_from = $this->_var['partner_img_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');if (count($_from)):
    foreach ($_from AS $this->_var['link']):
?>
                <a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>"><img src="<?php echo $this->_var['link']['logo']; ?>" alt="<?php echo $this->_var['link']['name']; ?>" border="0" /></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php if ($this->_var['txt_links']): ?>
                <?php $_from = $this->_var['partner_txt_links']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'link');$this->_foreach['nolink'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nolink']['total'] > 0):
    foreach ($_from AS $this->_var['link']):
        $this->_foreach['nolink']['iteration']++;
?>
                <a href="<?php echo $this->_var['link']['url']; ?>" target="_blank" title="<?php echo $this->_var['link']['name']; ?>" class="mr0"><?php echo $this->_var['link']['name']; ?></a>
                <?php if (! ($this->_foreach['nolink']['iteration'] == $this->_foreach['nolink']['total'])): ?> 
                | 
                <?php endif; ?> 
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </p>    
            <?php else: ?>
            <p class="copyright_auth">&nbsp;</p>
            <?php endif; ?>
            
            <?php if ($this->_var['stats_code']): ?>
            	<p style="display:none;"><?php echo $this->_var['stats_code']; ?></p>
            <?php endif; ?>
        </div>
    </div>
    
    
    <div class="hide" id="pd_coupons">
        <span class="success-icon m-icon"></span>
        <div class="item-fore">
            <h3><?php echo $this->_var['lang']['Coupon_redemption_succeed']; ?></h3>
            <div class="txt ftx-03"><?php echo $this->_var['lang']['coupons_prompt']; ?></div>
        </div>
    </div>
    
    
    <div class="hidden">
        <input type="hidden" name="seller_kf_IM" value="<?php echo $this->_var['shop_information']['is_IM']; ?>" rev="<?php echo $this->_var['site_domain']; ?>" ru_id="<?php echo empty($this->_var['merchant_id']) ? '0' : $this->_var['merchant_id']; ?>" />
        <input type="hidden" name="seller_kf_qq" value="<?php echo $this->_var['basic_info']['kf_qq']; ?>" />
        <input type="hidden" name="seller_kf_tel" value="<?php echo $this->_var['basic_info']['kf_tel']; ?>" />
        <input type="hidden" name="user_id" ectype="user_id" value="<?php if ($this->_var['user_id']): ?><?php echo $this->_var['user_id']; ?><?php else: ?><?php echo empty($_SESSION['user_id']) ? '0' : $_SESSION['user_id']; ?><?php endif; ?>" />
    </div>
</div>

<?php if ($this->_var['site_domain']): ?>
<script type="text/jscript" src="<?php echo $this->_var['site_domain']; ?>js/suggest.js"></script>
<script type="text/jscript" src="<?php echo $this->_var['site_domain']; ?>js/scroll_city.js"></script>
<script type="text/jscript" src="<?php echo $this->_var['site_domain']; ?>js/utils.js"></script>
<?php else: ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'suggest.js,scroll_city.js,utils.js')); ?>
<?php endif; ?>

<?php if ($this->_var['site_domain']): ?>
<?php if ($this->_var['area_htmlType'] != 'goods' && $this->_var['area_htmlType'] != 'exchange'): ?>
	<script type="text/javascript" src="<?php echo $this->_var['site_domain']; ?>js/warehouse_area.js"></script>
<?php else: ?>
	<script type="text/javascript" src="<?php echo $this->_var['site_domain']; ?>js/warehouse.js"></script>
<?php endif; ?>
<?php else: ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'warehouse.js,warehouse_area.js')); ?>
<?php endif; ?>

<?php if ($this->_var['suspension_two']): ?>
<script>var seller_qrcode='<img src="<?php echo $this->_var['site_domain']; ?><?php echo $this->_var['seller_qrcode_img']; ?>" alt="<?php echo $this->_var['seller_qrcode_text']; ?>" width="164" height="164">'; //by wu</script>
<?php echo $this->_var['suspension_two']; ?>
<?php endif; ?>