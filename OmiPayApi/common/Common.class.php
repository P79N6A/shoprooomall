
<?php

    class Common{

        public static function printf_info($data)
        {
            foreach ($data as $key => $value) {
                echo "<div color='#f00;' class='example_form'><span>$key:</span><span class='second'>$value</span> </div> <br/>";
            }
            echo "<div color='#f00;' class='example_form'><span>Tips:</span><span class='second'>Refresh the page to regain</span> </div> <br/>";
        }

        public static function create_qrcode($name, $qrcode, $jump)
        {
            echo '<div style="margin-left: 10%;color:#556B2F;font-size:30px;font-weight: bolder;">' . $name . '<font color="#9ACD32" style="font-size:26px;"><b></font></div>
            <br/>
            <img alt="扫码支付" src="qrcode.php?data='.urlencode($qrcode).'" style="width:150px;height:150px;display:block; margin: 50px auto;"/>
            <a target="_blank" class="jump" style="display:block; width:210px; height:50px; text-align: center; line-height: 50px;
                                    margin: 50px auto; border-radius: 15px;background-color:#f97933; border:0px #FE6714 solid; 
                                    cursor: pointer;  color:white;  font-size:16px;" 
                href="'.$jump.'">跳转
            </a>';
        }
    }

?>