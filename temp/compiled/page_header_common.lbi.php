<?php if ($this->_var['topBanner']): ?>
<?php echo $this->_var['topBanner']; ?>
<?php else: ?>
<?php 
$k = array (
  'name' => 'get_adv',
  'logo_name' => $this->_var['top_banner'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
<?php endif; ?>
<div class="site-nav" id="site-nav">
    <div class="w w1200">
        <div class="fl">
			<?php 
$k = array (
  'name' => 'header_region',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
            <div class="txt-info" id="ECS_MEMBERZONE">
				<?php 
$k = array (
  'name' => 'member_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>	
            </div>
        </div>
        <ul class="quick-menu fr">
            <?php if ($this->_var['navigator_list']['top']): ?>
            <?php $_from = $this->_var['navigator_list']['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'nav');$this->_foreach['nav_top_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_top_list']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['nav']):
        $this->_foreach['nav_top_list']['iteration']++;
?>
            <?php if (($this->_foreach['nav_top_list']['iteration'] - 1) < 4): ?>
            <li>
                <div class="dt"><a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a></div>
            </li>
            <li class="spacer"></li>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
            <?php if ($this->_var['navigator_list']['top']): ?>
            <li class="li_dorpdown" data-ectype="dorpdown">
                <div class="dt dsc-cm"><?php echo $this->_var['lang']['Site_navigation']; ?><i class="iconfont icon-down"></i></div>
                <div class="dd dorpdown-layer">
                    <dl class="fore1">
                        <dt><?php echo $this->_var['lang']['characteristic_theme']; ?></dt>
                        <dd>
                            <?php $_from = $this->_var['top_cat_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'topc_cats');$this->_foreach['nav_top_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_top_list']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['topc_cats']):
        $this->_foreach['nav_top_list']['iteration']++;
?>
                                <?php if (($this->_foreach['nav_top_list']['iteration'] - 1) < 3): ?>
                                    <div class="item"><a href="<?php echo $this->_var['topc_cats']['url']; ?>" target="_blank"><?php echo $this->_var['topc_cats']['cat_alias_name']; ?></a></div>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </dd>
                    </dl>
                    <dl class="fore2">
                        <dt><?php echo $this->_var['lang']['modules_txt_promo']; ?></dt>
                        <dd>
                            <?php $_from = $this->_var['navigator_list']['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'nav');$this->_foreach['nav_top_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_top_list']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['nav']):
        $this->_foreach['nav_top_list']['iteration']++;
?>
                                <?php if (($this->_foreach['nav_top_list']['iteration'] - 1) >= 4): ?>
                                    <div class="item"><a href="<?php echo $this->_var['nav']['url']; ?>"<?php if ($this->_var['nav']['opennew']): ?> target="_blank"<?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a></div>
                                <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </dd>
                    </dl>
                </div>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="header">
    <div class="w w1200">
        <div class="logo">
            <div class="logoImg"><a href="<?php echo $this->_var['url_index']; ?>"><img src="<?php if ($this->_var['shop_logo']): ?><?php echo $this->_var['shop_logo']; ?><?php else: ?><?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/logo.gif<?php endif; ?>" /></a></div>
			<?php if ($this->_var['activity_title']): ?>
			<!--<div class="tit"><?php echo $this->_var['activity_title']; ?></div>-->
            <div class="logoAdv"><a href="<?php echo $this->_var['url_merchants']; ?>"><img src="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/ecsc-join.gif" /></a></div>
			<?php else: ?>
			<div class="logoAdv"><a href="<?php echo $this->_var['url_merchants']; ?>"><img src="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/ecsc-join.gif" /></a></div>
			<?php endif; ?>
        </div>
        <div class="dsc-search">
            <div class="form">
                <form id="searchForm" name="searchForm" method="get" action="<?php echo $this->_var['site_domain']; ?>search.php" onSubmit="return checkSearchForm(this)" class="search-form">
                    <input autocomplete="off" onKeyUp="lookup(this.value);" name="keywords" type="text" id="keyword" value="<?php if ($this->_var['search_keywords']): ?><?php echo $this->_var['search_keywords']; ?><?php else: ?><?php 
$k = array (
  'name' => 'rand_keyword',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?><?php endif; ?>" class="search-text"/>
                    <?php if ($this->_var['filename'] == 'merchants_store'): ?>
                     <input type="hidden" value="<?php echo $this->_var['merchant_id']; ?>" id="merchant_id" name="merchant_id">
                    <input type="hidden" value="<?php echo $this->_var['cat_id']; ?>" id="cat_id" name="cat_id">
                    <button type="submit" class="button button-goods" onclick="checkstore_search_cmt(0,this)"><?php echo $this->_var['lang']['serch_whole_station']; ?></button>
                    <button type="button" class="button button-store" onclick="checkstore_search_cmt(2,this)" data-domain="<?php echo $this->_var['site_domain']; ?>" ><?php echo $this->_var['lang']['serch_our_shop']; ?></button>
                     <?php else: ?>
                    <input type="hidden" name="store_search_cmt" value="<?php echo empty($this->_var['search_type']) ? '0' : $this->_var['search_type']; ?>">
                    <button type="submit" class="button button-goods" onclick="checkstore_search_cmt(0)"><?php echo $this->_var['lang']['serch_goods']; ?></button>
                    <button type="submit" class="button button-store" onclick="checkstore_search_cmt(1)"><?php echo $this->_var['lang']['serch_shop']; ?></button>
                    <?php endif; ?>
                </form>
                <?php if ($this->_var['searchkeywords']): ?>
                <ul class="keyword">
                <?php $_from = $this->_var['searchkeywords']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['val']):
?>
                <li><a href="<?php echo $this->_var['site_domain']; ?>search.php?keywords=<?php echo urlencode($this->_var['val']); ?>" target="_blank"><?php echo $this->_var['val']; ?></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                <?php endif; ?>
                
                <div class="suggestions_box" id="suggestions" style="display:none;">
                    <div class="suggestions_list" id="auto_suggestions_list">
                        &nbsp;
                    </div>
                </div>
                
            </div>
        </div>
        <div class="shopCart" data-ectype="dorpdown" id="ECS_CARTINFO" data-carteveval="0">
        <?php 
$k = array (
  'name' => 'cart_info',
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
        </div>
    </div>
</div>
<?php if (! ( ( $this->_var['filename'] == 'merchants_store' && $this->_var['licence_type'] != 1 ) || $this->_var['snapshot'] )): ?>
<div class="nav<?php if ($this->_var['filename'] != 'index'): ?> dsc-zoom<?php endif; ?>" ectype="dscNav">
    <div class="w w1200">
        <div class="categorys <?php if ($this->_var['filename'] != 'index'): ?>site-mast<?php endif; ?>">
            <div class="categorys-type"><a href="<?php echo $this->_var['url_categoryall']; ?>" target="_blank"><?php echo $this->_var['lang']['all_goods_cat']; ?></a></div>
            <div class="categorys-tab-content">
            	<?php 
$k = array (
  'name' => 'category_tree_nav',
  'cat_model' => $this->_var['nav_cat_model'],
  'cat_num' => $this->_var['nav_cat_num'],
);
echo $this->_echash . $k['name'] . '|' . serialize($k) . $this->_echash;
?>
            </div>
        </div>
        <?php if ($this->_var['nav_page'] && $this->_var['nav_page'] != 'undefined'): ?>
        <?php echo $this->_var['nav_page']; ?>
        <?php else: ?>
        <div class="nav-main" id="nav">
            <ul class="navitems">
                <li><a href="<?php echo $this->_var['site_domain']; ?>index.php" <?php if ($this->_var['navigator_list']['config']['index'] == 1): ?>class="curr"<?php endif; ?>><?php echo $this->_var['lang']['home']; ?></a></li>
                <?php $_from = $this->_var['navigator_list']['middle']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'nav');$this->_foreach['nav_middle_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav_middle_list']['total'] > 0):
    foreach ($_from AS $this->_var['nav']):
        $this->_foreach['nav_middle_list']['iteration']++;
?>
                <li><a href="<?php echo $this->_var['nav']['url']; ?>" <?php if ($this->_var['nav']['active'] == 1): ?>class="curr"<?php endif; ?> <?php if ($this->_var['nav']['opennew']): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['nav']['name']; ?></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>