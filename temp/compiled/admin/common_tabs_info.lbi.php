<form method="post" action="<?php echo $this->_var['common_tabs']['url']; ?>" name="commonTabsForm">
<div class="tabs_info">
    <ul>
        <?php if (! $this->_var['rs_id']): ?>
        <li <?php if (! $this->_var['common_tabs']['info']): ?>class="curr"<?php endif; ?>>
            <a href="javascript:;" data-val="0" ectype="tabs_info"><?php echo $this->_var['lang']['self_run']; ?></a>
        </li>
        <?php endif; ?>
        <li <?php if ($this->_var['common_tabs']['info'] == 1): ?>class="curr"<?php endif; ?>>
            <a href="javascript:;" data-val="1" ectype="tabs_info"><?php echo $this->_var['lang']['19_merchants_store']; ?></a>
        </li>
        <?php if ($this->_var['supplier_enabled'] && ( $this->_var['filename'] == 'gallery_album.php' || $this->_var['filename'] == 'goods_type.php' )): ?>
        <li <?php if ($this->_var['common_tabs']['info'] == 2): ?>class="curr"<?php endif; ?>>
            <a href="javascript:;" data-val="2" ectype="tabs_info"><?php echo $this->_var['lang']['suppliers']; ?></a>
        </li>
        <?php endif; ?>
    </ul>
</div>

<?php if ($this->_var['filter']['user_id']): ?><input type="hidden" name="user_id" value="<?php echo empty($this->_var['filter']['user_id']) ? '0' : $this->_var['filter']['user_id']; ?>" /><!-- 会员ID --><?php endif; ?> 
<?php if ($this->_var['filter']['composite_status']): ?><input type="hidden" name="composite_status" value="<?php echo empty($this->_var['filter']['composite_status']) ? '-1' : $this->_var['filter']['composite_status']; ?>" /><!-- 订单状态ID --><?php endif; ?> 
<input type="hidden" name="seller_list" value="0" />
<input type="hidden" value="<?php echo $this->_var['filter']['store_id']; ?>" name='store_id' />
</form>

<script type="text/javascript">
    $(document).on('click','*[ectype="tabs_info"]',function(){
        var val = $(this).data('val');
        $(":input[name='seller_list']").val(val);
        $("form[name='commonTabsForm']").submit();	        
    });
</script>