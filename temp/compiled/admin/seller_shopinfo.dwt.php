<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><a href="<?php echo $this->_var['action_link']['href']; ?>" class="s-back"><?php echo $this->_var['lang']['back']; ?></a><?php echo $this->_var['lang']['seller']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
			<?php echo $this->fetch('library/seller_manage_tab.lbi'); ?>
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span></div>
                <ul>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['seller_shopinfo']['0']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['seller_shopinfo']['1']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content_common']; ?></li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="common-content">
                    <div class="mian-info">
                        <form action="merchants_users_list.php?act=save_seller_shopinfo" method="post" name="theForm" enctype="multipart/form-data" id="merchants_second">
                            <div class="switch_info">
                                <div class="item">
                                    <div class="label"><?php if ($this->_var['priv_ru']): ?><?php echo $this->_var['lang']['steps_shop_name']; ?><?php else: ?><?php echo $this->_var['lang']['company_name']; ?><?php endif; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="shop_name" value="<?php echo $this->_var['shop_info']['shop_name']; ?>" size="40" class="text" autocomplete="off" />
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
								<?php if (! $this->_var['priv_ru']): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['settled_shop_name']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="brand_shop_name" value="<?php echo $this->_var['shop_information']['shop_name']; ?>" disabled="disabled" size="40" class="text" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['expect_shop_name']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="ec_rz_shopName" value="<?php echo $this->_var['shop_information']['rz_shopName']; ?>" disabled="disabled" size="40" class="text" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['display_shop_name']; ?>：</div>
                                    <div class="label_value">
                                       <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="check_sellername" id="check_sellername_0" value="0" <?php if ($this->_var['shop_info']['check_sellername'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="check_sellername_0" class="ui-radio-label"><?php echo $this->_var['lang']['settled_brand_shop_name']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="check_sellername" id="check_sellername_1" value="1" <?php if ($this->_var['shop_info']['check_sellername'] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="check_sellername_1" class="ui-radio-label"><?php echo $this->_var['lang']['expect_shop_name']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="check_sellername" id="check_sellername_2" value="2" <?php if ($this->_var['shop_info']['check_sellername'] == 2): ?> checked="true" <?php endif; ?>  />
                                                <label for="check_sellername_2" class="ui-radio-label"><?php echo $this->_var['lang']['company_name']; ?></label>
                                            </div>											
                                        </div>
										<?php if ($this->_var['shop_info']['shopname_audit'] == 1): ?>
											&nbsp;&nbsp;<font class="red"><?php echo $this->_var['lang']['already_examine']; ?></font>
										<?php else: ?>
											&nbsp;&nbsp;<font class="org"><?php echo $this->_var['lang']['stay_examine']; ?></font>
										<?php endif; ?>
                                    </div>
                                </div>								
								<?php endif; ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_title']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="shop_title" value="<?php echo $this->_var['shop_info']['shop_title']; ?>" class="text" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_keyword']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="shop_keyword" value="<?php echo $this->_var['shop_info']['shop_keyword']; ?>" class="text" autocomplete="off" />
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['lab_seller_site']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['domain_name']; ?>" name="domain_name" class="text" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_country']; ?>：</div>
                                    <div class="label_value">
										<div id="dlcountry" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['shop_info']['country']; ?>" name="shop_country" id="selcountry">
                                            <div class="txt"><?php echo $this->_var['lang']['country']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="1"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_province']; ?>：</div>
                                    <div class="label_value">
                                        <div id="dlProvinces" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['shop_info']['province']; ?>" name="shop_province" id="selProvinces">
                                            <div class="txt"><?php echo $this->_var['lang']['province_alt']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['provinces']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="2"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_city']; ?>：</div>
                                    <div class="label_value">
                                        <div id="dlCity" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['shop_info']['city']; ?>" name="shop_city" id="selCities">
                                            <div class="txt"><?php echo $this->_var['lang']['city']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['cities']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="3"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['local_area']; ?>：</div>
                                    <div class="label_value">
                                        <div id="dlRegion" class="ui-dropdown smartdropdown alien">
                                            <input type="hidden" value="<?php echo $this->_var['shop_info']['district']; ?>" name="shop_district" id="selDistricts">
                                            <div class="txt"><?php echo $this->_var['lang']['area_alt']; ?></div>
                                            <i class="down u-dropdown-icon"></i>
                                            <div class="options clearfix" style="max-height:300px;">
                                                <?php $_from = $this->_var['districts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                                <span class="liv" data-text="<?php echo $this->_var['list']['region_name']; ?>" data-type="4"  data-value="<?php echo $this->_var['list']['region_id']; ?>"><?php echo $this->_var['list']['region_name']; ?></span>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>	
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_address']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="shop_address" value="<?php echo $this->_var['shop_info']['shop_address']; ?>" class="text" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['longitude']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="longitude" value="<?php echo $this->_var['shop_info']['longitude']; ?>" class="text" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['longitude_desc']; ?></div>
										<br/><a href="javascript:;" onclick="get_coordinate();" class="txtline"><?php echo $this->_var['lang']['get_coordinate']; ?></a>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['latitude']; ?>：</div>
                                    <div class="label_value">
										<input type="text" name="latitude" value="<?php echo $this->_var['shop_info']['latitude']; ?>" class="text" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['latitude_desc']; ?></div>
                                    </div>
                                </div>								
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['03_shipping_list']; ?>：</div>
                                    <div class="label_value">
										<div id="shipping_id_div" class="imitate_select select_w320">
											<div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
											<ul>
												<li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
												<?php $_from = $this->_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
												<?php if ($this->_var['ru_id'] == 0 || ( $this->_var['ru_id'] > 0 && $this->_var['list']['shipping_code'] != 'cac' )): ?>
												<li><a href="javascript:;" data-value="<?php echo $this->_var['list']['shipping_id']; ?>" class="ftx-01"><?php echo $this->_var['list']['shipping_name']; ?></a></li>
												<?php endif; ?>
												<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>												
											</ul>
											<input name="shipping_id" type="hidden" value="<?php echo $this->_var['shop_info']['shipping_id']; ?>" id="shipping_id">
										</div>
                                        <div class="form_prompt"></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['customer_service_mobile']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['mobile']; ?>" name="mobile" class="text" autocomplete="off" />
                                        <div class="form_prompt"><?php echo $this->_var['lang']['mobile_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['customer_service_address']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['seller_email']; ?>" name="seller_email" class="text" autocomplete="off" />
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['customer_service_qq']; ?>：</div>
                                    <div class="label_value">
										<textarea name='kf_qq' value="<?php echo $this->_var['shop_info']['kf_qq']; ?>" rows="6" cols="48" class="textarea"><?php echo $this->_var['shop_info']['kf_qq']; ?></textarea>
										<div class="notic"><?php echo $this->_var['lang']['kf_qq_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['customer_service_taobao']; ?>：</div>
                                    <div class="label_value">
										<textarea name='kf_ww' value="<?php echo $this->_var['shop_info']['kf_ww']; ?>" rows="6" cols="48" class="textarea"><?php echo $this->_var['shop_info']['kf_ww']; ?></textarea>
										<div class="notic"><?php echo $this->_var['lang']['kf_ww_prompt']; ?></div>
                                    </div>
                                </div>
								<!-- 平台 IM start -->
								<?php if ($this->_var['shop_information']['is_IM'] == 1 || $this->_var['shop_information']['is_dsc']): ?>
								<?php if ($this->_var['shop_information']['is_dsc']): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['is_kf_im_switch']; ?>：</div>
                                    <div class="label_value">
                                    	<div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" name="kf_im_switch" value="1" class="ui-radio" id="kf_im_switch1" <?php if ($this->_var['shop_info']['kf_im_switch'] == 1): ?> checked="checked" <?php endif; ?>>
                                                <label class="ui-radio-label" for="kf_im_switch1"><?php echo $this->_var['lang']['open']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" name="kf_im_switch" value="0" class="ui-radio" id="kf_im_switch0" <?php if ($this->_var['shop_info']['kf_im_switch'] == 0): ?> checked="checked" <?php endif; ?>>
                                                <label class="ui-radio-label" for="kf_im_switch0"><?php echo $this->_var['lang']['close']; ?></label>
                                            </div>
                                            <div class="fn_notic"><?php echo $this->_var['lang']['is_kf_im_switch_prompt']; ?></div>
                                        </div>
                                    </div>
                                </div>
								<?php endif; ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['kf_touid']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_touid']; ?>" name="kf_touid" class="text text_1" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['kf_touid_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['kf_appkey']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_appkey']; ?>" name="kf_appkey" class="text text_1" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['kf_appkey_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['kf_secretkey']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_secretkey']; ?>" name="kf_secretkey" class="text text_1" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['kf_secretkey_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['kf_logo']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_logo']; ?>" name="kf_logo" class="text text_1" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['kf_logo_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['kf_welcomeMsg']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_welcomeMsg']; ?>" name="kf_welcomeMsg" class="text text_1" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['kf_welcomeMsg_prompt']; ?></div>
                                    </div>
                                </div>								
								<?php endif; ?>
								<!-- 平台 IM end -->
								<!-- 美洽客服 start -->
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['meiqia']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['meiqia']; ?>" name="meiqia" class="text" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['meiqia_prompt']; ?></div>
                                    </div>
                                </div>
								<!-- 美洽客服 end -->
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['customer_service_tel']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kf_tel']; ?>" name="kf_tel" class="text" autocomplete="off" />
                                        <div class="notic"><?php echo $this->_var['lang']['kf_tel_prompt']; ?></div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['customer_service_css']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="kf_type" id="kf_type_0" value="0" <?php if ($this->_var['shop_info']['kf_type'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="kf_type_0" class="ui-radio-label"><?php echo $this->_var['lang']['QQ_kf']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="kf_type" id="kf_type_1" value="1" <?php if ($this->_var['shop_info']['kf_type'] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="kf_type_1" class="ui-radio-label"><?php echo $this->_var['lang']['wangwang_kf']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
								<?php if ($this->_var['ru_id']): ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['seller_logo']; ?>：</div>
                                    <div class="label_value">
										<input type="file" name="shop_logo" class="file mt5 mb5"/><label class="blue_label"><?php echo $this->_var['lang']['shop_logo_prompt']; ?></label><br />
										<?php if ($this->_var['shop_info']['shop_logo']): ?>
										<div class="seller_img"><img src="<?php echo $this->_var['shop_info']['shop_logo']; ?>" width="150" /></div>
										<?php endif; ?>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['logo_sbt']; ?>：</div>
                                    <div class="label_value">
										<input type="file" name="logo_thumb" class="file mt5 mb5"/><label class="blue_label"><?php echo $this->_var['lang']['logo_thumb_prompt']; ?></label><br />
										<?php if ($this->_var['shop_info']['logo_thumb']): ?>
										<div class="seller_img"><img src="<?php echo $this->_var['shop_info']['logo_thumb']; ?>" width="120" height="120" /></div>
										<?php endif; ?>
                                    </div>
                                </div>
								<div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_street_sbt']; ?>：</div>
                                    <div class="label_value">
										<input type="file" name="street_thumb" class="file mt5 mb5"/><label class="blue_label"><?php echo $this->_var['lang']['street_thumb_prompt']; ?></label><br />
										<?php if ($this->_var['shop_info']['street_thumb']): ?>
										<div class="seller_img"><img src="<?php echo $this->_var['shop_info']['street_thumb']; ?>" width="128" height="62" /></div>
										<?php endif; ?>
                                    </div>
                                </div>
								<div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_street_brand_sbt']; ?>：</div>
                                    <div class="label_value">
										<input type="file" name="brand_thumb" class="file mt5 mb5"/><label class="blue_label"><?php echo $this->_var['lang']['brand_thumb_prompt']; ?></label><br />
										<?php if ($this->_var['shop_info']['brand_thumb']): ?>
										<div class="seller_img"><img src="<?php echo $this->_var['shop_info']['brand_thumb']; ?>" width="180" height="60" /></div>
										<?php endif; ?>
                                    </div>
                                </div>
								<div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['qrcode_thumb']; ?>: </div>
                                    <div class="label_value">
										<input type="file" name="qrcode_thumb"/><label class="blue_label"><?php echo $this->_var['lang']['qrcode_thumb_prompt']; ?></label><br />
										<?php if ($this->_var['shop_info']['qrcode_thumb']): ?>
										<img src="<?php echo $this->_var['shop_info']['qrcode_thumb']; ?>" width="80" height="80" /> 
										<?php endif; ?>
                                    </div>
                                </div>
								<?php endif; ?>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['js_appkey']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['js_appkey']; ?>" name="js_appkey" class="text text_1" autocomplete="off" id="code_appkey" />
										<div class="notic"><?php echo $this->_var['lang']['js_appkey_prompt']; ?></div>
                                    </div>
                                </div>	
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['js_appsecret']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['js_appsecret']; ?>" name="js_appsecret" class="text text_1" autocomplete="off" />
                                    </div>
                                </div>
								<!--快递鸟设置 start-->
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['print_type']; ?>：</div>
                                    <div class="label_value">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="print_type" id="print_type_0" value="0" <?php if ($this->_var['shop_info']['print_type'] == 0): ?> checked="true" <?php endif; ?>  />
                                                <label for="print_type_0" class="ui-radio-label"><?php echo $this->_var['lang']['print_type_0']; ?></label>
                                            </div>
                                            <div class="checkbox_item">
                                                <input type="radio" class="ui-radio" name="print_type" id="print_type_1" value="1" <?php if ($this->_var['shop_info']['print_type'] == 1): ?> checked="true" <?php endif; ?>  />
                                                <label for="print_type_1" class="ui-radio-label"><?php echo $this->_var['lang']['print_type_1']; ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['kdniao_printer']; ?>：</div>
                                    <div class="label_value">
										<input type="text" size="40" value="<?php echo $this->_var['shop_info']['kdniao_printer']; ?>" name="kdniao_printer" class="text text_1" autocomplete="off" />
										<div class="notic"><?php echo $this->_var['lang']['kdniao_printer_prompt']; ?></div>
                                    </div>
                                </div>								
								<!--快递鸟设置 end-->									
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_notice']; ?>：</div>
                                    <div class="label_value">
										<textarea name="notice" class="textarea"><?php echo $this->_var['shop_info']['notice']; ?></textarea>
                                    </div>
                                </div>		
                                <div class="item">
                                    <div class="label"><?php echo $this->_var['lang']['shop_street_desc']; ?>：</div>
                                    <div class="label_value">
										<textarea name="street_desc" class="textarea"><?php echo $this->_var['shop_info']['street_desc']; ?></textarea>
                                        <div class="notic"><?php echo $this->_var['lang']['street_desc']; ?></div>
                                    </div>
                                </div>	
								<div class="item">
									<div class="label"><?php echo $this->_var['lang']['adopt_status']; ?>：</div>
									<div class="label_value">
										<div class="checkbox_items" ectype="general_audit_status">
											<div class="checkbox_item"> 
												<input name="review_status" type="radio" class="ui-radio" value="1" id="review_status_1" <?php if ($this->_var['shop_info']['review_status'] == 1 || ! $this->_var['shop_info']['review_status']): ?>checked="checked"<?php endif; ?> />
												<label for="review_status_1" class="ui-radio-label"><?php echo $this->_var['lang']['not_audited']; ?></label>
											</div>
											<div class="checkbox_item"> 
												<input name="review_status" type="radio" class="ui-radio" value="2" id="review_status_2" <?php if ($this->_var['shop_info']['review_status'] == 2): ?>checked="checked"<?php endif; ?> />
												<label for="review_status_2" class="ui-radio-label"><?php echo $this->_var['lang']['audited_not_adopt']; ?></label>
											</div>
											<div class="checkbox_item"> 
												<input name="review_status" type="radio" class="ui-radio" value="3" id="review_status_3" <?php if ($this->_var['shop_info']['review_status'] == 3): ?>checked="checked"<?php endif; ?> />
												<label for="review_status_3" class="ui-radio-label"><?php echo $this->_var['lang']['audited_yes_adopt']; ?></label>
											</div>
										</div>
									</div>
								</div>			
								<div class="item <?php if ($this->_var['shop_info']['review_status'] != 2): ?>hide<?php endif; ?>" id="review_content">
									<div class="label"><?php echo $this->_var['lang']['adopt_reply']; ?>：</div>
									<div class="value">
										<textarea name="review_content" class="textarea h100"><?php echo $this->_var['shop_info']['review_content']; ?></textarea>
									</div>
								</div>								
                                <div class="item">
                                    <div class="label">&nbsp;</div>
                                    <div class="label_value info_btn">
                                        <input name="lngX" type="hidden" value="0" />
                                        <input name="latY" type="hidden" value="0" />
										<input type="hidden" name="ru_id" value="<?php echo $this->_var['ru_id']; ?>"/>
										<input type="hidden" name="data_op" value="<?php echo $this->_var['data_op']; ?>"/>
										<input type="submit" value="<?php echo $this->_var['lang']['adopt_edit']; ?>" class="button" id="submitBtn" />
                                        <input name="templates_mode" type="hidden" value="1" />
                                    </div>
                                </div>								
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
    </div>
 	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
	<?php echo $this->smarty_insert_scripts(array('files'=>'../js/region.js')); ?>
	<script type="text/javascript" src="js/region.js"></script>
	
	<script type="text/javascript" src="js/jquery.purebox.js"></script>	
	<script type="text/javascript"src="<?php echo $this->_var['http']; ?>webapi.amap.com/maps?v=1.3&key=2761558037cb710a1cebefe5ec5faacd&plugin=AMap.Autocomplete"></script>
	<script type="Text/Javascript" language="JavaScript">
	$(function(){
		$.levelLink();
		
		//表单验证
		$("#submitBtn").click(function(){
			if($("#merchants_second").valid()){
				$("#merchants_second").submit();
			}
		});
		
		$('#merchants_second').validate({
			errorPlacement:function(error, element){
				var error_div = element.parents('div.label_value').find('div.form_prompt');
				element.parents('div.label_value').find(".notic").hide();
				error_div.append(error);
			},
			rules:{
				shop_name:{
					required : true
				},
				shipping_id :{
					min : 1
				}
			},
			messages:{
				shop_name : {
					required : '<i class="icon icon-exclamation-sign"></i>'+shop_name_not_null
				},
				shipping_id:{
					 min : '<i class="icon icon-exclamation-sign"></i>'+select_shipping
				}
			}			
		});
                //加载获取地区坐标值
	get_lngxlaty();
	});
	
	<!--
	
	region.isAdmin = true;

	onload = function()
	{
		if(document.getElementById('paynon')){
			document.getElementById('paynon').style.display = 'none';
		}
	}

	function show_shipping_area()
	{
	  Ajax.call('shipping.php?act=shipping_priv', '', shippingResponse, 'GET', 'JSON');
	}

	function shippingResponse(result)
	{
	  var shipping_name = document.getElementById('shipping_type');
	  if (result.error == '1' && result.message != '')
	  {
		alert(result.message);
		shipping_name.options[0].selected = true;
		return;
	  }
	  
	  var area = document.getElementById('shipping_area');
	  if(shipping_name.value == '')
	  {
		area.style.display = 'none';
	  }
	  else
	  {
		area.style.display = "block";
	  }
	}

	function loadConfig()
	{
	  var payment = document.forms['theForm'].elements['payment'];
	  var paymentConfig = document.getElementById('paymentConfig');
	  if(payment.value == '')
	  {
		paymentConfig.style.display = 'none';
		return;
	  }
	  else
	  {
		paymentConfig.style.display = 'block';
	  }
	  if(document.getElementById('paynon')){
		  if(payment.value == 'alipay')
		 {
		  document.getElementById('paynon').style.display = 'block';
		}
		else
		{
		  document.getElementById('paynon').style.display = 'none';
		}
	  }
		
	  var params = 'code=' + payment.value;

	  Ajax.call('payment.php?is_ajax=1&act=get_config', params, showConfig, 'GET', 'JSON');
	}

	<?php if ($this->_var['is_false'] && $this->_var['priv_ru']): ?>
	Ajax.call('users.php?is_ajax=1&act=main_user','', start_user, 'GET', 'TEXT','FLASE');
	function start_user(){
		//
	}
	<?php endif; ?>
	function showConfig(result)
	{
	  var payment = document.forms['theForm'].elements['payment'];
	  if (result.error == '1' && result.message != '')
	  {
		alert(result.message);
		payment.options[0].selected = true;
		return;
	  }
	  var paymentConfig = document.getElementById('paymentConfig');
	  var config = result.content;

	  paymentConfig.innerHTML = config;
	}
	<?php if ($this->_var['goods_false'] && $this->_var['priv_ru']): ?>
	Ajax.call('goods.php?is_ajax=1&act=main_dsc','', start_dsc, 'GET', 'TEXT','FLASE');
	function start_dsc(){
		//
	}
	<?php endif; ?>
	
	//-->
	</script>
	<script type="text/javascript">
	
	/* 点击弹出地图 获取坐标 by kong start*/
	function get_coordinate(){
		var lngX;
		var latY;
		
		get_lngxlaty(); 
		
		$.jqueryAjax('dialog.php', 'is_ajax=1&act=getmap_html', function(data){
		var content = data.content;
				 pb({
				id: "getlnglat",
				title: "<?php echo $this->_var['lang']['longitude_latitude_title']; ?>",
				width: 1050,
				height:460,
				content: content,
				ok_title: "<?php echo $this->_var['lang']['button_submit_alt']; ?>",
				drag: true,
				foot: true,
				cl_cBtn: false,
				onOk: function () {
					coordinateResponse()
				}
			});

			lngX = $(":input[name='lngX']").val();
			latY = $(":input[name='latY']").val();
			
			$("#lnglat").val(lngX+','+latY);
			
			//根据地址获取地图默认位置 start
			 var map = new AMap.Map("mapcontainer", {
				resizeEnable: true,
				icon: "images/mark_b.png",
				zoom: 17,
				center: [lngX,latY],
			});
			
			 var marker = new AMap.Marker({ //添加自定义点标记
				map: map,
				position: [lngX,latY], //基点位置
				offset: new AMap.Pixel(-10, -42), //相对于基点的偏移位置
				draggable: false,  //是否可拖动
				content : '<img src="images/mark_b.png">'
			});
			//根据地址获取地图默认位置 end
			
			marker.on('click', function() {
				$("#lnglat").val(lngX+','+latY);
			});
			
			//为地图注册click事件获取鼠标点击出的经纬度坐标
			var clickEventListener = map.on('click', function(e) {
				document.getElementById("lnglat").value = e.lnglat.getLng() + ',' + e.lnglat.getLat()
			});
			var auto = new AMap.Autocomplete({
				input: "tipinput"
			});
			AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
			function select(e) {
				if (e.poi && e.poi.location) {
					map.setZoom(15);
					map.setCenter(e.poi.location);
					addMarker(e.poi.location.lat,e.poi.location.lng);
				}
			}
			 // 实例化点标记
			function addMarker(lat,lng) {
				var marker = new AMap.Marker({
					icon: "images/mark_b.png",
					position: [lng, lat]
				});
				marker.setMap(map);
				marker.on('click', function() {
					$("#lnglat").val(lngX+','+latY);
				});
			}
			
			$("#mapsubmit").click(function(){
			   var keywords = document.getElementById("tipinput").value;  
			   var auto = new AMap.Autocomplete({
					input: "tipinput"
				});
				//查询成功时返回查询结果  
				AMap.event.addListener(auto, "select", select);//注册监听，当选中某条记录时会触发
				auto.search(keywords);
			})
		});
	}

	/* 加载获取地区获取坐标 */
	function get_lngxlaty(){
		var province = $("#dlProvinces").find(".txt").html();
		var city = $("#dlCity").find(".txt").html();
		var district = $("#dlRegion").find(".txt").html();
		var address = province + city + district + $(":input[name='shop_address']").val();
		
		var mapObj = new AMap.Map('iCenter'); 
		mapObj.plugin(["AMap.Geocoder"], function() {     //加载地理编码插件
			MGeocoder = new AMap.Geocoder({
				city:"<?php echo $this->_var['lang']['whole_country']; ?>", //城市，默认：“全国”
				radius:500 //范围，默认：500
			});
			//返回地理编码结果
			AMap.event.addListener(MGeocoder, "complete", function(data){
				var geocode = data.geocodes; 
				var lngX = geocode[0].location.getLng();
				var latY = geocode[0].location.getLat();
				mapObj.setCenter(new AMap.LngLat(lngX, latY));
				
				$(":input[name='lngX']").val(lngX);
				$(":input[name='latY']").val(latY);
			});        
			MGeocoder.getLocation(address);  //地理编码
		});
	}

	function coordinateResponse(){
		var lnglat = $("#lnglat").val();
		if(lnglat){
			var arr = lnglat.split(",");
			var lng = arr[0];
			var lat = arr[1];
			$(":input[name='latitude']").val(lat);
			$(":input[name='longitude']").val(lng);
		}
	}
	/* 点击弹出地图 获取坐标 by kong end*/	
	</script>
</body>
</html>
