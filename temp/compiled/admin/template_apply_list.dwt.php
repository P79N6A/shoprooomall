<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php echo $this->_var['lang']['19_merchants_store']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
            <?php echo $this->fetch('library/seller_step_tab.lbi'); ?>
        	<div class="explanation" id="explanation">
            	<div class="ex_tit"><i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span></div>
                <ul>
                	<li><?php echo $this->_var['lang']['operation_prompt_content']['list']['0']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['list']['1']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['list']['2']; ?></li>
                </ul>
            </div>
            <div class="flexilist">
            	<!--商品分类列表-->
                <div class="common-head">
                    <div class="search">
                    	<form action="javascript:;" name="searchForm" onSubmit="searchGoodsname(this);">
                        <div class="input">
                            <input type="text" name="apply_sn" class="text nofocus" placeholder="<?php echo $this->_var['lang']['apply_sn']; ?>" autocomplete="off" />
                            <input type="submit" class="btn" name="secrch_btn" ectype="secrch_btn" value="" />
                        </div>
                        </form>
                    </div>
                    <div class="refresh">
                    	<div class="refresh_tit" title="<?php echo $this->_var['lang']['refresh_data']; ?>"><i class="icon icon-refresh"></i></div>
                    	<div class="refresh_span"><?php echo $this->_var['lang']['refresh_common']; ?><?php echo $this->_var['record_count']; ?><?php echo $this->_var['lang']['record']; ?></div>
                    </div>
                </div>
                <div class="common-content">
                    <form method="post" action="agency.php" name="listForm" onsubmit="return confirm(batch_drop_confirm);">
                	<div class="list-div" id="listDiv">
                        <?php endif; ?>
                    	<table cellpadding="0" cellspacing="0" border="0">
                            <thead>
                                <tr>
                                   <th><div class="tDiv"><?php echo $this->_var['lang']['apply_sn']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['goods_steps_name']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['temp_name']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['total_amount']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['pay_fee']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['pay_name']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['apply_time']; ?></div></th>
                                    <th><div class="tDiv"><?php echo $this->_var['lang']['apply_status_tr']; ?></div></th>
                                    <th class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $_from = $this->_var['available_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                            	<tr>
                                    <td class="first-cell">
                                        <div class="tDiv"><?php echo htmlspecialchars($this->_var['list']['apply_sn']); ?></div>
                                    </td>
                                    <td><div class="tDiv red"><?php echo $this->_var['list']['shop_name']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['name']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['total_amount']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['pay_fee']; ?></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['list']['pay_name']; ?></div></td>
                                    <td>
                                        <div class="tDiv">
                                            <p><?php echo $this->_var['lang']['add_time']; ?>：<?php echo $this->_var['list']['add_time']; ?></p>
                                            <?php if ($this->_var['list']['pay_time'] != 0): ?><p><?php echo $this->_var['lang']['pay_time']; ?>：<?php echo $this->_var['list']['pay_time']; ?></p><?php endif; ?>
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><?php echo $this->_var['lang']['apply_status'][$this->_var['list']['apply_status']]; ?>,<?php echo $this->_var['lang']['pay_status'][$this->_var['list']['pay_status']]; ?></div></td>
                                    <td class="handle">
                                        <div class="tDiv a2">
                                            <a href="../merchants_store.php?preview=1&temp_code=<?php echo $this->_var['list']['temp_code']; ?>" title="<?php echo $this->_var['lang']['preview']; ?>" target="_blank" class="btn_see"><i class="sc_icon sc_icon_see"></i><?php echo $this->_var['lang']['preview']; ?></a>
                                            <?php if ($this->_var['list']['pay_status'] == 0): ?>
                                            <a onclick="listTable.remove('<?php echo $this->_var['list']['apply_id']; ?>', '<?php echo $this->_var['lang']['confirm_operation']; ?>','confirm_operation')" href="javascript:;" title="<?php echo $this->_var['lang']['yes_payment']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['yes_payment']; ?></a>
                                            <a onclick="listTable.remove('<?php echo $this->_var['list']['apply_id']; ?>', '<?php echo $this->_var['lang']['remove_data']; ?>','remove')" href="javascript:;" title="<?php echo $this->_var['lang']['yes_payment']; ?>" class="btn-red"><i class="icon-trash"></i><?php echo $this->_var['lang']['drop']; ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr><td class="no-records" colspan="12"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="12">
                                        <div class="tDiv">
                                            <div class="list-page">
                                                <?php echo $this->fetch('library/page.lbi'); ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?php if ($this->_var['full_page']): ?>
                    </div>
                    </form>
                </div>
            </div>
            <div class="gj_search">
                <div class="search-gao-list" id="searchBarOpen">
                    <i class="icon icon-zoom-in"></i><?php echo $this->_var['lang']['advanced_search']; ?>
                </div>
                <div class="search-gao-bar">
                    <div class="handle-btn" id="searchBarClose"><i class="icon icon-zoom-out"></i><?php echo $this->_var['lang']['pack_up']; ?></div>
                    <div class="title"><h3><?php echo $this->_var['lang']['advanced_search']; ?></h3></div>
                    <form method="get" name="formSearch_senior" action="javascript:searchOrder()">
                        <div class="searchContent">
                            <div class="layout-box">
                                <dl>
                                    <dt><?php echo htmlspecialchars($this->_var['lang']['conf_pay']); ?></dt>
                                    <dd>
                                        <div  class="select_w145 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="-1" class="ftx-01">请选择..</a></li>
                                                <li><a href="javascript:;" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['pay_status']['0']; ?></a></li>
                                                <li><a href="javascript:;" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['pay_status']['1']; ?></a></li>
                                            </ul>
                                            <input name="pay_starts" type="hidden" value="-1">
                                        </div>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt><?php echo $this->_var['lang']['steps_shop_name']; ?></dt>
                                    <dd>
                                        <div id="shop_name_select" class="select_w145 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                                               <li><a href="javascript:;" data-value="1"><?php echo $this->_var['lang']['s_shop_name']; ?></a></li>
                                               <li><a href="javascript:;" data-value="2"><?php echo $this->_var['lang']['s_qw_shop_name']; ?></a></li>
                                               <li><a href="javascript:;" data-value="3"><?php echo $this->_var['lang']['s_brand_type']; ?></a></li>
                                            </ul>
                                            <input name="store_search" type="hidden" value="0" id="shop_name_val">
                                        </div>
                                    </dd>
                                </dl>
                                <dl style="display:none" id="merchant_box">
                                    <dd>
                                        <div class="select_w145 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                               <?php $_from = $this->_var['store_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['store']['ru_id']; ?>"><?php echo $this->_var['store']['store_name']; ?></a></li>
                                               <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </ul>
                                            <input name="merchant_id" type="hidden" value="0" >
                                        </div>
                                    </dd>
                                </dl>
                                <dl id="store_keyword" style="display:none">
                                    <dd><input type="text" value="" name="store_keyword" class="s-input-txt" autocomplete="off" /></dd>
                                </dl>
                                <dl style="display:none" id="store_type">
                                    <dd>
                                        <div class="select_w145 imitate_select">
                                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                            <ul>
                                               <li><a href="javascript:;" data-value="0"><?php echo $this->_var['lang']['steps_shop_type']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['flagship_store']; ?>"><?php echo $this->_var['lang']['flagship_store']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['exclusive_shop']; ?>"><?php echo $this->_var['lang']['exclusive_shop']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['franchised_store']; ?>"><?php echo $this->_var['lang']['franchised_store']; ?></a></li>
                                               <li><a href="javascript:;" data-value="<?php echo $this->_var['lang']['shop_store']; ?>"><?php echo $this->_var['lang']['shop_store']; ?></a></li>
                                            </ul>
                                            <input name="store_type" type="hidden" value="0" >
                                        </div>
                                    </dd>
                                </dl>
                                <dl class="bot_btn">
                                    <dd>
                                       <input type="submit" class="btn red_btn" name="tj_search" value="<?php echo $this->_var['lang']['button_inquire']; ?>" /><input type="reset" class="btn btn_reset" name="reset" value="<?php echo $this->_var['lang']['button_reset_alt']; ?>" />
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
	</div>
	</div>
    <?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <script type="text/javascript">

	listTable.recordCount = <?php echo empty($this->_var['record_count']) ? '0' : $this->_var['record_count']; ?>;
	listTable.pageCount = <?php echo empty($this->_var['page_count']) ? '1' : $this->_var['page_count']; ?>;
	listTable.query = 'apply_query';
	
	<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
	listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		$.gjSearch("-240px");  //高级搜索
	$(".ps-container").perfectScrollbar();
	 /**
	* 搜索订单
	*/
	function searchOrder()
	{
		var frm = $("form[name='formSearch_senior']");
		listTable.filter['store_search'] = Utils.trim(frm.find("input[name='store_search']").val());
		listTable.filter['merchant_id'] = Utils.trim(frm.find("input[name='merchant_id']").val());
		listTable.filter['store_keyword'] = Utils.trim(frm.find("input[name='store_keyword']").val());
		listTable.filter['store_type'] = Utils.trim(frm.find("input[name='store_type']").val());
		listTable.filter['pay_starts'] = Utils.trim(frm.find("input[name='pay_starts']").val());
		listTable.filter['apply_sn'] = Utils.trim($("form[name='searchForm']").find("input[name='apply_sn']").val()) ;
		listTable.filter['page'] = 1;
		listTable.loadList();
	}
	$.divselect("#shop_name_select","#shop_name_val",function(obj){
		var val = obj.attr("data-value");
		get_store_search(val);
	});
	function get_store_search(val){
		if(val == 1){
			$("#merchant_box").css("display",'');
			$("#store_keyword").css("display",'none');
			$("#store_type").css("display",'none');
		}else if(val == 2){
			$("#merchant_box").css("display",'none');
			$("#store_keyword").css("display",'');
			$("#store_type").css("display",'none');
		}else if(val == 3){
			$("#merchant_box").css("display",'none');
			$("#store_keyword").css("display",'');
			$("#store_type").css("display",'');
		}else{
			$("#merchant_box").css("display",'none');
			$("#store_keyword").css("display",'none');
			$("#store_type").css("display",'none');
		}
	}
	</script>
</body>
</html>
<?php endif; ?>
