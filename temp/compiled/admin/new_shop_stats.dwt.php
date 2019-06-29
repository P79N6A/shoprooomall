<!doctype html>
<html>
<head><?php echo $this->fetch('library/admin_html_head.lbi'); ?></head>

<body class="iframe_body"> 
    <div class="warpper">
        <div class="title"><?php echo $this->_var['lang']['report_form']; ?> - <?php echo $this->_var['ur_here']; ?></div>
        <div class="content">
            <div class="tabs_info">
                <ul>
                    <li class="curr"><a href="shop_stats.php?act=new"><?php echo $this->_var['lang']['newadd_shop']; ?></a></li>
                    <li><a href="shop_stats.php?act=shop_sale_stats"><?php echo $this->_var['lang']['shop_sale_stats']; ?></a></li>
                    <li><a href="shop_stats.php?act=shop_area"><?php echo $this->_var['lang']['shop_area_distribution']; ?></a></li>
                </ul>
            </div>
            <div class="explanation" id="explanation">
                <div class="ex_tit"><i class="sc_icon"></i><h4><?php echo $this->_var['lang']['operating_hints']; ?></h4><span id="explanationZoom" title="<?php echo $this->_var['lang']['fold_tips']; ?>"></span></div>
                <ul>
                    <li><?php echo $this->_var['lang']['operation_prompt_content']['new']['0']; ?></li>
                </ul>
            </div>
            <div class="flexilist mt30">
                <div class="query_result">
                    <div class="common-content">
                        <div class="switch_info">
                            <div class="stat_order_search stat_order_search2">
                                <form action="" method="post" id="" name="selectForm">
                                    <div class="search_item">
                                        <strong class="label_txt"><?php echo $this->_var['lang']['time']; ?>：</strong>
                                        <div class="text_time" id="text_time_start">
                                            <input type="text" class="text" name="start_date" value="" id="start_date" value="" autocomplete="off" readonly>
                                        </div>
                                        <span class="bolang">&nbsp;&nbsp;~&nbsp;&nbsp;</span>
                                        <div class="text_time" id="text_time_end">
                                            <input type="text" class="text" name="end_date" value="" id="end_date" value="" autocomplete="off" readonly>
                                        </div>
                                    </div>
                                    <div class="search_item search_item">
                                        <strong class="label_txt"><?php echo $this->_var['lang']['main_self']; ?>：</strong>
                                        <div class="imitate_select select_w145">
                                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                            <ul>
                                                <li><a href="javascript:;" data-value="0" class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                                <?php $_from = $this->_var['main_category']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS $this->_var['cat']):
?>
                                                <li><a href="javascript:;" data-value="<?php echo $this->_var['cat']['cat_id']; ?>" class="ftx-01"><?php echo $this->_var['cat']['cat_name']; ?></a></li>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </ul>
                                            <input name="shop_categoryMain" type="hidden" value="0" id="shop_categoryMain">
                                        </div>
                                        <strong class="label_txt"><?php echo $this->_var['lang']['steps_shop_type']; ?>：</strong>
                                        <div class="imitate_select select_w145">
                                            <div class="cite"><?php echo $this->_var['lang']['please_select']; ?></div>
                                            <ul>
                                                <li><a href="javascript:;" data-value="" class="ftx-01"><?php echo $this->_var['lang']['please_select']; ?></a></li>
                                                <?php $_from = $this->_var['store_type']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
                                                <li><a href="javascript:;" data-value="<?php echo $this->_var['store']; ?>" class="ftx-01"><?php echo $this->_var['store']; ?></a></li>
                                                <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                                            </ul>
                                            <input name="shopNameSuffix" type="hidden" value="" id="shopNameSuffix">
                                        </div>
                                    </div>
                                    <div class="search_item">
                                        <strong class="label_txt">&nbsp</strong>
                                        <a href="javascript:void(0);" class="btn btn30 blue_btn ml0" ectype="searchButton"><i class="icon icon-search"></i><?php echo $this->_var['lang']['btn_search']; ?></a>
                                    </div>
                                    <input type="hidden" name="act" value="get_chart_data">
                                </form>
                            </div>
                            <div class="query_result mt30">
                                <div class="section-module">
                                    <div class="title_head"><h3><?php echo $this->_var['lang']['new_add_shop_stats']; ?></h3></div>
                                    <div class="module-content" id="chart_view" style="height:378px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php echo $this->fetch('library/pagefooter.lbi'); ?>
    <script type="text/javascript" src="../js/echarts-all.js"></script>
	<script type="text/javascript">
        //日期选择插件调用start sunle
        var opts1 = {
            'targetId':'start_date',//时间写入对象的id
            'triggerId':['start_date'],//触发事件的对象id
            'alignId':'start_date',//日历对齐对象
            'format':'-',//时间格式 默认'YYYY-MM-DD HH:MM:SS'
            'min':'' //最小时间
        },opts2 = {
            'targetId':'end_date',
            'triggerId':['end_date'],
            'alignId':'end_date',
            'format':'-',
            'min':''
        }
        xvDate(opts1);
        xvDate(opts2);
        //日期选择插件调用end sunle

        //统计
        $(function(){
            search_chart_view('shop_stats.php', "form[name='selectForm']", 'chart_view');
            $(document).on('click', "[ectype='searchButton']", function(){
                search_chart_view('shop_stats.php', "form[name='selectForm']", 'chart_view');
            })
        })
    </script>
</body>
</html>