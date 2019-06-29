<?php
define('IN_ECS', true);
@set_time_limit(300);
require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH  . ADMIN_PATH . '/includes/lib_goods.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);


$url=$_GET['id'];
$id =  GetGoodsID($url);
$num = intval($_GET['num']) ? intval($_GET['num']) : 5;

$cnum = intval($_GET['cnum']);

$price=$_GET['price'];

$cat_name=$_GET['cat_id'];

$brand_id=$_GET['brand_id'];	   

$kucun=$_GET['kucun'];


if($cnum > 0&&!stristr($url, ".taobao.com")) $iscomment = 1;
if(!$id){
	$smarty->assign('cat_id', $cat_name);
	$smarty->display ( 'gettaobaogoods1.dwt' );
}else{
	$json = file_get_contents_curl("https://hws.alicdn.com/cache/mtop.wdetail.getItemDescx/4.1/?data=%7B%22item_num_id%22%3A%22{$id}%22%7D");
	$data = json_decode($json,1);





	if($data['data']['pages']){
		$content = "";
		foreach($data['data']['pages'] as $p){
			$content .= $p;
		}
		$content = str_replace("<img>","<img src=",$content);
		$content = str_replace("</img>",">",$content);
		$content = str_replace("txt","p",$content);
	}
	if($content){
		//if($istitle or $iscomment){
			$json = file_get_contents_curl("https://hws.alicdn.com/cache/wdetail/5.0/?id={$id}");
			$data = json_decode($json,1);

	
			$goods_name = $data['data']['itemInfoModel']['title'];
			$props = $data['data']['props'];
			if($props){
				$goods_props = '';
				foreach($props as $p){
					$goods_props .= "<p>{$p['name']}：{$p['value']}</p>";
				}
				$content = $goods_props.$content;
			}
			if($data['data']['itemInfoModel']['picsPath']){
				$imgArr = array();
				foreach($data['data']['itemInfoModel']['picsPath'] as $k=>$img){
					if($k>=$num) break;
					//$imgArr[$k] = str_replace("../",ROOT_PATH,getImg($img));
					$imgArr[$k] = getImg($img);
				}
			}
			if($iscomment){
				
			 $userNumId = $data['data']['seller']['userNumId'];

			

               
				
                $comment_list = getEvalution($userNumId,$id,$cnum);


				

			
         
			
			}
		//}
		if(1 == 1){
			




			 $max_id  = $db->getOne("SELECT MAX(goods_id) + 1 FROM ".$ecs->table('goods')) ;
             $goods_sn   = generate_goods_sn($max_id);
			
			 $goods=array();

			 $goods['goods_name']=$goods_name;
            
			
			 $goods['shop_price']=$price;

			 $goods['market_price']=$price*1.2;

			 $goods['goods_sn']=$goods_sn;

			 $goods['brand_id']=$brand_id;

             $goods['review_status']=5; //审核状态：3为审核已通过、5为无需审核
			 
			 $goods['is_new']=1;

			  $goods['goods_desc']=$content;

              if($_GET['xiangqing'] == 1){
			  
			  preg_match_all('/<img[^>]*src\s*=\s*([\'"]?)([^\'" >]*)\1/isu',$content, $src);


			  $xt=$src[2];


			  foreach($xt as $k=>$v){


             $img = file_get_contents_curl($v);


			  $fileName = ROOT_PATH.'/images/taobao/';
	          $arr = explode('.',$v);
	          $ext = end($arr);
	          $uniq = md5($v);//设置一个唯一id
	          $name = $fileName.$uniq.'.'.$ext; //图像保存的名称和路径

			  $root=$GLOBALS['ecs']->url();


	         $content = str_replace($v,$root.'images/taobao/'.$uniq.'.'.$ext,$content);
			 
			 file_put_contents($name,$img);


			  }


              $goods['goods_desc']=$content;



		   }
			  $goods['goods_thumb']=$imgArr[0]['thumb'];

			  $goods['goods_img']=$imgArr[0]['goods'];
			$goods['original_img']=$imgArr[0]['source'];

            
			 $goods['cat_id']=$cat_name;


			

			  $goods['goods_number']=$kucun;

				 
			 
			 $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('goods'), $goods, 'INSERT');
	         $gid = $GLOBALS['db']->insert_id();
			
			
			
			foreach($imgArr as $v){
				$db->query("insert into {$ecs->table('goods_gallery')} (goods_id,img_url,thumb_url,img_original) value ($gid,'{$v[goods]}','{$v[thumb]}','{$v[source]}')");
			}
			if($iscomment == 1 && $comment_list){
				
				foreach($comment_list as $k=>$c){
				
					$db->query("insert into {$ecs->table('comment')} (id_value,content,comment_rank,add_time,user_name,status) 
					value ($gid,'{$c['feedback']}',5,'{$c['sj']}','{$c['nick']}',1)");
					//createOrder($gid,$t,$c['nick']);
				}
			}
			$link [] = array ('href' => 'goods.php?act=edit&goods_id='.$gid,'text' => '继续编辑');
			sys_msg ( '提取成功', 0, $link );
		}else{
			include_once(ROOT_PATH . 'includes/fckeditor/fckeditor.php');
			$smarty->assign ( 'content', $content );
			$smarty->assign ( 'img_list', $imgArr );
			$smarty->assign ( 'id', $id );
			$smarty->assign ( 'gid', $gid );
			$smarty->assign ( 'istitle', $istitle );
			$smarty->assign ( 'iscomment', $iscomment );
			$smarty->assign ( 'goods_name', $goods_name );
			$smarty->assign ( 'comment_list', $comment_list );
			$smarty->display ( 'gettaobaogoodsview.html' );
		}
	}else{
		sys_msg ( '提取失败', 0, $link );
	}
}

function getImg($url) {
	global $image,$_CFG;

	//判断是否$url前面有http
	$qz = substr($url, 0, 2);
	if(strtolower($qz) == '//'){
		$url = 'https:'.$url;
	}
	
	$fileName ='../images/taobao/';
	$arr = explode('.',$url);
	$ext = end($arr);
	$uniq = md5($url);//设置一个唯一id
	$name = $fileName.$uniq.'.'.$ext; //图像保存的名称和路径
	$img = file_get_contents_curl($url);
	file_put_contents($name,$img);
	$thumb_url = $image->make_thumb($name, $_CFG['thumb_width'],  $_CFG['thumb_height']);
	$img_url = $image->make_thumb($name , $_CFG['image_width'],  $_CFG['image_height']);
	$img_original = $image->make_thumb($name);
	$img_original = reformat_image_name('gallery', $gid, $img_original, 'source');
    $img_url = reformat_image_name('gallery', $gid, $img_url, 'goods');
	$thumb_url = reformat_image_name('gallery_thumb', $gid, $thumb_url, 'thumb');
	return array('source'=>$img_original,'goods'=>$img_url,'thumb'=>$thumb_url);
	//return $name;
}

function createOrder($gid,$t,$nick){
	$order = array();
	$order['add_time']     = $t-7*86400;//购买时间直接倒数7天
	$order['order_status'] = OS_CONFIRMED;
	$order['confirm_time'] = $t-7*86400;
	$order['pay_status']   = PS_PAYED;
	$order['pay_time']     = $t-7*86400;
	$order['shipping_status']   =2;
	$order['order_amount'] = 0;
	$order['order_sn'] = get_order_sn(); //获取新订单号
	$order['tb_nick'] = $nick;
    $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');
	$new_order_id = $GLOBALS['db']->insert_id();
	$goods = $GLOBALS['db']->getRow("select * from ".$GLOBALS['ecs']->table('goods')." where goods_id=$gid");
	$sql = "INSERT INTO " . $GLOBALS['ecs']->table('order_goods') . "( " .
		"order_id, goods_id, goods_name, goods_sn, goods_number, market_price,
		goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id) value ".
		"({$new_order_id},$gid,'{$goods['goods_name']}','{$goods['goods_sn']}',1,0,0,'',0,'',0,0,0)";
    $GLOBALS['db']->query($sql);
	return true;
}
function get_order_sn(){
    mt_srand((double) microtime() * 1000000);
    return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function getEvalution($userNumId,$id,$cnum=20)
{
    $allpage = round($cnum/20);
    if ($allpage >= 1)
    {
       	
        for ($i = 1; $i <= $allpage; $i++) 
        {   


		$reviews_url="http://rate.tmall.com/list_detail_rate.htm?itemId={$id}&spuId=0&sellerId={$userNumId}&order=1";
         $pageContents = '';
        $reviews_url  = str_replace('currentPage', '', $reviews_url);
        $reviews_url  = $reviews_url . "&currentPage=$i";
        $pageContents = file_get_contents($reviews_url);
        $pageContents = iconv('GB2312', 'UTF-8', $pageContents);


		
        preg_match_all('/,\"rateContent\"\:\"(.*?)\",\"/i', $pageContents, $match1);
        preg_match_all('/displayUserNick\"\:\"(.*?)\",\"/i', $pageContents, $match2);
        preg_match_all('/rateDate\"\:\"(.*?)\",\"/i', $pageContents, $match3);

       
		$a[$i]=$match1[1];
		$b[$i]=$match2[1];

		$c[$i]=$match3[1];
		
		$nick=$b[1];
		$comment=$a[1];

		$sj=$c[1];

	
		
		if($i>1){
		$comment=array_merge($comment,$a[$i]) ;
		$nick=array_merge($nick,$b[$i]) ;

		$sj=array_merge($sj,$c[$i]) ;
		}

        }



     for($j=0;$j<count($comment);$j++){

         $comment_list[$j]['nick']=$nick[$j];
		 $comment_list[$j]['feedback']=$comment[$j];
		 $comment_list[$j]['sj']=strtotime($sj[$j]);

	 }
        return  $comment_list;
    }
}



function GetGoodsID($Url)
{
    $b = (explode("&", $Url));
    foreach ($b as $v) {
        if (stristr($v, "id=")) {
            $str = $v . ">";
            preg_match("/id=(.*)>/", $str, $c);
            $reslt = $c[1];
            return $reslt;
            break;
        }
    }
}
function Getselerdid($Url)
{
    $tmall_content =file_get_contents_curl($Url);
    ereg("sellerId:\"(.*)\",shopId:", $tmall_content, $c);
    return $c[1];
}


function file_get_contents_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 从证书中检查SSL加密算法是否存在
	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10); 
	$dxycontent = curl_exec($ch); 
	return $dxycontent;
}