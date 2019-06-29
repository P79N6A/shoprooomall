<div class="mui-mbar-tabs">
	<div class="quick_link_mian" data-userid="<?php echo $this->_var['user_id']; ?>">
        <div class="quick_links_panel">
            <div id="quick_links" class="quick_links">
            	<ul>
                    <li>
                        <a href="user.php"><i class="setting"></i></a>
                        <div class="ibar_login_box status_login">
                            <div class="avatar_box">
                                <p class="avatar_imgbox">
                                    <?php if ($this->_var['info']['user_picture']): ?>
                                    <img src="<?php echo $this->_var['info']['user_picture']; ?>" width="100" height="100" />
                                    <?php else: ?>
                                    <img src="themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/touxiang.jpg" width="100" height="100"/>
                                    <?php endif; ?>
                                </p>
                                <ul class="user_info">
                                    <li><?php echo $this->_var['lang']['username']; ?>：<?php echo empty($this->_var['info']['nick_name']) ? $this->_var['lang']['temporary_no'] : $this->_var['info']['nick_name']; ?></li>
                                    <li><?php echo $this->_var['lang']['level_pos']; ?>：<?php echo empty($this->_var['info']['rank_name']) ? $this->_var['lang']['temporary_no'] : $this->_var['info']['rank_name']; ?></li>
                                </ul>
                            </div>
                            <div class="login_btnbox">
                                <a href="user.php?act=order_list" class="login_order"><?php echo $this->_var['lang']['order_list']; ?></a>
                                <a href="user.php?act=collection_list" class="login_favorite"><?php echo $this->_var['lang']['label_collection']; ?></a>
                            </div>
                            <i class="icon_arrow_white"></i>
                        </div>
                    </li>
                    
                    <li id="shopCart">
                        <a href="javascript:void(0);" class="cart_list">
                            <i class="message"></i>
                            <div class="span"><?php echo $this->_var['lang']['cat_list']; ?></div>
                            <span class="cart_num"><?php echo empty($this->_var['cart_info']['number']) ? '0' : $this->_var['cart_info']['number']; ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="mpbtn_order"><i class="chongzhi"></i></a>
                        <div class="mp_tooltip">
                            <font id="mpbtn_money" style="font-size:12px; cursor:pointer;"><?php echo $this->_var['lang']['order_list']; ?></font>
                            <i class="icon_arrow_right_black"></i>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="mpbtn_yhq"><i class="yhq"></i></a>
                        <div class="mp_tooltip">
                            <font id="mpbtn_money" style="font-size:12px; cursor:pointer;"><?php echo $this->_var['lang']['preferential']; ?></font>
                            <i class="icon_arrow_right_black"></i>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="mpbtn_total"><i class="view"></i></a>
                        <div class="mp_tooltip" style=" visibility:hidden;">
                            <font id="mpbtn_myMoney" style="font-size:12px; cursor:pointer;"><?php echo $this->_var['lang']['My_assets']; ?></font>
                            <i class="icon_arrow_right_black"></i>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="mpbtn_history"><i class="zuji"></i></a>
                        <div class="mp_tooltip">
                            <font id="mpbtn_histroy" style="font-size:12px; cursor:pointer;"><?php echo $this->_var['lang']['My_footprint']; ?></font>
                            <i class="icon_arrow_right_black"></i>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="mpbtn_collection"><i class="wdsc"></i></a>
                        <div class="mp_tooltip">
                            <font id="mpbtn_wdsc" style="font-size:12px; cursor:pointer;"><?php echo $this->_var['lang']['label_collection']; ?></font>
                            <i class="icon_arrow_right_black"></i>
                        </div>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="mpbtn_email"><i class="email"></i></a>
                        <div class="mp_tooltip">
                            <font id="mpbtn_email" style="font-size:12px; cursor:pointer;"><?php echo $this->_var['lang']['Email_subscription']; ?></font>
                            <i class="icon_arrow_right_black"></i>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="quick_toggle">
            	<ul>
                    <li>
                        
                        <?php if ($this->_var['kf_im_switch']): ?>

                        <a id="IM" IM_type="dsc" onclick="openWin(this)" href="javascript:;"><i class="kfzx"></i></a>
                        <?php else: ?>
                            <?php if ($this->_var['basic_info']['kf_type'] == 1): ?>
                            <a href="http://www.taobao.com/webww/ww.php?ver=3&touid=<?php echo $this->_var['basic_info']['kf_ww']; ?>&siteid=cntaobao&status=1&charset=utf-8" class="seller-btn" target="_blank"><i class="icon" style="left: 10px;top: 10px;"></i><?php echo $this->_var['lang']['con_cus_service']; ?></a>
                            <?php else: ?>
                            <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $this->_var['basic_info']['kf_qq']; ?>&site=qq&menu=yes" class="seller-btn" target="_blank"><i class="icon" style="left: 10px;top: 10px;"></i><?php echo $this->_var['lang']['con_cus_service']; ?></a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <div class="mp_tooltip"><?php echo $this->_var['lang']['Customer_service_center']; ?><i class="icon_arrow_right_black"></i></div>
                        
                    </li>
                    <li class="returnTop">
                        <a href="javascript:void(0);" class="return_top"><i class="top"></i></a>
                    </li>
                </ul>

            </div>
        </div>
        <div id="quick_links_pop" class="quick_links_pop"></div>
    </div>
</div>
<div class="email_sub">
	<div class="attached_bg"></div>
	<div class="w1200">
        <div class="email_sub_btn">
            <input type="input" id="user_email" name="user_email" autocomplete="off" placeholder="<?php echo $this->_var['lang']['email_posi']; ?>">
            <a href="javascript:void(0);" onClick="add_email_list();" class="emp_btn"><?php echo $this->_var['lang']['email_list_ok']; ?></a>
            <a href="javascript:void(0);" onClick="cancel_email_list();" class="emp_btn emp_cancel_btn"><?php echo $this->_var['lang']['email_list_cancel']; ?></a>
        </div>
    </div>
</div>