<?php if ($this->_var['temp'] == 'addCategory'): ?>
<div class="dialog_addCategory">
	<dl>
    	<dt><?php echo $this->_var['lang']['category_name']; ?>：</dt>
        <dd><input type="text" class="text text_2" name="addedCategoryName" id="addedCategoryName" value="" autocomplete="off" /></dd>
    </dl>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBrand'): ?>
<div class="dialog_addBrand">
	<dl>
    	<dt><?php echo $this->_var['lang']['brand_name']; ?>：</dt>
        <dd><input type="text" class="text text_2" name="addBrandName" id="addBrandName" value="" autocomplete="off" /></dd>
    </dl>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addWarehouse'): ?>
<div class="addWarehouse">
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_name']; ?>：</dt>
        <dd>
            <div class="imitate_select select_w140">
                <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                <ul>
                	<?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                    <li><a href="javascript:;" data-value="<?php echo $this->_var['warehouse']['region_id']; ?>" class="ftx-01"><?php echo $this->_var['warehouse']['region_name']; ?></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                <input name="warehouse_name" type="hidden" value="" id="warehouse_name">
            </div>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_number']; ?>：</dt>
        <dd><input name="warehouse_number" id="warehouse_number" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_price']; ?>：</dt>
        <dd><input name="warehouse_price" id="warehouse_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_promote_price']; ?>：</dt>
        <dd><input name="warehouse_promote_price" id="warehouse_promote_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    
    <dl>
        <dt><?php echo $this->_var['lang']['lab_give_integral']; ?></dt>
        <dd>
        	<input name="give_integral" id="warehouse_give_integral" value="0" type="text" size="10" class="text text_2" rev="give" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="give_html"><?php echo $this->_var['lang']['can_setup']; ?><em id="give">0</em><?php echo $this->_var['lang']['spand_integral']; ?></span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_rank_integral']; ?></dt>
        <dd>
        	<input name="rank_integral" id="warehouse_rank_integral" value="0" type="text" size="10" class="text text_2" rev="rank" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="rank_html"><?php echo $this->_var['lang']['can_setup']; ?><em id="rank">0</em><?php echo $this->_var['lang']['level_integral']; ?></span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_integral']; ?></dt>
        <dd>
        	<input name="pay_integral" id="warehouse_pay_integral" value="0" type="text" size="10" class="text text_2" rev="pay" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="pay_html"><?php echo $this->_var['lang']['can_setup_integral_buy']; ?><em id="pay">0</em><?php echo $this->_var['lang']['money']; ?></span>
            <?php endif; ?>
        </dd>
    </dl>
</div>
<script type="text/javascript">
<?php if ($this->_var['user_id']): ?>
$(function(){
	$('#warehouse_price, #warehouse_promote_price').blur(function(){
		var warehouse_price = Number($("#warehouse_price").val());
		var warehouse_promote_price = Number($("#warehouse_promote_price").val());
		var shop_price;
		
		if(warehouse_price > warehouse_promote_price && warehouse_promote_price == 0){
			shop_price = warehouse_price;
		}else if(warehouse_price < warehouse_promote_price && warehouse_promote_price != 0){
			shop_price = warehouse_price;
		}else{
			shop_price = warehouse_promote_price;
		}
		
		var give_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['give_integral']; ?>);

		$("#give").html(give_integral);
		
		var rank_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['rank_integral']; ?>);
		$("#rank").html(rank_integral);
		
		var pay_integral = Math.floor(shop_price / 100 * <?php echo $this->_var['integral_scale']; ?> * <?php echo $this->_var['grade_rank']['pay_integral']; ?>);
		$("#pay").html(pay_integral);
		
		$("#warehouse_give_integral").val('');
		$("#warehouse_rank_integral").val('');
		$("#warehouse_pay_integral").val('');
	});
	
	$('#warehouse_give_integral, #warehouse_rank_integral, #warehouse_pay_integral').blur(function(){
		var give = $('#give').html();
		var rank = $('#rank').html();
		var pay = $('#pay').html();
		var val = $(this).val();
		var rev = $(this).attr('rev');
		var integral = $("#" + rev).html();
		if(val > integral){
			if(rev == 'give'){
				alert(jl_can_setup + integral + jl_spand_integral);
			}else if(rev == 'rank'){
				alert(jl_can_setup + integral + jl_level_integral);
			}else{
				alert(jl_can_setup_integral_buy + integral + jl_money);
			}
			$(this).val(integral);
		}
	});
	
});
<?php endif; ?>
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBatchWarehouse'): ?>
<div class="warehouse_warpper" id="batchWarehouelist">
	<div class="add_warehouse_list">
		<div class="warehouse_item">
			<span class="item">
				<span class="tit"><?php echo $this->_var['lang']['warehouse_name']; ?></span>
				
                <div class="imitate_select select_w140">
                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                    <ul>
                        <?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['warehouse']['region_id']; ?>" class="ftx-01"><?php echo $this->_var['warehouse']['region_name']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input name="warehouse_name" type="hidden" value="0" id="warehouse_name">
                </div>						
			</span>
			<span class="item"><span class="tit"><?php echo $this->_var['lang']['warehouse_stock']; ?></span><input type="text" value="0" name="warehouse_number" class="text w65" autocomplete="off" /></span>
			<span class="item"><span class="tit"><?php echo $this->_var['lang']['warehouse_price']; ?></span><input type="text" value="0" name="warehouse_price" class="text w65" autocomplete="off" /></span>
			<span class="item last"><span class="tit"><?php echo $this->_var['lang']['warehouse_promotion_price']; ?></span><input type="text" value="0" name="warehouse_promote_price" class="text w65" autocomplete="off" /></span>
			<div class="hide">
				<span class="item"><span class="tit"><?php echo $this->_var['lang']['give_spand_integral']; ?></span><input type="text" value="0" name="give_integral" class="text w65" autocomplete="off" /></span>
				<span class="item"><span class="tit"><?php echo $this->_var['lang']['give_level_integral']; ?></span><input type="text" value="0" name="rank_integral" class="text w65" autocomplete="off" /></span>
				<span class="item"><span class="tit"><?php echo $this->_var['lang']['integral_purchase_amount']; ?></span><input type="text" value="0" name="pay_integral" class="text w65" autocomplete="off" /></span>
			</div>
		</div>
		<a href="javascript:void(0);" class="addList"></a>
	</div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addRegion'): ?>
<div class="addWarehouse">
    <dl>
        <dt><?php echo $this->_var['lang']['warehouse_region_name']; ?>：</dt>
        <dd>
            <select name="warehouse_area_name" onchange="get_warehouse_area_name(this.value, this.id, <?php echo $this->_var['goods_id']; ?>, <?php echo $this->_var['user_id']; ?>)" id="1" class="select" style=" margin:0 10px 0 0;">
                <option value="0" selected><?php echo $this->_var['lang']['select_please']; ?></option>
                <?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                <option value="<?php echo $this->_var['warehouse']['region_id']; ?>"><?php echo $this->_var['warehouse']['region_name']; ?></option>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
            <font style="font-size:12px; float:left;" id="warehouse_area_list_1" name="warehouse_area_list"></font>
        </dd>
    </dl>    
    <dl>
        <dt><?php echo $this->_var['lang']['region_number']; ?>：</dt>
        <dd><input name="region_number" id="region_number" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['region_price']; ?>：</dt>
        <dd><input name="region_price" id="region_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['region_promote_price']; ?>：</dt>
        <dd><input name="region_promote_price" id="region_promote_price" value="0" type="text" size="10" class="text text_2" autocomplete="off" /></dd>
    </dl>
    
    <dl>
        <dt><?php echo $this->_var['lang']['lab_give_integral']; ?></dt>
        <dd>
        	<input name="give_integral" id="region_give_integral" value="0" type="text" size="10" class="text text_2" rev="give" autocomplete="off" />
            <?php if ($this->_var['user_id']): ?>
        	&nbsp;<span class="color999" id="give_html"><?php echo $this->_var['lang']['can_setup']; ?><em id="give">0</em><?php echo $this->_var['lang']['spand_integral']; ?></span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_rank_integral']; ?></dt>
        <dd>
        	<input name="rank_integral" id="region_rank_integral" value="0" type="text" size="10" class="text text_2" rev="rank" autocomplete="off" />
        	<?php if ($this->_var['user_id']): ?>
            &nbsp;<span class="color999" id="rank_html"><?php echo $this->_var['lang']['can_setup']; ?><em id="rank">0</em><?php echo $this->_var['lang']['level_integral']; ?></span>
            <?php endif; ?>
        </dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['lab_integral']; ?></dt>
        <dd>
        	<input name="pay_integral" id="region_pay_integral" value="0" type="text" size="10" class="text text_2" rev="pay" autocomplete="off" />
        	<?php if ($this->_var['user_id']): ?>
        	&nbsp;<span class="color999" id="pay_html"><?php echo $this->_var['lang']['can_setup_integral_buy']; ?><em id="pay">0</em><?php echo $this->_var['lang']['money']; ?></span>
            <?php endif; ?>
        </dd>
    </dl>
</div>
<script type="text/javascript">
<?php if ($this->_var['user_id']): ?>
$(function(){
	$('#region_price, #region_promote_price').blur(function(){
		var region_price = Number($('#region_price').val());
		var region_promote_price = Number($('#region_promote_price').val());
		var shop_price;
		
		if(region_price > region_promote_price && region_promote_price == 0){
			shop_price = region_price;
		}else if(region_price < region_promote_price && region_promote_price != 0){
			shop_price = region_price;
		}else{
			shop_price = region_promote_price;
		}
		
		var give_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['give_integral']; ?>);

		$("#give").html(give_integral);
		
		var rank_integral = Math.floor(shop_price * <?php echo $this->_var['grade_rank']['rank_integral']; ?>);
		$("#rank").html(rank_integral);
		
		var pay_integral = Math.floor(shop_price / 100 * <?php echo $this->_var['integral_scale']; ?> * <?php echo $this->_var['grade_rank']['pay_integral']; ?>);
		$("#pay").html(pay_integral);
		
		$("#warehouse_give_integral").val('');
		$("#warehouse_rank_integral").val('');
		$("#warehouse_pay_integral").val('');
	});
	
	$('#region_give_integral, #region_rank_integral, #region_pay_integral').blur(function(){
		var give = $('#give').html();
		var rank = $('#rank').html();
		var pay = $('#pay').html();
		var val = $(this).val();
		var rev = $(this).attr('rev');
		var integral = $("#" + rev).html();
		if(val > integral){
			if(rev == 'give'){
				alert(jl_can_setup + integral + jl_spand_integral);
			}else if(rev == 'rank'){
				alert(jl_can_setup + integral + jl_level_integral);
			}else{
				alert(jl_can_setup_integral_buy + integral + jl_money);
			}
			$(this).val(integral);
		}
	});
	
});
<?php endif; ?>
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBatchRegion'): ?>
<div class="warehouse_warpper" id="batchRegionlist">
	<div class="add_warehouse_list">
		<div class="warehouse_item" id="area_1">
			<span class="item">
				<span class="tit"><?php echo $this->_var['lang']['area_name']; ?></span>
                <div class="imitate_select select_w140 warehouse_area_name" data-key="1" data-goodsid="<?php echo $this->_var['goods_id']; ?>" data-userid="<?php echo $this->_var['user_id']; ?>" id="warehouse_area_name_1">
                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                    <ul>
                        <?php $_from = $this->_var['warehouse_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'warehouse');$this->_foreach['nowarehouse'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nowarehouse']['total'] > 0):
    foreach ($_from AS $this->_var['warehouse']):
        $this->_foreach['nowarehouse']['iteration']++;
?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['warehouse']['region_id']; ?>" class="ftx-01"><?php echo $this->_var['warehouse']['region_name']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input name="warehouse_area_name" type="hidden" value="" id="warehouse_area_name_val_1">
                </div>
				<font style="font-size:12px;" id="warehouse_area_list_1" name="warehouse_area_list"></font>									
			</span>
			<span class="item"><span class="tit"><?php echo $this->_var['lang']['area_stock']; ?></span><input type="text" value="0" name="region_number" class="text w65" autocomplete="off" /></span>
			<span class="item"><span class="tit"><?php echo $this->_var['lang']['area_price']; ?></span><input type="text" value="0" name="region_price" class="text w65" autocomplete="off" /></span>
			<span class="item"><span class="tit"><?php echo $this->_var['lang']['area_promotion_price']; ?></span><input type="text" value="0" name="region_promote_price" class="text w65" autocomplete="off" /></span>
			<div class="hide">
				<span class="item"><span class="tit"><?php echo $this->_var['lang']['give_spand_integral']; ?></span><input type="text" value="0" name="give_integral" class="text w65" autocomplete="off" /></span>
				<span class="item"><span class="tit"><?php echo $this->_var['lang']['give_level_integral']; ?></span><input type="text" value="0" name="rank_integral" class="text w65" autocomplete="off" /></span>
				<span class="item last"><span class="tit"><?php echo $this->_var['lang']['integral_purchase_amount']; ?></span><input type="text" value="0" name="pay_integral" class="text w65" autocomplete="off" /></span>
			</div>
		</div>
		<a href="javascript:void(0);" class="addList"></a>
	</div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addImg'): ?>
<form  action="goods.php?act=addImg" id="fileForm" method="post"  enctype="multipart/form-data"  runat="server" >
<div class="addImg" id="addImg">
	<dl>
        <dt><?php echo $this->_var['lang']['img_count']; ?>：</dt>
        <dd><input type="text" class="text_3 mr10"  name="img_desc[]" size="20" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['img_url']; ?>：</dt>
        <dd><input type="file" name="img_url[]" id="img_url"  class="file mr10 mt5" autocomplete="off" /></dd>
    </dl>
    <dl>
        <dt><?php echo $this->_var['lang']['img_file']; ?>：</dt>
        <dd><input type="text" size="40" value="<?php echo $this->_var['lang']['img_file']; ?>" style="color:#aaa;" autocomplete="off" onfocus="if (this.value == '<?php echo $this->_var['lang']['img_file']; ?>'){this.value='http://';this.style.color='#000';}" name="img_file[]"/></dd>
    </dl>
    <input type="hidden"   value="<?php echo $this->_var['goods_id']; ?>" name="goods_id_img"/>
</div>
</form>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'addBatchImg'): ?>
<form  action="goods.php?act=addBatchImg" id="addBatchImg_from" method="post"  enctype="multipart/form-data"  runat="server" >
	<div class="img_item"  >
		<span class="red"><?php echo $this->_var['lang']['remind']; ?></span>
	</div>
	<div class="img_item">
    <a href="javascript:;" onclick="addImg(this)" class="up"></a>
    <?php echo $this->_var['lang']['img_count']; ?>：<input type="text" class="text_2 mr10" name="img_desc[]" size="20" autocomplete="off" />
    <?php echo $this->_var['lang']['img_url']; ?>：<input type="file" name="img_url[]" id="Batch_img_url" class="mr10" autocomplete="off" />
    <input type="text" size="40" value="<?php echo $this->_var['lang']['img_file']; ?>" style="color:#aaa;" autocomplete="off" onfocus="if (this.value == '<?php echo $this->_var['lang']['img_file']; ?>'){this.value='http://';this.style.color='#000';}" name="img_file[]"/>
    <input type="hidden"   value="<?php echo $this->_var['goods_id']; ?>" name="goods_id_img"/>
    </div>
</form>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'privilege'): ?>
<div class="dialog_privilege" id="dialog_privilege">
	<dl>
    	<dt><?php echo $this->_var['lang']['label_region']; ?>：</dt>
        <dd>
        	<select name="country" id="selCountries" onChange="region.changed(this, 1, 'selProvinces')" class="select">
              <?php $_from = $this->_var['countries']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'country');$this->_foreach['fe_country'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_country']['total'] > 0):
    foreach ($_from AS $this->_var['country']):
        $this->_foreach['fe_country']['iteration']++;
?>
                <option value="<?php echo $this->_var['country']['region_id']; ?>" <?php if (($this->_foreach['fe_country']['iteration'] <= 1)): ?>selected<?php endif; ?>><?php echo htmlspecialchars($this->_var['country']['region_name']); ?></option>
              <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </select>
            <select name="province" id="selProvinces" onChange="region.changed(this, 2, 'selCities')" class="select mr10">
              <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
            </select>
            <select name="city" id="selCities" onChange="region.changed(this, 3, 'selDistricts')" class="select mr10">
              <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
            </select>
            <select name="district" id="selDistricts" class="select mr10">
              <option value=""><?php echo $this->_var['lang']['select_please']; ?></option>
            </select>
        </dd>
    </dl>
</div>
<script type="text/javascript">
	var selCountry = document.getElementById("selCountries");
	if (selCountry.selectedIndex >= 0)
	{
		region.loadProvinces(selCountry.options[selCountry.selectedIndex].value);
	}
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'load_url'): ?>
<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>
<body>
	<div class="loadSpin">
		<i class="icon-spinner icon-spin"></i>
    </div>
</body>
</html>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'pic_album'): ?>
<div class="pic_album">
    <div class="items bor_bt_not">
        <div class="item album_Percent hide">
            <div class="label"><?php echo $this->_var['lang']['label_upload_progress']; ?></div>
            <div class="label_value">
                <div class="text_div mr0 w120 pl0"><span class="Percent_pic" ></span></div><div class="Percent"></div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_select_album']; ?></div>
            <div class="label_value">
                <div id="parent_cat" class="imitate_select select_w320">
                    <div class="cite"><?php if ($this->_var['album_mame']): ?><?php echo $this->_var['album_mame']; ?><?php else: ?><?php echo $this->_var['lang']['please_select']; ?><?php endif; ?></div>
                    <ul>
                        <?php $_from = $this->_var['cat_select']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['item']['album_id']; ?>"  class="ftx-01"><?php echo $this->_var['item']['name']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input name="album_id" type="hidden" id="album_number" value="<?php echo $this->_var['album_id']; ?>" >
                </div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['img_url']; ?>：</div>
            <div class="label_value">
                <div class="type-file-box">
                    <input type="button" name="button" id="button" class="type-file-button" value="" />
                    <span class="red ml10 lh30"><?php echo $this->_var['lang']['press_ctrl_multi_upload']; ?></span>
                </div>
                <div class="form_prompt"></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">
var uploader_gallery = new plupload.Uploader({//创建实例的构造方法
	runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
	browse_button: 'button', // 上传按钮
	url: "gallery_album.php?is_ajax=1&act=upload_pic", //远程上传地址
	filters: {
		max_file_size: '2mb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
		mime_types: [//允许文件上传类型
			{title: "files", extensions: "jpg,png,gif,mp4,jpeg"}
		]
	},
	multi_selection: true, //true:ctrl多文件上传, false 单文件上传
	init: {
		FilesAdded: function(up, files) { //文件上传前
			window.Percent = 0; //初始化进度
			var i = 0;
			plupload.each(files, function(file) { //遍历文件
				i ++;
			});
			
			window.Percentage = 1/i;//单个文件比例
			$(".album_Percent").show();
			album_submitBtn();
		},
		FileUploaded: function(up, file, info) { //文件上传成功的时候触发
			window.Percent = window.Percent + Percentage*100;
			
			$(".Percent_pic").css({"width": window.Percent + "%"});
			$(".Percent").html(Math.round(window.Percent) + "%");
		},
		UploadComplete:function(up,file){//所有文件上传成功时触发
			window.location.href="gallery_album.php?act=view&id=<?php echo $this->_var['album_id']; ?>"; 
		},
		Error: function(up, err) { //上传出错的时候触发
			alert(err.message);
		}
	}
});
uploader_gallery.init();
	function album_submitBtn(){
		var album_id = $("#album_number").val();
		var data = {
			album_id: album_id
		};
		uploader_gallery.setOption("multipart_params", data);
		uploader_gallery.start();
	};
	
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'album_move'): ?>
<div class="switch_info">
    <div class="items">
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_select_album']; ?></div>
            <div class="label_value">
                <div id="parent_cat" class="imitate_select select_w145">
                  <div class="cite"><?php if ($this->_var['album_mame']): ?><?php echo $this->_var['album_mame']; ?><?php else: ?><?php echo $this->_var['lang']['please_select']; ?><?php endif; ?></div>
                  <ul>
                    <?php $_from = $this->_var['cat_select']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <?php if ($this->_var['list']['album_id'] != $this->_var['album_id']): ?><li><a href="javascript:;" data-value="<?php echo $this->_var['list']['album_id']; ?>" class="ftx-01"><?php echo $this->_var['list']['name']; ?></a></li><?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                  </ul>
                  <input name="album_id" type="hidden" value="0" >
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
	//select下拉默认值赋值
	$('.imitate_select').each(function()
	{
		var sel_this = $(this)
		var val = sel_this.children('input[type=hidden]').val();
		sel_this.find('a').each(function(){
			if($(this).attr('data-value') == val){
				sel_this.children('.cite').html($(this).html());
			}
		})
	});
})
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'add_albun_pic'): ?>
<form id="add_albun_pic" method="post" enctype="multipart/form-data" runat="server" >
<div class="items">
    <div class="item">
        <div class="label"><em class="red">*</em><?php echo $this->_var['lang']['label_album_name']; ?></div>
        <div class="value">
            <input type="text" name='album_mame'  class="text" autocomplete="off" ectype="required" data-msg="<?php echo $this->_var['lang']['please_album_name']; ?>"/>
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_cover']; ?></div>
        <div class="value">
            <div class="type-file-box">
                <input type="button" name="button" id="button" class="type-file-button" value="">
                <input type="file" class="type-file-file" id="album_cover" name="album_cover" data-state="imgfile" size="30" hidefocus="true" value="">
                <input type="text" name="textfile" class="type-file-text" id="textfield" autocomplete="off" readonly>
            </div>
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_description']; ?></div>
        <div class="value">
            <textarea class="textarea" name="album_desc" id="role_describe"></textarea>
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_sort']; ?></div>
        <div class="value">
            <input type="text" name="sort_order" value="50" size="35" class="text w100" />
        </div>
    </div>
</div>
</form>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'img_list'): ?>
<?php if ($this->_var['mode'] != 'fh-discount' && $this->_var['mode'] != 'fh-hot' && $this->_var['mode'] != 'fh-pindao' && $this->_var['mode'] != 'fh-haohuo' && $this->_var['mode'] != 'h-phb'): ?>
<?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
	<?php if ($this->_var['mode'] == 'lunbo'): ?>
    <li style="background-image:url(<?php echo $this->_var['img']['pic_src']; ?>); background-position: center center;  background-repeat: no-repeat; background-color:<?php echo $this->_var['img']['bg_color']; ?>;"><div class="banner-width"><a href="<?php echo $this->_var['img']['link']; ?>" target="<?php echo $this->_var['attr']['target']; ?>" style="height:<?php echo $this->_var['attr']['picHeight']; ?>px;"></a></div></li>
    <?php elseif ($this->_var['mode'] == 'topBanner'): ?>
    <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img width="1200" height="80" src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_011.jpg<?php endif; ?>"></a>
    <i class="iconfont icon-cha" ectype="close"></i>
    <?php elseif ($this->_var['mode'] == 'h-streamer'): ?>
    <li><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>../data/gallery_album/visualDefault/ad_01_pic.jpg<?php endif; ?>"></a></li>
    <?php else: ?>
    <?php if ($this->_var['is_li'] == 1): ?><li style="height:<?php echo $this->_var['attr']['picHeight']; ?>px;"><?php endif; ?><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><?php if ($this->_var['mode'] == 'advImg4'): ?><span class="btm"></span><?php endif; ?><img src="<?php echo $this->_var['img']['pic_src']; ?>" width="<?php if ($this->_var['mode'] == 'advImg2'): ?>1200<?php else: ?><?php echo $this->_var['width']; ?><?php endif; ?>" height="<?php echo $this->_var['height']; ?>"></a><?php if ($this->_var['is_li'] == 1): ?></li><?php endif; ?>
    <?php endif; ?>
<?php endforeach; else: ?>
	<?php if ($this->_var['mode'] == 'advImg1'): ?>
		<li><img src="images/default/ad_01_pic.jpg"></li>
    <?php elseif ($this->_var['mode'] == 'advImg2'): ?>
    	<li><img src="images/default/ad_02_a_pic.jpg" width="595" height="595"></li>
        <li><img src="images/default/ad_02_a_pic.jpg" width="595" height="595"></li>
    <?php elseif ($this->_var['mode'] == 'advImg3'): ?>
    	<?php if ($this->_var['attr']['itemsLayout'] == "left-right"): ?>
    	<li><a href="#"><img src="images/default/ad_02_c_pic.jpg"></a></li>
        <li><a href="#"><img src="images/default/ad_02_d_pic.jpg"></a></li>
        <?php else: ?>
        <li><a href="#"><img src="images/default/ad_02_d_pic.jpg"></a></li>
        <li><a href="#"><img src="images/default/ad_02_c_pic.jpg"></a></li>
        <?php endif; ?>
    <?php elseif ($this->_var['mode'] == 'advImg4'): ?>
    	<?php if ($this->_var['attr']['itemsLayout'] == "row3"): ?>
    	<li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_03.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_03.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_03.jpg"></a></li>
        <?php elseif ($this->_var['attr']['itemsLayout'] == "row4"): ?>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_04.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_04.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_04.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_04.jpg"></a></li>
        <?php elseif ($this->_var['attr']['itemsLayout'] == "row5"): ?>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_02.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic_02.jpg"></a></li>
        <?php else: ?>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic.jpg"></a></li>
        <li><a href="#"><span class="btm"></span><img src="images/default/ad_03_pic.jpg"></a></li>
        <?php endif; ?>
    <?php elseif ($this->_var['mode'] == 'lunbo'): ?>
    	<li><a href="#"><img src="images/default/shop_banner_pic.jpg"></a></li>
    <?php elseif ($this->_var['mode'] == 'topBanner'): ?>
    <a href="#"><img width="1200" height="80" src="../data/gallery_album/visualDefault/homeIndex_011.jpg"></a>
    <i class="iconfont icon-cha" ectype="close"></i>
    <?php elseif ($this->_var['mode'] == 'h-streamer'): ?>
    <li><a href=""><img src="../data/gallery_album/visualDefault/ad_01_pic.jpg"></a></li>
    <?php endif; ?>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>

<?php if ($this->_var['mode'] == 'fh-hot'): ?>
<?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
<?php if (($this->_foreach['img_list']['iteration'] - 1) == 0): ?>
<li class="festival-adv-top"><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>../data/gallery_album/visualDefault/ad_01_pic.jpg<?php endif; ?>"></a></li>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<li class="festival-adv-bot">
<?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
    <?php if (($this->_foreach['img_list']['iteration'] - 1) != 0): ?>
    <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>../data/gallery_album/visualDefault/ad_01_pic.jpg<?php endif; ?>"></a>
    <?php endif; ?>
<?php endforeach; else: ?>
    <a href="#" target="_blank"><img src="images/visual/festival_home/adv/festival_adv_1.png"></a>
    <a href="#" target="_blank"><img src="images/visual/festival_home/adv/festival_adv_2.png"></a>
    <a href="#" target="_blank"><img src="images/visual/festival_home/adv/festival_adv_3.png"></a>
    <a href="#" target="_blank"><img src="images/visual/festival_home/adv/festival_adv_4.png"></a>
    <a href="#" target="_blank"><img src="images/visual/festival_home/adv/festival_adv_5.png"></a>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</li>
<?php endif; ?>

<?php if ($this->_var['mode'] == 'fh-discount'): ?>
<div class="venue_act_list">
    <?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
    <?php if (($this->_foreach['img_list']['iteration'] - 1) < 10): ?>
    <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>../data/gallery_album/visualDefault/ad_01_pic.jpg<?php endif; ?>"></a>
    <?php endif; ?>
    <?php endforeach; else: ?>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<div class="venue_shop_list">
    <?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
    <?php if (($this->_foreach['img_list']['iteration'] - 1) > 9 && ($this->_foreach['img_list']['iteration'] - 1) < 14): ?>
    <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>../data/gallery_album/visualDefault/ad_01_pic.jpg<?php endif; ?>"></a>
    <?php endif; ?>
    <?php endforeach; else: ?>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['mode'] == 'fh-pindao'): ?>
<?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
<li class="item<?php echo $this->_foreach['img_list']['iteration']; ?>">
    <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>">
        <span class="line n-line"></span>
        <p class="name"><?php if ($this->_var['img']['title']): ?><?php echo $this->_var['img']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></p>
        <span class="line w-line"></span>
        <p class="desc"><?php if ($this->_var['img']['subtitle']): ?><?php echo $this->_var['img']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></p>
        <img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>images/visual/festival_home/adv/pindao-enter-1.png<?php endif; ?>" alt="">
    </a>
</li>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['mode'] == 'fh-haohuo'): ?>
<div class="title">
    <h5><?php echo $this->_var['attr']['toptitle']; ?></h5>
    <a href="<?php if ($this->_var['attr']['toptitle_url'] && $this->_var['attr']['toptitle_url'] != 'http://'): ?><?php echo $this->_var['attr']['toptitle_url']; ?><?php endif; ?>" class="more" target="<?php echo $this->_var['attr']['target']; ?>"><span><?php if ($this->_var['attr']['hierarchy'] == 1): ?><?php echo $this->_var['lang']['more_good_goods']; ?><?php else: ?><?php echo $this->_var['lang']['more_special']; ?><?php endif; ?></span><i class="iconfont icon-right"></i></a>
</div>
<div class="second-content">
<?php if ($this->_var['attr']['hierarchy'] == 1): ?>
    <ul>
        <?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
        <?php if (($this->_foreach['img_list']['iteration'] - 1) == 0): ?>
        <li class="first">
            <div class="p-img p-img-scale"><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>images/visual/festival_home/adv/second_1.jpg<?php endif; ?>"></a></div>
            <div class="p-name"><?php if ($this->_var['img']['title']): ?><?php echo $this->_var['img']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></div>
            <div class="p-desc"><?php if ($this->_var['img']['subtitle']): ?><?php echo $this->_var['img']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></div>
            <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" class="sew_btn" target="<?php echo $this->_var['attr']['target']; ?>"><?php echo $this->_var['lang']['click_view']; ?></a>
        </li>
        <?php else: ?>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>images/visual/festival_home/adv/second_2.jpg<?php endif; ?>"></a></div>
            <div class="p-info">
                <div class="p-name"><?php if ($this->_var['img']['title']): ?><?php echo $this->_var['img']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></div>
                <div class="p-desc"><?php if ($this->_var['img']['subtitle']): ?><?php echo $this->_var['img']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></div>
            </div>
        </li>
        <?php endif; ?>
        <?php endforeach; else: ?>
        <li class="first">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_1.jpg"></a></div>
            <div class="p-name">阿迪达斯三叶草</div>
            <div class="p-desc">也许是每一款经典系列都应该有一个独特的故事吧</div>
            <a href="#" class="sew_btn" target="_blank"><?php echo $this->_var['lang']['click_view']; ?></a>
        </li>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_2.jpg"></a></div>
            <div class="p-info">
                <div class="p-name">攀升兄弟</div>
                <div class="p-desc">I7独显主机</div>
            </div>
        </li>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_3.jpg"></a></div>
            <div class="p-info">
                <div class="p-name">360行车记录</div>
                <div class="p-desc">夜视 监控 电子狗 蓝牙</div>
            </div>
        </li>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_4.jpg"></a></div>
            <div class="p-info">
                <div class="p-name">三星55吋</div>
                <div class="p-desc">4K处理器流畅不卡顿</div>
            </div>
        </li>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_5.jpg"></a></div>
            <div class="p-info">
                <div class="p-name">海尔对开门</div>
                <div class="p-desc">风冷无霜 低温净味</div>
            </div>
        </li>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_6.jpg"></a></div>
            <div class="p-info">
                <div class="p-name">华为智能腕表</div>
                <div class="p-desc">防水蓝宝石玻璃镜面</div>
            </div>
        </li>
        <li class="sew-item">
            <div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_7.jpg"></a></div>
            <div class="p-info">
                <div class="p-name">OPPO R11</div>
                <div class="p-desc">新品热力红 30天免息</div>
            </div>
        </li>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
<?php else: ?>
    <?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
    <div class="tj-item tj-item-<?php echo $this->_foreach['img_list']['iteration']; ?>">
        <p class="tit"><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><i class="tj-icon"><?php if ($this->_var['img']['title']): ?><?php echo $this->_var['img']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></i><?php if ($this->_var['img']['subtitle']): ?><?php echo $this->_var['img']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></a></p>
        <ul class="clearfix">
            <li><div class="p-img p-img-scale"><a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>images/visual/festival_home/adv/second_8.jpg<?php endif; ?>"></a></div></li>
        </ul>
    </div>
    <?php endforeach; else: ?>
    <div class="tj-item tj-item-1">
        <p class="tit"><a href="#" target="_blank"><i class="tj-icon">纸 因有爱</i>用心呵护您和家人的健康</a></p>
        <ul class="clearfix">
            <li><div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_8.jpg"></a></div></li>
        </ul>
    </div>
    <div class="tj-item tj-item-1">
        <p class="tit"><a href="#" target="_blank"><i class="tj-icon">纸 因有爱</i>用心呵护您和家人的健康</a></p>
        <ul class="clearfix">
            <li><div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_10.jpg"></a></div></li>
        </ul>
    </div>
    <div class="tj-item tj-item-1">
        <p class="tit"><a href="#" target="_blank"><i class="tj-icon">纸 因有爱</i>用心呵护您和家人的健康</a></p>
        <ul class="clearfix">
            <li><div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_12.jpg"></a></div></li>
        </ul>
    </div>
    <div class="tj-item tj-item-1">
        <p class="tit"><a href="#" target="_blank"><i class="tj-icon">纸 因有爱</i>用心呵护您和家人的健康</a></p>
        <ul class="clearfix">
            <li><div class="p-img p-img-scale"><a href="#" target="_blank"><img src="images/visual/festival_home/adv/second_14.jpg"></a></div></li>
        </ul>
    </div>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <div class="line"></div>
<?php endif; ?>
    </div>
<?php endif; ?>
<?php if ($this->_var['mode'] == 'h-phb'): ?>
<div class="title">
    <h5><?php echo $this->_var['attr']['toptitle']; ?></h5>
    <a href="<?php if ($this->_var['attr']['toptitle_url'] && $this->_var['attr']['toptitle_url'] != 'http://'): ?><?php echo $this->_var['attr']['toptitle_url']; ?><?php endif; ?>" class="more" target="<?php echo $this->_var['attr']['target']; ?>"><span><?php if ($this->_var['attr']['hierarchy'] == 1): ?><?php echo $this->_var['lang']['more_special_sale']; ?><?php elseif ($this->_var['attr']['hierarchy'] == 2): ?><?php echo $this->_var['lang']['more_new_goods']; ?><?php else: ?><?php echo $this->_var['lang']['best_goods_vane']; ?><?php endif; ?></span><i class="iconfont icon-right"></i></a>
</div>
<div class="second-content">
    <?php if ($this->_var['attr']['hierarchy'] != 3): ?>
    <ul>
        <?php $_from = $this->_var['img_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'img');$this->_foreach['img_list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['img_list']['total'] > 0):
    foreach ($_from AS $this->_var['img']):
        $this->_foreach['img_list']['iteration']++;
?>
        <li <?php if (($this->_foreach['img_list']['iteration'] - 1) == 0 && $this->_var['attr']['hierarchy'] == 1): ?>class="first"<?php endif; ?>>
            <a href="<?php if ($this->_var['img']['link']): ?><?php echo $this->_var['img']['link']; ?><?php else: ?>#<?php endif; ?>" target="<?php echo $this->_var['attr']['target']; ?>">
                <div class="p-name"><?php if ($this->_var['img']['title']): ?><?php echo $this->_var['img']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></div>
                <div class="p-desc"><?php if ($this->_var['img']['subtitle']): ?><?php echo $this->_var['img']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></div>
                <div class="p-img p-img-scale"><img src="<?php if ($this->_var['img']['pic_src']): ?><?php echo $this->_var['img']['pic_src']; ?><?php else: ?>images/visual/festival_home/adv/second_16.jpg<?php endif; ?>" alt=""></div>
            </a>
        </li>
        <?php endforeach; else: ?>
        <li class="first">
            <a href="#" target="_blank">
                <div class="p-name">新年心愿单</div>
                <div class="p-desc">满269减50,满999减100</div>
                <div class="p-img p-img-scale"><img src="images/visual/festival_home/adv/second_16.jpg" alt=""></div>
            </a>
        </li>
        <li>
            <a href="#" target="_blank">
                <div class="p-name">茵曼 新年新衣</div>
                <div class="p-desc">满500减100</div>
                <div class="p-img p-img-scale"><img src="images/visual/festival_home/adv/second_17.jpg" alt=""></div>
            </a>
        </li>
        <li>
            <a href="#" target="_blank">
                <div class="p-name">茵曼 新年新衣</div>
                <div class="p-desc">满500减100</div>
                <div class="p-img p-img-scale"><img src="images/visual/festival_home/adv/second_18.jpg" alt=""></div>
            </a>
        </li>
        <li>
            <a href="#" target="_blank">
                <div class="p-name">茵曼 新年新衣</div>
                <div class="p-desc">满500减100</div>
                <div class="p-img p-img-scale"><img src="images/visual/festival_home/adv/second_19.jpg" alt=""></div>
            </a>
        </li>
        <li>
            <a href="#" target="_blank">
                <div class="p-name">茵曼 新年新衣</div>
                <div class="p-desc">满500减100</div>
                <div class="p-img p-img-scale"><img src="images/visual/festival_home/adv/second_20.jpg" alt=""></div>
            </a>
        </li>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <?php else: ?>
    <div class="com-list" ectype="h-phb3" data-goodsids="<?php echo $this->_var['attr']['activity_goods']; ?>" data-activitytype="<?php echo $this->_var['attr']['activity_type']; ?>">
        <div class="com-ul" >
            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['list']['iteration']++;
?>
            <?php if ($this->_foreach['list']['iteration'] < 4): ?>
            <div class="com-li">
                <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
                    <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
                    <div class="p-name"><?php echo $this->_var['goods']['goods_name']; ?></div>
                    <div class="p-price">
                        <?php if ($this->_var['attr']['activity_type'] == 'exchange'): ?>
                        <?php echo $this->_var['goods']['exchange_integral']; ?>
                        <?php else: ?>
                        <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                        <?php echo $this->_var['goods']['promote_price']; ?>
                        <?php else: ?>
                        <?php echo $this->_var['goods']['shop_price']; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <i class="ph-icon ph-icon<?php echo $this->_foreach['list']['iteration']; ?>"><?php echo $this->_foreach['list']['iteration']; ?></i>
                </a>
            </div>
            <?php endif; ?>
            <?php endforeach; else: ?>
            <div class="com-li">
                <a href="#" target="_blank">
                    <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
                    <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
                    <div class="p-price"><em>¥</em>8388.00</div>
                    <i class="ph-icon ph-icon2">2</i>
                </a>
            </div>
            <div class="com-li">
                <a href="#" target="_blank">
                    <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
                    <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
                    <div class="p-price"><em>¥</em>8388.00</div>
                    <i class="ph-icon ph-icon2">2</i>
                </a>
            </div>
            <div class="com-li">
                <a href="#" target="_blank">
                    <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
                    <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
                    <div class="p-price"><em>¥</em>8388.00</div>
                    <i class="ph-icon ph-icon3">3</i>
                </a>
            </div>
            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <div class="com-ul">
            <?php if ($this->_var['goods_list']): ?>
        	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['list'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['list']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['list']['iteration']++;
?>
                    <?php if ($this->_foreach['list']['iteration'] > 3): ?>
                    <div class="com-li">
                        <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
                            <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
                            <div class="p-name"><?php echo $this->_var['goods']['goods_name']; ?></div>
                            <div class="p-price">
                                <?php if ($this->_var['attr']['activity_type'] == 'exchange'): ?>
                                <?php echo $this->_var['goods']['exchange_integral']; ?>
                                <?php else: ?>
                                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                                <?php echo $this->_var['goods']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['goods']['shop_price']; ?>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <i class="ph-icon ph-icon<?php echo $this->_foreach['list']['iteration']; ?>"><?php echo $this->_foreach['list']['iteration']; ?></i>
                        </a>
                    </div>
                    <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php else: ?>
            <div class="com-li">
                <a href="#" target="_blank">
                    <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
                    <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
                    <div class="p-price"><em>¥</em>8388.00</div>
                    <i class="ph-icon ph-icon4">4</i>
                </a>
            </div>
            <div class="com-li">
                <a href="#" target="_blank">
                    <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
                    <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
                    <div class="p-price"><em>¥</em>8388.00</div>
                    <i class="ph-icon ph-icon5">5</i>
                </a>
            </div>
            <div class="com-li">
                <a href="#" target="_blank">
                    <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
                    <div class="p-name">【享12期免息】Apple iPhone X 64GB 深空灰 移动联通电信4G手机</div>
                    <div class="p-price"><em>¥</em>8388.00</div>
                    <i class="ph-icon ph-icon6">6</i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'goods_list'): ?>
<div class="gallery_album" data-act="<?php if ($this->_var['action']): ?><?php echo $this->_var['action']; ?><?php else: ?>changedgoods<?php endif; ?>" data-goods='1' data-inid="goods_list" data-url='<?php if ($this->_var['action']): ?><?php echo $this->_var['url']; ?><?php else: ?>get_ajax_content.php<?php endif; ?>' data-where="cat_id=<?php echo $this->_var['filter']['cat_id']; ?>&sort_order=<?php echo $this->_var['filter']['sort_order']; ?>&keyword=<?php echo $this->_var['filter']['keyword']; ?>&search_type=<?php echo $this->_var['filter']['search_type']; ?>&goods_id=<?php echo $this->_var['filter']['goods_id']; ?>&ru_id=<?php echo $this->_var['filter']['ru_id']; ?>&type=1&PromotionType=<?php echo $this->_var['PromotionType']; ?>&temp=<?php echo $this->_var['filter']['temp']; ?>&time_bucket=<?php echo $this->_var['filter']['time_bucket']; ?>">
    <ul class="ga-goods-ul" id="goods_list">
        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['gl'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['gl']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['gl']['iteration']++;
?>
        <li class="<?php if ($this->_var['goods']['is_selected'] == 1): ?>on<?php endif; ?>">
            <div class="img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
            <div class="name"><?php echo $this->_var['goods']['goods_name']; ?></div>
            <div class="price">
                <?php if ($this->_var['PromotionType'] == 'exchange'): ?>
                    <?php echo $this->_var['goods']['exchange_integral']; ?>
                <?php else: ?>
                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                        <?php echo $this->_var['goods']['promote_price']; ?>
                    <?php else: ?>
                        <?php echo $this->_var['goods']['shop_price']; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="choose">
                <a href="javascript:void(0);" <?php if ($this->_var['goods']['is_selected'] == 1): ?>class="on"<?php endif; ?> onclick="selected_goods(this,'<?php echo $this->_var['goods']['goods_id']; ?>')"><i class="iconfont <?php if ($this->_var['goods']['is_selected'] == 1): ?>icon-gou<?php else: ?>icon-dsc-plus<?php endif; ?>"></i><?php if ($this->_var['goods']['is_selected'] == 1): ?><?php echo $this->_var['lang']['selected']; ?><?php else: ?><?php echo $this->_var['lang']['btn_select']; ?><?php endif; ?></a>
                <?php if ($this->_var['PromotionType'] && $this->_var['activity_dialog'] != 1): ?>
                <div class="checkbox_item"> 
                    <input name="recommend" type="radio" class="ui-radio" value="<?php echo $this->_var['goods']['goods_id']; ?>" id="recommend<?php echo $this->_var['goods']['goods_id']; ?>"<?php if ($this->_var['goods']['goods_id'] == $this->_var['recommend']): ?> checked="checked"<?php endif; ?>>
                    <label for="recommend<?php echo $this->_var['goods']['goods_id']; ?>" class="ui-radio-label-shou"><i class="iconfont icon-thumb"></i><?php echo $this->_var['lang']['main_push']; ?></label>
                </div>
                <?php endif; ?>
            </div>
        </li>
        <?php endforeach; else: ?>
        <li class="notic"><?php echo $this->_var['lang']['this_cate_no_goods']; ?></li>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <div class="clear"></div>
    <?php echo $this->fetch('library/lib_page.lbi'); ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'replace'): ?>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
    <li>
        <div class="img"><a href="<?php echo $this->_var['goods']['url']; ?>"><img src="<?php echo $this->_var['goods']['original_img']; ?>"></a></div>
        <div class="info">
            <div class="name"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
                <div class="price">
                    <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                        <?php echo $this->_var['goods']['promote_price']; ?>
                    <?php else: ?>
                        <?php echo $this->_var['goods']['shop_price']; ?>
                    <?php endif; ?>
                </div>
            <div class="btn_hover"><a href="<?php echo $this->_var['goods']['url']; ?>"><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
<?php endforeach; else: ?>
	<?php if ($this->_var['attr']['itemsLayout'] == "row3"): ?>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <?php elseif ($this->_var['attr']['itemsLayout'] == "row4"): ?>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
	<?php else: ?>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <li>
        <div class="img"><a href="" title=""><img src="images/default/gd_pic_02.jpg"></a></div>
        <div class="info">
            <div class="name"><a href="">商品名称</a></div>
            <div class="price">￥65.00</div>
            <div class="btn_hover"><a href=""><?php echo $this->_var['lang']['buy_now']; ?></a></div>
        </div>
    </li>
    <?php endif; ?>
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'navigator_home'): ?>
    <li><a href="<?php if ($this->_var['index_url']): ?><?php echo $this->_var['index_url']; ?><?php else: ?>index.php<?php endif; ?>" class="curr"><?php echo $this->_var['lang']['00_home']; ?></a></li>
    <?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navigator');if (count($_from)):
    foreach ($_from AS $this->_var['navigator']):
?>
    <li><a href="<?php echo $this->_var['navigator']['url']; ?>" style="text-align:<?php echo $this->_var['attr']['align']; ?>" <?php if ($this->_var['navigator']['opennew'] == 1): ?>target="_blank"<?php endif; ?>><?php echo $this->_var['navigator']['name']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'navigator'): ?>
    <?php $_from = $this->_var['navigator']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'navigator');if (count($_from)):
    foreach ($_from AS $this->_var['navigator']):
?>
    <li><a href="<?php echo $this->_var['navigator']['url']; ?>" style="text-align:<?php echo $this->_var['attr']['align']; ?>" target="<?php echo $this->_var['attr']['target']; ?>"><?php echo $this->_var['navigator']['name']; ?></a></li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'insertVipEdit'): ?>
<div class="userVip-info" ectype="user_info">
    <div class="avatar">
        <a href="user.php?act=profile"><img src="../themes/ecmoban_dsc2017/images/avatar.png"></a>
    </div>
    <div class="login-info">
        <span><?php echo $this->_var['lang']['hi_welcome_dsc']; ?></span>
        <a href="user.php" class="login-button"><?php echo $this->_var['lang']['please_login']; ?></a>
        <a href="merchants.php" target="_blank" class="register_button"><?php echo $this->_var['lang']['iwant_open_shop']; ?></a>
    </div>
</div>
<?php if ($this->_var['index_article_cat']): ?>
<div class="vip-item vip_article_cat">
    <div class="tit">
        <?php $_from = $this->_var['index_article_cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'cat');$this->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat']['total'] > 0):
    foreach ($_from AS $this->_var['key'] => $this->_var['cat']):
        $this->_foreach['cat']['iteration']++;
?>
        <a href="javascript:void(0);" class="tab_head_item" data-catid="<?php echo $this->_var['cat']['cat']['id']; ?>"><?php echo $this->_var['cat']['cat']['name']; ?></a>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    <div class="con">
        <?php $_from = $this->_var['index_article_cat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['cat'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['cat']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['cat']['iteration']++;
?>
        <ul <?php if (! ($this->_foreach['cat']['iteration'] <= 1)): ?>style="display:none;"<?php endif; ?> data-id="<?php echo $this->_var['cat']['cat']['id']; ?>">
            <?php $_from = $this->_var['cat']['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'article');$this->_foreach['article'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['article']['total'] > 0):
    foreach ($_from AS $this->_var['article']):
        $this->_foreach['article']['iteration']++;
?>
            <li><a href="<?php echo $this->_var['article']['url']; ?>" target="_blank"><?php echo $this->_var['article']['title']; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['name_count'] > 0): ?>
<div class="vip-item <?php echo $this->_var['suffix']; ?>">
    <div class="tit"><?php echo $this->_var['lang']['fast_entry']; ?></div>
    <div class="kj_con">
        <?php $_from = $this->_var['attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['attr']):
        $this->_foreach['name']['iteration']++;
?>
        <?php if ($this->_var['attr']['quick_name']): ?>
        <?php if ($this->_var['suffix'] == 'backup_festival_1'): ?>
        <div class="item item_<?php echo $this->_foreach['name']['iteration']; ?>">
            <a href="<?php echo $this->_var['attr']['quick_url']; ?>" target="_blank">
                <i class="img-<?php echo $this->_var['attr']['style_icon']; ?>"></i>
                <span><?php echo $this->_var['attr']['quick_name']; ?></span>
            </a>
        </div>
        <?php else: ?>
        <div class="item item_<?php echo $this->_foreach['name']['iteration']; ?>">
            <a href="<?php echo $this->_var['attr']['quick_url']; ?>" target="_blank">
                <i class="iconfont icon-<?php echo $this->_var['attr']['style_icon']; ?><?php if ($this->_var['attr']['style_icon'] == 'zan' || $this->_var['attr']['style_icon'] == 'password' || $this->_var['attr']['style_icon'] == 'share'): ?>-alt<?php endif; ?>"></i>
                <span><?php echo $this->_var['attr']['quick_name']; ?></span>
            </a>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'h-need'): ?>
<?php $_from = $this->_var['spec_attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');if (count($_from)):
    foreach ($_from AS $this->_var['attr']):
?>
<?php if ($this->_var['attr']['original_img']): ?>
<div class="channel-column" style="background:url(<?php if ($this->_var['attr']['homeAdvBg']): ?><?php echo $this->_var['attr']['homeAdvBg']; ?><?php else: ?>../data/gallery_album/visualDefault/ad_03_pic_02.jpg<?php endif; ?>) no-repeat;">
    <div class="column-title">
        <h3 <?php if ($this->_var['needColor']): ?>style="color: <?php echo $this->_var['needColor']; ?>"<?php endif; ?>><?php echo $this->_var['attr']['title']; ?></h3>
        <p <?php if ($this->_var['needColor']): ?>style="color: <?php echo $this->_var['needColor']; ?>"<?php endif; ?>><?php echo $this->_var['attr']['subtitle']; ?></p>
    </div>
    <div class="column-img"><img src="<?php if ($this->_var['attr']['original_img']): ?><?php echo $this->_var['attr']['original_img']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_009.png<?php endif; ?>"></div>
    <a href="<?php echo $this->_var['attr']['url']; ?>" target="_blank" class="column-btn"><?php echo $this->_var['lang']['go_see_see']; ?></a>
</div>
<?php else: ?>
<div class="channel-column" style="background:url(../data/gallery_album/visualDefault/ad_03_pic_02.jpg) no-repeat;">
    <div class="column-title">
        <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
        <p><?php echo $this->_var['lang']['sub_title']; ?></p>
    </div>
    <div class="column-img"><img src="../data/gallery_album/visualDefault/homeIndex_008.png"></div>
    <a href="#" target="_blank" class="column-btn"><?php echo $this->_var['lang']['go_see_see']; ?></a>
</div>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'h-master'): ?>
<?php if ($this->_var['masterTitle']): ?>
<div class="ftit"><h3><?php echo $this->_var['masterTitle']; ?></h3></div>
<?php endif; ?>
<div class="master-con">
    <?php $_from = $this->_var['spec_attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');$this->_foreach['master'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['master']['total'] > 0):
    foreach ($_from AS $this->_var['attr']):
        $this->_foreach['master']['iteration']++;
?>
    <?php if ($this->_var['attr']['original_img']): ?>
    <div class="m-c-item m-c-i-<?php echo $this->_foreach['master']['iteration']; ?>" style="background:url(<?php if ($this->_var['attr']['homeAdvBg']): ?><?php echo $this->_var['attr']['homeAdvBg']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_003.jpg<?php endif; ?>) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php if ($this->_var['attr']['title']): ?><?php echo $this->_var['attr']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                <span><?php if ($this->_var['attr']['subtitle']): ?><?php echo $this->_var['attr']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
            </div>
            <a href="<?php echo $this->_var['attr']['url']; ?>" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="<?php echo $this->_var['attr']['url']; ?>" target="_blank"><img src="<?php if ($this->_var['attr']['original_img']): ?><?php echo $this->_var['attr']['original_img']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_009.png<?php endif; ?>"></a></div>
    </div>
    <?php else: ?>
    <div class="m-c-item m-c-i-<?php echo $this->_foreach['master']['iteration']; ?>" style="background:url(../data/gallery_album/visualDefault/homeIndex_003.jpg) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
                <span><?php echo $this->_var['lang']['sub_title']; ?></span>
            </div>
            <a href="http://" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="http://" target="_blank"><img src="../data/gallery_album/visualDefault/homeIndex_009.png"></a></div>
    </div>
    <?php endif; ?>
    <?php endforeach; else: ?>
    <div class="m-c-item m-c-i-1" style="background:url(../data/gallery_album/visualDefault/homeIndex_003.jpg) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
                <span><?php echo $this->_var['lang']['sub_title']; ?></span>
            </div>
            <a href="http://" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="http://" target="_blank"><img src="../data/gallery_album/visualDefault/homeIndex_009.png"></a></div>
    </div>
    <div class="m-c-item m-c-i-2" style="background:url(../data/gallery_album/visualDefault/homeIndex_003.jpg) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
                <span><?php echo $this->_var['lang']['sub_title']; ?></span>
            </div>
            <a href="http://" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="http://" target="_blank"><img src="../data/gallery_album/visualDefault/homeIndex_009.png"></a></div>
    </div>
    <div class="m-c-item m-c-i-3" style="background:url(../data/gallery_album/visualDefault/homeIndex_003.jpg) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
                <span><?php echo $this->_var['lang']['sub_title']; ?></span>
            </div>
            <a href="http://" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="http://" target="_blank"><img src="../data/gallery_album/visualDefault/homeIndex_009.png"></a></div>
    </div>
    <div class="m-c-item m-c-i-4" style="background:url(../data/afficheimg/1490914187412324261.jpg) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
                <span><?php echo $this->_var['lang']['sub_title']; ?></span>
            </div>
            <a href="http://" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="http://" target="_blank"><img src="../data/gallery_album/visualDefault/homeIndex_009.png"></a></div>
    </div>
    <div class="m-c-item m-c-i-5" style="background:url(../data/gallery_album/visualDefault/homeIndex_003.jpg) center center no-repeat;">
        <div class="m-c-main">
            <div class="title">
                <h3><?php echo $this->_var['lang']['main_title']; ?></h3>
                <span><?php echo $this->_var['lang']['sub_title']; ?></span>
            </div>
            <a href="http://" class="m-c-btn" target="_blank"><?php echo $this->_var['lang']['go_insigh']; ?></a>
        </div>
        <div class="img"><a href="http://" target="_blank"><img src="../data/gallery_album/visualDefault/homeIndex_009.png"></a></div>
    </div>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'h-storeRec'): ?>
<?php if ($this->_var['masterTitle']): ?><div class="ftit"><h3><?php echo $this->_var['masterTitle']; ?></h3></div><?php endif; ?>
<div class="rec-store-list">
    <?php $_from = $this->_var['spec_attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'attr');if (count($_from)):
    foreach ($_from AS $this->_var['attr']):
?>
    <?php if ($this->_var['attr']['original_img']): ?>
    <div class="rec-store-item opacity_img">
        <a href="<?php echo $this->_var['attr']['url']; ?>" target="_blank">
            <div class="p-img"><img src="<?php if ($this->_var['attr']['original_img']): ?><?php echo $this->_var['attr']['original_img']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_005.jpg<?php endif; ?>"></div>
            <div class="info">
                <div class="s-logo"><div class="img"><img src="<?php if ($this->_var['attr']['homeAdvBg']): ?><?php echo $this->_var['attr']['homeAdvBg']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_001.jpg<?php endif; ?>"></div></div>
                <div class="s-title">
                    <div class="tit"><?php echo $this->_var['attr']['title']; ?></div>
                    <div class="ui-tit"><?php echo $this->_var['attr']['subtitle']; ?></div>
                </div>
            </div>
        </a>
    </div>
    <?php else: ?>
    <div class="rec-store-item opacity_img">
        <a href="#" target="_blank">
            <div class="p-img"><img src="../data/gallery_album/visualDefault/homeIndex_005.jpg"></div>
            <div class="info">
                <div class="s-logo"><div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_001.jpg"></div></div>
                <div class="s-title">
                    <div class="tit"><?php echo $this->_var['lang']['main_title']; ?></div>
                    <div class="ui-tit"><?php echo $this->_var['lang']['sub_title']; ?></div>
                </div>
            </div>
        </a>
    </div>
    <?php endif; ?>
    <?php endforeach; else: ?>
    <div class="rec-store-item opacity_img">
        <a href="#" target="_blank">
            <div class="p-img"><img src="../data/gallery_album/visualDefault/homeIndex_005.jpg"></div>
            <div class="info">
                <div class="s-logo"><div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_001.jpg"></div></div>
                <div class="s-title">
                    <div class="tit"><?php echo $this->_var['lang']['main_title']; ?></div>
                    <div class="ui-tit"><?php echo $this->_var['lang']['sub_title']; ?></div>
                </div>
            </div>
        </a>
    </div>
    <div class="rec-store-item opacity_img">
        <a href="#" target="_blank">
            <div class="p-img"><img src="../data/gallery_album/visualDefault/homeIndex_005.jpg"></div>
            <div class="info">
                <div class="s-logo"><div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_001.jpg"></div></div>
                <div class="s-title">
                    <div class="tit"><?php echo $this->_var['lang']['main_title']; ?></div>
                    <div class="ui-tit"><?php echo $this->_var['lang']['sub_title']; ?></div>
                </div>
            </div>
        </a>
    </div>
    <div class="rec-store-item opacity_img">
        <a href="#" target="_blank">
            <div class="p-img"><img src="../data/gallery_album/visualDefault/homeIndex_005.jpg"></div>
            <div class="info">
                <div class="s-logo"><div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_001.jpg"></div></div>
                <div class="s-title">
                    <div class="tit"><?php echo $this->_var['lang']['main_title']; ?></div>
                    <div class="ui-tit"><?php echo $this->_var['lang']['sub_title']; ?></div>
                </div>
            </div>
        </a>
    </div>
    <div class="rec-store-item opacity_img">
        <a href="#" target="_blank">
            <div class="p-img"><img src="../data/gallery_album/visualDefault/homeIndex_005.jpg"></div>
            <div class="info">
                <div class="s-logo"><div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_001.jpg"></div></div>
                <div class="s-title">
                    <div class="tit"><?php echo $this->_var['lang']['main_title']; ?></div>
                    <div class="ui-tit"><?php echo $this->_var['lang']['sub_title']; ?></div>
                </div>
            </div>
        </a>
    </div>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'homeFloor'): ?>
<!-- 楼层一 -->
<div class="floor-line-con floorOne <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
    	<i class="box_hd_arrow"></i>
    	<i class="box_hd_dec"></i>
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current">
                    <span><?php echo $this->_var['lang']['new_recommend']; ?></span>
                    <i class="arrowImg"></i>
                </li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" class="first" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="6" data-id="<?php echo $this->_var['cat']['cat_id']; ?>">
                    <span><?php echo $this->_var['cat']['cat_name']; ?></span>
                    <i class="arrowImg"></i>
                </li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd bd-mode-0<?php echo $this->_var['spec_attr']['floorMode']; ?>">
        <div class="bd-left">
            <?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_002.jpg<?php endif; ?>"></a></li>
                        <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>"></a></li>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php endif; ?>
            
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>"></a>
                <?php else: ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            
            <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php endif; ?>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                    <div class="f-r-m-item
                    <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 5): ?> f-r-m-i-double<?php endif; ?>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 1): ?> f-r-m-i-double<?php endif; ?>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 2): ?> f-r-m-i-double<?php endif; ?>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                    	<?php if ($this->_foreach['name']['iteration'] == 4): ?> f-r-m-i-double<?php endif; ?>
                    <?php endif; ?>">
                        <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                            <div class="title">
                                <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                            </div>
                            <img src="
                            	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 5): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 1): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 2): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 4): ?>
                            			<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_006.jpg<?php endif; ?>
                                    <?php else: ?>
                                        <?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_004.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            ">
                        </a>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorModule'): ?>
<!-- 楼层二 -->
<div class="floor-line-con floorTwo <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" <?php if ($this->_var['spec_attr']['hierarchy'] != 2): ?>id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>"<?php endif; ?> ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
    	<i class="box_hd_arrow"></i>
    	<i class="box_hd_dec"></i>
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current">
                    <span><?php echo $this->_var['lang']['new_recommend']; ?></span>
                    <i class="arrowImg"></i>
                </li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" class="first" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="4" data-id="<?php echo $this->_var['cat']['cat_id']; ?>">
                    <span><?php echo $this->_var['cat']['cat_name']; ?></span>
                    <i class="arrowImg"></i>
                </li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_013.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content clearfix">
                <div class="f-r-main f-r-m-adv">
                    <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                    <?php if ($this->_foreach['name']['iteration'] < $this->_var['advNumber']): ?>
                        <div class="f-r-m-item<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?><?php if ($this->_foreach['name']['iteration'] == 3): ?> f-r-m-i-double<?php endif; ?><?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?><?php if ($this->_foreach['name']['iteration'] == 1): ?> f-r-m-i-double<?php endif; ?><?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?> f-r-m-i-double<?php endif; ?>">
                            <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                <div class="title">
                                    <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                    <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                                </div>
                                <img src="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                                	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_012.jpg<?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                    <?php if ($this->_foreach['name']['iteration'] == 3): ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_014.jpg<?php endif; ?>
                                    <?php else: ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_012.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                                	<?php if ($this->_foreach['name']['iteration'] == 1): ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_014.jpg<?php endif; ?>
                                    <?php else: ?>
                                    	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_012.jpg<?php endif; ?>
                                    <?php endif; ?>
                                <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                                	<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_014.jpg<?php endif; ?>    
                                <?php endif; ?>">
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorThree'): ?>
<!-- 楼层三 -->
<div class="floor-line-con floorThree <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
	<div class="floor-hd" ectype="floorTit">
		<?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
			<ul>
				<li class="first current"><?php echo $this->_var['lang']['new_recommend']; ?></li>
				<?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>10<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>6<?php else: ?>8<?php endif; ?>" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="1"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
			</ul>
		</div>
	</div>
    
    <div class="floor-bd FT-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php endif; ?>">
    	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
        <div class="floor-tabs-content clearfix">
            <div class="f-r-main f-r-m-adv">
                <ul>
                     <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                     <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x590.jpg<?php endif; ?>"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>"></div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <?php else: ?>
    	<div class="bd-left">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x590.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
            	<div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x290.jpg<?php endif; ?>"></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="bd-right">
        	<div class="floor-tabs-content clearfix">
        	<div class="f-r-main f-r-m-adv">
            <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual474x290.jpg<?php endif; ?>"></a></div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php endif; ?>
            
            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-m-item">
                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                    <div class="title">
                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                    </div>
                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual232x290.jpg<?php endif; ?>">
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
            	<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual474x290.jpg<?php endif; ?>"></a></div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>    
    	<?php endif; ?>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorFour'): ?>
<!-- 楼层四 -->
<div class="floor-line-con floorFour <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>8<?php elseif ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>10<?php else: ?>12<?php endif; ?>" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>2<?php else: ?>0<?php endif; ?>"><?php echo $this->_var['lang']['new_recommend']; ?></li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>8<?php elseif ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>10<?php else: ?>12<?php endif; ?>" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>2<?php else: ?>0<?php endif; ?>"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd FF-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php endif; ?>">
        <?php if ($this->_var['spec_attr']['floorMode'] != 4): ?>
        <div class="bd-left">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adc']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adc']['iteration']++;
?>
                <?php if ($this->_foreach['adc']['iteration'] == 1): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x520.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <ul class="p-list" ectype="pList">
                <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <?php if (($this->_foreach['goods']['iteration'] - 1) < 4): ?>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                        <div class="p-price">
                            <div class="shop-price">
                                <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                <?php echo $this->_var['list']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['list']['shop_price']; ?>
                                <?php endif; ?>
                            </div>
                        </div>    
                    </div>
                </li>
                <?php endif; ?>
                <?php endforeach; else: ?>
                <!--<li class="li right-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li right-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li left-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li right-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">请选择您所需的商品</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>-->
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x520.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adc']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adc']['iteration']++;
?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x260.jpg<?php endif; ?>"></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="bd-right">
            <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adc'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adc']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adc']['iteration']++;
?>
                <?php if ($this->_foreach['adc']['iteration'] == 2): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x520.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <ul class="p-list" ectype="pList">
                <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <?php if (($this->_foreach['goods']['iteration'] - 1) > $this->_var['goods_num']): ?>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                        <div class="p-price">
                            <div class="shop-price">
                                <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                <?php echo $this->_var['list']['promote_price']; ?>
                                <?php else: ?>
                                <?php echo $this->_var['list']['shop_price']; ?>
                                <?php endif; ?>
                            </div>
                        </div>    
                    </div>
                </li>
                <?php endif; ?>
                <?php endforeach; else: ?>
                <!--<li class="li left-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li left-child opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>
                <li class="li opacity_img">
                    <div class="product">
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                        <div class="p-price"><em>¥</em>370.50</div>
                    </div>
                </li>-->
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php else: ?>
            <div class="floor-tabs-content clearfix">
            	<div class="f-r-main f-r-m-adv" ectype="floor_cat_<?php echo $this->_var['spec_attr']['cat_id']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if (($this->_foreach['goods']['iteration'] - 1) > $this->_var['goods_num']): ?>
                        <li class="<?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?><?php if ($this->_foreach['goods']['iteration'] % 5 == 1): ?>left-child <?php endif; ?><?php else: ?><?php if ($this->_foreach['goods']['iteration'] % 6 == 1): ?>left-child <?php endif; ?><?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>    
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <!--<li class="left-child opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="<?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>left-child <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="<?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>left-child <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>-->
                    </ul>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
        <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
        <div class="floor-fd">
            <div class="floor-fd-brand clearfix" ectype="defaultBrand">
                <div class="item">
                    <a href="#" target="_blank">
                        <div class="link-l"></div>
                        <div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                        <div class="link"></div>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorFive'): ?>
<!-- 楼层五 -->
<div class="floor-line-con floorFive <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['style_icon'] != 'other'): ?><i class="iconfont icon-<?php echo $this->_var['spec_attr']['style_icon']; ?>"></i><?php else: ?><i class="iconfont"><img src="<?php echo $this->_var['spec_attr']['cat_icon']; ?>" alt="<?php echo $this->_var['lang']['cate_icon']; ?>"></i><?php endif; ?><em class="iconfont icon-spot"></em><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current"><?php echo $this->_var['lang']['new_recommend']; ?></li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['cat']['cat_id']; ?>"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd FFI-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php elseif ($this->_var['spec_attr']['floorMode'] == 5): ?>5<?php endif; ?>">
        <div class="floor-tabs-content clearfix">
            <div class="f-r-main f-r-m-adv">
                <div class="bd-left">
                    <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                        <div class="floor-left-slide">
                            <div class="bd">
                                <ul>
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                    <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual477x450.jpg<?php endif; ?>"></a></li>
                                   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                            </div>
                            <div class="hd">
                                <ul></ul>
                            </div>
                        </div>
                        <div class="floor-left-adv">
                            <ul>
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x450.jpg<?php endif; ?>"></a></li>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                    <?php else: ?>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 3 || $this->_var['spec_attr']['floorMode'] == 4 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                        <div class="floor-left-adv">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x450.jpg<?php endif; ?>"></a>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                        <?php endif; ?>
                    
                        <?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
                        <div class="floor-left-slide">
                            <div class="bd">
                                <ul>
                                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                    <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual477x450.jpg<?php endif; ?>"></a></li>
                                   <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                            </div>
                            <div class="hd">
                                <ul></ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($this->_var['spec_attr']['floorMode'] == 4 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                        <ul>
                            <li class="f-bd-item">
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                                <div class="floor-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                
                                <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                                <div class="fr-adv mt5">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </li>
                            <?php if ($this->_var['spec_attr']['floorMode'] == 5): ?>
                            <li class="f-bd-item">
                                <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                                <div class="fr-adv">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                                <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                                <div class="floor-adv mt5"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                                <?php endif; ?>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </li>
                            <?php endif; ?>
                        </ul>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            
                <?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
                <div class="bd-right">
                    <?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
                    <ul>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        <li class="f-bd-item">
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <div class="fr-adv">
                                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                    <div class="title">
                                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                    </div>
                                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                            <div class="floor-adv mt5"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>                    
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </li>
                        <?php endif; ?>
                        <li class="f-bd-item">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                            <div class="floor-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                                <div class="fr-adv mt5">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if ($this->_foreach['adv']['iteration'] == 1): ?>
                                <div class="fr-adv mt5">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </li>
                        <li class="f-bd-item">
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                                <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                                <div class="fr-adv">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php else: ?>
                                 <?php if ($this->_foreach['adv']['iteration'] == 2): ?>
                                <div class="fr-adv">
                                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                        <div class="title">
                                            <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
                                            <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?></span>
                                        </div>
                                        <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x280.jpg<?php endif; ?>">
                                    </a>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                            <div class="floor-adv mt5"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x165.jpg<?php endif; ?>"></a></div>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </li>
                    </ul>
                    <?php endif; ?>
                    
                    <?php if ($this->_var['spec_attr']['floorMode'] == 4 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                    <div class="floor-left-slide">
                        <div class="bd">
                            <ul>
                                <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual477x450.jpg<?php endif; ?>"></a></li>
                               <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </ul>
                        </div>
                        <div class="hd">
                            <ul></ul>
                        </div>
                    </div>
                    <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                    <div class="floor-left-adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                        <?php if ($this->_foreach['adv']['iteration'] == 3): ?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual236x450.jpg<?php endif; ?>"></a>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
            <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                <ul class="p-list"></ul>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <div class="floor-fd">
        <div class="floor-fd-slide">
            <div class="bd">
                <ul class="current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="identi_floorgoods" data-identi="1" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="2">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <li>
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                            <div class="p-price">
                            	<div class="shop-price">
                                    <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                    <?php echo $this->_var['list']['promote_price']; ?>
                                    <?php else: ?>
                                    <?php echo $this->_var['list']['shop_price']; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endforeach; else: ?>
                    <!--<li>
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>
                    <li>
                        <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                        <div class="p-info">
                            <div class="p-name"><a href="#" target="_blank">唐人基 灌汤鱼丸180g*4袋 福州鱼丸 贡丸冷冻肉丸海鲜</a></div>
                            <div class="p-price"><em>¥</em>370.50</div>
                        </div>
                    </li>-->
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <a href="javascript:void(0);" class="ff-prev"></a>
            <a href="javascript:void(0);" class="ff-next"></a>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorSix'): ?>
<!-- 楼层六 -->    
<div class="floor-line-con floorSix <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <div class="floor-hd" ectype="floorTit">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><i class="icon"></i><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
            <ul>
                <li class="first current"data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" <?php if ($this->_var['spec_attr']['floorMode'] > 2): ?> ectype="floor_cat_content" <?php endif; ?>data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>6<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>8<?php else: ?>0<?php endif; ?>" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>2<?php else: ?>0<?php endif; ?>"><?php echo $this->_var['lang']['new_recommend']; ?></li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['cat']['cat_id']; ?>"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd FS-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php endif; ?>">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x480.jpg<?php endif; ?>"></a></li>
                       <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd">
                    <ul></ul>
                </div>
            </div>
            <?php if ($this->_var['brand_list']): ?>
            <div class="floor-brand">
                <div class="fb-bd">
                    <ul>
                        <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank" title="<?php echo $this->_var['list']['brand_name']; ?>"><img src="<?php echo $this->_var['list']['brand_logo']; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <a href="javascript:void(0);" class="fs_prev"><i class="iconfont icon-left"></i></a>
                <a href="javascript:void(0);" class="fs_next"><i class="iconfont icon-right"></i></a>
            </div>
            <?php endif; ?>
        </div>
        <div class="bd-right">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-tabs-content clearfix">
            	<div class="f-r-main f-r-m-adv">
                    <div class="floor-left-adv">
                        <ul>
                            <li class="f-bd-item child-double">
                                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x240.jpg<?php endif; ?>"></a>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </li>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                            <?php if ($this->_foreach['adv']['iteration'] > 2): ?>
                            <li class="f-bd-item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x480.jpg<?php endif; ?>"></a></li>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                    </div>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
            <div class="floor-left-adv">
            	<ul>
                    <li class="f-bd-item child-double">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x240.jpg<?php endif; ?>"></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </li>
                </ul>
            </div>   
            <?php endif; ?>
            
            <?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
            <div class="floor-tabs-content">
                <div class="f-r-main f-r-curr" ectype="floor_cat_<?php echo $this->_var['spec_attr']['cat_id']; ?>">
                    <ul class="p-list">
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <li class="child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                    <?php echo $this->_var['list']['promote_price']; ?>
                                    <?php else: ?>
                                    <?php echo $this->_var['list']['shop_price']; ?>
                                	<?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <?php endforeach; else: ?>
                        <!--<li class="child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <li class="child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 3 || $this->_var['spec_attr']['floorMode'] == 4): ?>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <li class="opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                            <a href="#" target="_blank" class="fr-btn"><?php echo $this->_var['lang']['buy_now']; ?></a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>-->
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
            <?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>
            <div class="floor-left-adv">
                <ul>
                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['adv']['iteration']++;
?>
                    <li class="f-bd-item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x480.jpg<?php endif; ?>"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorSeven'): ?>
<!-- 楼层七 -->
<div class="floor-line-con floorSeven <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="ftit"><h3><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h3></div><?php endif; ?>
    <div class="floor-bd FSE-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php elseif ($this->_var['spec_attr']['floorMode'] == 5): ?>5<?php endif; ?>">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                        <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x440.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd">
                    <ul></ul>
                </div>
            </div>
            <div class="floor-nav">
                <ul>
                    <li class="current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['lang']['new_recommend']; ?><i></i></li>
                    <?php if ($this->_var['spec_attr']['cateValue']): ?>
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <?php if ($this->_var['cat']['cat_name']): ?>
                    <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="10" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['cat']['cat_name']; ?><i></i></li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="bd-right">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <div class="floor-left-adv">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x440.jpg<?php endif; ?>"></a>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <?php endif; ?>
            <div class="floor-tabs-content">
            	<?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 5): ?>
                <div class="f-r-main f-r-curr">
                    <ul class="p-list<?php if ($this->_var['spec_attr']['floorMode'] == 5): ?> p-list-six<?php endif; ?>" ectype="pList">
                    	<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        <li class="child-double opacity_img">
                        	<div class="floor-left-adv">
                            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x220.jpg<?php endif; ?>"></a>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </div>
                        </li>
                        <?php endif; ?>
                        
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <li class="li <?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 5): ?><?php if ($this->_foreach['goods']['iteration'] < 4): ?>child-curr <?php endif; ?><?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; else: ?>
                        <!--<li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>-->
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <?php if ($this->_var['spec_attr']['floorMode'] == 5): ?>
                    <div class="floor-left-adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x440.jpg<?php endif; ?>"></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                <div class="f-r-main f-r-curr">
                	<ul class="p-list p-list-two" ectype="pList">
                    	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] < 3): ?>
                        <li class="li <?php if ($this->_foreach['goods']['iteration'] == 1): ?>child-curr <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <!--<li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>-->
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <div class="floor-left-adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x440.jpg<?php endif; ?>"></a>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <ul class="p-list p-list-four" ectype="pList">
                    	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] > 2 && $this->_foreach['goods']['iteration'] < 7): ?>
                        <li class="li <?php if ($this->_foreach['goods']['iteration'] < 3): ?>child-curr <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <!--<li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>-->
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
                <div class="f-r-main f-r-curr">
                    <ul class="p-list" ectype="pList">
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] < 6): ?>
                        <li class="li<?php if ($this->_foreach['goods']['iteration'] < 6): ?> child-curr <?php endif; ?>opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <!--<li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>-->
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <li class="child-double opacity_img">
                        	<div class="floor-left-adv">
                            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x220.jpg<?php endif; ?>"></a>
                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </div>
                        </li>
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <?php if ($this->_foreach['goods']['iteration'] == 6): ?>
                        <li class="li opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-price">
                                    <div class="shop-price">
                                        <?php if ($this->_var['list']['promote_price'] != ''): ?>
                                        <?php echo $this->_var['list']['promote_price']; ?>
                                        <?php else: ?>
                                        <?php echo $this->_var['list']['shop_price']; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>
                        <?php endforeach; else: ?>
                        <!--<li class="li child-curr opacity_img">
                            <div class="product">
                                <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
                                <div class="p-name"><a href="#" target="_blank">亿健家用彩屏多功能折叠</a></div>
                                <div class="p-price"><em>¥</em>370.50</div>
                            </div>
                        </li>-->
                        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($this->_var['brand_list']): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix">
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <div class="item">
                <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></div>
                    <div class="link"></div>
                </a>
            </div>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php else: ?>
    <?php if ($this->_var['spec_attr']['cat_id'] == 0): ?>
    <div class="floor-fd">
        <div class="floor-fd-brand clearfix" ectype="defaultBrand">
            <div class="item">
                <a href="#" target="_blank">
                    <div class="link-l"></div>
                    <div class="img"><img src="../data/gallery_album/visualDefault/homeIndex_010.jpg" title="esprit"></div>
                    <div class="link"></div>
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorEight'): ?>
<!-- 楼层八 -->
<div class="floor-line-con floorEight <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
	<?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
    <div class="floor-hd" ectype="floorTit">
		<?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="hd-tit"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div><?php endif; ?>
        <div class="hd-tags">
			<ul>
				<li class="first current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>6<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>8<?php else: ?>4<?php endif; ?>" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['lang']['new_recommend']; ?></li>
				<?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>6<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>8<?php else: ?>4<?php endif; ?>" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
			</ul>
		</div>
	</div>
    <?php endif; ?>
    
    <div class="floor-bd FE-bd-more-0<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>1<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>2<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>3<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>4<?php elseif ($this->_var['spec_attr']['floorMode'] == 5): ?>5<?php endif; ?>">
    	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftbanner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftbanner']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftbanner']['iteration']++;
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual393x280.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
        	</div>
        </div>            
        <?php else: ?>
        <div class="bd-left">
            <div class="floor_silder floor_silder1">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftbanner'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftbanner']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftbanner']['iteration']++;
?>
                        <li class="<?php if (($this->_foreach['leftbanner']['iteration'] - 1) == 0): ?>img_first<?php elseif (($this->_foreach['leftbanner']['iteration'] - 1) == 1): ?>img_second<?php elseif (($this->_foreach['leftbanner']['iteration'] - 1) == 2): ?>img_third<?php endif; ?>">
                            <a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank">
                                <div class="silder-img"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x220.jpg<?php endif; ?>"></div>
                                <div class="silder-title">
                                    <h3><?php echo $this->_var['list']['leftBannerTitle']; ?></h3>
                                    <span><?php echo $this->_var['list']['leftBannerSubtitle']; ?></span>
                                </div>
                            </a>
                            <div class="color_mask"></div>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd"><ul></ul></div>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="bd-right">
            <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                <div class="floor-left-adv">
                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftadv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftadv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftadv']['iteration']++;
?>
                    <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual192x280.jpg<?php endif; ?>"></a>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>                
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 5): ?>  
            	<ul class="p-list" ectype="pList">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <?php if (($this->_foreach['goods']['iteration'] - 1) < 2): ?>
                    <li class="li opacity_img">
                        <div class="product">
                            <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                            <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                            <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>

                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftadv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftadv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftadv']['iteration']++;
?>
                <?php if (($this->_foreach['leftadv']['iteration'] - 1) == 0): ?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual486x430.jpg<?php endif; ?>"></a></div>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>

                <ul class="p-list" ectype="pList">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <?php if (($this->_foreach['goods']['iteration'] - 1) > 1): ?>
                    <li class="li opacity_img">
                        <div class="product">
                            <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                            <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                            <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftadv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftadv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftadv']['iteration']++;
?>
                <?php if (($this->_foreach['leftadv']['iteration'] - 1) == 1): ?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual486x430.jpg<?php endif; ?>"></a></div>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            <?php else: ?>
                <ul class="p-list" ectype="pList">
                    <?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                    <li class="child-double opacity_img">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual484x215.jpg<?php endif; ?>"></a></div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </li>
                    <?php endif; ?>
                    
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <?php if (($this->_foreach['goods']['iteration'] - 1) > $this->_var['goods_num']): ?>
                    <li class="li opacity_img">
                        <div class="product">
                            <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                            <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                            <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        </div>
                    </li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                
                <?php if ($this->_var['spec_attr']['floorMode'] == 4): ?>
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <div class="floor-left-adv"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual486x430.jpg<?php endif; ?>"></a></div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            <?php endif; ?>
   		</div>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorNine'): ?>
<!-- 楼层九 -->
<div class="floor-line-con floorNine FN-bd-more-0<?php echo $this->_var['spec_attr']['floorMode']; ?>" data-title="<?php echo $this->_var['lang']['main_cate_name']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <i class="floor-tit-arrow"></i>
    <div class="floor-hd" ectype="floorTit">
        <div class="hd-tags">
            <ul>
                <li class="first current"><?php echo $this->_var['lang']['new_recommend']; ?></li>
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="8" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="1"><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="floor-bd">
        <div class="bd-left">
            <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><div class="bd-left-title"><h3><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h3><i></i></div><?php endif; ?>
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual160x472.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd">
                    <ul></ul>
                </div>
            </div>
        </div>
        
        <div class="bd-right">
            <div class="floor-tabs-content">
                <div class="f-r-main f-r-m-adv">
                	<?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2): ?>
                        <div class="f-r-m-items">
                            <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                            <div class="f-r-m-item">
                                <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                    <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>">
                                    <div class="title">
                                        <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                        <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                                    </div>
                                </a>
                            </div>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                        <div class="floor-left-adv">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftadv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftadv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftadv']['iteration']++;
?>
                            <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                            <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank" class="adv<?php if (($this->_foreach['leftadv']['iteration'] <= 1)): ?> adv-module<?php endif; ?>"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>"></a>
                            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                            <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank" class="adv-module"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>"></a>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                    <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                        <div class="floor-left-adv">
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftadv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftadv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftadv']['iteration']++;
?>
                            <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank" class="adv-module"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>"></a>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </div>
                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                        <div class="f-r-m-item">
                            <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>">
                                <div class="title">
                                    <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                    <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php else: ?>
                        <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                        <div class="f-r-m-item">
                            <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                                <img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>">
                                <div class="title">
                                    <h3><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></h3>
                                    <span><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></span>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                </div>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <div class="f-r-main" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul class="p-list"></ul>
                </div>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeFloorTen'): ?>
<!-- 楼层十 -->
<div class="floor-line-con floorTen <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-title="<?php echo $this->_var['lang']['main_cate_name']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
    <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?>
    <div class="floor-title">
        <div class="floor-title-con">
            <i class="left-arrow"></i>
            <h3><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h3>
            <i class="right-arrow"></i>
        </div>
    </div>
    <?php endif; ?>
    <div class="floor-bd FTEN-bd-more-0<?php echo $this->_var['spec_attr']['floorMode']; ?>">
        <div class="bd-left">
            <div class="floor-left-slide">
                <div class="bd">
                    <ul>
                    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x472.jpg<?php endif; ?>"></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                </div>
                <div class="hd">
                    <ul></ul>
                </div>
            </div>
            <div class="floor-nav">
                <ul>
                	<li class="first current" data-catGoods="<?php echo $this->_var['spec_attr']['top_goods']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2): ?>8<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>6<?php else: ?>10<?php endif; ?>" data-id="<?php echo $this->_var['spec_attr']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['lang']['new_recommend']; ?></li>
                	<?php if ($this->_var['spec_attr']['cateValue']): ?>
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <?php if ($this->_var['cat']['cat_name']): ?>
                    <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="<?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2): ?>8<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>6<?php else: ?>10<?php endif; ?>" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="2"><?php echo $this->_var['cat']['cat_name']; ?></li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="bd-right">
            <div class="floor-tabs-content">
                <div class="f-r-main f-r-m-adv">
                	<ul class="p-list" ectype="pList">
                    	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <?php if ($this->_foreach['goods']['iteration'] < 4): ?>
                            <li class="li opacity_img">
                                <div class="product">
                                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                            <li class="child-double opacity_img">
                                <div class="floor-left-adv">
                                    <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x236.jpg<?php endif; ?>"></a>
                                </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <?php if ($this->_foreach['goods']['iteration'] > 3): ?>
                            <li class="li opacity_img">
                                <div class="product">
                                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
                        	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                        	<li class="child-double opacity_img">
                                <div class="floor-left-adv">
                                    <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x236.jpg<?php endif; ?>"></a>
                                </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <li class="li opacity_img">
                                <div class="product">
                                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <?php if ($this->_foreach['goods']['iteration'] < 7): ?>
                            <li class="li opacity_img">
                                <div class="product">
                                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            
                            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                            <li class="child-double opacity_img">
                                <div class="floor-left-adv"> 
                                    <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual400x236.jpg<?php endif; ?>"></a>
                                </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <?php else: ?>
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <li class="li opacity_img">
                                <div class="product">
                                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'FhomeFloorModule'): ?>
<!-- 节日模板楼层 -->
<div class="floor-line-con floorFestival <?php echo $this->_var['spec_attr']['typeColor']; ?>" data-title="<?php echo $this->_var['lang']['main_cate_name']; ?>" data-idx="1" id="floor_module_<?php echo $this->_var['spec_attr']['floorMode']; ?>" ectype="floorItem">
	<div class="floor-hd" ectype="floorTit">
        <div class="title">
            <div class="title-name"><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></div>
            <div class="keyword">
            	<?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <a href="<?php echo $this->_var['cat']['url']; ?>" target="_blank"><?php echo $this->_var['cat']['cat_name']; ?></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="floor-bd">
        <div class="left-img">
            <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank">
                <div class="p-img"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual200x472.jpg<?php endif; ?>"></div>
                <div class="p-info">
                    <div class="p-name"><?php if ($this->_var['list']['leftBannerTitle']): ?><?php echo $this->_var['list']['leftBannerTitle']; ?><?php endif; ?></div>
                    <div class="p-desc"><?php if ($this->_var['list']['leftBannerSubtitle']): ?><?php echo $this->_var['list']['leftBannerSubtitle']; ?><?php endif; ?></div>
                </div>
            </a>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <div class="ul-list-img">
            <ul>
                <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_foreach['name']['iteration'] < 5): ?>
                <li>
                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                        <div class="p-name"><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></div>
                        <div class="p-desc"><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></div>
                        <div class="p-img p-img-scale"><img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>"></div>
                    </a>
                </li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="bottom-list">
            <ul>
                <?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_foreach['name']['iteration'] > 4): ?>
                <li>
                    <a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank">
                        <div class="p-img p-img-scale"><img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual245x255.jpg<?php endif; ?>"></div>
                        <div class="p-name"><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php endif; ?></div>
                        <div class="p-desc"><?php if ($this->_var['list']['rightAdvSubtitle']): ?><?php echo $this->_var['list']['rightAdvSubtitle']; ?><?php endif; ?></div>
                    </a>
                </li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php if ($this->_var['brand_list']): ?>
        <div class="brand-list">
            <ul>
                <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <li><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['brand_logo']; ?>" title="<?php echo $this->_var['list']['brand_name']; ?>"></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'storeOneFloor1'): ?>
<!-- 店铺模板一 楼层 -->
<div class="st-section<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?> st-section-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?> st-section-two<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?> st-section-three<?php else: ?> st-section-four<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
    <div class="title">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h1><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h1><?php endif; ?>
        <?php if ($this->_var['spec_attr']['sub_title']): ?><span><?php echo $this->_var['spec_attr']['sub_title']; ?></span><?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
    <div class="st_item_slide">
    	<div class="bd">
            <ul>
                <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <li><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1100x273.jpg<?php endif; ?>"></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="hd"><ul></ul></div>
    </div>
    <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
    <div class="st_item">
        <ul class="row4">
        	<?php $_from = $this->_var['spec_attr']['rightAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['name']['iteration']++;
?>
            <li>
                <div class="img"><a href="<?php echo $this->_var['list']['rightAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['rightAdv']): ?><?php echo $this->_var['list']['rightAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual262x360.jpg<?php endif; ?>"></a></div>
                <div class="tit"><?php if ($this->_var['list']['rightAdvTitle']): ?><?php echo $this->_var['list']['rightAdvTitle']; ?><?php else: ?><?php echo $this->_var['lang']['title']; ?><?php endif; ?></div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <div class="st_item">
    	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0): ?>
        <div class="row1"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1100x273.jpg<?php endif; ?>"></a></div>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    <div class="st_item">
        <div class="row2">
        	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        	<?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 1): ?>
            <div class="row2-left"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual534x350.jpg<?php endif; ?>"></a></div>
            <?php endif; ?>
            <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 2): ?>
            <div class="row2-right"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual534x350.jpg<?php endif; ?>"></a></div>
            <?php endif; ?>
        	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
    <div class="st_item">
    	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <div class="row1"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1100x458.jpg<?php endif; ?>"></a></div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    <div class="st_item">
        <ul class="st_goods_list st_goods_row3">
        	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
            <li>
                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
    <div class="st_item">
        <ul class="st_goods_list st_goods_row4">
        	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
            <li>
                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                <div class="p-lie">
                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                    <div class="p-number"><?php echo $this->_var['lang']['sold']; ?> 0 <?php echo $this->_var['lang']['jian']; ?></div>
                </div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'storeTwoFloor1'): ?>
<!-- 店铺模板二 楼层 -->
<div class="st-section <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>st-section-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>st-section-two<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>st-section-sthree<?php else: ?>st-section-four<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
    <div class="title">
    	<?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h1><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h1><?php endif; ?>
    </div>
    <?php endif; ?>
    <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
    <ul class="st_item st_item_lr">
    	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0 || ($this->_foreach['leftAdv']['iteration'] - 1) == 1): ?>
        <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?><?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0): ?>../data/gallery_album/visualDefault/visual670x317.jpg<?php elseif (($this->_foreach['leftAdv']['iteration'] - 1) == 1): ?>../data/gallery_album/visualDefault/visual512x317.jpg<?php endif; ?><?php endif; ?>"></a></li>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <ul class="st_item st_item_rl">
    	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 2 || ($this->_foreach['leftAdv']['iteration'] - 1) == 3): ?>
        <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?><?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 2): ?>../data/gallery_album/visualDefault/visual484x317.jpg<?php elseif (($this->_foreach['leftAdv']['iteration'] - 1) == 3): ?>../data/gallery_album/visualDefault/visual702x317.jpg<?php endif; ?><?php endif; ?>"></a></li>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
    <div class="st_item">
        <ul class="row3">
        	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual393x228.jpg<?php endif; ?>"></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <div class="st_item">
        <ul class="st_goods_list st_goods_row4">
        	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
            <li>
                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?><i class="arrow"></i></div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
    <div class="st_item">
    	<div class="sti_left">
        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0): ?><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual562x562.jpg<?php endif; ?>"></a><?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <div class="sti_right">
        	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        	<?php if (($this->_foreach['leftAdv']['iteration'] - 1) > 0): ?>
        	<div class="item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual615x270.jpg<?php endif; ?>"></a></div>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
    </div>
    <div class="st_item">
    	<?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
        <a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1200x242.jpg<?php endif; ?>"></a>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    <div class="st_item">
        <ul class="st_goods_list st_goods_row3">
        	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
            <li>
                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?><i class="arrow"></i></div>
                <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>"><?php echo $this->_var['lang']['add_to_cart']; ?></a></div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'storeThreeFloor1'): ?>
<!-- 店铺模板三 楼层 -->
<div class="st-section <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>st-section-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>st-section-three<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>st-section-four<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>st-section-two<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['sub_title']): ?>
    <div class="title">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h1><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h1><?php endif; ?>
        <?php if ($this->_var['spec_attr']['sub_title']): ?><span><?php echo $this->_var['spec_attr']['sub_title']; ?></span><?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="st_item">
        <div class="w w1200">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <ul>
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual277x106.jpg<?php endif; ?>"></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
            <div class="movable-warp" ectype="floorItem">
                <ul class="tab">
                    <?php if ($this->_var['spec_attr']['cateValue']): ?>
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <?php if ($this->_var['cat']['cat_name']): ?>
                    <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="8" data-id="<?php echo $this->_var['cat']['cat_id']; ?>"<?php if (($this->_foreach['name']['iteration'] <= 1)): ?> class="current"<?php endif; ?>><?php echo $this->_var['cat']['cat_name']; ?></li>
                    <?php endif; ?>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php endif; ?>
                </ul>
                <div class="floor-tabs-content clearfix">
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <div class="f-r-main<?php if (($this->_foreach['name']['iteration'] <= 1)): ?> f-r-m-adv<?php endif; ?>" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                        <ul class="p-list">
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <li>
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-lie">
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                    <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['rush_buy_now']; ?></a></div>
                                </div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
            	<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>
                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <div class="adv"><a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1200x375.jpg<?php endif; ?>"></a></div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>

				<?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>             
                <ul class="row3">
                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual393x113.jpg<?php endif; ?>"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                <?php else: ?>
                <ul class="row3">
                    <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                    <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual390x447.jpg<?php endif; ?>"></a></li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
                <?php endif; ?>
                
                <ul class="st_goods_list">
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <li>
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['rush_buy_now']; ?></a></div>
                    </li>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'storeFourFloor1'): ?>
<!-- 店铺模板四 楼层 -->
<div class="st-section <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>st-section-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>st-section-two<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>st-section-three<?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>st-section-four<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <div class="w w1200">
    	<?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['sub_title']): ?>
        <div class="title">
            <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h1><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h1><?php endif; ?>
            <?php if ($this->_var['spec_attr']['sub_title']): ?><span>/ <em><?php echo $this->_var['spec_attr']['sub_title']; ?></em> /</span><?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="st_item">
        	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
            <ul class="row3">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1400x300.jpg<?php endif; ?>"></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
            <ul class="st_goods_list st_goods_row4">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                	<div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>" /></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-lie"><div class="fl"><?php echo $this->_var['lang']['label_rush_buy_price']; ?></div><div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div></div>
                    <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['rush_buy_now']; ?></a></div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
                <div class="st_item_left">
                    <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>      
                    <a href="<?php echo $this->_var['list']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftBanner']): ?><?php echo $this->_var['list']['leftBanner']; ?><?php else: ?>../data/gallery_album/visualDefault/visual398x472.jpg<?php endif; ?>"></a>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
                <div class="st_item_right">
                    <div class="stir_adv">
                        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                        <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0): ?>
                        <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual399x235.jpg<?php endif; ?>"></a>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                    <div class="stir_goods_item">
                        <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>" /></a></div>
                        <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
            <ul class="st_goods_list st_goods_row4">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>" /></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-lie">
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        <a href="<?php echo $this->_var['list']['url']; ?>" target="_blank" class="p-btn"><i class="iconfont icon-carts"></i></a>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>    
            <?php endif; ?>
        </div>
        <?php if ($this->_var['spec_attr']['floorMode'] == 3): ?>
        <div class="st_item mt20">
            <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
            <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 1): ?>
            <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1200x215.jpg<?php endif; ?>"></a>
            <?php endif; ?>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'storeFiveFloor1'): ?>
<!-- 店铺模板五 楼层 -->
<div class="st-section <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>st-section-two<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>st-section-four<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>st-section-one<?php else: ?>st-section-five<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <div class="w w1200">
    	<?php if ($this->_var['spec_attr']['floorMode'] != 3): ?>
        <div class="title">
            <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h1><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h1><?php endif; ?>
            <?php if ($this->_var['spec_attr']['sub_title']): ?><span><?php echo $this->_var['spec_attr']['sub_title']; ?></span><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if ($this->_var['spec_attr']['floorMode'] == 1 || $this->_var['spec_attr']['floorMode'] == 2): ?>
        <div class="st_item">
            <ul class="st_goods_list <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>st_goods_row3<?php else: ?>st_goods_row4<?php endif; ?>">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>" alt=""/></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-lie">
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['buy_now']; ?></a></div>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
        <div class="st_item">
            <div class="st-one-left">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0 || ($this->_foreach['leftAdv']['iteration'] - 1) == 1): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual288x360.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <div class="st-one-con">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 2 || ($this->_foreach['leftAdv']['iteration'] - 1) == 3): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?><?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 2): ?>../data/gallery_album/visualDefault/visual590x488.jpg<?php elseif (($this->_foreach['leftAdv']['iteration'] - 1) == 3): ?>../data/gallery_album/visualDefault/visual590x228.jpg<?php endif; ?><?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <div class="st-one-right">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 4 || ($this->_foreach['leftAdv']['iteration'] - 1) == 5): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual288x360.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 4): ?>
        <div class="st_item">
        <ul>
        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual393x260.jpg<?php endif; ?>"></a></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'topicOneFloor'): ?>
<!-- 专题模板一 楼层 -->
<div class="tt-section<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?> tt-section-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?> tt-section-two<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?> tt-section-three<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <div class="w1000">
        <div class="title">
            <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h3><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h3><?php endif; ?>
            <?php if ($this->_var['spec_attr']['sub_title']): ?><h1><?php echo $this->_var['spec_attr']['sub_title']; ?></h1><?php endif; ?>
        </div>
        <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
        <?php if (($this->_foreach['leftAdv']['iteration'] - 1) == 0): ?>
        <div class="tt_item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1000x305.jpg<?php endif; ?>"></a></div>
        <?php endif; ?>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <div class="tt_item">
            <ul class="row3">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if (($this->_foreach['leftAdv']['iteration'] - 1) > 0): ?>
                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual326x384.jpg<?php endif; ?>"></a></li>
                <?php endif; ?>
        		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
        <div class="tt_item">
            <ul class="st_goods_list st_goods_row3">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-lie">
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        <s>原价：¥14.9</s>
                    </div>
                    <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>"><?php echo $this->_var['lang']['buy_now']; ?><i class="iconfont icon-right"></i></a></div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
        <div class="tt_item" ectype="floorItem">
            <ul class="tt_item_tab">
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="8" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="2"<?php if (($this->_foreach['name']['iteration'] <= 1)): ?> class="current"<?php endif; ?>><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
            <div class="tt_item_content">
            	<div class="floor-tabs-content clearfix">
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <div class="f-r-main<?php if (($this->_foreach['name']['iteration'] <= 1)): ?> f-r-m-adv<?php endif; ?>" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                    <ul>
                        <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                        <li>
                            <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                            <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                            <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                            <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>"><?php echo $this->_var['lang']['buy_now']; ?></a></div>
                        </li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            	</div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'topicTwoFloor'): ?>
<!-- 专题模板二 楼层 -->
<div class="tt-section<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?> tt-section-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?> tt-section-two<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?> tt-section-three<?php else: ?> tt-section-four<?php endif; ?> <?php echo $this->_var['spec_attr']['fontColor']; ?>">
	<?php if ($this->_var['spec_attr']['floorMode'] != 1): ?>
    <div class="title">
        <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h3><?php if ($this->_var['spec_attr']['floor_title']): ?># <?php echo $this->_var['spec_attr']['floor_title']; ?> #<?php elseif ($this->_var['spec_attr']['cat_name']): ?># <?php echo $this->_var['spec_attr']['cat_name']; ?> #<?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h3><?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="w w1000">
    	<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
        <div class="tt_item">
            <ul class="row5">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual190x290.jpg<?php endif; ?>"></a></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
        <div class="tt_item tt_item_1">
            <ul class="st_goods_list st_goods_row5">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                    <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><i class="iconfont icon-cart-alt"></i><?php echo $this->_var['lang']['buy_now']; ?></a></div>
                    <i class="i-icon icon-miao"></i>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
        <div class="tt_item mt30"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1000x200.jpg<?php endif; ?>"></a></div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?>
        <div class="tt_item tt_item_1">
            <ul class="st_goods_list st_goods_row5">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-lie">
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                    </div>
                    <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><i class="iconfont icon-cart-alt"></i><?php echo $this->_var['lang']['buy_now']; ?></a></div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php else: ?>
        <div class="tt_item tt_item_1">
            <ul class="tt_item_tab">
                <?php if ($this->_var['spec_attr']['cateValue']): ?>
                <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                <?php if ($this->_var['cat']['cat_name']): ?>
                <li data-catGoods="<?php echo $this->_var['cat']['goods_id']; ?>" ectype="floor_cat_content" data-flooreveval="0" data-visualhome="1" data-floornum="8" data-id="<?php echo $this->_var['cat']['cat_id']; ?>" data-floorcat="2"<?php if (($this->_foreach['name']['iteration'] <= 1)): ?> class="current"<?php endif; ?>><?php echo $this->_var['cat']['cat_name']; ?></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <?php endif; ?>
            </ul>
            <div class="tt_item_content">
            	<div class="floor-tabs-content clearfix">
                    <?php $_from = $this->_var['spec_attr']['cateValue']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from AS $this->_var['cat']):
        $this->_foreach['name']['iteration']++;
?>
                    <div class="f-r-main<?php if (($this->_foreach['name']['iteration'] <= 1)): ?> f-r-m-adv<?php endif; ?>" ectype="floor_cat_<?php echo $this->_var['cat']['cat_id']; ?>">
                        <ul class="st_goods_list st_goods_row5">
                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                            <li>
                                <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                                <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                                <div class="p-lie">
                                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                                </div>
                                <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><i class="iconfont icon-cart-alt"></i><?php echo $this->_var['lang']['buy_now']; ?></a></div>
                            </li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                	</div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>    
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'topicThreeFloor'): ?>
<!-- 专题模板三 楼层 -->
<div class="tt-section <?php echo $this->_var['spec_attr']['fontColor']; ?>">
    <div class="w w1000">
        <div class="title">
            <?php if ($this->_var['spec_attr']['floor_title'] || $this->_var['spec_attr']['cat_name']): ?><h3><?php if ($this->_var['spec_attr']['floor_title']): ?><?php echo $this->_var['spec_attr']['floor_title']; ?><?php elseif ($this->_var['spec_attr']['cat_name']): ?><?php echo $this->_var['spec_attr']['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['main_cate_name']; ?><?php endif; ?></h3><?php endif; ?>
        </div>
        <?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>
        <div class="tt_item">
            <ul class="st_goods_list st_goods_row4">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-lie">
                        <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                        <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['rush_buy_now']; ?></a></div>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
        <div class="tt_item"><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual1000x150.jpg<?php endif; ?>"></a></div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        <?php elseif ($this->_var['spec_attr']['floorMode'] == 2): ?>
        <div class="tt_item tt_item_1">
            <ul class="row5">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if ($this->_foreach['leftAdv']['iteration'] < 6): ?>
                <li><a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual190x290.jpg<?php endif; ?>"></a></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="tt_item">
            <div class="tt_item_left">
            	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if ($this->_foreach['leftAdv']['iteration'] > 5 && $this->_foreach['leftAdv']['iteration'] < 8): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual324x200.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <div class="tt_item_con">
                <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if ($this->_foreach['leftAdv']['iteration'] == 8): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual324x409.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
            <div class="tt_item_right">
               	<?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['leftAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['leftAdv']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['leftAdv']['iteration']++;
?>
                <?php if ($this->_foreach['leftAdv']['iteration'] > 8 && $this->_foreach['leftAdv']['iteration'] < 11): ?>
                <a href="<?php echo $this->_var['list']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['leftAdv']): ?><?php echo $this->_var['list']['leftAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/visual324x200.jpg<?php endif; ?>"></a>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="tt_item">
            <ul class="st_goods_list st_goods_row5">
            	<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>
                    <div class="p-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['goods_thumb']; ?>"></a></div>
                    <div class="p-name"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['goods_name']; ?></a></div>
                    <div class="p-price"><?php if ($this->_var['list']['promote_price'] != ''): ?><?php echo $this->_var['list']['promote_price']; ?><?php else: ?><?php echo $this->_var['list']['shop_price']; ?><?php endif; ?></div>
                    <div class="p-btn"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['rush_buy_now']; ?></a></div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'guessYouLike'): ?>
<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
<li class="opacity_img">
    <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
        <div class="p-img"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></div>
        <div class="p-name" title="<?php echo $this->_var['goods']['goods_name']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></div>
        <div class="p-price">
            <div class="shop-price">
                <?php if ($this->_var['goods']['promote_price'] != ''): ?>
                <?php echo $this->_var['goods']['promote_price']; ?>
                <?php else: ?>
                <?php echo $this->_var['goods']['shop_price']; ?>
                <?php endif; ?>
            </div>
            <div class="original-price"><?php echo $this->_var['goods']['market_price']; ?></div>
        </div>
    </a>
</li>
<?php endforeach; else: ?>
<!--<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>
<li class="opacity_img">
    <a href="#">
        <div class="p-img"><img src="../data/gallery_album/zhanwei.png"></div>
        <div class="p-name" title="">商品名称商品名称商品名称...</div>
        <div class="p-price">
            <div class="shop-price"><em>¥</em>0.00</div>
            <div class="original-price"></div>
        </div>
    </a>
</li>-->
<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'brand_query'): ?>
<ul class="brand_list">
    <?php $_from = $this->_var['recommend_brands']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'brand');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['brand']):
?>
    <li ectype='cliclkBrand' <?php if ($this->_var['brand']['selected'] == 1): ?> class="selected"<?php endif; ?> data-brand='<?php echo $this->_var['brand']['brand_id']; ?>' data-type="homeFloorBrand"><a href="JavaScript:void(0);"><img src="<?php echo $this->_var['brand']['brand_logo']; ?>" title="<?php echo $this->_var['brand']['brand_name']; ?>"></a><b></b></li>
    <?php endforeach; else: ?>
    <li class="notic"><?php echo $this->_var['lang']['you_selected_this_cate_no_goods']; ?></li>
    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
</ul>
<div class="clear"></div>
<?php echo $this->fetch('library/lib_page.lbi'); ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'h-brand'): ?>
<?php if ($this->_var['suffix'] == 'backup_festival_1'): ?>
<div class="brand-adv-warp">
	<?php $_from = $this->_var['barndAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'adv');$this->_foreach['barndAdv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['barndAdv']['total'] > 0):
    foreach ($_from AS $this->_var['adv']):
        $this->_foreach['barndAdv']['iteration']++;
?>
    <div class="brand-adv-item brand-adv-item-<?php echo $this->_foreach['barndAdv']['iteration']; ?>">
        <div class="brand-item-head">
            <span class="cn"><?php echo $this->_var['adv']['barndAdvTitle']; ?></span>
            <span class="en"><?php echo $this->_var['adv']['barndAdvSubtitle']; ?></span>
            <a href="<?php echo $this->_var['adv']['barndAdvLink']; ?>" class="more"><?php echo $this->_var['lang']['more']; ?><i class="iconfont icon-right"></i></a>
        </div>
        <div class="brand-item-img"><a href="<?php echo $this->_var['adv']['barndAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['adv']['barndAdv']): ?><?php echo $this->_var['adv']['barndAdv']; ?><?php else: ?>images/visual/festival_home/adv/brand-adv-item-1.jpg<?php endif; ?>"></a></div>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php if ($this->_var['brand_list']): ?>
<div class="brand-list" id="recommend_brands" data-value="<?php echo $this->_var['brand_ids']; ?>">
    <ul>
    	<?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
        <li>
            <div class="brand-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['brand_logo']): ?><?php echo $this->_var['list']['brand_logo']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_010.jpg<?php endif; ?>"></a></div>
            <div class="brand-mash">
                <div data-bid="<?php echo $this->_var['list']['brand_id']; ?>" ectype="coll_brand"><i class="iconfont icon-zan-alt"></i></div>
                <div class="coupon"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><span><?php echo $this->_var['lang']['focus_man_num']; ?> <em id="collect_count_<?php echo $this->_var['list']['brand_id']; ?>"><?php echo empty($this->_var['list']['collect_count']) ? '0' : $this->_var['list']['collect_count']; ?></em></span><div class="click-enter"><?php echo $this->_var['lang']['click_entry']; ?></div></a></div>
            </div>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <a href="javascript:void(0);" ectype="changeBrand" class="refresh-btn"><i class="iconfont icon-rotate-alt"></i><span><?php echo $this->_var['lang']['change_a_group']; ?></span></a>
</div>
<?php endif; ?>
<?php else: ?>
<div class="home-brand-adv slide_lr_info">
    <?php $_from = $this->_var['barndAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'adv');if (count($_from)):
    foreach ($_from AS $this->_var['adv']):
?>
    <a href="<?php echo $this->_var['adv']['barndAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['adv']['barndAdv']): ?><?php echo $this->_var['adv']['barndAdv']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_007.jpg<?php endif; ?>" class="slide_lr_img"></a>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<div ectype="homeBrand">
	<?php if ($this->_var['brand_list']): ?>
    <div class="brand-list" id="recommend_brands" data-value="<?php echo $this->_var['brand_ids']; ?>">
        <ul>
            <?php $_from = $this->_var['brand_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
            <li>
                <div class="brand-img"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><img src="<?php if ($this->_var['list']['brand_logo']): ?><?php echo $this->_var['list']['brand_logo']; ?><?php else: ?>../data/gallery_album/visualDefault/homeIndex_010.jpg<?php endif; ?>"></a></div>
                <div class="brand-mash">
                    <div data-bid="<?php echo $this->_var['list']['brand_id']; ?>" ectype="coll_brand"><i class="iconfont icon-zan-alt"></i></div>
                    <div class="coupon"><a href="<?php echo $this->_var['list']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['focus_man_num']; ?><br><div id="collect_count_<?php echo $this->_var['list']['brand_id']; ?>"><?php echo empty($this->_var['list']['collect_count']) ? '0' : $this->_var['list']['collect_count']; ?></div></a></div>
                </div>
            </li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
        <a href="javascript:void(0);" ectype="changeBrand" class="refresh-btn"><i class="iconfont icon-rotate-alt"></i><span><?php echo $this->_var['lang']['change_a_group']; ?></span></a>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'backupTemplates'): ?>
<?php $_from = $this->_var['available_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'template');$this->_foreach['template'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['template']['total'] > 0):
    foreach ($_from AS $this->_var['template']):
        $this->_foreach['template']['iteration']++;
?>
<li <?php if ($this->_var['default_tem'] == $this->_var['template']['code']): ?>class="curr"<?php endif; ?>>
    <div class="tit"><?php echo $this->_var['template']['name']; ?><a href="<?php if ($this->_var['template']['author_uri']): ?><?php echo $this->_var['template']['author_uri']; ?><?php else: ?>#<?php endif; ?>" target="_blank"/>-<?php echo $this->_var['template']['author']; ?></a></div>
    <div class="span"><?php echo $this->_var['template']['desc']; ?></div>
    <div class="img">
        <?php if ($this->_var['template']['screenshot']): ?><img width="263" height="338" src="<?php echo $this->_var['template']['screenshot']; ?>" data-src-wide="<?php echo $this->_var['template']['template']; ?>" border="0" id="<?php echo $this->_var['template']['code']; ?>" class="pic"/><?php endif; ?>
    </div>
    <div class="info">
        <p><a href="<?php echo $this->_var['template']['template']; ?>" class="btnSeeImg"><?php echo $this->_var['lang']['see_big_img']; ?></a></p>
        <p class="mt5">
            <a href="../index.php?suffix=<?php echo $this->_var['template']['code']; ?>" class="ml10" target="_blank" ><?php echo $this->_var['lang']['preview_template']; ?></a>
            <a href="visual_editing.php?act=template_information&tem=<?php echo $this->_var['template']['code']; ?>&merchant_id=<?php echo $this->_var['ru_id']; ?>" class="ml10"><?php echo $this->_var['lang']['edit_template_infor']; ?></a>
            <a href="javascript:removeTemplate('<?php echo $this->_var['template']['code']; ?>')" class="ml10"><?php echo $this->_var['lang']['remove_template']; ?></a>
        </p>
    </div>
    <?php if ($this->_var['default_tem'] == $this->_var['template']['code']): ?>
    <i class="ing"></i>
    <?php endif; ?>
</li>								
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'ajaxPiclist'): ?>
	<?php if ($this->_var['is_vis'] == 1 || $this->_var['is_vis'] == 2): ?>
	<div class="gallery_album" data-act="get_albun_pic" data-vis="<?php echo $this->_var['is_vis']; ?>" data-inid="pic_list" data-url='get_ajax_content.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>&is_vis=<?php echo $this->_var['is_vis']; ?>&inid=<?php echo $this->_var['inid']; ?>">
		<ul class="ga-images-ul" ectype="pic_replace" data-type="<?php if ($this->_var['is_vis'] == 1): ?>check<?php elseif ($this->_var['is_vis'] == 2): ?>radio<?php endif; ?>">
			<?php $_from = $this->_var['pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'pic_album');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['pic_album']):
?>
			<li data-url="<?php echo $this->_var['pic_album']['pic_file']; ?>" data-picid='<?php echo $this->_var['pic_album']['pic_id']; ?>'><div class="img-container"><img src="<?php echo $this->_var['pic_album']['pic_file']; ?>"></div><i class="checked"></i></li>
			<?php endforeach; else: ?>
			<li class="notic"><?php echo $this->_var['lang']['no_image']; ?></li>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
        <div class="clear"></div>
		<?php echo $this->fetch('library/lib_page.lbi'); ?>
	</div>
	<?php else: ?>
	<div class="gallery_album" data-act="get_albun_pic" data-inid="pic_list" data-url='get_ajax_content.php' data-where="sort_name=<?php echo $this->_var['filter']['sort_name']; ?>&album_id=<?php echo $this->_var['filter']['album_id']; ?>">
		<ul class="ga-images-ul">
			<?php $_from = $this->_var['pic_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'pic_list');if (count($_from)):
    foreach ($_from AS $this->_var['pic_list']):
?>
			<li><a href="javascript:;" onclick="addpic('<?php echo $this->_var['pic_list']['pic_file']; ?>',this)"><img src="<?php echo $this->_var['pic_list']['pic_file']; ?>"><span class="pixel"><?php echo $this->_var['pic_list']['pic_spec']; ?></span></a></li>
			<?php endforeach; else: ?>
			<li class="notic"><?php echo $this->_var['lang']['no_image']; ?></li>
			<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</ul>
		<div class="clear"></div>
		<?php echo $this->fetch('library/lib_page.lbi'); ?>
	</div>
	<?php endif; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'template_information'): ?>
<form  action="visualhome.php?act=edit_information" id="information" method="post"  enctype="multipart/form-data"  runat="server" >
<div class="items">
    <div class="item">
        <div class="label"><em class="require-field">*</em><?php echo $this->_var['lang']['label_template_name']; ?></div>
        <div class="value">
            <input type="text" class="text w300" name="name"  value="<?php echo htmlspecialchars($this->_var['template']['name']); ?>"  autocomplete="off" />
            <div class="form_prompt"></div>
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_version']; ?></div>
        <div class="value">
            <input type="text" class="text w300" name="version"  value="<?php echo htmlspecialchars($this->_var['template']['version']); ?>"  autocomplete="off" />
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_author']; ?></div>
        <div class="value">
            <input type="text" class="text w300" name="author"  value="<?php echo htmlspecialchars($this->_var['template']['author']); ?>"  autocomplete="off" />
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_author_link']; ?></div>
        <div class="value">
            <input type="text" class="text w300" name="author_url"  value="<?php echo htmlspecialchars($this->_var['template']['author_uri']); ?>"  autocomplete="off" />
        </div>
    </div>
    <div class="item">
        <div class="label"><em class="require-field">*</em><?php echo $this->_var['lang']['label_template_cover']; ?></div>
        <div class="value">
            <div class="type-file-box mb0">
                <input type="button" name="button" id="button" class="type-file-button" value="" />
                <input type="file" class="type-file-file" id="ten_file" name="ten_file" data-state="imgfile" size="30" hidefocus="true" value="" />
                <?php if ($this->_var['template']['screenshot']): ?>
                <span class="show">
                    <a href="<?php echo $this->_var['template']['screenshot']; ?>" ectype="see" target="_blank" class="nyroModal"><i class="iconfont icon-image" ectype="iconImage"></i></a>
                </span>
                <?php endif; ?>
                <input type="text" name="ten_file_textfile" class="type-file-text" id="textfield" autocomplete="off" value="<?php echo $this->_var['template']['screenshot']; ?>" readonly />
            </div>
            <div class="form_prompt"></div>
            <div class="notic lh30"><?php echo $this->_var['lang']['pleas_upload_265_388']; ?></div>
        </div>
    </div>
    <div class="item">
        <div class="label"><em class="require-field">*</em><?php echo $this->_var['lang']['label_template_bigimg']; ?></div>
        <div class="value">
            <div class="type-file-box mb0">
                <input type="button" name="button" id="button" class="type-file-button" value="" />
                <input type="file" class="type-file-file" id="big_file" name="big_file" data-state="imgfile" size="30" hidefocus="true" />
                <?php if ($this->_var['template']['template']): ?>
                <span class="show">
                    <a href="<?php echo $this->_var['template']['template']; ?>" target="_blank" ectype="see" class="nyroModal"><i class="iconfont icon-image" ectype="iconImage"></i></a>
                </span>
                <?php endif; ?>
                <input type="text" name="big_file_textfile" class="type-file-text" id="textfield" autocomplete="off" value="<?php echo $this->_var['template']['template']; ?>" readonly />
            </div>
            <div class="form_prompt"></div>
        </div>
    </div>
    <div class="item">
        <div class="label"><?php echo $this->_var['lang']['label_description']; ?></div>
        <div class="value">
            <textarea class="textarea" name="description"><?php echo htmlspecialchars($this->_var['template']['desc']); ?></textarea>
        </div>
    </div>
    <?php if ($this->_var['template_type'] == 'seller'): ?>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_template_mode']; ?></div>
            <div class="value lh30">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" name="temp_mode" value="0" class="ui-radio" ectype='temp_mode' id="temp_mode1" <?php if ($this->_var['template_mall_info']['temp_mode'] == 0): ?>checked<?php endif; ?> >
                        <label class="ui-radio-label" for="temp_mode1"><?php echo $this->_var['lang']['free']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" name="temp_mode" value="1" class="ui-radio" ectype='temp_mode' id="temp_mode2" <?php if ($this->_var['template_mall_info']['temp_mode'] != 0): ?>checked<?php endif; ?>>
                        <label class="ui-radio-label" for="temp_mode2"><?php echo $this->_var['lang']['pay']; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="item hide" ectype='ecs_temp_cost'>
            <div class="label"><em class="require-field">*</em><?php echo $this->_var['lang']['label_template_cost']; ?></div>
            <div class="value">
                <input type="text" class="text w300" name="temp_cost"  value="<?php echo htmlspecialchars($this->_var['template_mall_info']['temp_cost']); ?>"  autocomplete="off" />
                <div class="form_prompt"></div>
            </div>
        </div>
    <?php endif; ?>
    <input type="submit" class="hide" value="" ectype="submitBtn" />
    <input type="hidden" name="tem" value="<?php echo $this->_var['code']; ?>" />
    <input type="hidden" name="check" value="<?php echo $this->_var['check']; ?>" />
    <input type="hidden" name="temp_id" value="<?php echo $this->_var['temp_id']; ?>" />
    <input type="hidden" name="template_type" value="<?php echo $this->_var['template_type']; ?>" />
</div>
</form>
<script type="text/javascript">
$(function(){
	$(".nyroModal").nyroModal();
	resetHref();
	
	$("[ectype='iconImage']").mouseover(function(){
		var src = $(this).parents("[ectype='see']").attr("href");
		
		toolTip("<img src='"+src+"'>");
	});
	
	$("[ectype='iconImage']").mouseout(function(){
		toolTip();
	});
         checkmode()
         $("[ectype='temp_mode']").click(function(){
		checkmode()
	});
})
function checkmode(){
    var temp_mode = $("input[name='temp_mode']:checked").val();
    if(temp_mode == 0){
        $("*[ectype='ecs_temp_cost']").hide()
    }else if(temp_mode == 1){
        $("*[ectype='ecs_temp_cost']").show()
    }
}
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'homeTemplates'): ?>
<?php $_from = $this->_var['available_templates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'template');$this->_foreach['template'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['template']['total'] > 0):
    foreach ($_from AS $this->_var['template']):
        $this->_foreach['template']['iteration']++;
?>
<li <?php if ($this->_var['default_tem'] == $this->_var['template']['code']): ?>class="curr"<?php endif; ?>>
    <div class="checkbox_item">
        <input type="checkbox" name="checkboxes[]" value="<?php echo $this->_var['template']['code']; ?>" class="ui-checkbox" id="checkbox_<?php echo $this->_var['template']['code']; ?>" />
        <label for="checkbox_<?php echo $this->_var['template']['code']; ?>" class="ui-label"></label>
    </div>
    <div class="tit"><?php echo $this->_var['template']['name']; ?>-<a href="<?php if ($this->_var['template']['author_uri']): ?><?php echo $this->_var['template']['author_uri']; ?><?php else: ?>#<?php endif; ?>" target="_blank"/><?php echo $this->_var['template']['author']; ?></a></div>
    <div class="span"><?php echo $this->_var['template']['desc']; ?></div>
    <div class="img" ectype="setupTemplate" data-code="<?php echo $this->_var['template']['code']; ?>">
        <?php if ($this->_var['template']['screenshot']): ?><img width="263" height="338" src="<?php echo $this->_var['template']['screenshot']; ?>" data-src-wide="<?php echo $this->_var['template']['template']; ?>" border="0" id="<?php echo $this->_var['template']['code']; ?>" ectype="pic" class="pic"/><?php endif; ?>
        <div class="bg"></div>
    </div>
    <div class="box" ectype="setupTemplate" data-code="<?php echo $this->_var['template']['code']; ?>">
        <i class="icon icon-gou"></i>
        <span><?php echo $this->_var['lang']['use_template']; ?></span>
    </div>
    <div class="info">
        <div class="row">
            <a href="<?php echo $this->_var['template']['template']; ?>" class="mr10" target="_blank" ectype="see"><?php echo $this->_var['lang']['see_big_img']; ?></a>
            <a href="visualhome.php?act=visual&code=<?php echo $this->_var['template']['code']; ?>" target="_blank"><?php echo $this->_var['lang']['decorate']; ?></a>
        </div>
        <div class="row">
            <a href="../index.php?suffix=<?php echo $this->_var['template']['code']; ?>" class="mr10" target="_blank" ><?php echo $this->_var['lang']['preview_template']; ?></a>
            <a href="javascript:void(0);" ectype='information' data-code="<?php echo $this->_var['template']['code']; ?>" class="mr10"><?php echo $this->_var['lang']['edit_template_infor']; ?></a>
            <a href="javascript:removeTemplate('<?php echo $this->_var['template']['code']; ?>')"><?php echo $this->_var['lang']['remove_template']; ?></a>
        </div>
    </div>
    <i<?php if ($this->_var['default_tem'] == $this->_var['template']['code']): ?> class="ing"<?php endif; ?> ectype="default"></i>
</li>							
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'h-promo'): ?>
<div class="tit" style="background-color:<?php echo $this->_var['spec_attr']['navColor']; ?>;">
    <h3><?php echo $this->_var['spec_attr']['title']; ?></h3>
    <span><?php echo $this->_var['spec_attr']['subtitle']; ?></span>
    <i class="titIcon"></i>
</div>
<ul>
    <?php if ($this->_var['spec_attr']['goods_list']): ?>
    <?php $_from = $this->_var['spec_attr']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
    <li class="opacity_img">
        <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>"></a></div>
        <div class="info">
            <div class="price"><?php echo $this->_var['goods']['promote_price']; ?></div>
            <div class="name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
            <div class="time" ectype="time" data-time="<?php echo $this->_var['goods']['formated_end_date']; ?>">
                <span class="label"><?php echo $this->_var['lang']['label_left_time']; ?></span>
                <span class="days">00</span>
                <em>：</em>
                <span class="hours">00</span>
                <em>：</em>
                <span class="minutes">00</span>
                <em>：</em>
                <span class="seconds">00</span>
            </div>
        </div>
    </li>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php else: ?>
    <!--<li class="opacity_img">
        <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
        <div class="info">
            <div class="price">￥370.50</div>
            <div class="name"><a href="#" target="_blank">夏季短袖连衣裙新款打底裙碎</a></div>
            <div class="time" ectype="time">
                <span class="label">剩余时间：</span>
                <span class="days">00</span>
                <em>：</em>
                <span class="hours">00</span>
                <em>：</em>
                <span class="minutes">00</span>
                <em>：</em>
                <span class="seconds">00</span>
            </div>
        </div>
    </li>
    <li class="opacity_img">
        <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
        <div class="info">
            <div class="price">￥370.50</div>
            <div class="name"><a href="#" target="_blank">夏季短袖连衣裙新款打底裙碎</a></div>
            <div class="time" ectype="time">
                <span class="label">剩余时间：</span>
                <span class="days">00</span>
                <em>：</em>
                <span class="hours">00</span>
                <em>：</em>
                <span class="minutes">00</span>
                <em>：</em>
                <span class="seconds">00</span>
            </div>
        </div>
    </li>
    <li class="opacity_img">
        <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
        <div class="info">
            <div class="price">￥370.50</div>
            <div class="name"><a href="#" target="_blank">夏季短袖连衣裙新款打底裙碎</a></div>
            <div class="time" ectype="time">
                <span class="label">剩余时间：</span>
                <span class="days">00</span>
                <em>：</em>
                <span class="hours">00</span>
                <em>：</em>
                <span class="minutes">00</span>
                <em>：</em>
                <span class="seconds">00</span>
            </div>
        </div>
    </li>
    <li class="opacity_img">
        <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
        <div class="info">
            <div class="price">￥370.50</div>
            <div class="name"><a href="#" target="_blank">夏季短袖连衣裙新款打底裙碎</a></div>
            <div class="time" ectype="time">
                <span class="label">剩余时间：</span>
                <span class="days">00</span>
                <em>：</em>
                <span class="hours">00</span>
                <em>：</em>
                <span class="minutes">00</span>
                <em>：</em>
                <span class="seconds">00</span>
            </div>
        </div>
    </li>
    <li class="opacity_img">
        <div class="p-img"><img src="../data/gallery_album/visualDefault/zhanwei.png"></div>
        <div class="info">
            <div class="price">￥370.50</div>
            <div class="name"><a href="#" target="_blank">夏季短袖连衣裙新款打底裙碎</a></div>
            <div class="time" ectype="time">
                <span class="label">剩余时间：</span>
                <span class="days">00</span>
                <em>：</em>
                <span class="hours">00</span>
                <em>：</em>
                <span class="minutes">00</span>
                <em>：</em>
                <span class="seconds">00</span>
            </div>
        </div>
    </li>-->
    <?php endif; ?>
</ul>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'h-sepmodule'): ?>
<div class="sepmod-left" style="background-color:<?php echo $this->_var['spec_attr']['navColor']; ?>;">
    <div class="tit">
        <h3><?php if ($this->_var['spec_attr']['title']): ?><?php echo $this->_var['spec_attr']['title']; ?><?php else: ?><?php echo $this->_var['lang']['main_title']; ?><?php endif; ?></h3>
        <div class="subtit"><em style="background-color:<?php echo $this->_var['spec_attr']['navColor']; ?>;"><?php if ($this->_var['spec_attr']['subtitle']): ?><?php echo $this->_var['spec_attr']['subtitle']; ?><?php else: ?><?php echo $this->_var['lang']['sub_title']; ?><?php endif; ?><i></i></em><span></span></div>
        <i class="tit_icon"></i>
    </div>
    <div class="opacity_img sepmod-goods">
        <div class="p-img"><a href="<?php echo $this->_var['recommend']['url']; ?>" target="_blank"><img src="<?php if ($this->_var['recommend']['goods_thumb']): ?><?php echo $this->_var['recommend']['goods_thumb']; ?><?php else: ?>../data/gallery_album/visualDefault/zhanwei.png<?php endif; ?>"></a></div>
        <div class="name"><a href="<?php echo $this->_var['recommend']['url']; ?>" target="_blank"><?php if ($this->_var['recommend']['goods_name']): ?><?php echo $this->_var['recommend']['goods_name']; ?><?php else: ?><?php echo $this->_var['lang']['please_select_goods']; ?><?php endif; ?></a></div>
        <div class="price"><?php if ($this->_var['recommend']): ?><?php if ($this->_var['spec_attr']['PromotionType'] == 'exchange'): ?><?php echo $this->_var['recommend']['exchange_integral']; ?><?php else: ?><?php if ($this->_var['recommend']['promote_price'] != ''): ?><?php echo $this->_var['recommend']['promote_price']; ?><?php else: ?><?php echo $this->_var['recommend']['shop_price']; ?><?php endif; ?><?php endif; ?><?php else: ?>￥0.00<?php endif; ?></div>
    </div>
    <i class="titIcon"></i>
</div>
<div class="sepmod-right">
    <ul>
    <?php if ($this->_var['spec_attr']['goods_list']): ?>
        <?php $_from = $this->_var['spec_attr']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
        <li class="opacity_img sepmod-goods">
            <div class="p-img"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><img src="<?php if ($this->_var['goods']['goods_thumb']): ?><?php echo $this->_var['goods']['goods_thumb']; ?><?php else: ?>../data/gallery_album/visualDefault/zhanwei.png<?php endif; ?>"></a></div>
            <div class="name"><a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php if ($this->_var['goods']['goods_name']): ?><?php echo $this->_var['goods']['goods_name']; ?><?php else: ?><?php echo $this->_var['lang']['please_select_goods']; ?><?php endif; ?></a></div>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <?php else: ?>
        <!--<li class="opacity_img sepmod-goods">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="name"><a href="#" target="_blank"><?php echo $this->_var['lang']['please_select_goods']; ?></a></div>
        </li>
        <li class="opacity_img sepmod-goods">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="name"><a href="#" target="_blank"><?php echo $this->_var['lang']['please_select_goods']; ?></a></div>
        </li>
        <li class="opacity_img sepmod-goods">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="name"><a href="#" target="_blank"><?php echo $this->_var['lang']['please_select_goods']; ?></a></div>
        </li>
        <li class="opacity_img sepmod-goods">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="name"><a href="#" target="_blank"><?php echo $this->_var['lang']['please_select_goods']; ?></a></div>
        </li>-->
    <?php endif; ?>
    </ul>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'getmap_html'): ?>
<div>
    <div id="mapcontainer" class="mapcontainer"></div>
    <div id="myPageTop" class="ml10 fl">
        <dl class="button_info">
            <dt><?php echo $this->_var['lang']['label_search_by_keywords']; ?></dt>
            <dd>
                <input type="text" class="text text_2" placeholder="<?php echo $this->_var['lang']['please_input_keywords_to_search']; ?>" id="tipinput"><input type="button" value="<?php echo $this->_var['lang']['button_search']; ?>" class="sc-btn btn30 sc-blueBg-btn ml10 auto-item" id="mapsubmit" >
            </dd>
        </dl>
        <br />
        <dl class="button_info">
            <dt><?php echo $this->_var['lang']['label_lng_lat']; ?></dt>
            <dd>
                <input type="text" class="text text_2" readonly id="lnglat" name="lnglat">
            </dd>
        </dl>
    </div>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'set_free_shipping'): ?>
<div class="switch_info business_info free_shipping_info">
    <?php $_from = $this->_var['region_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
    <div class="step">
        <div class="tit">
            <div class="checkbox_items">
                <div class="checkbox_item w120">
                    <input type="checkbox" name="ra_id" value="checkbox" class="ui-checkbox" id="ra_id_<?php echo $this->_var['list']['ra_id']; ?>" />
                    <label for="ra_id_<?php echo $this->_var['list']['ra_id']; ?>" class="ui-label blod"><?php echo $this->_var['list']['ra_name']; ?></label>
                </div>
            </div>
        </div>
        <div class="qx_items">
            <div class="qx_item">
                <div class="checkbox_items">
                    <?php $_from = $this->_var['list']['area_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'region');if (count($_from)):
    foreach ($_from AS $this->_var['region']):
?>
                    <div class="checkbox_item">
                        <input type="checkbox" value="<?php echo $this->_var['region']['region_id']; ?>" name="region_id[]" class="ui-checkbox" id="region_id<?php echo $this->_var['region']['region_id']; ?>" <?php if ($this->_var['region']['is_checked'] == 1): ?>checked="true"<?php endif; ?> title="<?php echo $this->_var['region']['region_name']; ?>"/>
                        <label for="region_id<?php echo $this->_var['region']['region_id']; ?>" class="ui-label"><?php echo $this->_var['region']['region_name']; ?></label>
                    </div>
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    <div class="steplast">
        <div class="checkbox_items">
            <div class="checkbox_item">
                <input type="checkbox" name="checkall" value="checkbox" class="ui-checkbox" id="checkall" />
                <label for="checkall" class="ui-label"><?php echo $this->_var['lang']['check_all']; ?></label>
            </div>
        </div>
    </div>
</div>
<script language="javascript">
$("#checkall").click(function(){
	var checkbox = $(this).parents(".switch_info").find('input:checkbox[type="checkbox"]');
	if($(this).prop("checked") == true){
		checkbox.prop("checked",true);
	}else{
		checkbox.prop("checked",false);
	}
});

$("input[name='ra_id']").click(function(){
	var checkbox = $(this).parents(".tit").next(".qx_items").find('input:checkbox[type="checkbox"]');
	if($(this).prop("checked") == true){
			checkbox.prop("checked",true);
	}else{
			checkbox.prop("checked",false);
	}
});

$("input[name='region_id[]']").click(function(){    
	var qx_items = $(this).parents(".qx_items");
	var length = qx_items.find("input[name='region_id[]']").length;
	var length2 =  qx_items.find("input[name='region_id[]']:checked").length;
	if(length == length2){
		qx_items.prev().find("input[name='ra_id']").prop("checked",true);
	}else{
		qx_items.prev().find("input[name='ra_id']").prop("checked",false);
	}
});

$(".qx_items").each(function(index, element) {
	var length = $(this).find("input[name='region_id[]']").length;
	var length2 = $(this).find("input[name='region_id[]']:checked").length;
	
	if(length == length2){
		$(this).prev().find("input[name='ra_id']").prop("checked",true);
	}else{
		$(this).prev().find("input[name='ra_id']").prop("checked",false);
	}
});
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'CMS_ADV'): ?>
<div class="banner-article<?php if ($this->_var['spec_attr']['floorMode'] == 1): ?> banner-article-one<?php elseif ($this->_var['spec_attr']['floorMode'] == 3): ?> banner-article-three<?php endif; ?>">
    <div class="banner-main">
        <div class="bd">
            <ul>
            <?php $_from = $this->_var['spec_attr']['leftBanner']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'leftBanner');if (count($_from)):
    foreach ($_from AS $this->_var['leftBanner']):
?>
            <li><a href="<?php echo $this->_var['leftBanner']['leftBannerLink']; ?>" target="_blank"><img src="<?php if ($this->_var['leftBanner']['leftBanner']): ?><?php echo $this->_var['leftBanner']['leftBanner']; ?><?php else: ?><?php if ($this->_var['spec_attr']['floorMode'] == 1): ?>../data/gallery_album/visualDefault/visual1200x310.jpg<?php else: ?>../data/gallery_album/visualDefault/visual800x310.jpg<?php endif; ?><?php endif; ?>" alt=""><?php if ($this->_var['leftBanner']['leftBannerTitle']): ?><p><?php echo $this->_var['leftBanner']['leftBannerTitle']; ?></p><?php endif; ?></a></li>
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="hd"><ul></ul></div>
    </div>
    <?php if ($this->_var['spec_attr']['floorMode'] == 2 || $this->_var['spec_attr']['floorMode'] == 3): ?>
    <div class="banner-second">
        <?php $_from = $this->_var['spec_attr']['leftAdv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'leftAdv');$this->_foreach['adv'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['adv']['total'] > 0):
    foreach ($_from AS $this->_var['leftAdv']):
        $this->_foreach['adv']['iteration']++;
?>
        <div class="s<?php if (($this->_foreach['adv']['iteration'] == $this->_foreach['adv']['total'])): ?> last<?php endif; ?>"><a href="<?php echo $this->_var['leftAdv']['leftAdvLink']; ?>" target="_blank"><img src="<?php if ($this->_var['leftAdv']['leftAdv']): ?><?php echo $this->_var['leftAdv']['leftAdv']; ?><?php else: ?><?php if ($this->_var['spec_attr']['floorMode'] == 2): ?>../data/gallery_album/visualDefault/visual390x150.jpg<?php else: ?>../data/gallery_album/visualDefault/visual390x310.jpg<?php endif; ?><?php endif; ?>" alt=""><?php if ($this->_var['leftAdv']['leftAdvTitle']): ?><p><?php echo $this->_var['leftAdv']['leftAdvTitle']; ?></p><?php endif; ?></a></div>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'get_childcat'): ?>
<!--获取下级文章分类-->
<?php if ($this->_var['cat_select']): ?>
<div class="imitate_select select_w220" id="cat_id" data-level='<?php echo $this->_var['level']; ?>'>
    <div class="cite"><?php echo $this->_var['lang']['please_select_article_cate']; ?></div>		
    <ul style="display: none;" class="ps-container" ectype="articlecat">
        <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['please_select_article_cate']; ?></a></li>
        <?php $_from = $this->_var['cat_select']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat_list');if (count($_from)):
    foreach ($_from AS $this->_var['cat_list']):
?>
        <li><a href="javascript:;" data-value="<?php echo $this->_var['cat_list']['cat_id']; ?>" class="ftx-01"><?php echo $this->_var['cat_list']['cat_name']; ?></a></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <input name="" type="hidden" value="" id="cat_id_val">
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'getcat_atr'): ?>
<?php if (! $this->_var['full_page']): ?>
<div class="cms_dialog_main">
    <div class="tishi">
        <div class="tishi_info">
            <p class="first"><?php echo $this->_var['lang']['note_getcat_atr_1']; ?></p>
            <i class="icon"></i>
        </div>
    </div>
    <div class="modal-body">
    	<div class="checkobx-item mt20">
            <input type="checkbox" name="arti_selected" id="arti_selected" class="ui-checkbox" onclick="checkd_article(this)"/>
            <label class="ui-label" for="arti_selected"><?php echo $this->_var['lang']['selected_article']; ?></label>
            <input type='hidden' id="on_cat_id" name='articat_id' value='<?php echo $this->_var['cat_id']; ?>'>
        </div>
        <div class="body_info" id="banner_info">
            <div class="table_list" ectype='atr_list'>
                <?php endif; ?>
                <div class="gallery_album" data-act="getcat_atr" data-inid="atr_list" data-url='get_ajax_content.php' data-where="cat_id=<?php echo $this->_var['cat_id']; ?>&old_article=<?php echo $this->_var['filter']['old_article']; ?>&def_article=<?php echo $this->_var['def_article']; ?>">
                    <div class="ps_table mt10">
                        <table id="addpictable" class="table">
                            <thead>
                                <tr>
                                    <th width="20%"><?php echo $this->_var['lang']['article_id']; ?></th>
                                    <th width="35%"><?php echo $this->_var['lang']['article_name']; ?></th>
                                    <th width="15%" class="tc"><?php echo $this->_var['lang']['main_push']; ?></th>
                                    <th width="25%" class="tc"><?php echo $this->_var['lang']['handler']; ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $_from = $this->_var['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
                                <tr>
                                    <td><?php echo $this->_var['list']['article_id']; ?></td>
                                    <td><?php echo $this->_var['list']['title']; ?></td>
                                    <td class="tc"><img src="<?php if ($this->_var['def_article'] == $this->_var['list']['article_id']): ?>images/yes.gif<?php else: ?>images/no.gif<?php endif; ?>" ectype="def_article" data-id="<?php echo $this->_var['list']['article_id']; ?>" <?php if ($this->_var['list']['selected'] != 1): ?>class="hide"<?php endif; ?>></td>
                                    <td class="tc"><div class="choose"><a href="javascript:;" onclick="addatr('<?php echo $this->_var['list']['article_id']; ?>',this)" <?php if ($this->_var['list']['selected'] == 1): ?> class="on"<?php endif; ?>><em><?php if ($this->_var['list']['selected'] == 1): ?><i class="iconfont icon-gou"></i><?php echo $this->_var['lang']['selected']; ?><?php else: ?><i class="iconfont icon-dsc-plus"></i><?php echo $this->_var['lang']['btn_select']; ?><?php endif; ?></em></a></div></td>
                                </tr>
                                <?php endforeach; else: ?>
                                <tr class="notic"><td colspan="4"><?php echo $this->_var['lang']['this_cate_no_article']; ?></td></tr>
                                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clear"></div>
                    <?php echo $this->fetch('library/lib_page.lbi'); ?>
                </div>
                <?php if (! $this->_var['full_page']): ?>
            </div>
        </div>
    </div>
</div>      
<input type='hidden' name='def_article' value='<?php echo $this->_var['def_article']; ?>'>
<input type='hidden' name='select_article_ids' value='<?php echo $this->_var['filter']['old_article']; ?>'>
<script type="text/javascript">
	$(".ps_table").perfectScrollbar("destroy");
	$(".ps_table").perfectScrollbar();
</script>
<?php endif; ?>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'CMS_TWO_LIE'): ?>
<div class="article-col-2 clearfix">
    <?php $_from = $this->_var['spec_attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
    <div class="article-box">
        <div class="ab-hd"><h2><i class="iconfont icon-icon02"></i><?php echo $this->_var['list']['cat_name']; ?></h2><a href="../article_cat.php?id=<?php echo $this->_var['list']['cat_id']; ?>" class="more" target="_blank">more&gt;</a></div>
        <div class="ab-bd">
            <?php if ($this->_var['list']['first_article_list']): ?>
            <div class="focus">
                <div class="img"><a href="<?php echo $this->_var['list']['first_article_list']['url']; ?>" title="<?php echo $this->_var['list']['first_article_list']['title']; ?>"><img src="<?php echo $this->_var['list']['first_article_list']['file_url']; ?>" /></a></div>
                <div class="info">
                    <div class="info-name"><a href="<?php echo $this->_var['list']['first_article_list']['url']; ?>" title="<?php echo $this->_var['list']['first_article_list']['title']; ?>"><?php echo $this->_var['list']['first_article_list']['title']; ?></a></div>
                    <div class="info-intro"><?php echo $this->_var['list']['first_article_list']['description']; ?></div>
                    <div class="info-time"><?php echo $this->_var['list']['first_article_list']['add_time']; ?></div>
                </div>
            </div>
            <?php endif; ?>
            <ul class="list">
                <?php $_from = $this->_var['list']['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'articles');$this->_foreach['article'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['article']['total'] > 0):
    foreach ($_from AS $this->_var['articles']):
        $this->_foreach['article']['iteration']++;
?>
                <?php if (($this->_foreach['article']['iteration'] - 1) < 5): ?>
                <li><a href="<?php echo $this->_var['articles']['url']; ?>" target="_blank"><?php echo $this->_var['articles']['title']; ?></a><span class="time"><?php echo $this->_var['articles']['add_time']; ?></span></li>
                <?php endif; ?>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>                 
            </ul>
        </div>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'CMS_THREE_LIE'): ?>
<div class="article-col-3 clearfix">
    <?php $_from = $this->_var['spec_attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
    <div class="article-box">
        <div class="ab-hd"><h2><i class="iconfont icon-article"></i><?php echo $this->_var['list']['cat_name']; ?></h2><a href="../article_cat.php?id=<?php echo $this->_var['list']['cat_id']; ?>" class="more" target="_blank">more&gt;</a></div>
        <div class="ab-bd">
            <ul class="list">
                <?php if ($this->_var['list']['first_article_list']): ?>
                <li><a href="<?php echo $this->_var['list']['first_article_list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['first_article_list']['title']; ?></a><span class="time"><?php echo $this->_var['list']['first_article_list']['add_time']; ?></span></li>
                <?php endif; ?>
                <?php $_from = $this->_var['list']['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'articles');$this->_foreach['article'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['article']['total'] > 0):
    foreach ($_from AS $this->_var['articles']):
        $this->_foreach['article']['iteration']++;
?>
                <li><a href="<?php echo $this->_var['articles']['url']; ?>" target="_blank"><?php echo $this->_var['articles']['title']; ?></a><span class="time"><?php echo $this->_var['articles']['add_time']; ?></span></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>    
        </div>
    </div>
    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>   
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'CMS_FAST_LIE'): ?>
<div class="article-col-4 clearfix">
<?php $_from = $this->_var['spec_attr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');if (count($_from)):
    foreach ($_from AS $this->_var['list']):
?>
<div class="article-box">
    <div class="ab-hd"><h2><i class="iconfont icon-article"></i><?php echo $this->_var['list']['cat_name']; ?></h2><a href="../article_cat.php?id=<?php echo $this->_var['list']['cat_id']; ?>" class="more" target="_blank">more&gt;</a></div>
    <div class="ab-bd">
        <ul class="quick clearfix">
            <?php if ($this->_var['list']['first_article_list']): ?>
            <li>
                <div class="q-img"><a href="<?php echo $this->_var['list']['first_article_list']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['list']['first_article_list']['file_url']; ?>" alt=""></a></div>
                <div class="q-name"><a href="<?php echo $this->_var['list']['first_article_list']['url']; ?>" target="_blank"><?php echo $this->_var['list']['first_article_list']['title']; ?></a></div>
                <div class="q-info"><?php echo $this->_var['list']['first_article_list']['description']; ?></div>
            </li>
            <?php endif; ?>
            <?php $_from = $this->_var['list']['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'articles');$this->_foreach['no'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['no']['total'] > 0):
    foreach ($_from AS $this->_var['articles']):
        $this->_foreach['no']['iteration']++;
?>
            <!-- <?php if ($this->_foreach['no']['iteration'] < 5): ?>-->
            <li>
                <div class="q-img"><a href="<?php echo $this->_var['articles']['url']; ?>" target="_blank"><img src="<?php echo $this->_var['articles']['file_url']; ?>" alt=""></a></div>
                <div class="q-name"><a href="<?php echo $this->_var['articles']['url']; ?>" target="_blank"><?php echo $this->_var['articles']['title']; ?></a></div>
                <div class="q-info"><?php echo $this->_var['articles']['description']; ?></div>
            </li>
            <!-- <?php endif; ?> -->
            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
        </ul>
    </div>
</div>
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
</div>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'CMS_HEAT_LIE'): ?>
<div class="article-col-1-2 clearfix">
    <div class="article-box">
        <div class="ab-hd"><h2><i class="iconfont icon-article"></i><?php if ($this->_var['spec_attr']['article_title']): ?><?php echo $this->_var['spec_attr']['article_title']; ?><?php else: ?><?php echo $this->_var['lang']['recent_hot']; ?><?php endif; ?></h2></div>
        <div class="ab-bd">
            <ul class="list">
                <?php if ($this->_var['def_article_list']): ?>
                <li><a href="<?php echo $this->_var['def_article_list']['url']; ?>"><?php echo $this->_var['def_article_list']['title']; ?></a><span class="time"><?php echo $this->_var['def_article_list']['add_time']; ?></span></li>
                <?php endif; ?>
                <?php $_from = $this->_var['article_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'articles');if (count($_from)):
    foreach ($_from AS $this->_var['articles']):
?>
                <li><a href="<?php echo $this->_var['articles']['url']; ?>" target="_blank"><?php echo $this->_var['articles']['title']; ?></a><span class="time"><?php echo $this->_var['articles']['add_time']; ?></span></li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
    </div>
    <div class="article-box">
        <div class="ab-hd"><h2><i class="iconfont icon-article"></i><?php if ($this->_var['spec_attr']['goods_title']): ?><?php echo $this->_var['spec_attr']['goods_title']; ?><?php else: ?><?php echo $this->_var['lang']['best_recommend']; ?><?php endif; ?></h2></div>
        <div class="ab-bd">
            <ul class="g-list clearfix">
                <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                <li>
                    <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank">
                        <img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" alt="">
                        <p><?php echo $this->_var['goods']['goods_name']; ?></p>
                    </a>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'add_goods_type_cat'): ?>
<!-- 添加属性分类 -->
<form action="get_ajax_content.php" id="information" method="post" enctype="multipart/form-data" runat="server">
    <div class="items">
        <div class="item">
            <div class="label"><em class="require-field">*</em><?php echo $this->_var['lang']['label_cate_name']; ?></div>
            <div class="value">
                <input type="text" class="text w300" name="cat_name"  value=""  autocomplete="off" ectype="required" data-msg="<?php echo $this->_var['lang']['please_input_cate_name']; ?>"/>
                <div class="form_prompt"></div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_parent_cate']; ?></div>
            <div class="label_value" ectype="type_cat">
                <div id="parent_id1" class="imitate_select select_w145" ectype="typeCatSelect">
                    <div class="cite"><?php echo $this->_var['lang']['category_top']; ?></div>
                    <ul>
                        <li><a href="javascript:;" data-value="0" data-level='1' class="ftx-01"><?php echo $this->_var['lang']['category_top']; ?></a></li>
                        <?php $_from = $this->_var['cat_level']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                        <?php if ($this->_var['type_cat']['cat_name'] != $this->_var['cat']['cat_name']): ?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['cat']['cat_id']; ?>" data-level=<?php echo $this->_var['cat']['level']; ?> class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></li>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input type="hidden" value="0" id="parent_id_val1" ectype="typeCatVal">
                </div>
                <input name="attr_parent_id" type="hidden" value="0">
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['sort_order']; ?>：</div>
            <div class="label_value">
                <input type="text" name="sort_order" value="50" size="40" class="text" autocomplete="off" />
                <div class="form_prompt"></div>
            </div>
        </div>
        <input type="hidden" value="<?php echo $this->_var['temp']; ?>" name="act">
        <input type="hidden" value="<?php echo $this->_var['user_id']; ?>" name="user_id">
    </div>
</form>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'add_goods_type'): ?>
<!-- 添加属性类型 -->
<form action="get_ajax_content.php"  method="post"  enctype="multipart/form-data"  runat="server" >
    <div class="items">
        <div class="item">
            <div class="label"><em class="require-field">*</em><?php echo $this->_var['lang']['label_goods_type_name']; ?></div>
            <div class="value">
                <input type="text" class="text w300" name="cat_name"  value=""  autocomplete="off" ectype="required" data-msg="<?php echo $this->_var['lang']['please_input_goods_type_name']; ?>"/>
                <div class="form_prompt"></div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_cate']; ?></div>
            <div class="label_value" ectype="type_cat">
                <div id="parent_id1" class="imitate_select select_w145" ectype="typeCatSelect">
                    <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                    <ul>
                        <li><a href="javascript:;" data-value="0" data-level='1' class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                        <?php $_from = $this->_var['cat_level']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                        <?php if ($this->_var['type_cat']['cat_name'] != $this->_var['cat']['cat_name']): ?>
                        <li><a href="javascript:;" data-value="<?php echo $this->_var['cat']['cat_id']; ?>" data-level=<?php echo $this->_var['cat']['level']; ?> class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></li>
                        <?php endif; ?>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input type="hidden" value="0" id="parent_id_val1" ectype="typeCatVal">
                </div>
                <input name="attr_parent_id" type="hidden" value="0">
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_attr_cate']; ?></div>
            <div class="label_value">
                <textarea name="attr_group" rows="5" cols="40" class="textarea valid" aria-invalid="false"></textarea>
                <label class="blue_label ml0" id="noticeAttrGroups"><?php echo $this->_var['lang']['one_row_one_attr_group']; ?></label>
            </div>
        </div>
        <input type="hidden" value="<?php echo $this->_var['temp']; ?>" name="act">
        <input type="hidden" value="<?php echo $this->_var['user_id']; ?>" name="user_id">
        <input type="hidden" value="<?php echo $this->_var['goods_id']; ?>" name="goods_id">
        <input type="submit" class="hide" value="" ectype="submitBtn" />
    </div>
</form>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'attribute_add'): ?>
<!-- 添加属性 -->
<div class="dialogAddattribute">
<form action="get_ajax_content.php" method="post" enctype="multipart/form-data" runat="server">
    <div class="items ps-scrollbar-visible">
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['require_field']; ?><?php echo $this->_var['lang']['label_attr_name']; ?></div>
            <div class="label_value">
                <input type='text' name='attr_name' value="<?php echo $this->_var['attr']['attr_name']; ?>" size='30' class="text" autocomplete="off" ectype="required" data-msg="<?php echo $this->_var['lang']['please_input_attr_name']; ?>" />
                <div class="form_prompt"></div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['require_field']; ?><?php echo $this->_var['lang']['label_cat_id']; ?></div>
            <div class="label_value">
                <div class="imitate_select select_w170">
                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                    <ul style="display: none;"> 
                        <?php echo $this->_var['goods_type_list']; ?>
                    </ul>
                    <input name="cat_id" type="hidden" value="<?php echo $this->_var['attr']['cat_id']; ?>"  autocomplete="off" ectype="required" data-msg="<?php echo $this->_var['lang']['please_select_belong_goods_type']; ?>">
                </div>										
                <div class="form_prompt"></div>
            </div>
        </div>								
        <div class="item" id="attrGroups" style="display:none">
            <div class="label"><?php echo $this->_var['lang']['label_attr_group']; ?></div>
            <div class="label_value">
                <?php if ($this->_var['attr_groups']): ?>
                <div class="imitate_select select_w170">
                    <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                    <ul style="display: none;">
                        <li><a data-value="-1"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                        <?php $_from = $this->_var['attr_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
                        <li><a data-value="<?php echo $this->_var['key']; ?>"><?php echo $this->_var['item']; ?></a></li>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </ul>
                    <input name="attr_group" type="hidden" value="<?php echo $this->_var['attr']['attr_group']; ?>">
                </div>										
                <div class="form_prompt"></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['category_style']; ?>：</div>
            <div class="label_value">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_cat_type" id="attr_cat_type_0" value="0" <?php if ($this->_var['attr']['attr_cat_type'] == 0): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_cat_type_0" class="ui-radio-label"><?php echo $this->_var['lang']['category_style_one']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_cat_type" id="attr_cat_type_1" value="1" <?php if ($this->_var['attr']['attr_cat_type'] == 1): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_cat_type_1" class="ui-radio-label"><?php echo $this->_var['lang']['category_style_color']; ?></label>
                    </div>
                </div>
            </div>
        </div>								
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_attr_index']; ?></div>
            <div class="label_value">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_index" id="attr_index_0" value="0" <?php if ($this->_var['attr']['attr_index'] == 0): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_index_0" class="ui-radio-label"><?php echo $this->_var['lang']['no_index']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_index" id="attr_index_1" value="1" <?php if ($this->_var['attr']['attr_index'] == 1): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_index_1" class="ui-radio-label"><?php echo $this->_var['lang']['keywords_index']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_index" id="attr_index_2" value="2" <?php if ($this->_var['attr']['attr_index'] == 2): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_index_2" class="ui-radio-label"><?php echo $this->_var['lang']['range_index']; ?></label>
                    </div>											
                </div>
                <div class="noict bf100" id="noticeindex"><?php echo $this->_var['lang']['note_attr_index']; ?></div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_is_linked']; ?></div>
            <div class="label_value">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="is_linked" id="is_linked_0" value="0" <?php if ($this->_var['attr']['is_linked'] == 0): ?> checked="true" <?php endif; ?>  />
                        <label for="is_linked_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="is_linked" id="is_linked_1" value="1" <?php if ($this->_var['attr']['is_linked'] == 1): ?> checked="true" <?php endif; ?>  />
                        <label for="is_linked_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_attr_type']; ?>：</div>
            <div class="label_value">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_type" id="attr_type_0" value="0" <?php if ($this->_var['attr']['attr_type'] == 0): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_type_0" class="ui-radio-label"><?php echo $this->_var['lang']['attr_type_values']['0']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_type" id="attr_type_1" value="1" <?php if ($this->_var['attr']['attr_type'] == 1): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_type_1" class="ui-radio-label"><?php echo $this->_var['lang']['attr_type_values']['1']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_type" id="attr_type_2" value="2" <?php if ($this->_var['attr']['attr_type'] == 2): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_type_2" class="ui-radio-label"><?php echo $this->_var['lang']['attr_type_values']['2']; ?></label>
                    </div>											
                </div>
                <p class="fl bf100"><label class="blue_label ml0" id="noticeAttrType"><?php echo $this->_var['lang']['note_attr_type']; ?></label></p>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_attr_input_type']; ?></div>
            <div class="label_value">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_input_type" id="attr_input_type_0" value="0" <?php if ($this->_var['attr']['attr_input_type'] == 0): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_input_type_0" class="ui-radio-label"><?php echo $this->_var['lang']['text']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" class="ui-radio" name="attr_input_type" id="attr_input_type_1" value="1" <?php if ($this->_var['attr']['attr_input_type'] == 1): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_input_type_1" class="ui-radio-label"><?php echo $this->_var['lang']['select']; ?></label>
                    </div>
                    <div class="checkbox_item hide">
                        <input type="radio" class="ui-radio" name="attr_input_type" id="attr_input_type_2" value="2" <?php if ($this->_var['attr']['attr_input_type'] == 2): ?> checked="true" <?php endif; ?>  />
                        <label for="attr_input_type_2" class="ui-radio-label"><?php echo $this->_var['lang']['text_area']; ?></label>
                    </div>											
                </div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['label_attr_values']; ?></div>
            <div class="label_value">
                <textarea name="attr_values" cols="30" rows="5" class="textarea h120"><?php echo $this->_var['attr']['attr_values']; ?></textarea>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['sort_order']; ?>：</div>
            <div class="label_value">
                <input type='text' name='sort_order' value="<?php echo $this->_var['attr']['sort_order']; ?>" size='30' class="text" autocomplete="off" />
                <div class="form_prompt"></div>
            </div>
        </div>		

        <?php if ($this->_var['attr']['attr_cat_type'] == 1): ?>
        <div class="item">
            <div class="label">&nbsp;</div>
            <div class="label_value">
                <a href="attribute.php?act=set_gcolor&attr_id=<?php echo $this->_var['attr']['attr_id']; ?>" class="org"><?php echo $this->_var['lang']['add_attribute_color']; ?></a>
            </div>
        </div>		
        <?php endif; ?>		
    </div>
    <input type="hidden" name="attr_id" value="<?php echo $this->_var['attr']['attr_id']; ?>" />
    <input type="hidden" value="<?php echo $this->_var['temp']; ?>" name="act">
    <input type="hidden" value="<?php echo $this->_var['user_id']; ?>" name="user_id">
    <input type="hidden" value="<?php echo $this->_var['goods_id']; ?>" name="goods_id">
</form>
</div>
<script type="text/javascript">
	$(function(){
		//属性值录入方式切换
		$("input[name='attr_input_type']").click(function(){
			var val = $(this).val();
			if(val != 1){
				$("textarea[name='attr_values']").attr("disabled",true);
			}else{
				$("textarea[name='attr_values']").attr("disabled",false);
			}
		});
		
		if($("#attr_input_type_0").is(":checked")){
			$("textarea[name='attr_values']").attr("disabled",true);
		}
		
		onChangeGoodsType(<?php echo $this->_var['attr']['cat_id']; ?>);
		
		$(".dialogAddattribute .items").perfectScrollbar("destroy");
		$(".dialogAddattribute .items").perfectScrollbar();
	});
	
	/* 防止报错 start */
	function changeCat(obj){}
	/* 防止报错 end */
	
	/**
	 * 改变商品类型的处理函数
	 */
	function onChangeGoodsType(catId){
		Ajax.call('attribute.php?act=get_attr_groups&cat_id=' + catId, '', changeGoodsTypeResponse, 'GET', 'JSON');
	}
	
	function changeGoodsTypeResponse(res){
		if (res.error == 0){
			var row = document.getElementById('attrGroups');
			if (res.content.length == 0) {
				row.style.display = 'none';
			}else{
				row.style.display = document.all ? 'block' : 'table-row';
			}
		}
		
		if(res.message){
			alert(res.message);
		}
	}
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'ajaxTransport'): ?>
<form action="dialog.php" method="post" name="theForm" enctype="multipart/form-data" id="goods_transport_form">
<div class="ajaxTransport ps-scrollbar-visible" id="transport_edit">
    <div class="items">
    	<div class="item">   
           	<div class="label"><?php echo $this->_var['lang']['lab_freight_type']; ?>：</div>
            <div class="label_value">
                <div class="checkbox_items">
                    <div class="checkbox_item">
                        <input type="radio" name="freight_type" class="ui-radio" id="freight_type0" onclick="check_type(0)" value="0" <?php if ($this->_var['transport_info']['freight_type'] != 1): ?>checked<?php endif; ?> autocomplete="off" />
                        <label for="freight_type0" class="ui-radio-label"><?php echo $this->_var['lang']['freight_type']['one']; ?></label>
                    </div>
                    <div class="checkbox_item">
                        <input type="radio" name="freight_type" class="ui-radio" id="freight_type2" value="1" onclick="check_type(1)" <?php if ($this->_var['transport_info']['freight_type'] == 1): ?>checked<?php endif; ?> autocomplete="off" />
                        <label for="freight_type2" class="ui-radio-label"><?php echo $this->_var['lang']['freight_type']['two']; ?></label>
                    </div>
                </div>
            </div>
		</div>                                
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['title']; ?>：</div>
            <div class="label_value">
                <input type="text" name="title" class="text" autocomplete="off" value="<?php echo htmlspecialchars($this->_var['transport_info']['title']); ?>" />
                <div class="form_prompt"></div>
            </div>
        </div>
        <div class="item">
            <div class="label"><?php echo $this->_var['lang']['shipping_title']; ?>：</div>
            <div class="label_value">
                <input type="text" name="shipping_title" class="text" autocomplete="off" value="<?php echo htmlspecialchars($this->_var['transport_info']['shipping_title']); ?>" />
                <div class="notic"><?php echo $this->_var['lang']['shipping_title_notic']; ?></div>
            </div>
        </div>
        <div id="Fixed_freight"<?php if ($this->_var['transport_info']['freight_type'] != 0): ?> style="display:none;"<?php endif; ?>>
            <div class="item">
                <div class="label"><?php echo $this->_var['lang']['transport_type_name']; ?>：</div>
                <div class="label_value">
                    <div class="checkbox_items">
                        <div class="checkbox_item">
                            <input type="radio" name="type" class="ui-radio" id="type_off" value="0" autocomplete="off" <?php if ($this->_var['transport_info']['type'] == 0): ?>checked<?php endif; ?> />
                            <label for="type_off" class="ui-radio-label"><?php echo $this->_var['lang']['transport_type_off']; ?></label>
                        </div>
                        <div class="checkbox_item">
                            <input type="radio" name="type" class="ui-radio" id="type_on" value="1" autocomplete="off" <?php if ($this->_var['transport_info']['type'] == 1): ?>checked<?php endif; ?> />
                            <label for="type_on" class="ui-radio-label"><?php echo $this->_var['lang']['transport_type_on']; ?></label>
                        </div>
                    </div>
                </div>
            </div>	                                
            <div class="item">
                <div class="label"><?php echo $this->_var['lang']['free_money']; ?>：</div>
                <div class="label_value">
                    <div class="checkbox_items">
                        <input name="free_money" value="<?php echo empty($this->_var['transport_info']['free_money']) ? '0.00' : $this->_var['transport_info']['free_money']; ?>" type="text" class="text w150" />
                    </div>
                </div>
            </div>	
            <div class="item">
                <div class="label"><?php echo $this->_var['lang']['area_id']; ?>：</div>
                <div class="label_value">
                    <div ectype="transportArea"><?php echo $this->fetch('library/goods_transport_area.lbi'); ?></div>
                    <input type="button" value="<?php echo $this->_var['lang']['add_area']; ?>" class="button" data-role="add_area" ectype="add_area">
                </div>
            </div>	
            <div class="item">
                <div class="label"><?php echo $this->_var['lang']['shipping_id']; ?>：</div>
                <div class="label_value">
                    <div ectype="transportExpress"><?php echo $this->fetch('library/goods_transport_express.lbi'); ?></div>
                    <input type="button" value="<?php echo $this->_var['lang']['add_express']; ?>" class="button" data-role="add_express" ectype="add_express">
                </div>
            </div>
        </div>
        <div id="Template_freight"<?php if ($this->_var['transport_info']['freight_type'] == 0): ?> style="display:none;"<?php endif; ?>>
            <div class="item">
                <div class="label"><?php echo $this->_var['lang']['lab_goods_shipping']; ?>：</div>
                <div class="label_value">
                    <div id="shipping_id" class="imitate_select select_w320">
                        <div class="cite"><?php echo $this->_var['lang']['select_please']; ?></div>
                        <ul>
                            <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['select_please']; ?></a></li>
                            <?php $_from = $this->_var['shipping_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'shipping');if (count($_from)):
    foreach ($_from AS $this->_var['shipping']):
?>
                            <li><a href="javascript:;" data-value="<?php echo $this->_var['shipping']['shipping_id']; ?>" class="ftx-01"><?php echo $this->_var['shipping']['shipping_name']; ?></a></li>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </ul>
                        <input name="shipping_id" type="hidden" value="<?php echo empty($this->_var['transport_info']['shipping_id']) ? '0' : $this->_var['transport_info']['shipping_id']; ?>" id="shipping_id_val" autocomplete="off" />
                    </div>
                    <div class="form_prompt"></div>
                </div>
            </div>
            <div id="shipping_com">
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['lab_goods_freighttemp']; ?>：</div>
                    <div class="label_value" id="transport_tpl">
                        <?php echo $this->fetch('library/goods_transport_tpl.lbi'); ?>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="tid" value="<?php echo empty($this->_var['tid']) ? '0' : $this->_var['tid']; ?>" />
        <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
    </div>
</div>
	<script type="text/javascript">
	$(function(){
		var freight_type = Number("<?php echo empty($this->_var['transport_info']['freight_type']) ? '0' : $this->_var['transport_info']['freight_type']; ?>");
		var shipping_id = Number("<?php echo empty($this->_var['transport_info']['shipping_id']) ? '0' : $this->_var['transport_info']['shipping_id']; ?>");
		var tid = Number($("#transport_edit input[name='tid']").val());
	});

	//运费模板
	function freightTemplate(){
		var doc = $(document),
			tid = $("#transport_edit input[name='tid']").val();
			id = 0;
		/***************************模板类型 -> 快递模板*************************/
		//选择配送方式
		$.divselect("#shipping_id","#shipping_id_val",function(obj){
			var val = obj.data("value"),
				name  = obj.html();
				
			dialog_shipping(val, id);
		});
		
		//编辑运费模板内的快递
		doc.off("click","*[ectype='edit_shipping']").on("click","*[ectype='edit_shipping']",function(){
			var val = $(this).parents('tr').data('shipping_id');
			var id = $(this).data('id');
			dialog_shipping(val, id);
		});
		
		//删除运费模板内的快递
		doc.off("click","*[ectype='drop_shipping']").on("click","*[ectype='drop_shipping']",function(){
			var t = $(this),
			 	id = t.data('id');
				
			if(confirm(jl_sure_delete_template)){
				$.jqueryAjax('goods_transport.php', 'act=drop_shipping&tid='+tid + "&id=" + id, function(data){
					$("#transport_tpl").html(data.content);
				});
			}
		});
		
		//添加地区
		doc.off("click","*[ectype='add_shipping_area']").on("click","*[ectype='add_shipping_area']",function(){
			var val = $(this).parents('tr').data('shipping_id');
			dialog_shipping(val, id);
		});

		/***************************模板类型 -> 自定义 *************************/
		//添加地区
        doc.off("click","*[ectype='add_area']").off("click","*[ectype='add_area']").on("click","*[ectype='add_area']",function(){
			$.jqueryAjax('goods_transport.php', 'act=add_area&tid='+tid, function(data){
				$("*[ectype='transportArea']").html(data.content);
			});
		});
		//编辑地区	
		doc.off("click","*[ectype='edit_area']").on("click","*[ectype='edit_area']",function(){
			var id = $(this).parents('tr').find('input[name=id]').val();
			$.jqueryAjax('goods_transport.php', 'act=edit_area&id='+id, function(data){
				var content = data.content;
				pb({
					id:"area_dialog",
					title:"<?php echo $this->_var['lang']['select_region']; ?>",
					width:900,
					content:content,
					ok_title:"<?php echo $this->_var['lang']['button_submit_alt']; ?>",
					cl_title:"<?php echo $this->_var['lang']['cancel']; ?>",
					drag:false,
					foot:true,
					cl_cBtn:true,
					onOk:function(){save_area();}
				});			
			})
		});
		
		//删除地区
		doc.off("click","*[ectype='drop_area']").on("click","*[ectype='drop_area']",function(){
			var id = $(this).parents('tr').find('input[name=id]').val();
			$.jqueryAjax('goods_transport.php', 'act=drop_area&id='+id, function(data){
				$("*[ectype='transportArea']").html(data.content);
			});
		});
		
		//展开地区二级
		doc.off("click",".area-province i").on("click",".area-province i", function(){
			var area_city = $(this).siblings(".area-city");
			if(area_city.hasClass("hide")){
				$(this).parents(".area-province").find(".area-city").addClass("hide");
				area_city.removeClass("hide");
				$(this).removeClass("icon-angle-down").addClass("icon-angle-up");
			}else{
				area_city.addClass("hide");
				$(this).removeClass("icon-angle-up").addClass("icon-angle-down");
			}
		});
		
		//选中省份
		doc.off("click","input[name=province]").on("click","input[name=province]", function(){
			if($(this).prop('checked')){
				$(this).parents('li:first').find('ul.area-city input:enabled').prop('checked', true);
			}else{
				$(this).parents('li:first').find('ul.area-city input:enabled').prop('checked', false);
			}
			var child_num = $(this).parents('li:first').find('ul.area-city input:enabled:checked').length;
			var child_obj = $(this).siblings(".ui-label").find('[data-role=child_num]');
			change_child_num(child_obj, child_num);
		});
		
		//选中城市
		doc.off("click","input[name=city]").on("click","input[name=city]", function(){
			var child_num = $(this).parents('ul.area-city').find('input:enabled:checked').length;
			var child_obj = $(this).parents('.area-city').siblings(".ui-label").find('[data-role=child_num]');
			change_child_num(child_obj, child_num);
		});
		
		//添加快递
		doc.off("click","*[ectype='add_express']").on("click","*[ectype='add_express']", function(){
			var tid = $("#transport_edit input[name='tid']").val();
			$.jqueryAjax('goods_transport.php', 'act=add_express&tid='+tid, function(data){
				$("[ectype='transportExpress']").html(data.content);
			})
		});
		
		//删除快递
		doc.off("click","*[ectype='drop_express']").on("click","*[ectype='drop_express']", function(){
			var id = $(this).parents('tr').find('input[name=id]').val();
			$.jqueryAjax('goods_transport.php', 'act=drop_express&id='+id, function(data){
				$("[ectype='transportExpress']").html(data.content);
			})
		});
		
		//编辑快递
		doc.off("click","*[ectype='edit_express']").on("click","*[ectype='edit_express']", function(){
			var id = $(this).parents('tr').find('input[name=id]').val();
			$.jqueryAjax('goods_transport.php', 'act=edit_express&id='+id, function(data){
				var content = data.content;
				pb({
					id:"express_dialog",
					title:"<?php echo $this->_var['lang']['select_express']; ?>",
					width:900,
					content:content,
					ok_title:"<?php echo $this->_var['lang']['button_submit_alt']; ?>",
					cl_title:"<?php echo $this->_var['lang']['cancel']; ?>",
					drag:false,
					foot:true,
					cl_cBtn:true,
					onOk:function(){save_express();}
				});			
			})
		});
		
		//全选地区
		doc.off("click","input[name=all]").on("click","input[name=all]",function(){
			if($(this).prop('checked')){
				$(this).parents('.area-province,.transport-express').find('input[type=checkbox]').prop('checked', true);
			}else{
				$(this).parents('.area-province,.transport-express').find('input[type=checkbox]').prop('checked', false);
			}
		});
		
		//点击空白处
		doc.click(function(e){
			if(e.target.className != "area-city" && !$(e.target).parents("ul").is(".area-city") && e.target.className != "icon icon-angle-up"){
				$(".area-city").addClass("hide");
				$(".area-province").find("i").removeClass("icon-angle-up").addClass("icon-angle-down");
			}
		});
		
		/*************************************方法**********************************/
		//快递模板类型切换
		check_type = function(type){
			if(type == 0){
				$("#Template_freight").hide();
				$("#Fixed_freight").show();
			}else{
				$("#Template_freight").show();
				$("#Fixed_freight").hide();
			}
		}
		
		//统计数量
		change_child_num = function(obj, num){
			obj.html(obj.html().replace(/\d+/, num));
			if(num > 0){
				obj.removeClass('hide');
				obj.parents('.ui-label').siblings('input[name=province]').prop('checked', true);
			}else{
				obj.addClass('hide');
				obj.parents('.ui-label').siblings('input[name=province]').prop('checked', false);
			}
		}
		
		//自定义编辑配送区域保存
		save_area = function(){
			var id = $('.area-province').data('id');
			var province = new Array();
			var city = new Array();
			//省份
			$('.area-province').find("input[name=province]:enabled:checked").each(function(){
				province.push($(this).val());
			})
			//城市
			$('.area-province').find("input[name=city]:enabled:checked").each(function(){
				city.push($(this).val());
			})
			province = province.join(',');
			city = city.join(',');
			$.jqueryAjax('goods_transport.php', 'act=save_area&id='+id+'&top_area_id='+province+'&area_id='+city, function(data){
				$("*[ectype='transportArea']").html(data.content);
			});
		}
	
		//自定义模式编辑快递方式保存	
		save_express = function(){
			var id = $('.transport-express').data('id');
			var express = new Array();
			$('.transport-express').find("input[name=shipping]:enabled:checked").each(function(){
				express.push($(this).val());
			})
			express = express.join(',');
			$.jqueryAjax('goods_transport.php', 'act=save_express&id='+id+'&shipping_id='+express, function(data){
				$("[ectype='transportExpress']").html(data.content);
			});
		}
		
		//快递模板 运费模板编辑
		dialog_shipping = function(val, id){
			$.jqueryAjax('goods_transport.php', 'act=get_shipping_tem&shipping_id='+val + "&id=" + id + "&tid=" + tid, function(data){
				var content = data.content;
				pb({
					id:"area_dialog",
					title:"<?php echo $this->_var['lang']['edit_transport']; ?>",
					width:900,
					content:content,
					ok_title:"<?php echo $this->_var['lang']['button_submit_alt']; ?>",
					cl_title:"<?php echo $this->_var['lang']['cancel']; ?>",
					drag:true,
					foot:true,
					cl_cBtn:true,
					onOk:function(){
						var actionUrl = "goods_transport.php?act=add_shipping_tpl";
						$("form[name='shipping_tplForm']").ajaxSubmit({
							type: "POST",
							dataType: "JSON",
							url: actionUrl,
							data: {"action": "TemporaryImage"},
							success: function (data) {
								$("#transport_tpl").html(data.content);
								$(".tpl_region").perfectScrollbar("destroy");
								$(".tpl_region").perfectScrollbar();
							},
							async: true
						});
					}
				});
			});
		}
		
		$(".tpl_region").perfectScrollbar("destroy");
		$(".tpl_region").perfectScrollbar();
	}
	freightTemplate();
    
    $("#transport_edit").perfectScrollbar("destroy");
	$("#transport_edit").perfectScrollbar();
	</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'transport_reload'): ?>
    <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
    <ul style="display: none;">
        <?php $_from = $this->_var['transport_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
        <li><a href="javascript:;" data-value="<?php echo $this->_var['item']['tid']; ?>" class="ftx-01"><?php echo $this->_var['item']['title']; ?></a></li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <input name="tid" type="hidden" value="0">
<?php endif; ?>
<?php if ($this->_var['temp'] == 'ajaxArticle'): ?>
<div class="content">
    <div class="tabs_info mt30">
        <ul>
            <li class="curr"><a href="javascript:void(0);"><?php echo $this->_var['lang']['tab_general']; ?></a></li>
            <li><a href="javascript:void(0);"><?php echo $this->_var['lang']['tab_content']; ?></a></li>
            <li><a href="javascript:void(0);"><?php echo $this->_var['lang']['tab_goods']; ?></a></li>
        </ul>
    </div>
    <form action="dialog.php" method="post" enctype="multipart/form-data" name="theForm" id="article_form">
        <div class="ajaxArticle ps-scrollbar-visible" id="article_edit">
            <div class="items switch_info" style="display:block">
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['title']; ?>：</div>
                    <div class="label_value">
                        <input type="text" name="title" class="text" value="<?php echo htmlspecialchars($this->_var['article']['title']); ?>" maxlength="40" autocomplete="off" id="title"/>
                        <div class="form_prompt"></div>
                    </div>
                </div>
                <!-- <?php if ($this->_var['article']['cat_id'] >= 0): ?> -->
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['cat']; ?>：</div>
                    <div class="label_value">
                        <div id="parent_cat" class="imitate_select select_w320">
                          <div class="cite"><?php if ($this->_var['cat_name']): ?><?php echo $this->_var['cat_name']; ?><?php else: ?><?php echo $this->_var['lang']['select_plz']; ?><?php endif; ?></div>
                          <ul>
                             <?php echo $this->_var['cat_select']; ?>
                          </ul>
                          <input name="article_cat" type="hidden" value="<?php echo $this->_var['article']['cat_id']; ?>" id="parent_cat_val">
                        </div>
                        <div class="form_prompt"></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['article_type']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="article_type" id="sex_0" value="0" <?php if ($this->_var['article']['article_type'] == 0): ?>checked<?php endif; ?> />
                                <label for="sex_0" class="ui-radio-label"><?php echo $this->_var['lang']['common']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="article_type" id="sex_1" value="1" <?php if ($this->_var['article']['article_type'] == 1): ?>checked<?php endif; ?> />
                                <label for="sex_1" class="ui-radio-label"><?php echo $this->_var['lang']['top']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['is_open']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_open" id="sex_3" value="1" <?php if ($this->_var['article']['is_open'] == 1): ?>checked<?php endif; ?> />
                                <label for="sex_3" class="ui-radio-label"><?php echo $this->_var['lang']['isopen']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_open" id="sex_4" value="0" <?php if ($this->_var['article']['is_open'] == 0): ?>checked<?php endif; ?> />
                                <label for="sex_4" class="ui-radio-label"><?php echo $this->_var['lang']['isclose']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <?php else: ?> -->
                <input type="hidden" name="article_cat" value="-1" id="parent_cat_val"/>
                <input type="hidden" name="article_type" value="0" />
                <input type="hidden" name="is_open" value="1" />
                <!-- <?php endif; ?> -->
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['author']; ?>：</div>
                    <div class="label_value"><input type="text" name="author" class="text" autocomplete="off" value="<?php echo htmlspecialchars($this->_var['article']['author']); ?>"/></div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['email']; ?>：</div>
                    <div class="label_value"><input type="text" name="author_email" class="text" autocomplete="off" value="<?php echo htmlspecialchars($this->_var['article']['author_email']); ?>"/></div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['keywords']; ?>：</div>
                    <div class="label_value"><input type="text" name="keywords" class="text" autocomplete="off" value="<?php echo htmlspecialchars($this->_var['article']['keywords']); ?>"/></div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['lable_description']; ?>：</div>
                    <div class="label_value">
                        <textarea name="description" class="textarea"><?php echo htmlspecialchars($this->_var['article']['description']); ?></textarea>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['external_links']; ?>：</div>
                    <div class="label_value"><input type="text" name="link_url" class="text" autocomplete="off" id="link_url" value="<?php if ($this->_var['article']['link'] != ''): ?><?php echo htmlspecialchars($this->_var['article']['link']); ?><?php else: ?>http://<?php endif; ?>"/></div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['upload_file']; ?>：</div>
                    <div class="label_value">
                        <div class="type-file-box">
                            <input type="button" name="button" id="button" class="type-file-button" value="" />
                            <input type="file" class="type-file-file" id="file" name="file" data-state="imgfile" size="30" hidefocus="true" value="" />
                            <?php if ($this->_var['article']['file_url']): ?>
                            <span class="show">
                                <a href="<?php echo $this->_var['article']['file_url']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="<?php echo $this->_var['article']['file_url']; ?>" ectype="tooltip" title="tooltip"></i></a>
                            </span>
                            <input type="text" name="textfile" class="type-file-text" id="textfield" value="<?php echo $this->_var['article']['file_url']; ?>" autocomplete="off" readonly />
                            <?php endif; ?>
                        </div>
                        <?php if ($this->_var['article']['file_url']): ?>
                        <a href="article.php?act=del_file&article_id=<?php echo $this->_var['article']['article_id']; ?>" class="btn red_btn h30 mr10 fl" style="line-height:30px;"><?php echo $this->_var['lang']['drop']; ?></a>
                        <?php endif; ?>
                        <div class="notic"><?php echo $this->_var['lang']['picture_size']; ?> 280*160</div>
                    </div>	
                </div>
            </div>    
            <div class="items switch_info" style="display:none">
                <div class="item">
                    <?php echo $this->_var['FCKeditor']; ?>
                </div>
            </div>
            <div class="items switch_info" style="display:none">
                <div class="step" ectype="filter" style="padding:0;">
                    <div class="step_content" style="padding-top:0;">
                        <div class="goods_search_div">
                            <div class="search_select">
                                <div class="categorySelect">
                                    <div class="selection">
                                        <input type="text" name="category_name" id="category_name" class="text w250 valid" value="<?php echo $this->_var['lang']['select_cat']; ?>" autocomplete="off" readonly data-filter="cat_name" />
                                        <input type="hidden" name="category_id" id="category_id" value="0" data-filter="cat_id" />
                                    </div>
                                    <div class="select-container" style="display:none;">
                                        <?php echo $this->fetch('library/filter_category.lbi'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="search_select">
                                <div class="brandSelect">
                                    <div class="selection">
                                        <input type="text" name="brand_name" id="brand_name" class="text w120 valid" <?php echo $this->_var['lang']['select_barnd']; ?> autocomplete="off" readonly data-filter="brand_name" />
                                        <input type="hidden" name="brand_id" id="brand_id" value="0" data-filter="brand_id" />
                                    </div>
                                    <div class="brand-select-container" style="display:none;">
                                        <?php echo $this->fetch('library/filter_brand.lbi'); ?>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="ru_id" value="<?php echo $this->_var['ru_id']; ?>" />
                            <input type="text" name="keyword" class="text w150" placeholder=<?php echo $this->_var['lang']['input_keywords']; ?> autocomplete="off" data-filter="keyword" autocomplete="off" />
                            <a href="javascript:void(0);" class="btn btn30" onclick="searchGoods()" ><i class="icon icon-search"></i><?php echo $this->_var['lang']['search_word']; ?></a>
                        </div>
                        <div class="move_div">
                            <div class="move_left">
                                <h4><?php echo $this->_var['lang']['all_goods']; ?></h4>
                                <div class="move_info">
                                    <div class="move_list" id="source_select">
                                        <ul>
                                        </ul>	
                                    </div>
                                </div>
                                <div class="move_handle">
                                    <a href="javascript:void(0);" class="btn btn25 moveAll" ectype="moveAll"><?php echo $this->_var['lang']['check_all']; ?></a>
                                    <a href="javascript:void(0);" ectype="sub" data-operation="add_link_artic_goods" class="btn btn25 red_btn"><?php echo $this->_var['lang']['button_submit_alt']; ?></a>
                                </div>
                            </div>
                            <div class="move_middle">
                                <div class="move_point" onclick="addGoods()"></div>
                            </div>
                            <div class="move_right">
                                <h4><?php echo $this->_var['lang']['group_goods']; ?></h4>
                                <div class="move_info">
                                    <div class="move_list" id="target_select" >
                                        <ul>
                                            <?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
                                            <li data-value="<?php echo $this->_var['goods']['goods_id']; ?>" data-text="<?php echo $this->_var['goods']['goods_name']; ?>"><i class="sc_icon sc_icon_no"></i><a href="javascript:;" data-value="<?php echo $this->_var['goods']['goods_id']; ?>" class="ftx-01"><?php echo $this->_var['goods']['goods_name']; ?></a></li>
                                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                        </ul>											
                                    </div>
                                </div>
                                <div class="move_handle">
                                    <a href="javascript:void(0);" class="btn btn25 moveAll" ectype="moveAll"><?php echo $this->_var['lang']['check_all']; ?></a>
                                    <a href="javascript:void(0);" ectype="sub" data-operation="drop_link_artic_goods" class="btn btn25 btn_red"><?php echo $this->_var['lang']['remove']; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
            <input type="hidden" name="old_title" value="<?php echo $this->_var['article']['title']; ?>"/>
            <input type="hidden" name="id" value="<?php echo $this->_var['article']['article_id']; ?>" />
        </div>
    </form>    
</div>
<script type="text/javascript">
	$(function(){
		$('.nyroModal').nyroModal();
	})

	//会员基本信息 div仿select 
	$.divselect("#parent_cat","#parent_cat_val",function(obj){
		var select = obj.parents("#parent_cat");
		var val = obj.attr("cat_type");
		catChanged(val);
	});

	
	/**
	* 选取上级分类时判断选定的分类是不是底层分类
	*/
	function catChanged(cat_type)
	{
		<?php if ($this->_var['cat_name']): ?>
			var text = "<?php echo $this->_var['cat_name']; ?>";
		<?php else: ?>
			var text = "<?php echo $this->_var['lang']['select_plz']; ?>";
		<?php endif; ?>
	
		if (cat_type == '')
		{
			cat_type = 1;
		}
		if (cat_type == 2 || cat_type == 4)
		{
			alert(not_allow_add);
			$("#parent_cat_val").val(0);
			$("#parent_cat .cite").html(text);
			return false;
		}
		return true;
	}
	
	function searchGoods(){
		var filters   = new Object;
		filters.cat_id = $("#article_edit input[name='category_id']").val();
		filters.brand_id = $("#article_edit input[name='brand_id']").val();
		filters.keyword = Utils.trim($("#article_edit input[name='keyword']").val());
		$("#source_select").find("ul").html('<i class="icon-spinner icon-spin"></i>');

		setTimeout(function(){
			$.jqueryAjax("bonus.php?is_ajax=1&act=get_goods_list","JSON="+$.toJSON(filters), searchGoodsResponse, 'GET', 'JSON');
		},300);
	}
	
	function searchGoodsResponse(result){
		$("#source_select").find("li").remove();
		$("#source_select").find("ul").html('');
		var step = $("#source_select").parents(".step[ectype=filter]:first");
		var goods = result.content;
		if (goods)
		{
				for (i = 0; i < goods.length; i++)
				{
						$("#source_select").children("ul").append("<li data-value='"+goods[i].value+"'><i class='sc_icon sc_icon_ok'></i><a href='javascript:;' data-value='"+goods[i].value+"' class='ftx-01'>"+goods[i].text+"</a><input type='hidden' name='user_search[]' value='"+goods[i].value+"'></li>")
				}
		}
		step.find(".move_list").perfectScrollbar();
					
	}
	
	function addGoods()
	{
		var step = $("#source_select").parents(".step[ectype=filter]:first");
		$("#source_select").find("li").each(function(){
			if($(this).attr("class") == 'current'){
				var user = $(this).text();
				var user_id = $(this).find("input").val();
				var exists = false;
				$("#target_select").find("li").each(function(){
					if($(this).find("input").val() == user_id){
						exists = true;
						return false;
					}
				})
				if(exists == false){
					$("#target_select").children("ul").append("<li><i class='sc_icon sc_icon_no'></i><a href='javascript:void(0);'>"+user+"</a><input type='hidden' name='target_select[]' value='"+user_id+"'></li>")		  
				}
			}
		});       
		step.find(".move_left .move_list, .move_all .move_list").perfectScrollbar();
	}

	function delGoods()
	{
		<!-- var filters  = new Array(); -->
		$("#target_select").find("li").each(function(){
			if($(this).attr("class") == 'current'){
				<!-- filters.push($(this).find("a").data("value")); -->
				$(this).remove();
			}
		});
	}
	
$("#article_edit").perfectScrollbar("destroy");
$("#article_edit").perfectScrollbar();
</script>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'ajaxArea'): ?>
<?php if ($this->_var['is_ajax']): ?>
    <div class="content">
        <div class="flexilist" id='ajax_area'>
		<?php endif; ?>
            <div class="common-head">
                <div class="fl mt2">
                    <?php if ($this->_var['action_link']['type'] == 1): ?><a href="<?php echo $this->_var['action_link']['href']; ?>" data-type="<?php echo $this->_var['action_link']['type']; ?>" data-pid="<?php echo $this->_var['action_link']['pid']; ?>" ectype="nextView"><div class="fbutton"><div class="add_region" title="<?php echo $this->_var['action_link']['text']; ?>"><span><i class="icon icon-reply"></i><?php echo $this->_var['action_link']['text']; ?></span></div></div></a><?php endif; ?>
                </div>            
                <div class="add_area fr">
                    <form method="post" action="area_manage.php" name="theForm">
                       <input type="hidden" name="region_type" value="<?php echo $this->_var['region_type']; ?>" />
                       <input type="hidden" name="parent_id" value="<?php echo $this->_var['parent_id']; ?>" />
                       <input type="text" name="region_name" class="text" placeholder="<?php echo $this->_var['lang']['please_input_area_name']; ?>" autocomplete="off" />
                       <a href="javascript:void(0);" ectype="addArea" class="btn btn30 red_btn"><?php echo $this->_var['lang']['new_area']; ?></a>
                    </form>
                </div>
            </div>
            <div class="common-content" id="area_edit" style="position:relative; max-height: 500px; min-height:300px;overflow: hidden;">
                <div class="list-div" id="listDiv">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <thead>
                            <tr>
                                <th width="3%"><div class="tDiv">&nbsp;</div></th>
                                <th width="20%"><div class="tDiv"><?php echo $this->_var['lang']['area_name']; ?></div></th>
                                <th width="25%"><div class="tDiv"><?php echo $this->_var['lang']['belong_level']; ?></div></th>
                                <th width="25%"><div class="tDiv"><?php echo $this->_var['lang']['belong_area']; ?></div></th>
                                <th width="25%" class="handle"><?php echo $this->_var['lang']['handler']; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $_from = $this->_var['region_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'list');$this->_foreach['area_name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['area_name']['total'] > 0):
    foreach ($_from AS $this->_var['list']):
        $this->_foreach['area_name']['iteration']++;
?>
                            <tr>
                                <td><div class="tDiv">&nbsp;</div></td>
                                <td>
                                    <div class="tDiv">
                                        <input type="text" name="measure_unit" class="text w80" value="<?php echo htmlspecialchars($this->_var['list']['region_name']); ?>" onkeyup="listTable.editInput(this, 'edit_area_name', <?php echo $this->_var['list']['region_id']; ?>)"/>
                                    </div>
                                </td>
                                <td><div class="tDiv"><?php echo $this->_var['area_here']; ?></div></td>
                                <td><div class="tDiv"><?php echo $this->_var['area_top']; ?></div></td>
                                <td class="handle">
                                    <div class="tDiv a1">
                                        <?php if ($this->_var['region_type'] < 4): ?>
                                        <a href="javascript:;" data-type="<?php echo $this->_var['list']['region_type+1']; ?>" data-pid="<?php echo $this->_var['list']['region_id']; ?>" ectype="nextView" class="btn_see"><i class="sc_icon sc_icon_see"></i><?php echo $this->_var['lang']['manage']; ?></a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td class="no-records" colspan="12"><?php echo $this->_var['lang']['no_records']; ?></td></tr>
                            <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        </tbody>
                    </table>
                </div>
            </div>
<?php if ($this->_var['is_ajax']): ?>
        </div>
    </div>
    <script type="text/javascript">
    $(document).on('click','a[ectype="addArea"]',function(){
        var region_name = $("input[name='region_name']").val();
        var region_type = $("input[name='region_type']").val();
        var parent_id   = $("input[name='parent_id']").val();

        if (region_name.length == 0)
        {
            alert(jl_area_name_no_empty);
        }
        else
        {
            $.jqueryAjax("dialog.php", "is_ajax=1&act=area_insert&parent_id="+parent_id+ '&region_name=' + region_name + '&region_type=' + region_type, function(data){
                $('#ajax_area').html(data.content);
            }, 'POST', 'JSON');
        }

        return false;
    })
    
    $(document).on("click","a[ectype='nextView']",function(){
        var type = $(this).data('type');
        var pid = $(this).data('pid');
        $.jqueryAjax("dialog.php", "act=ajaxArea&type=" + type + '&pid=' + pid, function(data){
            $('#ajax_area').html(data.content);
            $("#area_edit").perfectScrollbar("destroy");
            $("#area_edit").perfectScrollbar();            
        }, 'POST', 'JSON');
    })
    
    $("#area_edit").perfectScrollbar("destroy");
    $("#area_edit").perfectScrollbar();      

    </script>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_var['temp'] == 'ajaxBrand'): ?>
    <div class="content">
        <form action="dialog.php" method="post" enctype="multipart/form-data" name="theForm" id="article_form">
        <div class="ajaxBrand" id="brand_edit">
            <div class="items">
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?><?php echo $this->_var['lang']['brand_name_cn']; ?>：</div>
                    <div class="label_value">
                        <input type="text" name="brand_name" maxlength="60" value="<?php echo $this->_var['brand']['brand_name']; ?>" class="text" autocomplete="off" />
                        <div class="form_prompt"></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['brand_name_en']; ?>：</div>
                    <div class="label_value">
                        <input type="text" name="brand_letter" maxlength="60" value="<?php echo $this->_var['brand']['brand_letter']; ?>" class="text" autocomplete="off" />
                        <div class="form_prompt"></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['brand_first_char']; ?>：</div>
                    <div class="label_value">
                        <input type="text" name="brand_first_char" maxlength="60" value="<?php echo $this->_var['brand']['brand_first_char']; ?>" class="text" autocomplete="off" />
                        <div class="form_prompt"></div>
                        <div class="notic"><?php echo $this->_var['lang']['brand_first_char_desc']; ?></div>
                    </div>
                </div>								
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['site_url']; ?>：</div>
                    <div class="label_value">
                        <input type="text" name="site_url" size="40" value="<?php echo $this->_var['brand']['site_url']; ?>" class="text" autocomplete="off" />
                    </div>
                </div>									
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?><?php echo $this->_var['lang']['brand_logo']; ?>：</div>
                    <div class="label_value">
                        <div class="type-file-box">
                            <input type="button" name="button" id="button" class="type-file-button" value="">
                            <input type="file" class="type-file-file" id="logo" name="brand_logo" size="30" data-state="imgfile" hidefocus="true" value="">
                            <?php if ($this->_var['brand']['brand_logo'] != ""): ?>
                            <span class="show">
                                <a href="<?php echo $this->_var['brand']['brand_logo']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="<?php echo $this->_var['brand']['brand_logo']; ?>" ectype="tooltip" title="tooltip"></i></a>
                            </span>
                            <?php endif; ?>
                            <input type="text" name="textfile" class="type-file-text" id="textfield" value="<?php echo $this->_var['brand']['brand_logo']; ?>" autocomplete="off" readonly>
                        </div>
                        <div class="form_prompt"></div>
                        <div class="notic" id="warn_brandlogo">
                        <?php if ($this->_var['brand']['brand_logo'] == ''): ?>
                            <?php echo $this->_var['lang']['up_brandlogo']; ?>
                        <?php else: ?>
                            <?php echo $this->_var['lang']['warn_brandlogo']; ?>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- <?php if ($this->_var['is_need']): ?> 品牌专区大图 by wu start-->
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['index_img']; ?>：</div>
                    <div class="label_value">
                        <div class="type-file-box">
                            <input type="button" name="button" id="button" class="type-file-button" value="">
                            <input type="file" class="type-file-file" id="logo" name="index_img" size="30" data-state="imgfile" hidefocus="true" value="">
                            <?php if ($this->_var['brand']['index_img'] != ""): ?>
                            <span class="show">
                                <a href="<?php echo $this->_var['brand']['index_img']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="<?php echo $this->_var['brand']['index_img']; ?>" ectype="tooltip" title="tooltip"></i></a>
                            </span>
                            <?php endif; ?>
                            <input type="text" name="textfile" class="type-file-text" id="textfield" value="<?php echo $this->_var['brand']['index_img']; ?>" autocomplete="off" readonly>
                        </div>
                        <div class="form_prompt"></div>
                        <div class="notic"><?php echo $this->_var['lang']['index_img_desc']; ?></div>
                    </div>
                </div>		
                <!-- <?php endif; ?> end-->
                <!-- 品牌背景图 start -->
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['brand_bg']; ?>：</div>
                    <div class="label_value">
                        <div class="type-file-box">
                            <input type="button" name="button" id="button" class="type-file-button" value="">
                            <input type="file" class="type-file-file" id="logo" name="brand_bg" size="30" data-state="imgfile" hidefocus="true" value="">
                            <?php if ($this->_var['brand']['brand_bg'] != ""): ?>
                            <span class="show">
                                <a href="<?php echo $this->_var['brand']['brand_bg']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="<?php echo $this->_var['brand']['brand_bg']; ?>" ectype="tooltip" title="tooltip"></i></a>
                            </span>
                            <?php endif; ?>
                            <input type="text" name="textfile" class="type-file-text" id="textfield" value="<?php echo $this->_var['brand']['brand_bg']; ?>" autocomplete="off" readonly>
                        </div>
                        <div class="form_prompt"></div>
                        <div class="notic"><?php echo $this->_var['lang']['brand_bg_desc']; ?></div>
                    </div>
                </div>
                <!-- 品牌背景图 end -->
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['brand_desc']; ?>：</div>
                    <div class="label_value">
                        <textarea name="brand_desc" cols="60" rows="4" class="textarea"><?php echo $this->_var['brand']['brand_desc']; ?></textarea>
                    </div>
                </div>								
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['sort_order']; ?>：</div>
                    <div class="label_value">
                        <input type="text" name="sort_order" maxlength="40" size="15" value="<?php echo $this->_var['brand']['sort_order']; ?>" class="text text_5" autocomplete="off" />
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['is_show']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items" style="width:auto;">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_show" id="is_show_1" value="1" <?php if ($this->_var['brand']['is_show'] == 1): ?> checked="true" <?php endif; ?>  />
                                <label for="is_show_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_show" id="is_show_0" value="0" <?php if ($this->_var['brand']['is_show'] == 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_show_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                            </div>
                        </div>
                        <div class="notic">(<?php echo $this->_var['lang']['visibility_notes']; ?>)</div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['lab_intro']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_recommend" id="is_recommend_1" value="1" <?php if ($this->_var['brand']['is_recommend'] == 1): ?> checked="true" <?php endif; ?>  />
                                <label for="is_recommend_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_recommend" id="is_recommend_0" value="0" <?php if ($this->_var['brand']['is_recommend'] == 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_recommend_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>								
                <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />								
            </div>
        </div>
        </form>
    </div>
	<script type="text/javascript">
    $(function(){
        $('.nyroModal').nyroModal();
    });
    
    $("#brand_edit").perfectScrollbar("destroy");
	$("#brand_edit").perfectScrollbar();
    </script>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'h-seckill'): ?>
<?php if ($this->_var['suffix'] == 'backup_festival_1'): ?>
<div class="box-hd clearfix">
    <h3><?php echo $this->_var['spec_attr']['title']; ?></h3>
    <div class="tit"><?php echo $this->_var['spec_attr']['subtitle']; ?></div>
    <div class="seckill-more"><a href="seckill.php" target="_blank"><?php echo $this->_var['lang']['more_anniversary']; ?><i class="iconfont icon-right"></i></a></div>
</div>
<div class="box-bd clearfix">
    <ul>
    	<?php $_from = $this->_var['spec_attr']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
        <li class="opacity_img">
            <div class="p-img"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank"><img src="<?php if ($this->_var['goods']['goods_thumb']): ?><?php echo $this->_var['goods']['goods_thumb']; ?><?php else: ?>../data/gallery_album/visualDefault/zhanwei.png<?php endif; ?>"></a></div>
            <div class="p-name"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
            <div class="p-price">
                <span class="shop-price"><?php echo $this->_var['goods']['sec_price']; ?></span>
                <span class="original-price"><?php echo $this->_var['goods']['market_price']; ?></span>
            </div>
        </li>
        <?php endforeach; else: ?>
        <li class="opacity_img">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="p-name"><a href="#" target="_blank">韩版秋冬时尚修身显瘦连帽小棉袄韩版秋冬时尚修身显瘦连帽小棉袄</a></div>
            <div class="p-price">
                <span class="shop-price"><em>¥</em>5888.00</span>
                <span class="original-price"><em>¥</em>5888.00</span>
            </div>
        </li>
        <li class="opacity_img">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="p-name"><a href="#" target="_blank">韩版秋冬时尚修身显瘦连帽小棉袄韩版秋冬时尚修身显瘦连帽小棉袄</a></div>
            <div class="p-price">
                <span class="shop-price"><em>¥</em>5888.00</span>
                <span class="original-price"><em>¥</em>5888.00</span>
            </div>
        </li>
        <li class="opacity_img">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="p-name"><a href="#" target="_blank">韩版秋冬时尚修身显瘦连帽小棉袄韩版秋冬时尚修身显瘦连帽小棉袄</a></div>
            <div class="p-price">
                <span class="shop-price"><em>¥</em>5888.00</span>
                <span class="original-price"><em>¥</em>5888.00</span>
            </div>
        </li>
        <li class="opacity_img">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="p-name"><a href="#" target="_blank">韩版秋冬时尚修身显瘦连帽小棉袄韩版秋冬时尚修身显瘦连帽小棉袄</a></div>
            <div class="p-price">
                <span class="shop-price"><em>¥</em>5888.00</span>
                <span class="original-price"><em>¥</em>5888.00</span>
            </div>
        </li>
        <li class="opacity_img">
            <div class="p-img"><a href="#" target="_blank"><img src="../data/gallery_album/visualDefault/zhanwei.png"></a></div>
            <div class="p-name"><a href="#" target="_blank">韩版秋冬时尚修身显瘦连帽小棉袄韩版秋冬时尚修身显瘦连帽小棉袄</a></div>
            <div class="p-price">
                <span class="shop-price"><em>¥</em>5888.00</span>
                <span class="original-price"><em>¥</em>5888.00</span>
            </div>
        </li>
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</div>
<?php else: ?>
<div class="box-hd clearfix">
    <i class="box_hd_arrow"></i>
    <i class="box_hd_dec"></i>
    <i class="box-hd-icon"></i>
    <div class="sk-time-cd">
        <div class="sk-cd-tit"><?php echo $this->_var['lang']['end_of_the_distance']; ?></div>
        <div class="cd-time fl" ectype="time" data-time="2018-01-13 12:00:00">
            <div class="days hide">00</div>
            <span class="split hide"><?php echo $this->_var['lang']['tian']; ?></span>
            <div class="hours">00</div>
            <span class="split"><?php echo $this->_var['lang']['hour']; ?></span>
            <div class="minutes">00</div>
            <span class="split"><?php echo $this->_var['lang']['minute']; ?></span>
            <div class="seconds">00</div>
            <span class="split"><?php echo $this->_var['lang']['second']; ?></span>
        </div>
    </div>
    <div class="sk-more"><a href="seckill.php" target="_blank"><?php echo $this->_var['lang']['more_seckill']; ?></a> <i class="arrow"></i></div>
</div>
<div class="box-bd clearfix">
    <ul>
        <?php $_from = $this->_var['spec_attr']['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
        <li class="opacity_img">
            <div class="p-img"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank"><img src="<?php echo $this->_var['goods']['goods_thumb']; ?>" class="img-lazyload"></a></div>
            <div class="p-name"><a href="<?php echo $this->_var['goods']['list_url']; ?>" target="_blank" title="<?php echo $this->_var['goods']['goods_name']; ?>"><?php echo $this->_var['goods']['goods_name']; ?></a></div>
            <div class="p-over">
                <div class="p-info">
                    <div class="p-price">
                        <span class="shop-price"><?php echo $this->_var['goods']['sec_price']; ?></span>
                        <span class="original-price"><?php echo $this->_var['goods']['market_price']; ?></span>
                    </div>
                </div>
                <div class="p-btn">
                    <a href="<?php echo $this->_var['goods']['url']; ?>" target="_blank"><?php echo $this->_var['lang']['rush_buy_now']; ?></a>
                </div>
            </div>
        </li>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
    <a href="javascript:void(0);" class="prev"><i class="iconfont icon-left"></i></a>
	<a href="javascript:void(0);" class="next"><i class="iconfont icon-right"></i></a>
</div>
<?php endif; ?>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'ajaxCate'): ?>
    <div class="content">
        <form action="dialog.php" method="post" enctype="multipart/form-data" name="theForm" id="cate_form">
        <div class="ajaxCate" id="cate_edit">
            <div class="items">
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['require_field']; ?>&nbsp;<?php echo $this->_var['lang']['cat_name']; ?>：</div>
                    <div class="label_value">
                      <input type='text' class="text" name='cat_name' maxlength="20" value='<?php echo htmlspecialchars($this->_var['cat_info']['cat_name']); ?>' size='27' />
                      <div class="form_prompt"></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['cat_name_alias']; ?>：</div>
                    <div class="label_value">
                        <input type='text' name='cat_alias_name' class="text" id="cat_alias_name" maxlength="20" value='<?php if ($this->_var['cat_info']['parent_id'] == 0): ?><?php echo htmlspecialchars($this->_var['cat_info']['cat_alias_name']); ?><?php endif; ?>' size='27' <?php if ($this->_var['cat_info']['parent_id'] != 0): ?>disabled="true"<?php endif; ?> />
                        <div class="notic"><?php echo $this->_var['lang']['cat_name_alias_notic']; ?></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['phone_icon']; ?>：</div>
                    <div class="label_value">
                        <div class="type-file-box">
                            <input type="button" name="button" id="button" class="type-file-button" value="" />
                            <input type="file" class="type-file-file" id="ad_img" name="touch_icon" data-state="imgfile" size="30" hidefocus="true" value="" />
                            <?php if ($this->_var['cat_info']['touch_icon']): ?>
                            <span class="show">
                                <a href="../<?php echo $this->_var['cat_info']['touch_icon']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="../<?php echo $this->_var['cat_info']['touch_icon']; ?>" ectype="tooltip" title="tooltip"></i></a>
                            </span>
                            <?php endif; ?>
                            <input type="text" name="textfile" class="type-file-text" <?php if ($this->_var['cat_info']['touch_icon']): ?><?php echo $this->_var['cat_info']['touch_icon']; ?><?php endif; ?> id="textfield" autocomplete="off" readonly />
                        </div>
                        <div class="form_prompt"></div>
                        <div class="notic m20" id="AdCodeImg"><?php echo $this->_var['lang']['phone_icon_notic']; ?></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['parent_id']; ?>：</div>
                    <div class="label_value">
                        <div class="search_select">
                            <div class="categorySelect">
                                <div class="selection">
                                    <input type="text" name="category_name" id="category_name" class="text w290 valid" value="<?php if ($this->_var['parent_category']): ?><?php echo $this->_var['parent_category']; ?><?php else: ?><?php echo $this->_var['lang']['category_top']; ?><?php endif; ?>" autocomplete="off" readonly data-filter="cat_name" />
                                    <input type="hidden" name="parent_id" id="category_id" value="<?php echo empty($this->_var['parent_id']) ? '0' : $this->_var['parent_id']; ?>" data-filter="cat_id" />
                                </div>
                                <div class="select-container w319" style="display:none;">
                                    <?php echo $this->fetch('library/filter_category.lbi'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="notic"><?php echo $this->_var['lang']['parent_id_notic']; ?></div>
                    </div>
                </div>								
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['measure_unit']; ?>：</div>
                    <div class="label_value">
                        <input type="text" class="text text_4" name='measure_unit' value='<?php echo $this->_var['cat_info']['measure_unit']; ?>' size="12" autocomplete="off" />
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['sort_order']; ?>：</div>
                    <div class="label_value">
                        <input type="text" class="text text_4" name='sort_order' <?php if ($this->_var['cat_info']['sort_order']): ?>value='<?php echo $this->_var['cat_info']['sort_order']; ?>'<?php else: ?> value="50"<?php endif; ?> size="15" autocomplete="off" />
                    </div>
                </div>	
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['commission_rate']; ?>：</div>
                    <div class="label_value">
                        <input type="text" class="text text_2" name='commission_rate' value='<?php echo $this->_var['cat_info']['commission_rate']; ?>' size="12" autocomplete="off" />
                        <div class="notic">% <?php echo $this->_var['lang']['commission_rate_notic']; ?></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['is_show']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_show" id="is_show_1" value="1" <?php if ($this->_var['cat_info']['is_show'] != 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_show_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_show" id="is_show_0" value="0" <?php if ($this->_var['cat_info']['is_show'] == 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_show_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['show_in_nav']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="show_in_nav" id="show_in_nav_1" value="1" <?php if ($this->_var['cat_info']['show_in_nav'] != 0): ?> checked="true" <?php endif; ?>  />
                                <label for="show_in_nav_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="show_in_nav" id="show_in_nav_0" value="0" <?php if ($this->_var['cat_info']['show_in_nav'] == 0): ?> checked="true" <?php endif; ?>  />
                                <label for="show_in_nav_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>	
                <?php if ($this->_var['cat_info']['parent_id'] != 0): ?>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['show_category_top']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_top_show" id="is_top_show_1" value="1" <?php if ($this->_var['cat_info']['is_top_show'] != 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_top_show_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_top_show" id="is_top_show_0" value="0" <?php if ($this->_var['cat_info']['is_top_show'] == 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_top_show_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>	
                <?php endif; ?>
                <?php if ($this->_var['cat_info']['parent_id'] == 0): ?>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['show_category_top_css']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox_items">
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_top_style" id="is_top_style_1" value="1" <?php if ($this->_var['cat_info']['is_top_style'] != 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_top_style_1" class="ui-radio-label"><?php echo $this->_var['lang']['yes']; ?></label>
                            </div>
                            <div class="checkbox_item">
                                <input type="radio" class="ui-radio" name="is_top_style" id="is_top_style_0" value="0" <?php if ($this->_var['cat_info']['is_top_style'] == 0): ?> checked="true" <?php endif; ?>  />
                                <label for="is_top_style_0" class="ui-radio-label"><?php echo $this->_var['lang']['no']; ?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="item" <?php if ($this->_var['cat_info']['is_top_style'] == 0): ?>style="display:none"<?php endif; ?>>
                    <div class="label"><?php echo $this->_var['lang']['top_category_mode']; ?>：</div>
                    <div class="label_value">
                        <div class="imitate_select select_w120">
                            <div class="cite"><?php echo $this->_var['lang']['top_category_mode_0']; ?></div>
                            <ul style="display: none;">
                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['top_category_mode_0']; ?></a></li>
                                <li><a href="javascript:;" data-value="1" class="ftx-01"><?php echo $this->_var['lang']['top_category_mode_1']; ?></a></li>
                                <li><a href="javascript:;" data-value="2" class="ftx-01"><?php echo $this->_var['lang']['top_category_mode_2']; ?></a></li>
                                <li><a href="javascript:;" data-value="3" class="ftx-01"><?php echo $this->_var['lang']['top_category_mode_3']; ?></a></li>
                            </ul>
                            <input name="top_style_tpl" type="hidden" value="<?php echo $this->_var['cat_info']['top_style_tpl']; ?>">
                        </div>
                    </div>
                </div>							
                <?php endif; ?>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['category_icon']; ?>：</div>
                    <div class="label_value">
                        <div class="checkbox-items checkbox-cateicon-items" ectype="style_icon">
                            <?php $_from = $this->_var['lang']['icon']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'icon');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['icon']):
?>
                            <div class="checkbox-item">
                                <input type="radio" name="style_icon" class="ui-radio" id="radio_<?php echo $this->_var['key']; ?>" data-name="<?php echo $this->_var['key']; ?>" value="<?php echo $this->_var['key']; ?>" <?php if ($this->_var['cat_info']['style_icon'] == $this->_var['key']): ?>checked<?php endif; ?> />
                                <label class="ui-radio-label" for="radio_<?php echo $this->_var['key']; ?>"><span></span><i class="iconfont icon-<?php echo $this->_var['key']; ?>"></i></label>
                            </div>
                            <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                            <div class="checkbox-other">
                                <div class="checkbox-item">
                                    <input type="radio" name="style_icon" class="ui-radio" id="radio_other" data-name="other" value="other" <?php if ($this->_var['cat_info']['style_icon'] == 'other'): ?>checked<?php endif; ?> />
                                    <label class="ui-radio-label" for="radio_other"><span><?php echo $this->_var['lang']['custom']; ?></span></label>
                                </div>
                                <div class="type-file-box ml10 <?php if ($this->_var['cat_info']['style_icon'] != 'other'): ?>hide<?php endif; ?>" ectype="cat_icon">
                                    <input type="button" name="button" id="button" class="type-file-button" value="" />
                                    <input type="file" class="type-file-file" id="cat_icon" name="cat_icon" size="30" data-state="imgfile" hidefocus="true" value="" />
                                    <?php if ($this->_var['cat_info']['cat_icon']): ?>
                                    <span class="show">
                                        <a href="../<?php echo $this->_var['cat_info']['cat_icon']; ?>" target="_blank" class="nyroModal"><i class="icon icon-picture" data-tooltipimg="../<?php echo $this->_var['cat_info']['cat_icon']; ?>" ectype="tooltip" title="tooltip"></i></a>
                                    </span>
                                    <?php endif; ?>
                                    <input type="text" name="textfile" class="type-file-text" id="textfield" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="notic"><?php echo $this->_var['lang']['category_icon_notic']; ?></div>
                    </div>
                </div>	
                <div class="item" <?php if (! $this->_var['cat_name_arr']): ?>style="display:none"<?php endif; ?>>
                    <div class="label"><?php echo $this->_var['lang']['category_herf']; ?>：</div>
                    <div class="label_value">
                        <textarea name='category_links' rows="6" cols="48" class="textarea"><?php echo $this->_var['cat_info']['category_links']; ?></textarea>
                        <div class="notic"><?php echo $this->_var['lang']['category_herf_notic']; ?></div>
                    </div>
                </div>
                <div class="item" <?php if ($this->_var['parent_id'] != 0 || $this->_var['form_act'] == 'insert'): ?>style=" display:none"<?php endif; ?>>
                    <div class="label"><?php echo $this->_var['lang']['category_top_mode_content']; ?>：</div>
                    <div class="label_value">
                        <textarea name='category_topic' rows="6" cols="48" class="textarea"><?php echo $this->_var['cat_info']['category_topic']; ?></textarea>
                        <div class="notic"><?php echo $this->_var['lang']['category_top_mode_content_notic']; ?></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['filter_attr']; ?>：</div>
                    <div class="label_value">
                        <div class="lv-item" ectype="item">
                            <div class="value_select" ectype="type_cat">
                                <div id="parent_id1" class="imitate_select select_w145" ectype="typeCatSelect">
                                    <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                    <ul>
                                        <li><a href="javascript:;" data-value="0" data-level='1' data-cat='1' class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                        <?php $_from = $this->_var['type_level']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                                        <li><a href="javascript:;" data-value="<?php echo $this->_var['cat']['cat_id']; ?>" data-level="<?php echo $this->_var['cat']['level']; ?>"  data-cat='1' class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></li>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </ul>
                                    <input type="hidden" value="<?php echo empty($this->_var['cat_tree1']['checked_id']) ? '0' : $this->_var['cat_tree1']['checked_id']; ?>" id="parent_id_val1" ectype="typeCatVal">
                                </div>
                                <?php if ($this->_var['cat_tree1']['arr']): ?>
                                <div id="parent_id2" class="imitate_select select_w145" ectype="typeCatSelect">
                                    <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                    <ul>
                                        <li><a href="javascript:;" data-value="0" data-level='2' data-cat='1' class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                        <?php $_from = $this->_var['cat_tree1']['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                                        <li><a href="javascript:;" data-value="<?php echo $this->_var['cat']['cat_id']; ?>" data-level="<?php echo $this->_var['cat']['level']; ?>" data-cat='1' class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></li>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </ul>
                                    <input type="hidden" value="<?php echo empty($this->_var['cat_tree']['checked_id']) ? '0' : $this->_var['cat_tree']['checked_id']; ?>" id="parent_id_val2" ectype="typeCatVal">
                                </div>
                                <?php endif; ?>
                                <?php if ($this->_var['cat_tree']['arr']): ?>
                                <div id="parent_id<?php if ($this->_var['cat_tree1']['arr']): ?>3<?php else: ?>2<?php endif; ?>" class="imitate_select select_w145" ectype="typeCatSelect">
                                    <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                    <ul>
                                        <li><a href="javascript:;" data-value="0" data-level='<?php if ($this->_var['cat_tree1']['arr']): ?>3<?php else: ?>2<?php endif; ?>' data-cat='1' class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                        <?php $_from = $this->_var['cat_tree']['arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                                        <li><a href="javascript:;" data-value="<?php echo $this->_var['cat']['cat_id']; ?>" data-level="<?php echo $this->_var['cat']['level']; ?>" data-cat='1' class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></li>
                                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                    </ul>
                                    <input type="hidden" value="<?php echo empty($this->_var['type_c_id']) ? '0' : $this->_var['type_c_id']; ?>" id="parent_id_val<?php if ($this->_var['cat_tree1']['arr']): ?>3<?php else: ?>2<?php endif; ?>" ectype="typeCatVal">
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="imitate_select select_w170" ectype="attrTypeSelect">
                                <div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
                                <ul style="display: none;">
                                    <?php echo $this->_var['goods_type_list']; ?>
                                </ul>
                                <input name="goods_type" type="hidden" value="0">
                            </div>
                            <div class="imitate_select select_w120">
                                <div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
                                <ul style="display: none;">
                                    <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['sel_filter_attr']; ?></a></li>
                                </ul>
                                <input name="filter_attr[]" type="hidden" value="0">
                            </div>
                            <a href="javascript:;" onclick="addFilterAttr(this)" class="fl mr10" ectype="operation">[+]</a>
                        </div>         
                        <?php $_from = $this->_var['filter_attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'filter_attr');$this->_foreach['filter_attr_tab'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['filter_attr_tab']['total'] > 0):
    foreach ($_from AS $this->_var['filter_attr']):
        $this->_foreach['filter_attr_tab']['iteration']++;
?>
                        <div class="lv-item" ectype="item">
                            <div class="imitate_select select_w170" ectype="attrTypeSelect">
                                <div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
                                <ul style="display: none;">
                                    <?php echo $this->_var['goods_type_list']; ?>
                                </ul>
                                <input name="goods_type" type="hidden" value="<?php echo $this->_var['filter_attr']['goods_type']; ?>">
                            </div>
                            <div class="imitate_select select_w120">
                                <div class="cite"><?php echo $this->_var['lang']['sel_goods_type']; ?></div>
                                <ul style="display: none;">
                                    <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['sel_filter_attr']; ?></a></li>
                                    <?php $_from = $this->_var['filter_attr']['option']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['key'] => $this->_var['item']):
?>
                                    <li><a href="javascript:;" data-value="<?php echo $this->_var['key']; ?>" class="ftx-01"><?php echo $this->_var['item']; ?></a></li>
                                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                </ul>
                                <input name="filter_attr[]" type="hidden" value="<?php echo $this->_var['filter_attr']['filter_attr']; ?>">
                            </div>
                            <a href="javascript:;" onclick="removeFilterAttr(this)" class="fl mr10" ectype="operation">[-]&nbsp;</a>
                        </div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                        <input name="attr_parent_id" type="hidden" value="<?php echo empty($this->_var['type_c_id']) ? '0' : $this->_var['type_c_id']; ?>">
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['grade']; ?>：</div>
                    <div class="label_value">					
                      <input type="text" name="grade" value="<?php echo empty($this->_var['cat_info']['grade']) ? '0' : $this->_var['cat_info']['grade']; ?>" size="40" class="text mr10" autocomplete="off" />
                      <div class="form_prompt"></div>
                      <div class="notic"><?php echo $this->_var['lang']['notice_grade']; ?></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['cat_style']; ?>：</div>
                    <div class="label_value">					
                      <input type="text" name="style" value="<?php echo htmlspecialchars($this->_var['cat_info']['style']); ?>" size="40" class="text mr10" autocomplete="off" />
                      <div class="notic"><?php echo $this->_var['lang']['notice_style']; ?></div>
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['keywords']; ?>：</div>
                    <div class="label_value">					
                        <input type="text" name="keywords" value='<?php echo $this->_var['cat_info']['keywords']; ?>' size="50" class="text mr10" autocomplete="off" />										
                    </div>
                </div>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['cat_desc']; ?>：</div>
                    <div class="label_value">					
                        <textarea name='cat_desc' rows="6" cols="48" class="textarea"><?php echo $this->_var['cat_info']['cat_desc']; ?></textarea>							
                    </div>
                </div>
                <?php if ($this->_var['cat_info']['parent_id'] == 0 && $this->_var['form_act'] == 'update'): ?>								
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['categoryFile']; ?>：</div>
                    <div class="label_value">					
                      <table width="100%" align="center" id="documentTitle_table" style="border:none; padding:0px;">
                        <?php if ($this->_var['form_act'] == 'update' && $this->_var['title_list']): ?>
                        <tr>
                          <td>
                                <a href="category.php?act=titleFileView&cat_id=<?php echo $this->_var['cat_id']; ?>"><?php echo $this->_var['lang']['see_zj_list']; ?></a>
                          </td>
                        </tr>  	
                        <?php else: ?>
                            <tr>
                              <td>
                                  <a onclick="addCategoryFile(this)" href="javascript:;" class="fl mr10 w20 tc">[+]</a>
                                  <?php echo $this->_var['lang']['document_title']; ?> <input type="hidden" value="0" size="40" name="dt_id[]"><input type="text" value="" size="40" name="document_title[]" class="text" autocomplete="off" />
                              </td>
                            </tr> 
                            <?php if ($this->_var['title_list']): ?>
                            <?php $_from = $this->_var['title_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'title');if (count($_from)):
    foreach ($_from AS $this->_var['title']):
?>
                            <tr>
                              <td>
                                  <a onclick="removeCategoryFile(this,<?php echo $this->_var['title']['dt_id']; ?>)" href="javascript:;" class="fl mr10 w20 tc">[-]</a>
                                  <?php echo $this->_var['lang']['document_title']; ?> <input type="hidden" value="0" size="40" name="dt_id[]"><input type="text" value="<?php echo $this->_var['title']['dt_title']; ?>" size="40" name="document_title[]" class="text" autocomplete="off" />
                              </td>
                            </tr>
                             <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  
                             <?php endif; ?>
                         <?php endif; ?>         
                      </table>																			
                    </div>
                </div>
                <?php elseif ($this->_var['form_act'] == 'insert'): ?>
                <div class="item">
                    <div class="label"><?php echo $this->_var['lang']['categoryFile']; ?>：</div>
                    <div class="label_value">					
                      <table width="100%" align="center" id="documentTitle_table" style="border:none">
                        <tr>
                          <td>
                              <a onclick="addCategoryFile(this)" href="javascript:;" class="fl mr10 w20 tc">[+]</a>
                              <label class="fl lh mr10"><?php echo $this->_var['lang']['document_title']; ?></label>
                              <input type="text" value="" size="40" name="document_title[]" class="text" autocomplete="off" />
                              <input type="hidden" value="0" size="40" name="dt_id[]">
                          </td>
                        </tr>        
                      </table>																			
                    </div>
                </div>
                <?php endif; ?>
                <input type="hidden" name="act" value="<?php echo $this->_var['form_action']; ?>" />
                <input type="hidden" name="old_cat_name" value="<?php echo $this->_var['cat_info']['cat_name']; ?>" />
                <input type="hidden" name="cat_id" value="<?php echo $this->_var['cat_info']['cat_id']; ?>" />
        	</div>
        </form>
    </div>
    </div>
	<script type="text/javascript">
    $(function(){
        $('.nyroModal').nyroModal();
    });
	$(function(){
        $('.nyroModal').nyroModal();
		        
		//自定义图标
		$("*[ectype=style_icon] input:radio").click(function(){
			if($(this).val() == 'other'){
				$("*[ectype=cat_icon]").removeClass('hide');
			}else{
				$("*[ectype=cat_icon]").addClass('hide');
			}
		})
	
		jQuery.validator.addMethod("specialchar", function(value, element) {

		  return this.optional(element) || !/[@'\\"#$%&\^*]/.test(value);   
		},(jl_no_specialchar));
	});
    
    /**
     * 新增一个筛选属性
     */
    function addFilterAttr(obj)
	{
		var obj = $(obj);
		var parent = obj.parents("*[ectype='item']");
		var clone = parent.clone();
		clone.find("[ectype='operation']")
		.attr("onclick",'removeFilterAttr(this)')
		.html("[-]");

		parent.after(clone);
	}

    
    /**
     * 删除一个筛选属性
     */
    function removeFilterAttr(obj)
	{
		var obj = $(obj);
		var parent = obj.parents("*[ectype='item']");
		parent.remove();
	}
    
    //ecmoban模板堂 --zhuo start
    
    //判断选择的分类是否是顶级分类，如果是则可用 类目证件
    function get_cat_parent_val(f,lev){
        var cat_alias_name = document.getElementById("cat_alias_name");
        var title_list = document.getElementsByName("document_title[]");
        var cat_parent_id = f + "_" + Number(lev - 1);
        
        var arr = new Array();
        var str = new String(cat_parent_id);
        var arr = str.split("_");
        var sf = Number(arr[0]);
        var slevel = Number(arr[1]);
    
        catList(sf, lev);
    
        for(i=0; i<title_list.length; i++){
            if(sf != 0){
                title_list[i].disabled = true;
                title_list[i].value = '';
                cat_alias_name.disabled = true;
                cat_alias_name.value = '';
                
            }else{
				//顶级分类为0
                title_list[i].disabled = false;
                cat_alias_name.disabled = false;
            }	
        }
    }
    /**
       * 添加类目证件
       */
      function addCategoryFile(obj)
      {  
         var title_list = document.getElementsByName("document_title[]");
         var catParent = document.getElementById('category_id').value; 
    
         if(catParent != 0){
             alert(category_top_use_null);
    
             for(i=0; i<title_list.length; i++){
                 title_list[i].value = '';
             }
             
             return false;
        }
          
        var src      = obj.parentNode.parentNode;
        var tbl      = document.getElementById('documentTitle_table');
    
        var row  = tbl.insertRow(tbl.rows.length);
        var cell = row.insertCell(-1);
        cell.innerHTML = src.cells[0].innerHTML.replace(/(.*)(addCategoryFile)(.*)(\[)(\+)/i, "$1removeCategoryFile$3$4-");
    
        title_list[title_list.length-1].value = "";
      }
    
      /**
       * 删除类目证件
       */
      function removeCategoryFile(obj,dt_id)
      {
          if(dt_id > 0){
           if (confirm(is_delete_info)){
               <?php if ($this->_var['cat_id'] > 0): ?>
               location.href = 'category.php?act=title_remove&dt_id=' + dt_id + '&cat_id=' + <?php echo $this->_var['cat_id']; ?>;  
               <?php endif; ?>
           }
          }else{
              var row = rowindex(obj.parentNode.parentNode);
              var tbl = document.getElementById('documentTitle_table');
        
              tbl.deleteRow(row);
          }
      }
    //ecmoban模板堂 --zhuo end
    
    //-->
    
    //顶级分类页模板 by wu
    $(document).ready(function(){
        $("[name='is_top_style']").click(function(){
            if($(this).attr('value')==1)
            {
                $("[name='top_style_tpl']").parents('.item').show();
                //$("[name='cat_icon']").parents('.item').show();
            }
            else
            {
                $("[name='top_style_tpl']").parents('.item').hide();
                //$("[name='cat_icon']").parents('.item').hide();
            }
        })
    })
    
    function delete_icon(cat_id)
    {
        $.ajax({
            type:'get',
            url:'category.php',
            data:'act=delete_icon&cat_id='+cat_id,
            dataType:'json',
            success:function(data){
                if(data.error==1)
                {	
                    location.reload();
                }
                if(data.error==0)
                {	
                    alert(delete_fail);
                }			
            }
        })
    }
    
    // 分类分级 by qin
    function catList(val, level)
    {
        var cat_parent_id = val;
        Ajax.call('goods.php?is_ajax=1&act=sel_cat', 'cat_id='+cat_parent_id+'&cat_level='+level, catListResponse, 'GET', 'JSON');
    }
    
    function catListResponse(result)
    {
        document.getElementById('cat_parent_id').value = result.parent_id + "_" + Number(result.cat_level - 1);  
        if (result.error == '1' && result.message != '')
        {
            alert(result.message);
            return;
        }
        var response = result.content;
        var cat_level = result.cat_level; // 分类级别， 1为顶级分类
        for(var i=cat_level;i<10;i++)
        {
            $("#cat_list"+Number(i+1)).remove();
        }
        if(response)
        {
            $("#cat_list"+cat_level).after(response);
        }
        return;
    }
	
	
	var arr = new Array();
	var sel_filter_attr = "<?php echo $this->_var['lang']['sel_filter_attr']; ?>";
	<?php $_from = $this->_var['attr_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('att_cat_id', 'val');if (count($_from)):
    foreach ($_from AS $this->_var['att_cat_id'] => $this->_var['val']):
?>
		arr[<?php echo $this->_var['att_cat_id']; ?>] = new Array();
		<?php $_from = $this->_var['val']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('i', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['i'] => $this->_var['item']):
?>
		  <?php $_from = $this->_var['item']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('attr_id', 'attr_val');if (count($_from)):
    foreach ($_from AS $this->_var['attr_id'] => $this->_var['attr_val']):
?>
			arr[<?php echo $this->_var['att_cat_id']; ?>][<?php echo $this->_var['i']; ?>] = ["<?php echo $this->_var['attr_val']; ?>", <?php echo $this->_var['attr_id']; ?>];
		  <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	
	//修改 by wu
	function changeCat(obj)
	{
		var obj = $(obj);
		var key = obj.data('value');
		
		if(arr[key]){
			var tArr = arr[key];
			var target = obj.parents(".imitate_select").next().find("ul");
			target.find("li:gt(0)").remove();
			for(var i=0; i<tArr.length; i++){
				var line = "<li><a href='javascript:;' data-value='"+tArr[i][1]+"' class='ftx-01'>"+tArr[i][0]+"</a></li>";
				target.append(line);
			}
		}
	}
	
	//属性分类筛选出属性类型
	$.divselect("*[ectype='typeCatSelect']","*[ectype='typeCatVal']",function(obj){
		var level = obj.data('level'),
            cat = obj.data("cat"),
			val = obj.data("value");

		get_childcat(obj,2);
	});
    
    $("#cate_edit").perfectScrollbar("destroy");
	$("#cate_edit").perfectScrollbar();
    </script>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'cate_reload'): ?>
    <div class="sort_list sort_list_one">
        <div class="sort_list_warp">
            <div class="category_list">
                <ul ectype="category" data-cat_level="1">
                    <?php echo $this->_var['category_level']['1']; ?>
                </ul>
            </div>
            <div class="sort_point"></div>
        </div>
    </div>
    <div class="sort_list sort_list_one">
        <div class="sort_list_warp">
            <div class="category_list">
                <ul ectype="category" data-cat_level="2">
                    <?php echo $this->_var['category_level']['2']; ?>
                </ul>
            </div>
        </div>
        <div class="sort_point"></div>
    </div>
    <div class="sort_list">
        <div class="sort_list_warp">
            <div class="category_list">
                <ul ectype="category" data-cat_level="3">
                    <?php echo $this->_var['category_level']['3']; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($this->_var['temp'] == 'video_box_load'): ?>
<div class="video_box">
    <div id="video_upload_area">
        <div class="video_notice"><?php echo $this->_var['lang']['upload_10m_video_no_sex']; ?></div>
        <div class="video_textarea_upload" ectype="video_textarea_upload">
            <!-- <textarea id="video_text" placeholder="点击此处输入正文..." autocomplete="off" maxlength="130"></textarea> -->
            <div class="video_upload_words">
                <a href="javascript:void(0);" class="video_upload_btn" id="video_upload_btn" ectype="video_upload_btn">+</a>
                <a href="javascript:void(0);" class="video_name_box" ectype="video_name_box">
                    <img src="../js/plupload/images/icon_video.png" />
                    <em id="video_file_name" ectype="video_file_name"></em>
                    <span class="photo_upload_close" onclick="reupload_video()"></span>
                </a>
            </div>
            <div class="video_upload_words">
                
            </div>
        </div>
    </div>
    <div class="layer_point" style="display: none;">
        <dl id="video_loading" class="point clearfix" style="display: none;">
            <dt>
                <span class="loading_bar"><em id="percent"></em></span>
                <span id="percentnum" class="S_txt2 load_num"></span>
            </dt>
            <dd>
                <p id="updesc" class="updesc"><?php echo $this->_var['lang']['uploading_video']; ?></p>
                <p class="S_txt2"><label id="uploading_tip"><?php echo $this->_var['lang']['upload_speed_wait']; ?></label><a href="javascript:void(0);" id="video_upload_cancel" class="video_upload_cancel" onclick="video_upload_cancel()"><?php echo $this->_var['lang']['upload_cancel']; ?></a></p>
            </dd>
        </dl>
        <div id="video_success" style="display: none;">
            <dl class="point clearfix">
                <dt>
                    <span class="W_icon icon_succB"></span>
                </dt>
                <dd>
                    <p id="upload_succ" class="upload_succ"></p>
                </dd>
            </dl>
        </div>
    </div>
    <div class="upload_video_area disabled" id="upload_video_area" ectype="upload_video_area">
        <a id="upload_video" class="btn btn30 blue_btn mt10" href="javascript:void(0);"><?php echo $this->_var['lang']['upload_start']; ?></a>
    </div>
</div>
<script type="text/javascript">
    var uploader_video = new plupload.Uploader({//创建实例的构造方法
        runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
        browse_button: 'video_upload_btn', // 上传按钮
        url: "gallery_album.php?is_ajax=1&act=goods_video&goods_id="+goods_id, //远程上传地址
        flash_swf_url: 'js/plugins/plupload/Moxie.swf',         //flash文件地址 
        silverlight_xap_url: 'js/plugins/plupload/Moxie.xap',   //silverlight文件地址 
        filters: {
            max_file_size: '10mb', //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
            mime_types: [//允许文件上传类型
				//mpg,m4v,mp4,flv,3gp,mov,avi,rmvb,mkv,wmv
                {title: "files", extensions: "mp4"}
            ]
        },
        multi_selection: false, //true:ctrl多文件上传, false 单文件上传
        init:{
            FilesAdded: function(up, files) { //文件上传前
                $("*[ectype='video_name_box']").css("display","block");
                $("*[ectype='video_upload_btn']").hide();
                $("*[ectype='video_file_name']").text(files[0].name); 
                $("#upload_video").removeClass("disabled"); 
     
                $("#upload_video").click(function() { 
                    uploader_video.start(); //调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法 
                }) 
                $("#video_iput").attr("file_id", files[0]['id']);
            },
            UploadProgress:function(up, file){
                $("#video_loading,.layer_point").show(); 
                $('#upload_video_area,#video_upload_area').hide(); 
                var percent = file.percent; 
                $("#percent").css({"width": percent + "%"}); 
                $("#percentnum").text(percent + "%"); 
                $("#video_success").hide();
            },
            FileUploaded: function(up, file, info) { //文件上传成功的时候触发
                $("#video_loading").hide(); 
                $("#video_success").show(); 
				
				var data = eval("(" + info.response + ")");

				if(data.error == 1){
					$("#upload_succ").html(jl_upload_video_fail);
				}else if(data.error == 2){
					$("#upload_succ").html(jl_upload_video_format_wrong);
				}else{
					$("#upload_succ").html(jl_upload_video_success);
				}
				
                $("input[name=goods_video]").val(data.goods_video);
				
				if(data.error == 0){
					$(".goods_video_div").removeClass("hide");
					$(".goods_video_div").show();
					$(".goods_video_js").attr("src", data.goods_video_path);
				}
            },
            Error: function(up, err) { //上传出错的时候触发
                alert(err.message);
            }
        }
    });
    uploader_video.init();

    function reupload_video(){
        $("*[ectype='video_upload_btn']").show();
        $("*[ectype='video_name_box']").hide();
        $("*[ecyppe='upload_video_area']").show().addClass("disabled");
        $("#video_text").val("");
        $("#video_success").hide();
        $("#video_file,#video_name").val("");
    }

    function video_upload_cancel() {  //取消上传
        uploader_video.stop();
        $("#video_loading,#video_name_box").hide();
        $("#video_upload_area,#video_upload_btn").show();
        $("#upload_video_area").show();
        $("#upload_video").addClass("disabled");
        $("#video_text").val("");
        $("#video_words_num").text(0);
        $("#video_success").hide();
        $("#video_file,#video_name").val("");
        var file_id = $("#video_iput").attr("file_id");
        var obj_file = uploader_video.getFile(file_id);
        uploader_video.removeFile(obj_file);
    }
</script>
<?php endif; ?>