<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/css/base.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/css/iconfont.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/css/purebox.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/css/quickLinks.css" />

<?php if ($this->_var['site_domain']): ?>
<script type="text/jscript" src="<?php echo $this->_var['site_domain']; ?>js/jquery-1.9.1.min.js"></script>
<script type="text/jscript" src="<?php echo $this->_var['site_domain']; ?>js/jquery.json.js"></script>
<script type="text/jscript" src="<?php echo $this->_var['site_domain']; ?>js/transport_jquery.js"></script>
<?php else: ?>
<?php echo $this->smarty_insert_scripts(array('files'=>'jquery-1.9.1.min.js,jquery.json.js,transport_jquery.js')); ?>
<?php endif; ?>

<script type="text/javascript">
var json_languages = <?php echo $this->_var['json_languages']; ?>;

//加载效果
var load_cart_info = '<img src="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/load/loadGoods.gif" class="load" />';
var load_icon = '<img src="<?php echo $this->_var['site_domain']; ?>themes/<?php echo $GLOBALS['_CFG']['template']; ?>/images/load/load.gif" width="200" height="200" />';
</script>