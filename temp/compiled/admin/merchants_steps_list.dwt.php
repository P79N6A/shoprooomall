<?php if ($this->_var['full_page']): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><?php echo $this->_var['lang']['seller']; ?> - <?php echo $this->_var['ur_here']; ?></div>
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
            	<div class="common-head">
                	<div class="fl">
                        <a href="merchants_steps.php?act=add"><div class="fbutton"><div class="add" title="<?php echo $this->_var['lang']['02_merchants_add']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['lang']['02_merchants_add']; ?></span></div></div></a>
                    </div>
                   	<div class="refresh">
                    	<div class="refresh_tit" title="<?php echo $this->_var['lang']['refresh_data']; ?>"><i class="icon icon-refresh"></i></div>
                    	<div class="refresh_span"><?php echo $this->_var['lang']['refresh_common']; ?><?php echo $this->_var['record_count']; ?><?php echo $this->_var['lang']['record']; ?></div>
                    </div>
                    <div class="search">
                    	<form action="javascript:;" name="searchForm" onSubmit="searchGoodsname(this);">
                    	<div class="input">
                        	<input type="text" name="keywords" class="text nofocus" placeholder="<?php echo $this->_var['lang']['steps_process_title']; ?>" autocomplete="off" />
                            <input type="submit" class="btn" name="secrch_btn" ectype="secrch_btn" value="" />
                        </div>
                        </form>
                    </div>
                </div>
                <div class="common-content">
				<form method="POST" action="" name="listForm" onsubmit="return confirm_bath()">
                	<div class="list-div" id="listDiv">
						<?php endif; ?>
                    	<table cellpadding="0" cellspacing="0" border="0">
                        	<thead>
                            	<tr>
                                	<th width="3%" class="sign"><div class="tDiv"><input type="checkbox" name="all_list" class="checkbox" id="all_list" /><label for="all_list" class="checkbox_stars"></label></div></th>
                                    <th width="5%"><div class="tDiv"><?php echo $this->_var['lang']['record_id']; ?></div></th>
                                    <th width="25%"><div class="tDiv"><?php echo $this->_var['lang']['steps_process_title']; ?></div></th>
                                    <th width="25%"><div class="tDiv"><?php echo $this->_var['lang']['steps_process']; ?></div></th>
                                    <th width="13%"><div class="tDiv"><?php echo $this->_var['lang']['steps_sort']; ?></div></th>
                                    <th width="13%"><div class="tDiv"><?php echo $this->_var['lang']['is_show']; ?></div></th>
                                    <th width="15%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
								<?php $_from = $this->_var['process_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'process');if (count($_from)):
    foreach ($_from AS $this->_var['process']):
?>
                            	<tr>
                                	<td class="sign"><div class="tDiv"><input type="checkbox" name="checkboxes[]" class="checkbox" value="<?php echo $this->_var['process']['id']; ?>" id="checkbox_<?php echo $this->_var['process']['id']; ?>" /><label for="checkbox_<?php echo $this->_var['process']['id']; ?>" class="checkbox_stars"></label></div></td>
                                    <td><div class="tDiv"><?php echo $this->_var['process']['id']; ?></div></td>
                                	<td><div class="tDiv"><?php echo $this->_var['process']['process_title']; ?></div></td>
                                    <td>
                                        <div class="tDiv">
                                            <?php if ($this->_var['process']['process_steps'] == 1): ?>
                                            <?php echo $this->_var['lang']['rz_notis']; ?>
                                            <?php elseif ($this->_var['process']['process_steps'] == 2): ?>
                                            <?php echo $this->_var['lang']['company_info_auth']; ?>
                                            <?php elseif ($this->_var['process']['process_steps'] == 3): ?>
                                            <?php echo $this->_var['lang']['shop_info_auth']; ?>
                                            <?php else: ?>
                                            <?php echo $this->_var['lang']['steps_process_not']; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><div class="tDiv"><input name="sort_order" class="text w40" value="<?php echo $this->_var['process']['steps_sort']; ?>" onkeyup="listTable.editInput(this, 'edit_sort_order',<?php echo $this->_var['process']['id']; ?> )" type="text"></div></td>
                                    <td>
									<?php if ($this->_var['process']['id'] == 1 || $this->_var['process']['id'] == 5 || $this->_var['process']['id'] == 7 || $this->_var['process']['id'] == 9 || $this->_var['process']['id'] == 4): ?>
                                    	<div class="tDiv"><img src="images/yes.png" class="pl3" /></div>
									<?php else: ?>
                                    	<div class="tDiv">
                                        	<div class="switch <?php if ($this->_var['process']['is_show']): ?>active<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_steps_show', <?php echo $this->_var['process']['id']; ?>)" title="<?php echo $this->_var['lang']['yes']; ?>">
                                            	<div class="circle"></div>
                                            </div>
                                            <input type="hidden" value="1" name="">
                                        </div>
									<?php endif; ?>
                                    </td>
                                    <td class="handle">
                                        <div class="tDiv a2">
										    <a href="merchants_steps.php?act=title_list&id=<?php echo $this->_var['process']['id']; ?>" class="btn_see" title="<?php echo $this->_var['lang']['view_order']; ?>"><i class="sc_icon sc_icon_see"></i><?php echo $this->_var['lang']['view']; ?></a>
										    <a href="merchants_steps.php?act=edit&id=<?php echo $this->_var['process']['id']; ?>"  class="btn_see" title="<?php echo $this->_var['lang']['edit']; ?>"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                        </div>
                                    </td>
                                </tr>
								<?php endforeach; else: ?>
								<tr><td class="no-records" colspan="10"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
								<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="10">
                                        <div class="tDiv">
                                            <div class="tfoot_btninfo">
                                                <input type="hidden" name="act" value="batch_remove" />
                                                <input type="submit" value="<?php echo $this->_var['lang']['drop']; ?>" name="remove" ectype="btnSubmit" class="btn btn_disabled" disabled="">
                                            </div>
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
        </div>
    </div>
    <?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <script type="text/javascript">
	//列表导航栏设置下路选项
	$(".ps-container").perfectScrollbar();
		
	//分页传值
	listTable.recordCount = <?php echo empty($this->_var['record_count']) ? '0' : $this->_var['record_count']; ?>;
	listTable.pageCount = <?php echo empty($this->_var['page_count']) ? '1' : $this->_var['page_count']; ?>;

	<?php $_from = $this->_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
	listTable.filter.<?php echo $this->_var['key']; ?> = '<?php echo $this->_var['item']; ?>';
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

	function confirm_bath(){
		cfm = '<?php echo $this->_var['lang']['list_still_accounts']; ?>' + '<?php echo $this->_var['lang']['remove_confirm_process']; ?>';
		return confirm(cfm);
	}
    </script>
</body>
</html>
<?php endif; ?>