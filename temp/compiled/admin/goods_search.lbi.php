<!--高级搜索 start-->
<form action="javascript:searchGoods()" name="searchHighForm">
<div class="gj_search">
	<div class="search-gao-list" id="searchBarOpen">
		<i class="icon icon-zoom-in"></i><?php echo $this->_var['lang']['advanced_search']; ?>
	</div>
	<div class="search-gao-bar" style="right:-350px;">
		<div class="handle-btn" id="searchBarClose"><i class="icon icon-zoom-out"></i><?php echo $this->_var['lang']['pack_up']; ?></div>
		<div class="title"><h3><?php echo $this->_var['lang']['advanced_search']; ?></h3></div>
        <div class="searchContent w300">
            <div class="layout-box">
                <?php if ($_GET['act'] != "trash"): ?>
                <dl class="w300">
                    <dt><?php echo $this->_var['lang']['category']; ?></dt>
                    <dd>
                        <div class="categorySelect">
                            <div class="selection">
                                <input type="text" name="category_name" id="category_name" class="text w260 mr0 valid" value="<?php echo $this->_var['lang']['select_cat']; ?>" autocomplete="off" readonly data-filter="cat_name" autocomplete="off" />
                                <input type="hidden" name="cat_id" id="cat_id" value="0" data-filter="cat_id" autocomplete="off" />
                            </div>
                            <div class="select-container" style="width:290px; display:none;">
                                <?php echo $this->fetch('library/filter_category.lbi'); ?>
                            </div>
                        </div>
                    </dd>
                </dl>
                <dl class="w140">
                    <dt><?php echo $this->_var['lang']['act_rec']; ?></dt>
                    <dd>
                        <div id="" class="imitate_select select_w140">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
                                <?php $_from = $this->_var['intro_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <input name="intro_type" type="hidden" value="<?php echo empty($_GET['intro_type']) ? '0' : $_GET['intro_type']; ?>" autocomplete="off">
                        </div>
                    </dd>
                </dl>
                <?php if ($this->_var['judge_supplier_enabled'] && $this->_var['action'] == 'wholesale_list'): ?>
                <dl class="w140">
                    <dt><?php echo $this->_var['lang']['supplier']; ?></dt>
                    <dd>
                        <div id="" class="imitate_select select_w140">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
                                <?php $_from = $this->_var['suppliers_list_name']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['data']; ?></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <input name="suppliers_id" type="hidden" value="<?php echo empty($_GET['suppliers_id']) ? '0' : $_GET['suppliers_id']; ?>" autocomplete="off">
                        </div>
                    </dd>
                </dl>
                <?php endif; ?>
                <dl class="w140">
                    <dt><?php echo $this->_var['lang']['is_on_sale']; ?></dt>
                    <dd>	
                        <div id="" class="imitate_select select_w140">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="-1" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
                                <li><a href="javascript:;" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['on_sale']; ?></a></li>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['not_on_sale']; ?></a></li>
                            </ul>
                            <input name="is_on_sale" type="hidden" value="-1" autocomplete="off">
                        </div>								
                    </dd>
                </dl>
                <?php endif; ?>
                <dl class="w140">
                    <dt><?php echo $this->_var['lang']['audited']; ?></dt>
                    <dd>
                        <div id="" class="imitate_select select_w140">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['intro_type']; ?></a></li>
                                <li><a href="javascript:;" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['not_audited']; ?></a></li>
                                <li><a href="javascript:;" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['audited_not_adopt']; ?></a></li>
                                <li><a href="javascript:;" data-value="3" class="ftx-01"><?php echo $this->_var['lang']['audited_yes_adopt']; ?></a></li>
                            </ul>
                            <input name="review_status" type="hidden" value="0" autocomplete="off">
                        </div>
                    </dd>
                </dl>
                <?php if ($this->_var['priv_ru'] == 1): ?>
                <!--卖场 start-->
                <?php if ($this->_var['rs_enabled'] && ! $this->_var['rs_id']): ?>
                <dl class="w300">
                    <dt><?php echo $this->_var['lang']['rs_name']; ?></dt>
                    <dd>
                        <div id="" class="imitate_select select_w140">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                <?php $_from = $this->_var['region_store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'data');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['data']):
?>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['data']['rs_id']; ?>" class="ftx-01"><?php echo $this->_var['data']['rs_name']; ?></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <input name="rs_id" type="hidden" value="<?php echo empty($_GET['rs_id']) ? '0' : $_GET['rs_id']; ?>" autocomplete="off">
                        </div>
                    </dd>
                </dl>
                <?php endif; ?>
                <!--卖场 end-->
                <?php if ($this->_var['common_tabs']['info']): ?>
                <dl class="w300">
                    <dt><?php echo $this->_var['lang']['steps_shop_name']; ?></dt>
                    <dd>
                        <div class="imitate_select select_w140 mr10">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:get_store_search(0);" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                                <!--<li><a href="javascript:get_store_search(4);" data-value="4" class="ftx-01"><?php echo $this->_var['lang']['platform_self']; ?></a></li>-->
                                <li><a href="javascript:get_store_search(1);" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['s_shop_name']; ?></a></li>
                                <li><a href="javascript:get_store_search(2);" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['s_qw_shop_name']; ?></a></li>
                                <li><a href="javascript:get_store_search(3);" data-value="3" class="ftx-01"><?php echo $this->_var['lang']['s_brand_type']; ?></a></li>
                            </ul>
                            <input name="store_search" type="hidden" value="0" autocomplete="off">
                        </div>
                        <div class="imitate_select select_w140" style="display:none">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                                <?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['store']['ru_id']; ?>" class="ftx-01"><?php echo $this->_var['store']['store_name']; ?></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                            <input name="merchant_id" type="hidden" value="0" autocomplete="off">
                        </div>
                        <input name="store_keyword" type="text" style="display:none" class="text w120 mr0" autocomplete="off"/>	
                        <div class="imitate_select select_w140 mt10" style="display:none">
                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                            <ul>
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['steps_shop_type']; ?></a></li>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['flagship_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['flagship_store']; ?></a></li>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['exclusive_shop']; ?>" class="ftx-01"><?php echo $this->_var['lang']['exclusive_shop']; ?></a></li>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['franchised_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['franchised_store']; ?></a></li>
                                <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['shop_store']; ?>" class="ftx-01"><?php echo $this->_var['lang']['shop_store']; ?></a></li>
                            </ul>
                            <input name="store_type" type="hidden" value="0"  autocomplete="off">
                        </div>
                    </dd>
                </dl>
                <?php endif; ?>
                <?php endif; ?>
                
                <dl class="w300">
                    <dt><?php echo $this->_var['lang']['brand']; ?></dt>
                    <dd>
                        <div class="brandSelect">
                            <div class="selection">
                                <input type="text" name="brand_name" id="brand_name" class="text w120 valid" <?php echo $this->_var['lang']['select_barnd']; ?> autocomplete="off" readonly data-filter="brand_name" />
                                <input type="hidden" name="brand_id" id="brand_id" value="0" data-filter="brand_id" />
                            </div>
                            <div class="brand-select-container" style="display:none;">
                                <?php echo $this->fetch('library/filter_brand.lbi'); ?>
                            </div>
                        </div>												
                    </dd>
                </dl>
                <dl class="w140">
                    <dt><?php echo $this->_var['lang']['keyword']; ?></dt>
                    <dd>
                        <input type="text" name="keyword" size="15" class="text w270 mr0" autocomplete="off" />						
                    </dd>
                </dl>				
            </div>
        </div>
        <div class="bot_btn">
            <input type="submit" class="btn red_btn" name="tj_search" value="<?php echo $this->_var['lang']['button_inquire']; ?>" />
            <input type="reset" class="btn btn_reset" name="reset" value="<?php echo $this->_var['lang']['button_reset']; ?>" />
        </div>
	</div>
</div>
</form>
<!--高级搜索 end-->	


<script type="text/javascript">
	$.gjSearch("-350px");
	
	<?php if ($this->_var['priv_ru'] == 1): ?>
	function get_store_search(val){
		if(val == 1){
			$("input[name=merchant_id]").parent(".imitate_select").show();
			$("input[name=store_keyword]").hide();
			$("input[name=store_type]").parent(".imitate_select").hide();
		}else if(val == 2){
			$("input[name=merchant_id]").parent(".imitate_select").hide();
			$("input[name=store_keyword]").show();
			$("input[name=store_type]").parent(".imitate_select").hide();			
		}else if(val == 3){
			$("input[name=merchant_id]").parent(".imitate_select").hide();
			$("input[name=store_keyword]").show();
			$("input[name=store_type]").parent(".imitate_select").show();			
		}else{
			$("input[name=merchant_id]").parent(".imitate_select").hide();
			$("input[name=store_keyword]").hide();
			$("input[name=store_type]").parent(".imitate_select").hide();			
		}
	}
	<?php endif; ?>
	
	function searchGoods()
	{
		<?php if ($_GET['act'] != "trash"): ?>
		listTable.filter['cat_id'] = document.forms['searchHighForm'].elements['cat_id'].value;
		listTable.filter['brand_id'] = document.forms['searchHighForm'].elements['brand_id'].value;
		listTable.filter['review_status'] = document.forms['searchHighForm'].elements['review_status'].value;
		listTable.filter['intro_type'] = document.forms['searchHighForm'].elements['intro_type'].value;
		<?php if ($this->_var['judge_supplier_enabled']): ?>
		listTable.filter['suppliers_id'] = document.forms['searchHighForm'].elements['suppliers_id'].value;
		<?php endif; ?>
		listTable.filter['is_on_sale'] = document.forms['searchHighForm'].elements['is_on_sale'].value;
		<?php endif; ?>

		<?php if ($this->_var['priv_ru'] == 1 && $this->_var['common_tabs']['info']): ?>
		listTable.filter['store_search'] = Utils.trim(document.forms['searchHighForm'].elements['store_search'].value);
		listTable.filter['merchant_id'] = Utils.trim(document.forms['searchHighForm'].elements['merchant_id'].value);
		listTable.filter['store_keyword'] = Utils.trim(document.forms['searchHighForm'].elements['store_keyword'].value);
		listTable.filter['store_type'] = Utils.trim(document.forms['searchHighForm'].elements['store_type'].value);
		//卖场 start
		<?php if ($this->_var['rs_enabled'] && ! $this->_var['rs_id']): ?>
		listTable.filter['rs_id'] = Utils.trim(document.forms['searchHighForm'].elements['rs_id'].value);
		<?php endif; ?>
		//卖场 end
		<?php endif; ?>

		listTable.filter['keyword'] = Utils.trim(document.forms['searchHighForm'].elements['keyword'].value);
		
		// 品牌搜索  -qin
		if(document.forms['searchHighForm'].elements['brand_keyword']){
			listTable.filter['brand_keyword'] = Utils.trim(document.forms['searchHighForm'].elements['brand_keyword'].value);
		}

		listTable.filter['page'] = 1;

		listTable.loadList();
	}

// 显示品牌选择方式
function get_brand_type(val)
{
	if(val == 1)
	{
		$("#brand_list").hide();
		$("#brand_keyword").show();
	}
	else if(val == 2)
	{
		$("#brand_keyword").val('');
		$("#brand_list").show();
		$("#brand_keyword").hide();
	}
}
</script>
