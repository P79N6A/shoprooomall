
<div class="shopCart-con dsc-cm">
	<a href="<?php echo $this->_var['site_domain']; ?>flow.php">
		<i class="iconfont icon-carts"></i>
		<span><?php echo $this->_var['lang']['my_cart']; ?></span>
		<em class="count cart_num"><?php echo $this->_var['str']; ?></em>
	</a>
</div>
<div class="dorpdown-layer" ectype="dorpdownLayer">
    <?php if ($this->_var['goods']): ?>
    <div class="settleup-content">
        <div class="mc">
            <ul>
                <?php $_from = $this->_var['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_0_73622800_1553749812');$this->_foreach['goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods_0_73622800_1553749812']):
        $this->_foreach['goods']['iteration']++;
?>
                <li>		
                    <?php if ($this->_var['goods_0_73622800_1553749812']['rec_id'] > 0): ?>
					<?php if ($this->_var['goods_0_73622800_1553749812']['extension_code'] == 'package_buy'): ?>
                    <div class="p-img"><a href="javascript:void(0);"  target="_blank"><img src="themes/ecmoban_dsc2017/images/17184624079016pa.jpg" width="50" height="50" /></a></div>
					<?php else: ?>
					<div class="p-img"><a href="<?php echo $this->_var['goods_0_73622800_1553749812']['url']; ?>"  target="_blank"><img src="<?php echo $this->_var['goods_0_73622800_1553749812']['goods_thumb']; ?>" width="50" height="50" /></a></div>
					<?php endif; ?>
                    <?php endif; ?>
                    <?php if ($this->_var['goods_0_73622800_1553749812']['rec_id'] > 0 && $this->_var['goods_0_73622800_1553749812']['extension_code'] == 'package_buy'): ?>
                    <div class="p-name"><a href="javascript:void(0);"><?php echo $this->_var['goods_0_73622800_1553749812']['short_name']; ?><span style="color:#FF0000">（<?php echo $this->_var['lang']['remark_package']; ?>）</span></a></div>
                    <?php elseif ($this->_var['goods_0_73622800_1553749812']['rec_id'] > 0 && $this->_var['goods_0_73622800_1553749812']['is_gift'] != 0): ?>
                    <div class="p-name"><a href="javascript:void(0);"><?php echo $this->_var['goods_0_73622800_1553749812']['short_name']; ?><span style="color:#FF0000">（<?php echo $this->_var['lang']['largess']; ?>）</span></a></div>
                    <?php else: ?>
                    <div class="p-name"><a href="<?php echo $this->_var['goods_0_73622800_1553749812']['url']; ?>" target="_blank" title="<?php echo htmlspecialchars($this->_var['goods_0_73622800_1553749812']['short_name']); ?>"><?php echo $this->_var['goods_0_73622800_1553749812']['short_name']; ?></a></div>
                    <?php endif; ?>
                    <div class="p-number">
                        <span class="num" id="goods_number_<?php echo $this->_var['goods_0_73622800_1553749812']['rec_id']; ?>"><?php echo empty($this->_var['goods_0_73622800_1553749812']['goods_number']) ? '1' : $this->_var['goods_0_73622800_1553749812']['goods_number']; ?></span>
                        <div class="count">
                            <a href="javascript:void(0);"  id="min_number" onclick="changenum(<?php echo $this->_var['goods_0_73622800_1553749812']['rec_id']; ?>,1, <?php echo $this->_var['goods_0_73622800_1553749812']['warehouse_id']; ?>, <?php echo $this->_var['goods_0_73622800_1553749812']['area_id']; ?>)" class="count-add"><i class="iconfont icon-up"></i></a>
                            <a href="javascript:void(0);" onclick="changenum(<?php echo $this->_var['goods_0_73622800_1553749812']['rec_id']; ?>, -1, <?php echo $this->_var['goods_0_73622800_1553749812']['warehouse_id']; ?>, <?php echo $this->_var['goods_0_73622800_1553749812']['area_id']; ?>)" class="count-remove"><i class="iconfont icon-down"></i></a>
                        </div>
                    </div>
                    <div class="p-oper">
                        <div class="price"><?php echo $this->_var['goods_0_73622800_1553749812']['goods_price']; ?></div>
                        <a href="javascript:void(0);" onClick="deleteCartGoods(<?php echo $this->_var['goods_0_73622800_1553749812']['rec_id']; ?>,0)" class="remove"><?php echo $this->_var['lang']['drop']; ?></a>
                    </div>
                </li>
                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </ul>
        </div>
        <div class="mb">
            <input name="cart_value" id="cart_value" value="<?php echo $this->_var['cart_value']; ?>" type="hidden" />
            <div class="p-total">共<?php echo $this->_var['str']; ?>件商品&nbsp;&nbsp;共计：<?php echo $this->_var['cart_info']['amount']; ?></div>
            <a href="flow.php" class="btn-cart"><?php echo $this->_var['lang']['go_to_cart']; ?></a>
        </div>
    </div>
    <?php else: ?>
    <div class="prompt"><div class="nogoods"><b></b><span><?php echo $this->_var['lang']['goods_null_cart']; ?></span></div></div>
    <?php endif; ?>
</div>

<script type="text/javascript">
function changenum(rec_id, diff, warehouse_id, area_id)
{
	var cValue = $('#cart_value').val();
    var goods_number =Number($('#goods_number_' + rec_id).text()) + Number(diff);
 
	if(goods_number < 1)
	{
		return false;	
	}
	else
	{
		change_goods_number(rec_id,goods_number, warehouse_id, area_id, cValue);
	}
}
function change_goods_number(rec_id, goods_number, warehouse_id, area_id, cValue)
{
	if(cValue != '' || cValue == 'undefined'){
	   var cValue = $('#cart_value').val(); 
	}   
	Ajax.call('<?php echo $this->_var['site_domain']; ?>flow.php?step=ajax_update_cart', 'rec_id=' + rec_id +'&goods_number=' + goods_number +'&cValue=' + cValue +'&warehouse_id=' + warehouse_id +'&area_id=' + area_id, change_goods_number_response, 'POST','JSON');                
}
function change_goods_number_response(result)
{    
	var rec_id = result.rec_id;           
    if (result.error == 0)
    {
       $('#goods_number_' +rec_id).val(result.goods_number);//更新数量
       $('#goods_subtotal_' +rec_id).html(result.goods_subtotal);//更新小计
       if (result.goods_number <= 0)
        {
			//数量为零则隐藏所在行
            $('#tr_goods_' +rec_id).style.display = 'none';
            $('#tr_goods_' +rec_id).innerHTML = '';
        }
        $('#total_desc').html(result.flow_info);//更新合计
        if($('ECS_CARTINFO'))
        {//更新购物车数量
            $('#ECS_CARTINFO').html(result.cart_info);
        }

		if(result.group.length > 0){
			for(var i=0; i<result.group.length; i++){
				$("#" + result.group[i].rec_group).html(result.group[i].rec_group_number);//配件商品数量
				$("#" + result.group[i].rec_group_talId).html(result.group[i].rec_group_subtotal);//配件商品金额
			}
		}

		$("#goods_price_" + rec_id).html(result.goods_price);
		$(".cart_num").html(result.subtotal_number);
	}
	else if (result.message != '')
	{
		$('#goods_number_' +rec_id).val(result.cart_Num);//更新数量
		alert(result.message);
	}                
}

function deleteCartGoods(rec_id,index)
{
	Ajax.call('<?php echo $this->_var['site_domain']; ?>delete_cart_goods.php', 'id='+rec_id+'&index='+index, deleteCartGoodsResponse, 'POST', 'JSON');
}

/**
 * 接收返回的信息
 */
function deleteCartGoodsResponse(res)
{
  if (res.error)
  {
    alert(res.err_msg);
  }
  else if(res.index==1)
  {
		Ajax.call('<?php echo $this->_var['site_domain']; ?>get_ajax_content.php?act=get_content', 'data_type=cart_list', return_cart_list, 'POST', 'JSON');
  }
  else
  {
	  $("#ECS_CARTINFO").html(res.content);
	  $(".cart_num").html(res.cart_num);
  }
}

function return_cart_list(result)
{
	$(".cart_num").html(result.cart_num);
	$(".pop_panel").html(result.content);
	tbplHeigth();
}
</script>