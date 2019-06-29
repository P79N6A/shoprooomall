<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body">
	<div class="warpper">
    	<div class="title"><a href="seller_grade.php?act=list" class="s-back" ></a><?php echo $this->_var['lang']['seller']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
        	<?php echo $this->fetch('library/seller_step_tab.lbi'); ?>		
        	<div class="explanation" id="explanation">
            	<div class="ex_tit">
					<i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span>
                    <?php if ($this->_var['open'] == 1): ?>
                    <div class="view-case">
                    	<div class="view-case-tit"><i></i><?php echo $this->_var['lang']['view_tutorials']; ?></div>
                        <div class="view-case-info">
                        	<a href="http://help.ecmoban.com/article-3221.html" target="_blank"><?php echo $this->_var['lang']['tutorials_bonus_list_one']; ?></a>
                        </div>
                    </div>			
                    <?php endif; ?>				
				</div>
                <ul>
                	<li><?php echo $this->_var['lang']['operation_prompt_content']['allot']['0']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['allot']['1']; ?></li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="common-content">
                    <div class="mian-info">
						<form method="POST" action="merchants_privilege.php" name="theFrom">
                    	<div class="switch_info business_info" style="background:none;">
                            <div class="step">
                            	<div class="tit">
                                    <div class="checkbox_items">
                                    	<div class="checkbox_item"><?php echo $this->_var['lang']['require_field']; ?><?php echo $this->_var['lang']['grade_allot']; ?></div>
                                    </div>
                                </div>
                                <div class="qx_items">
                                	<div class="qx_item">
                                        <div class="center">
                                            <div id="grade_id" class="imitate_select select_w320">
                                                <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                                                <ul>
                                                    <?php $_from = $this->_var['seller_grade']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'seller_grade_0_78324600_1553747986');if (count($_from)):
    foreach ($_from AS $this->_var['seller_grade_0_78324600_1553747986']):
?>
                                                    <li><a href='javascript:;' data-value='<?php echo $this->_var['seller_grade_0_78324600_1553747986']['id']; ?>' class='ftx-01'><?php echo $this->_var['seller_grade_0_78324600_1553747986']['grade_name']; ?></a></li>
                                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                                </ul>
                                                <input name="grade_id" type="hidden" value="<?php echo $this->_var['grade_id']; ?>" id="grade_id_val">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step">
                            	<div class="tit">
                                    <div class="checkbox_items">
                                    	<div class="checkbox_item"><?php echo $this->_var['lang']['initialize_merchants_allot']; ?></div>
                                    </div>
                                </div>
                                <div class="qx_items">
                                	<div class="qx_item">
                                        <div class="checkbox_items">
                                            <div class="checkbox_item">
                                                <input type="checkbox" value="1" name="initialize_allot" id="initialize_allot" class="ui-checkbox"/>
                                                <label for="initialize_allot" class="ui-label"><?php echo $this->_var['lang']['edit_allot']; ?><font class="red"><?php echo $this->_var['lang']['prudent_operation']; ?></font></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="ajax_allot">
                                <?php echo $this->fetch('library/ajax_allot.lbi'); ?>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
	<script language="javascript">
		$.divselect("#grade_id","#grade_id_val",function(obj){
			var val = obj.attr("data-value");
			ajax_allot(val);
		});
		
		function ajax_allot(val){
			$.jqueryAjax('get_ajax_content.php', 'act=ajax_allot&grade_id='+val, function(data){
				$("#ajax_allot").html(data);
			});
		}

		$("#checkall").click(function(){
			var checkbox = $(this).parents(".switch_info").find('input:checkbox[type="checkbox"]');
			if($(this).prop("checked") == true){
				checkbox.prop("checked",true);
			}else{
				checkbox.prop("checked",false);
			}
		});
		
		$("input[name='chkGroup']").click(function(){
			var checkbox = $(this).parents(".tit").next(".qx_items").find('input:checkbox[type="checkbox"]');
			if($(this).prop("checked") == true){
				checkbox.prop("checked",true);
			}else{
				checkbox.prop("checked",false);
			}
		});
		
		$("input[name='action_code[]']").click(function(){    
			var qx_items = $(this).parents(".qx_items");
			var length = qx_items.find("input[name='action_code[]']").length;
			var length2 =  qx_items.find("input[name='action_code[]']:checked").length;
			if(length == length2){
				qx_items.prev().find("input[name='chkGroup']").prop("checked",true);
			}else{
				qx_items.prev().find("input[name='chkGroup']").prop("checked",false);
			}
		});
		
		$(".qx_items").each(function(index, element) {
			var length = $(this).find("input[name='action_code[]']").length;
			var length2 = $(this).find("input[name='action_code[]']:checked").length;
			
			if(length == length2){
				$(this).prev().find("input[name='chkGroup']").prop("checked",true);
			}else{
				$(this).prev().find("input[name='chkGroup']").prop("checked",false);
			}
		});
    </script>
</body>
</html>
