<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body class="iframe_body">
	<div class="warpper shop_special">
    	<div class="title"><?php echo $this->_var['lang']['11_system']; ?> - <?php echo $this->_var['ur_here']; ?></div>
		<div class="content">
        	<div class="tabs_info">
            	<ul>
                    <?php $_from = $this->_var['group_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'group');$this->_foreach['bar_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['bar_group']['total'] > 0):
    foreach ($_from AS $this->_var['group']):
        $this->_foreach['bar_group']['iteration']++;
?>
                    <li class="<?php if (($this->_foreach['bar_group']['iteration'] <= 1)): ?>curr<?php endif; ?>"><a href="javascript:void(0);"><?php echo $this->_var['group']['name']; ?></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <div class="explanation" id="explanation">
                <div class="ex_tit">
                    <i class="sc_icon"></i>
                    <h4><?php echo $this->_var['lang']['operating_hints']; ?></h4>
                    <span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span>
                    <?php if ($this->_var['open'] == 1): ?>
                    <div class="view-case">
                    	<div class="view-case-tit"><i></i><?php echo $this->_var['lang']['view_tutorials']; ?></div>
                        <div class="view-case-info"><?php echo $this->_var['lang']['tutorials_bonus_list_one']; ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <ul>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['shop_config']['0']; ?></li>
                    <li><?php echo $this->_var['lang']['operation_prompt_content_common']; ?></li>
                </ul>
            </div>
            <div class="flexilist">
                <div class="mian-info">
                    <form enctype="multipart/form-data" name="theForm" action="?act=post" method="post" id="shopConfigForm">
                        <?php $_from = $this->_var['group_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'group');$this->_foreach['body_group'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['body_group']['total'] > 0):
    foreach ($_from AS $this->_var['group']):
        $this->_foreach['body_group']['iteration']++;
?>
                        <div class="switch_info shopConfig_switch"<?php if ($this->_foreach['body_group']['iteration'] != 1): ?> style="display:none"<?php endif; ?>>
                            <?php $_from = $this->_var['group']['vars']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'var');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['var']):
?>
                                <?php echo $this->fetch('library/shop_config_form.lbi'); ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <div class="item">
                                <div class="label">&nbsp;</div>
                                <div class="label_value info_btn">
                                    <input type="submit" value="<?php echo $this->_var['lang']['button_submit']; ?>" ectype="btnSubmit" class="button" >	
                                </div>
                            </div>
                        </div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <?php echo $this->smarty_insert_scripts(array('files'=>'jquery.purebox.js')); ?>
    
    <script type="text/javascript" src="js/region.js"></script>
    <script type="text/javascript">
	$(function(){
		//地区三级联动调用
		$.levelLink();
		
		//图片点击放大
		$('.nyroModal').nyroModal();
		
		//表单验证
		$("[ectype='btnSubmit']").click(function(){
			var invoice_type = $("input[name='invoice_type[]']");
			var invoice_type_val = "";
			var arr = [];
			
			invoice_type.each(function(){
				invoice_type_val = $(this).val();
				arr.push(invoice_type_val);
			});
			
           	var nary = arr.sort();
			
			for(var i = 0; i < nary.length - 1; i++)
			{
			   if (nary[i] == nary[i+1])
				{
				   alert("<?php echo $this->_var['lang']['btnSubmit_notice']; ?>" + nary[i]);
				   return false;
				}
			}
			
			if($("#shopConfigForm").valid()){
				$("#shopConfigForm").submit();
			}
		});
		
		$('#shopConfigForm').validate({
			errorPlacement:function(error, element){
				var error_div = element.parents('div.label_value').find('div.form_prompt');
				element.parents('div.label_value').find(".notic").hide();
				error_div.append(error);
			},
			rules:{
				"value[101]" :{
					required : true
				}
			},
			messages:{
				"value[101]" :{
					 required : '<i class="icon icon-exclamation-sign"></i>'+seller_info_notic
				}
			}			
		});
	});


	/*url重写验证*/
	var ReWriteSelected = null;
	var ReWriteRadiobox = document.getElementsByName("value[209]");
	
	for (var i=0; i<ReWriteRadiobox.length; i++)
	{
	  if (ReWriteRadiobox[i].checked)
	  {
		ReWriteSelected = ReWriteRadiobox[i];
	  }
	}
	
	function ReWriterConfirm(sender)
	{
	  if (sender == ReWriteSelected) return true;
	  var res = true;
	  if (sender != ReWriteRadiobox[0]) {
		var res = confirm('<?php echo $this->_var['rewrite_confirm']; ?>');
	  }
	
	  if (res==false)
	  {
		  ReWriteSelected.checked = true;
	  }
	  else
	  {
		ReWriteSelected = sender;
	  }
	  return res;
	}
	
	function addCon_amount(obj)
	{  
		var obj = $(obj);
		var tbl = obj.parents('#consumtable');
		var fald = true;
		var fald2 = true;
		var error = "";
		var volumeNum = obj.siblings("input");
		var it_val ="";
		
		var new_it_val = obj.siblings("input[name='invoice_type[]']").val();
		
		tbl.find(".mt10").each(function(){
			var it_input =$(this).find("input[name='invoice_type[]']");
			it_val = it_input.val();
			
			if(new_it_val == it_val){
				obj.siblings("input[name='invoice_type[]']").addClass("error");
				fald2 = false;
				error = "<?php echo $this->_var['lang']['type_already_exists']; ?>";
			}
		});
		if(fald2 == true){
			volumeNum.each(function(index,element){
				var val = $(this).val(); 
				if(val == ""){
					$(this).addClass("error");
					fald = false;
					error = "<?php echo $this->_var['lang']['type_taxrate_not_notic']; ?>";
				}else if(!(/^[0-9]+.?[0-9]*$/.test(val)) && index == 1){
					$(this).addClass("error");
					fald = false;
					error = "<?php echo $this->_var['lang']['taxrate_number']; ?>";
				}else{
					$(this).removeClass("error");
					fald = true;
				}
			});
		
			if(fald == true){
				var input = tbl.find('p:first').clone();
				input.addClass("mt10");
				input.find("input[type='button']").remove();
				input.find(".form_prompt").remove();
				input.append("<a href='javascript:;' class='removeV' onclick='removeCon_amount(this)'><img src='images/no.gif' title='<?php echo $this->_var['lang']['drop']; ?>'></a>")
				tbl.append(input);
				volumeNum.val("");
			}else{
				obj.next(".form_prompt").find(".error").remove();
				obj.next(".form_prompt").append("<label class='error'><i class='icon icon-exclamation-sign'></i>"+error+"</label>"); 
			}
		}else{
			obj.next(".form_prompt").find(".error").remove();
			obj.next(".form_prompt").append("<label class='error'><i class='icon icon-exclamation-sign'></i>"+error+"</label>"); 
		}
	}

	function removeCon_amount(obj)
	{
		var obj = $(obj);
		obj.parent('p').remove();
	}
    </script>
</body>
</html>
