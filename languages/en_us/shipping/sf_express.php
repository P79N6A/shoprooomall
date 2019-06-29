<?php
//SHOPROOO商城资源
$_LANG['sf_express'] = 'Shun Feng shipping';
$_LANG['sf_express_desc'] = 'Cost of Jiang, Zhe, Hu region less than 1000g is less than 15 yuan/kg, more than 1000g is 2 yuan/kg, other region 20 yuan/kg';
$_LANG['item_fee'] = 'Single commodity costs:';
$_LANG['base_fee'] = 'Cost less than 1000g';
$_LANG['step_fee'] = 'Every less than or equal to 1000g:';
$_LANG['shipping_print'] = "<table style=\"width:18.8cm; height:3cm;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td>&nbsp;</td>\r\n  </tr>\r\n</table>\r\n<table style=\"width:18.8cm;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td style=\"width:9.4cm\" valign=\"top\">\r\n   <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n   <tr>\r\n      <td valign=\"middle\" style=\"width:1.5cm; height:0.8cm;\">&nbsp;</td>\r\n      <td width=\"85%\">\r\n     <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n     <tr>\r\n    <td valign=\"middle\" style=\"width:5cm; height:0.8cm;\">{\$shop_name}</td>\r\n      <td valign=\"middle\">&nbsp;</td>\r\n    <td valign=\"middle\" style=\"width:1.8cm; height:0.8cm;\">{\$order.order_sn}</td>\r\n    </tr>\r\n   </table>\r\n   </td>\r\n </tr>\r\n <tr valign=\"middle\">\r\n <td>&nbsp;</td>\r\n <td class=\"h\">\r\n <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td style=\"width:1.3cm; height:0.8cm;\">{\$province}</td>\r\n    <td>&nbsp;</td>\r\n    <td style=\"width:1.3cm; height:0.8cm;\">{\$city}</td>\r\n    <td>&nbsp;</td>\r\n    <td style=\"width:1.3cm; height:0.8cm;\">&nbsp;</td>\r\n    <td>&nbsp;</td>\r\n    <td style=\"width:1.3cm; height:0.8cm;\">&nbsp;</td>\r\n    <td>&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">{\$shop_address}</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">&nbsp;</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td>&nbsp;</td>\r\n    <td style=\"width:1.5cm; height:0.8cm;\">&nbsp;</td>\r\n    <td>&nbsp;</td>\r\n    <td style=\"width:3.5cm; height:0.8cm;\">{\$service_phone}</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n  </td>\r\n    <td style=\"width:9.4cm;\" valign=\"top\">\r\n  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n<td valign=\"middle\" style=\"width:1.5cm; height:0.8cm;\">&nbsp;</td>\r\n<td width=\"85%\">\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n  <td valign=\"middle\" style=\"width:5cm; height:0.8cm;\">{\$order.consignee}</td>\r\n  <td valign=\"middle\">&nbsp;</td>\r\n  <td valign=\"middle\" style=\"width:1.8cm; height:0.8cm;\">&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">{\$order.region}</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">{\$order.address}</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">&nbsp;</td>\r\n</tr>\r\n<tr valign=\"middle\">\r\n<td>&nbsp;</td>\r\n<td class=\"h\">\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td style=\"width:1.7cm;\">&nbsp;</td>\r\n    <td style=\"width:1.5cm; height:0.8cm;\">&nbsp;</td>\r\n    <td style=\"width:1.7cm;\">&nbsp;</td>\r\n    <td style=\"width:3.5cm; height:0.8cm;\">{\$order.tel}</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n</td>\r\n  </tr>\r\n</table>\r\n<table style=\"width:18.8cm; height:6.5cm;\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n    <td valign=\"top\" style=\"width:7.4cm;\">\r\n  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n   <td colspan=\"2\" style=\"height:0.5cm;\"></td>\r\n  </tr>\r\n<tr>\r\n<td rowspan=\"2\" style=\"width:4.9cm;\">&nbsp;</td>\r\n<td style=\"height:0.8cm;\">\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"height:0.8cm;\">\r\n  <tr>\r\n    <td style=\"width:1cm;\">&nbsp;</td>\r\n    <td>&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n<tr>\r\n<td style=\"height:1.3cm;\">\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\r\n  <tr>\r\n  <td style=\"height:0.7cm;\">&nbsp;</td>\r\n  </tr>\r\n  <tr>\r\n  <td>&nbsp;</td>\r\n  </tr>\r\n</table>\r\n</td>\r\n</tr>\r\n</table>\r\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" style=\"height:1.5cm\">\r\n<tr>\r\n<td>&nbsp;</td>\r\n</tr>\r\n</table>\r\n</td>\r\n<td valign=\"top\" style=\"width:11.4cm;\">&nbsp;</td>\r\n  </tr>\r\n</table>";

?>
