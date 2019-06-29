<div class="admin-header clearfix" style="min-width:1280px;">
	<div class="bgSelector"></div>
	<div class="admin-logo">
    	<a href="javascript:void(0);" data-param="home" target="workspace">
        <?php if ($this->_var['admin_logo']): ?>
        <img src="<?php echo $this->_var['admin_logo']; ?>" />
        <?php else: ?>
        <img src="images/admin-logo.png" />
        <?php endif; ?>
        </a>
    	<div class="foldsider"><i class="icon icon-indent-left"></i></div>
    </div>
	<div class="module-menu">
		<ul>
        <?php $_from = $this->_var['nav_top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');if (count($_from)):
    foreach ($_from AS $this->_var['nav']):
?>
        <?php if ($this->_var['nav']['children'] && $this->_var['nav']['type'] != 'home'): ?>
        <?php if ($this->_var['nav']['type'] != ""): ?><li data-param="<?php echo $this->_var['nav']['type']; ?>"><a href="javascript:void(0);"><?php echo $this->_var['nav']['label']; ?></a></li><?php endif; ?>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
	</div>
	<div class="admin-header-right">
		<div class="manager">
			<dl>
				<dt class="name"><?php echo $this->_var['admin_info']['user_name']; ?></dt>
				<dd class="group"><?php echo $this->_var['lang']['superadministrator']; ?></dd>
			</dl>
			<span class="avatar">
				<form action="index.php" id="fileForm" method="post"  enctype="multipart/form-data"  runat="server">
					<input name="img" type="file" class="admin-avatar-file" id="_pic" title="<?php echo $this->_var['lang']['set_admin_avatar']; ?>">
				</form>
				<img nctype="admin_avatar" src="<?php if ($this->_var['admin_info']['admin_user_img']): ?>../<?php echo $this->_var['admin_info']['admin_user_img']; ?><?php else: ?>images/admin.png<?php endif; ?>" />
			</span>
            <div id="admin-manager-btn" class="admin-manager-btn"><i class="arrow"></i></div>
			<div class="manager-menu">
				<div class="title">
					<h4><?php echo $this->_var['lang']['last_login']; ?></h4>
					<a href="privilege.php?act=edit&id=<?php echo $_SESSION['admin_id']; ?>" target="workspace" class="edit_pwd"><?php echo $this->_var['lang']['change_password']; ?></a>
				</div>
				<div class="login-date">
					<strong><?php echo $this->_var['admin_info']['last_login']; ?></strong>
					<span>(IP:<?php echo $this->_var['admin_info']['last_ip']; ?>)</span>
				</div>
				<div class="title mt10">
					<h4><?php echo $this->_var['lang']['common_operation']; ?></h4>
					<a href="javascript:;" class="add_nav"><?php echo $this->_var['lang']['add_menu']; ?></a>
				</div>
				<div class="quick_link">
					<ul>
                        <?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                        <li class="tl"><a href="<?php echo $this->_var['vo']['1']; ?>" target="workspace"><?php echo $this->_var['vo']['0']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</ul>
				</div>
			</div>
		</div>
		<div class="operate">
			<li style="position: relative;" ectype="oper_msg">
				<a href="javascript:void(0);" class="msg" title="<?php echo $this->_var['lang']['view_message']; ?>">&nbsp;</a>
				<div id="msg_Container">
                	<div class="item">
                        <h3 class="order_msg" ectype="msg_tit"><?php echo $this->_var['lang']['order_prompt']; ?><em class="iconfont icon-up"></em></h3>
                        <div class="msg_content" ectype="orderMsg" style="display:block;"></div>
                    </div>
					
					<div class="item">
                        <h3 class="goods_msg" ectype="msg_tit"><?php echo $this->_var['lang']['goods_prompt']; ?><em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="goodMsg"></div>
                    </div>
					
                    <div class="item">
                    	<h3 class="shop_msg" ectype="msg_tit"><?php echo $this->_var['lang']['seller_audit_prompt']; ?><em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="sellerMsg"></div>
                    </div>
					
                    <div class="item">
                    	<h3 class="ad_msg" ectype="msg_tit"><?php echo $this->_var['lang']['ad_position_prompt']; ?><em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="advMsg"></div>
                    </div>
					
                    <div class="item">
                    	<h3 class="user_msg" ectype="msg_tit"><?php echo $this->_var['lang']['member_remind']; ?><em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="userMsg"></div>
                    </div>
					
					<div class="item">
                    	<h3 class="campaign_msg" ectype="msg_tit"><?php echo $this->_var['lang']['activity_remind']; ?><em class="iconfont icon-down"></em></h3>
                        <div class="msg_content" ectype="promotionMsg"></div>
                    </div>
				</div>
			</li>
			<i></i>
			<li><a href="../" target="_blank" class="home" title="<?php echo $this->_var['lang']['new_window_homepage']; ?>">&nbsp;</a></li>
			<i></i>
			<li><a href="javascript:void(0);" class="sitemap" title="<?php echo $this->_var['lang']['view_all_manage_menu']; ?>">&nbsp;</a></li>
			<i></i>
			<li><a href="javascript:void(0);" id="trace_show" class="style-color" title="<?php echo $this->_var['lang']['chenge_color_admin']; ?>">&nbsp;</a></li>
			<i></i>
			<li><a href="index.php?act=clear_cache" class="clear" target="workspace" title="<?php echo $this->_var['lang']['clear_cache']; ?>">&nbsp;</a></li>
			<i></i>
			<li><a href="privilege.php?act=logout" class="prompt" title="<?php echo $this->_var['lang']['safe_logout_admin']; ?>">&nbsp;</a></li>
		</div>
	</div>
</div>

<div id="allMenu" style="display: none;">
	<div class="admincp-map ui-widget-content ui-draggable" nctype="map_nav" id="draggable">
		<div class="title ui-widget-header ui-draggable-handle" style="border:none; background:#fff;">
			<h3><?php echo $this->_var['lang']['admin_all_menu']; ?></h3>
			<h5><?php echo $this->_var['lang']['set_common_menu']; ?></h5>
			<span><a nctype="map_off" onclick="$('#allMenu').hide();" href="JavaScript:void(0);">X</a></span>
        </div>
		<div class="content">
			<ul class="admincp-map-nav">
				<li class=""><a href="javascript:void(0);" data-param="map-system"><?php echo $this->_var['lang']['menuplatform']; ?></a></li>
				<li class="selected"><a href="javascript:void(0);" data-param="map-shop"><?php echo $this->_var['lang']['menushopping']; ?></a></li>
				<li class=""><a href="javascript:void(0);" data-param="map-mobile"><?php echo $this->_var['lang']['mobile_terminal']; ?></a></li>
                <!--<li class=""><a href="javascript:void(0);" data-param="map-cms">APP</a></li>-->
                <!--<li class=""><a href="javascript:void(0);" data-param="map-cms"><?php echo $this->_var['lang']['menuinformation']; ?></a></li>-->
			</ul>
			<div class="admincp-map-div" data-param="map-system" style="display: none;">
                <?php $_from = $this->_var['nav_top']['menuplatform']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="<?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?><?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>"><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="admincp-map-div" data-param="map-shop" style="display: block;">
                <?php $_from = $this->_var['nav_top']['menushopping']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
				<dl>
					<dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
					<dd class="<?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?><?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>"><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
            <div class="admincp-map-div" data-param="map-mobile" style="display: none;">
                <?php $_from = $this->_var['nav_top']['ectouch']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="<?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?><?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>"><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
            <!--<div class="admincp-map-div" data-param="map-cms" style="display: none;">
                <?php $_from = $this->_var['nav_top']['ecjia']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="<?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?><?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>"><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>-->
			<div class="admincp-map-div" data-param="map-cms" style="display: none;">
                <?php $_from = $this->_var['nav_top']['menuinformation']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo');if (count($_from)):
    foreach ($_from AS $this->_var['vo']):
?>
                <dl>
                    <dt><?php echo $this->_var['vo']['label']; ?></dt>
                    <?php $_from = $this->_var['vo']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo2');if (count($_from)):
    foreach ($_from AS $this->_var['vo2']):
?>
                    <dd class="<?php $_from = $this->_var['auth_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'vo3');if (count($_from)):
    foreach ($_from AS $this->_var['vo3']):
?><?php if ($this->_var['vo3']['0'] == $this->_var['vo2']['label']): ?>selected<?php endif; ?><?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>"><a href="<?php echo $this->_var['vo2']['action']; ?>" data-param="" target="workspace"><?php echo $this->_var['vo2']['label']; ?></a><i class="fa fa-check-square-o"></i></dd>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </dl>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
		</div>
	</div>
</div>
