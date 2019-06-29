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
                </ul>
            </div>
            <div class="flexilist">
            	<div class="common-head">
					<div class="fl">
						<a href="<?php echo $this->_var['action_link']['href']; ?>"><div class="fbutton"><div class="add" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-plus"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a>
						<a href="<?php echo $this->_var['action_link2']['href']; ?>"><div class="fbutton"><div class="open" title="<?php echo $this->_var['action_link2']['text']; ?>"><span><i class="icon icon-screenshot"></i><?php echo $this->_var['action_link2']['text']; ?></span></div></div></a>
					</div>
                </div>
                <div class="common-content">
                	<div class="list-div"  id="listDiv">
                    	<div class="flexigrid ht_goods_list">
                    	<?php endif; ?>
                    	<table cellpadding="1" cellspacing="1">
                        	<thead>
                            	<tr>
                                	<th width="6%"><div class="tDiv"><?php echo $this->_var['lang']['grade_name']; ?></div></th>
                                    <th width="8%"><div class="tDiv"><?php echo $this->_var['lang']['goods_sun']; ?></div></th>
									<th width="7%"><div class="tDiv"><?php echo $this->_var['lang']['seller_temp']; ?></div></th>
                                    <th width="12%"><div class="tDiv"><?php echo $this->_var['lang']['grade_introduce']; ?></div></th>
                                    <th width="18%"><div class="tDiv"><?php echo $this->_var['lang']['entry_criteria']; ?></div></th>
                                    <th width="6%"><div class="tDiv"><?php echo $this->_var['lang']['is_open']; ?></div></th>
                                    <th width="6%"><div class="tDiv"><?php echo $this->_var['lang']['is_default']; ?></div></th>
                                    <th width="6%"><div class="tDiv"><?php echo $this->_var['lang']['grade_img']; ?></div></th>
                                    <th class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
							    <?php $_from = $this->_var['garde_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
								<tr>
								<td><div class="tDiv"><?php echo htmlspecialchars($this->_var['list']['grade_name']); ?></div></td>
                                <td><div class="tDiv"><input name="sort_order" class="text w40" value="<?php echo htmlspecialchars($this->_var['list']['goods_sun']); ?>" onkeyup="listTable.editInput(this, 'edit_goods_sun',<?php echo $this->_var['list']['id']; ?> )" type="text"></div></td>
                                <td><div class="tDiv"><input name="sort_order" class="text w40" value="<?php echo htmlspecialchars($this->_var['list']['seller_temp']); ?>" onkeyup="listTable.editInput(this, 'edit_seller_temp',<?php echo $this->_var['list']['id']; ?> )" type="text"></div></td>
                                <td><div class="tDiv"><?php echo htmlspecialchars($this->_var['list']['grade_introduce']); ?></div></td>
                                <td><div class="tDiv"><?php echo $this->_var['list']['entry_criteria']; ?></div></td>
								<td>
									<div class="tDiv">
										<div class="switch mauto <?php if ($this->_var['list']['is_open']): ?>active<?php endif; ?>" onclick="listTable.switchBt(this, 'toggle_show', <?php echo $this->_var['list']['id']; ?>)" title="<?php echo $this->_var['lang']['yes']; ?>">
											<div class="circle"></div>
										</div>
										<input type="hidden" value="" name="is_hot">
									</div>
								</td>
							    <td><div class="tDiv"><?php if ($this->_var['list']['is_default']): ?><?php echo $this->_var['lang']['default']; ?><?php else: ?>--<?php endif; ?></div></td>
								<td>
									<div class="tDiv"><?php if ($this->_var['list']['grade_img']): ?><a href="../<?php echo $this->_var['list']['grade_img']; ?>"  title="<?php echo $this->_var['lang']['see_img']; ?>" target="_blank"><img src="../<?php echo $this->_var['list']['grade_img']; ?>" width="20" /></a><?php endif; ?></div>
								</td>  
							    <td class="handle">
                                    <div class="tDiv ht_tdiv ht_tdiv_w130">
                                        <a href="merchants_privilege.php?act=allot&grade_id=<?php echo $this->_var['list']['id']; ?>" title="<?php echo $this->_var['lang']['icon_priv']; ?>" class="btn_see" ><i class="icon icon-cog"></i><?php echo $this->_var['lang']['icon_priv']; ?></a>
                                        <a href="seller_grade.php?act=edit&id=<?php echo $this->_var['list']['id']; ?>" title="<?php echo $this->_var['lang']['edit']; ?>" class="btn_edit"><i class="icon icon-edit"></i><?php echo $this->_var['lang']['edit']; ?></a>
                                        <a href="javascript:;" onclick="listTable.remove(<?php echo $this->_var['list']['id']; ?>, '<?php echo $this->_var['lang']['drop_confirm']; ?>')" title="<?php echo $this->_var['lang']['remove']; ?>" class="btn_trash"><i class="icon icon-trash"></i><?php echo $this->_var['lang']['drop']; ?></a>
                                    </div>
								</td>
								</tr>
								<?php endforeach; else: ?>
								<tr><td class="no-records"  colspan="11"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
								<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                            <tfoot>
                            	<tr>
                                    <td colspan="12">
                                    	<div class="list-page">
                                            <?php echo $this->fetch('library/page.lbi'); ?>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <?php if ($this->_var['full_page']): ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
</body>
</html>
<?php endif; ?>