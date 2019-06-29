<?php
 //SHOPROOO
require 'php/config.php';

if (!$enable) {
	exit('没有编辑器使用权限');
}

$lang = $_CFG['lang'] == 'en_us' ? 'en' : 'zh-cn';
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ecEditor - Powered by Ecmoban.com</title>
<script type="text/javascript" src="ueditor.config.js"></script>
<script type="text/javascript" src="ueditor.all.js">/*ecmoban模板堂 --zhuo*/</script> 
<script type="text/javascript" src="lang/';
echo $lang;
echo '/';
echo $lang;
echo '.js"></script>
<script type="text/javascript" src="third-party/jquery-1.10.2.min.js"></script>
<style type="text/css">
body {margin:0px; padding:0px;}
</style>
</head>

<body>
<script type="text/plain" name="content" id="container"></script>
<script type="text/javascript">
';
$item = htmlspecialchars($_GET['item']);
echo 'var cBox = $(\'#';
echo $item;
echo '\', parent.document);
var editor = UE.getEditor(\'container\');
editor.addListener(\'ready\', function() {
  $(\'#detail-table\', parent.document).hide();//先显示再隐藏编辑器，兼容部分浏览在display:none时无法创建的问题
  var content = cBox.val();
  editor.setContent(content);
});
//editor.addListener("contentChange", function(){setSync()});//触发同步
$(function(){
  window.setInterval("setSync()",1000);//自动同步
})
function setSync(){
  var content = editor.getContent();
  cBox.val(content);
}
</script>
</body>
</html>';

?>
