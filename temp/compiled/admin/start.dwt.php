<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="css/main.css" rel="stylesheet" type="text/css" />
<link href="css/purebox.css" rel="stylesheet" type="text/css">
<link href="../js/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
<link href="../js/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/jquery-1.9.1.min.js,../js/jquery.json.js,../js/transport_jquery.js,../js/jquery.cookie.js,../js/perfect-scrollbar/perfect-scrollbar.min.js,../js/jquery-ui/jquery-ui.min.js,common.js,dsc_admin2.0.js')); ?>
</head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php echo $this->_var['lang']['01_admin_core']; ?></div>
        <div class="content start_content">
        	<?php if (! $this->_var['phone_num'] && ! $this->_var['user_name'] && ! $this->_var['email'] && ! $this->_var['oss']): ?>
        	<div class="contentWarp">
            	<div class="explanation" id="explanation">
                	<div class="ex_tit"><h4><?php echo $this->_var['lang']['system_setup']; ?></h4></div>
                    <div class="ex_con">
						<?php if (! $this->_var['phone_num'] && ! $this->_var['user_name']): ?>
                    	<span><?php echo $this->_var['lang']['message_setup']; ?><a href="sms_setting.php?act=step_up"><?php echo $this->_var['lang']['goto_setup']; ?></a></span>
						<?php endif; ?>
						<?php if (! $this->_var['email']): ?>
                        <span><?php echo $this->_var['lang']['email_setup']; ?><a href="shop_config.php?act=mail_settings"><?php echo $this->_var['lang']['goto_setup']; ?></a></span>
						<?php endif; ?>
						<?php if (! $this->_var['pay']): ?>
                        <span><?php echo $this->_var['lang']['pay_setup']; ?><a href="payment.php?act=list"><?php echo $this->_var['lang']['goto_setup']; ?></a></span>
						<?php endif; ?>
						<?php if (! $this->_var['oss']): ?>
                        <span><?php echo $this->_var['lang']['oss_setup']; ?><a href="oss_configure.php?act=list"><?php echo $this->_var['lang']['goto_setup']; ?></a></span>
						<?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="contentWarp">
                <div class="contentWarp_item clearfix">
                    <div class="section_select">
						<?php if ($this->_var['index_sales_volume']): ?>
                        <div class="item item_price">
                            <i class="icon"><img src="images/1.png" width="71" height="74" /></i>
                            <div class="desc">
                                <div class="tit"><!--<?php if ($this->_var['today']['formatted_money']): ?>--><?php echo empty($this->_var['today']['formatted_money']) ? '0.00' : $this->_var['today']['formatted_money']; ?><!--<?php endif; ?>--></div>
                                <span><?php echo $this->_var['lang']['today_stats_total']; ?></span>
                            </div>
                        </div>
						<?php endif; ?>
						<?php if ($this->_var['index_today_order']): ?>
                        <div class="item item_order">
                            <i class="icon"><img src="images/2.png" /></i>
                            <div class="desc">
                                <div class="tit"><?php echo $this->_var['today']['order']; ?></div>
                                <span><?php echo $this->_var['lang']['today_order_number']; ?></span>
                            </div>
                            <i class="icon"></i>
                        </div>
						<?php endif; ?>
						<?php if ($this->_var['index_today_comment']): ?>
                        <div class="item item_comment">
                            <i class="icon"><img src="images/3.png" width="90" height="86" /></i>
                            <div class="desc">
                                <div class="tit"><?php echo $this->_var['today_comment_number']; ?></div>
                                <span><?php echo $this->_var['lang']['today_comment']; ?></span>
                            </div>
                        </div>
						<?php endif; ?>
						<?php if ($this->_var['index_seller_num']): ?>
                        <div class="item item_flow">
                            <i class="icon"><img src="images/4.png" width="86" /></i>
                            <div class="desc">
                                <div class="tit"><?php echo $this->_var['seller_num']; ?></div>
                                <span><?php echo $this->_var['lang']['seller_number']; ?></span>
                            </div>
                            <i class="icon"></i>
                        </div>
						<?php endif; ?>
                    </div>
					<?php if ($this->_var['index_member_info']): ?>
                    <div class="section user_section">
                        <div class="sc_title">
                            <i class="sc_icon"></i>
                            <h3><?php echo $this->_var['lang']['user_name_info']; ?></h3>
                            <cite><?php echo $this->_var['lang']['company_ge']; ?></cite>
                        </div>
                        <div class="sc_warp">
                            <div class="user_item user_today_new">
                                <div class="num"><?php echo $this->_var['today_user_number']; ?></div>
                                <span class="tit"><?php echo $this->_var['lang']['today_user_number']; ?></span>
                            </div>
                            <div class="user_item user_yest_new">
                                <div class="num"><?php echo $this->_var['yesterday_user_number']; ?></div>
                                <span class="tit"><?php echo $this->_var['lang']['yesterday_user_number']; ?></span>
                            </div>
                            <div class="user_item user_month_new">
                                <div class="num"><?php echo $this->_var['month_user_number']; ?></div>
                                <span class="tit"><?php echo $this->_var['lang']['month_user_number']; ?></span>
                            </div>
                            <div class="user_item user_total">
                                <div class="num"><?php echo $this->_var['user_number']; ?></div>
                                <span class="tit"><?php echo $this->_var['lang']['user_number']; ?></span>
                            </div>
                        </div>
                    </div>
					<?php endif; ?>
					<?php if ($this->_var['index_goods_view']): ?>
                    <div class="section goods_section">
                        <div class="sc_title">
                            <i class="sc_icon"></i>
                            <h3><?php echo $this->_var['lang']['goods_yilan']; ?></h3>
                            <cite><?php echo $this->_var['lang']['company_jian']; ?></cite>
                        </div>
                        <div class="sc_warp">
                            <div class="goods_item">
                                <div class="tit"><?php echo $this->_var['lang']['self_goods']; ?></div>
                                <div class="number">
                                    <div class="st"><?php echo $this->_var['lang']['goods_state']['0']; ?>：<a href="goods.php?act=list&self=1"><?php echo $this->_var['platform_real_goods_number']; ?></a></div>
                                    <div class="xn"><?php echo $this->_var['lang']['goods_state']['1']; ?>：<a href="goods.php?act=list&extension_code=virtual_card&self=1"><?php echo $this->_var['platform_virtual_goods_number']; ?></a></div>
                                </div>
                            </div>
                            <div class="goods_item">
                                <div class="tit"><?php echo $this->_var['lang']['seller_goods']; ?></div>
                                <div class="number">
                                    <div class="st"><?php echo $this->_var['lang']['goods_state']['0']; ?>：<a href="goods.php?act=list&merchants=1"><?php echo $this->_var['merchants_real_goods_number']; ?></a></div>
                                    <div class="xn"><?php echo $this->_var['lang']['goods_state']['1']; ?>：<a href="goods.php?act=list&extension_code=virtual_card&merchants=1"><?php echo $this->_var['merchants_virtual_goods_number']; ?></a></div>
                                </div>
                            </div>
                        </div>
                    </div>   
					<?php endif; ?>				
                </div>
                <div class="contentWarp_item clearfix">
					<?php if ($this->_var['index_order_status']): ?>
                    <div class="section_order_select">
                        <ul>
                            <li>
                                <a href="javascript:void(0);" data-url="order.php?act=list&seller_list=0&serch_type=0" data-param="menushopping|02_order_list" ectype="iframeHref">
                                    <i class="ice ice_w"></i>
                                    <div class="t"><?php echo $this->_var['lang']['unconfirmed']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['order']['unconfirmed']) ? '0' : $this->_var['order']['unconfirmed']; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-url="order.php?act=list&seller_list=0&serch_type=1" data-param="menushopping|02_order_list" ectype="iframeHref">
                                    <i class="ice ice_d"></i>
                                    <div class="t"><?php echo $this->_var['lang']['await_pay']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['order']['await_pay']) ? '0' : $this->_var['order']['await_pay']; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-url="order.php?act=list&seller_list=0&serch_type=8" data-param="menushopping|02_order_list" ectype="iframeHref">
                                    <i class="ice ice_n"></i>
                                    <div class="t"><?php echo $this->_var['lang']['await_ship']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['order']['await_ship']) ? '0' : $this->_var['order']['await_ship']; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-url="order.php?act=list&seller_list=0&serch_type=3" data-param="menushopping|02_order_list" ectype="iframeHref">
                                    <i class="ice ice_f"></i>
                                    <div class="t"><?php echo $this->_var['lang']['finished']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['order']['finished']) ? '0' : $this->_var['order']['finished']; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-url="goods_booking.php?act=list_all" data-param="menushopping|06_undispose_booking" ectype="iframeHref">
                                    <i class="ice ice_y"></i>
                                    <div class="t"><?php echo $this->_var['lang']['new_booking']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['booking_goods']) ? '0' : $this->_var['booking_goods']; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-url="order.php?act=list&composite_status=<?php echo $this->_var['status']['shipped_part']; ?>&source=start" data-param="menushopping|02_order_list" ectype="iframeHref">
                                    <i class="ice ice_q"></i>
                                    <div class="t"><?php echo $this->_var['lang']['shipped_part']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['order']['shipped_part']) ? '0' : $this->_var['order']['shipped_part']; ?></span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" data-url="user_account.php?act=list&process_type=1" data-param="menuplatform|09_user_account" ectype="iframeHref">
                                    <i class="ice ice_b"></i>
                                    <div class="t"><?php echo $this->_var['lang']['new_reimburse']; ?></div>
                                    <span class="number"><?php echo empty($this->_var['new_repay']) ? '0' : $this->_var['new_repay']; ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="clear"></div>
					<?php endif; ?>
					<?php if ($this->_var['index_order_stats']): ?>
                    <div class="section section_order_count">
                    	<div class="sc_title">
                            <i class="sc_icon"></i>
                            <h3><?php echo $this->_var['lang']['order_stats']; ?></h3>
							<div class="filter_date">
								<a href="javascript:;" onclick="set_statistical_chart(this, 'order', 'week')"><?php echo $this->_var['lang']['week']; ?></a>
								<a href="javascript:;" onclick="set_statistical_chart(this, 'order', 'month')"><?php echo $this->_var['lang']['month']; ?></a>
								<a href="javascript:;" onclick="set_statistical_chart(this, 'order', 'year')"><?php echo $this->_var['lang']['year']; ?></a>
							</div>
                        </div>
                        <div class="sc_warp">
                        	<div id="order_main" style="height:274px;"></div>
                        </div>
                    </div>
					<?php endif; ?>
					<?php if ($this->_var['index_sales_stats']): ?>
                    <div class="section section_total_count">
                    	<div class="sc_title">
                            <i class="sc_icon"></i>
                            <h3><?php echo $this->_var['lang']['sales_stats']; ?></h3>
							<div class="filter_date">
								<a href="javascript:;" onclick="set_statistical_chart(this, 'sale', 'week')"><?php echo $this->_var['lang']['week']; ?></a>
								<a href="javascript:;" onclick="set_statistical_chart(this, 'sale', 'month')"><?php echo $this->_var['lang']['month']; ?></a>
								<a href="javascript:;" onclick="set_statistical_chart(this, 'sale', 'year')"><?php echo $this->_var['lang']['year']; ?></a>
							</div>							
                        </div>
                        <div class="sc_warp">
                        	<div id="total_main" style="height:274px;"></div>
                        </div>
                    </div>
					<?php endif; ?>
                </div>
            </div>
			<?php if ($this->_var['index_control_panel']): ?>
            <div class="contentWarp bf100">
            	<div class="section col_section">
                	<div class="sc_title">
                        <i class="sc_icon"></i>
                        <h3><?php echo $this->_var['lang']['control_panel']; ?></h3>
                    </div>
                    <div class="sc_warp">
                    	<div class="item_section item_section_frist">
                        	<div class="section_header"><?php echo $this->_var['lang']['shopping_management']; ?></div>
                            <div class="section_body">
                            	<dl>
                                	<dt><?php echo $this->_var['lang']['shopping_index']; ?>：</dt>
                                    <dd><a href="<?php echo $this->_var['ecs_url']; ?>" target="_blank"><?php echo $this->_var['ecs_url']; ?></a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['admin']; ?>：</dt>
                                    <dd><a href="<?php echo $this->_var['ecs_url']; ?>admin" target="_blank"><?php echo $this->_var['ecs_url']; ?>admin</a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['seller']; ?>：</dt>
                                    <dd><a href="<?php echo $this->_var['ecs_url']; ?>seller" target="_blank"><?php echo $this->_var['ecs_url']; ?>seller</a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['stores']; ?>：</dt>
                                    <dd><a href="<?php echo $this->_var['ecs_url']; ?>stores" target="_blank"><?php echo $this->_var['ecs_url']; ?>stores</a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['mobile']; ?>：</dt>
                                    <dd><a href="<?php echo $this->_var['ecs_url']; ?>mobile" target="_blank"><?php echo $this->_var['ecs_url']; ?>mobile</a></dd>
                                </dl>
                            </div>
                        </div>
<!--                         <div class="item_section">
                        	<div class="section_header"><?php echo $this->_var['lang']['customer_service']; ?></div>
                            <div class="section_body">
                            	<dl>
                                	<dt><?php echo $this->_var['lang']['customer_service_tel']; ?>：</dt>
                                    <dd>4001-021-758</dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['customer_service_qq']; ?>：</dt>
                                    <dd><a href="http://crm2.qq.com/page/portalpage/wpa.php?uin=800007167&aty=0&a=0&curl=&ty=1" target="_blank">800007167</a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['community']; ?>：</dt>
                                    <dd><a href="http://wenda.ecmoban.com" target="_blank">http://wenda.ecmoban.com</a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['dscmall']; ?>：</dt>
                                    <dd><a href="http://www.shoprooo.com.au" target="_blank">http://www.shoprooo.com.au</a></dd>
                                </dl>
                                <dl>
                                	<dt><?php echo $this->_var['lang']['dacmall_qq']; ?>：</dt>
                                    <dd><?php echo $this->_var['lang']['dacmall_qq_content']; ?></dd>
                                </dl>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
			<?php endif; ?>
			<?php if ($this->_var['index_system_info']): ?>
            <div class="contentWarp">
                <div class="section system_section w190">
                	<div class="system_section_con">
                        <div class="sc_title">
                            <i class="sc_icon"></i>
                            <h3><?php echo $this->_var['lang']['system_info']; ?></h3>
                            <span class="stop stop_jia" title="<?php echo $this->_var['lang']['unfold_the_details']; ?>"></span>
                        </div>
                        <div class="sc_warp">
                            <table cellpadding="0" cellspacing="0" class="system_table">
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['os']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['os']; ?> (<?php echo $this->_var['sys_info']['ip']; ?>)</td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['web_server']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['web_server']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['php_version']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['php_ver']; ?></td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['mysql_version']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['mysql_ver']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['safe_mode']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['safe_mode']; ?></td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['safe_mode_gid']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['safe_mode_gid']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['socket']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['socket']; ?></td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['timezone']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['timezone']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['gd_version']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['gd']; ?></td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['zlib']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['zlib']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['ip_version']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['ip_version']; ?></td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['max_filesize']; ?></td>
                                    <td><?php echo $this->_var['sys_info']['max_filesize']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['ecs_version']; ?></td>
                                    <td><?php echo $this->_var['ecs_version']; ?> RELEASE <?php echo $this->_var['ecs_release']; ?></td>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['install_date']; ?></td>
                                    <td><?php echo $this->_var['install_date']; ?></td>
                                </tr>
                                <tr>
                                    <td class="gray_bg"><?php echo $this->_var['lang']['ec_charset']; ?></td>
                                    <td><?php echo $this->_var['ecs_charset']; ?></td>
                                    <td class="gray_bg"></td>
                                    <td></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
			<?php endif; ?>
        </div>
    </div>
    <div class="guide_dialog" ectype="guide_dialog" style="display:none;">
        <div class="guide_content" ectype="guide_content">
        	<div class="guide_step">
            	<div class="item current">
                	<h2><?php echo $this->_var['lang']['novice_guide_step_0']; ?></h2>
                	<div class="spliy">••••••••••••••••<i class="gicon"></i></div>
                </div>
                <div class="item">
                	<h2><?php echo $this->_var['lang']['novice_guide_step_1']; ?></h2>
                    <div class="spliy">••••••••••••••••<i class="gicon"></i></div>
                </div>
                <div class="item">
                	<h2><?php echo $this->_var['lang']['novice_guide_step_2']; ?></h2>
                    <div class="spliy">••••••••••••••••<i class="gicon"></i></div>
                </div>
                <div class="item">
                	<h2><?php echo $this->_var['lang']['novice_guide_step_3']; ?></h2>
                </div>
            </div>
            <div class="guide_list">
                <div class="guide_item guide_one"><a href="shop_config.php?act=list_edit" target="_blank"><img src="images/guide/guide_img_1.jpg" /></a></div>
                <div class="guide_item guide_two" style="display:none;"><a href="index.php?act=clear_cache" target="_blank"><img src="images/guide/guide_img_2.jpg" /></a></div>
                <div class="guide_item guide_three" style="display:none;"><a href="goods.php?act=step_up" target="_blank" class="a_left"></a><a href="merchants_steps.php?act=step_up" target="_blank" class="a_right"></a><img src="images/guide/guide_img_3.jpg" /></div>
                <div class="guide_item guide_four" style="display:none;"><a href="visualhome.php?act=list" target="_blank" class="a_top"></a><a href="../mobile/index.php?r=admin/editor" target="_blank" class="a_bot"></a><img src="images/guide/guide_img_4.jpg" /></div>
            </div>
            <div class="guide_btn" data-type="0">
                <a href="javascript:void(0);" class="btn_next" ectype="btnNext"><?php echo $this->_var['lang']['novice_guide_step_next']; ?></a>
                <a href="javascript:void(0);" class="btn_prev btn_disabled" ectype="btnPrev"><?php echo $this->_var['lang']['novice_guide_step_prev']; ?></a>
            </div>
        </div>
    </div>
 	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'../js/echarts-all.js')); ?>
    <script type="text/javascript">
        if($(".section_order_count").length > 0){
            set_statistical_chart(".section_order_count .filter_date a:first", "order", "week"); //初始设置
        }
        if($(".section_total_count").length > 0){
            set_statistical_chart(".section_total_count .filter_date a:first", "sale", "week"); //初始设置
        }
		function set_statistical_chart(obj, type, date)
		{
			var obj = $(obj);
			obj.addClass("active");
			obj.siblings().removeClass("active");
			
			$.ajax({
				type:'get',
				url:'index.php',
				data:'act=set_statistical_chart&type='+type+'&date='+date,
				dataType:'json',
				success:function(data){
					if(type == 'order'){
						var div_id = "order_main";
					}
					if(type == 'sale'){
						var div_id = "total_main";
					}	
					var myChart = echarts.init(document.getElementById(div_id));
					myChart.setOption(data);
				}
			})
		}
		
		//展开收起系统信息
		$.upDown(".stop",".sc_title",".system_section",73);

		$(function(){
			if($.cookie('adminStartHome') == null){
				var content = $("*[ectype='guide_dialog']").html();
				pb({
					id:"guide_dialog",
					title:"<?php echo $this->_var['lang']['novice_guide']; ?>",
					width:960,
					height:550,
					content:content,
					drag:false,
					foot:false
				});
				
				$("#guide_dialog .guide_list").perfectScrollbar("destroy");
				$("#guide_dialog .guide_list").perfectScrollbar();
				
				$("*[ectype='btnNext']").on("click",function(){
					if(!$(this).hasClass("btn_disabled")){
						var type = $(this).parents(".guide_btn").data("type");
						var g_con = $(this).parents("*[ectype='guide_content']");
						
						g_con.find(".guide_step .item").eq(type+1).addClass("current").siblings().removeClass("current");
						g_con.find(".guide_list .guide_item").eq(type+1).show().siblings().hide();
						
						$(this).parents(".guide_btn").data("type",type+1);
						$(this).siblings("*[ectype='btnPrev']").removeClass("btn_disabled");
		
						if(type == 2){
							$(this).addClass("btn_disabled");
							$(this).html(novice_guide_step_hint);
						}else{
							$(this).removeClass("btn_disabled");
							$(this).html(novice_guide_step_next);
						}
						
						$("#guide_dialog .guide_list").perfectScrollbar("destroy");
						$("#guide_dialog .guide_list").perfectScrollbar();
					}else{
						$("#guide_dialog,#pb-mask").remove();
						
					}
				});
				
				$("*[ectype='btnPrev']").on("click",function(){
					if(!$(this).hasClass("btn_disabled")){
						var type = $(this).parents(".guide_btn").data("type");
						var g_con = $(this).parents("*[ectype='guide_content']");
		
						g_con.find(".guide_step .item").eq(type-1).addClass("current").siblings().removeClass("current");
						g_con.find(".guide_list .guide_item").eq(type-1).show().siblings().hide();
						
						$(this).parents(".guide_btn").data("type",type-1);
						$(this).siblings("*[ectype='btnNext']").removeClass("btn_disabled");
						$(this).siblings("*[ectype='btnNext']").html(novice_guide_step_next);
		
						if(type == 1){
							$(this).addClass("btn_disabled");
						}else{
							$(this).removeClass("btn_disabled");
						}
						
						$("#guide_dialog .guide_list").perfectScrollbar("destroy");
						$("#guide_dialog .guide_list").perfectScrollbar();
					}
				});
				
				//生成cookie
				$.cookie('adminStartHome','cookieValue', {expires: 1 ,path:'/'});
			};
		});
    </script>
</body>
</html>
