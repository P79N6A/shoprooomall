<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<div class="footer">
    <div class="dsc-service">
        <div class="w w1200 relative">
            <ul class="service-list">
                <li>
                    <div class="service-tit service-zheng"><i class="iconfont icon-zheng"></i></div>
                    <div class="service-txt">{$lang.Authentic_guarantee}</div>
                </li>
                <li>
                    <div class="service-tit service-qi"><i class="iconfont icon-qi"></i></div>
                    <div class="service-txt">{$lang.7_days_return}</div>
                </li>
                <li>
                    <div class="service-tit service-hao"><i class="iconfont icon-grin"></i></div>
                    <div class="service-txt">{$lang.Rave_reviews}</div>
                </li>
                <li>
                    <div class="service-tit service-shan"><i class="iconfont icon-light"></i></div>
                    <div class="service-txt">{$lang.Lightning_delivery}</div>
                </li>
                <li class="last">
                    <div class="service-tit service-quan"><i class="iconfont icon-trophy"></i></div>
                    <div class="service-txt">{$lang.Authority_of_honor}</div>
                </li>
            </ul>
        </div>
    </div>
    <div class="dsc-help">
        <div class="w w1200">
            <div class="help-list">
                <!-- {foreach from=$helps item=help_cat name=no} -->
                {if $smarty.foreach.no.iteration < 6}
                <div class="help-item">
                    <h3>{$help_cat.cat_name}</h3>
                    <ul>
                    <!-- {foreach from=$help_cat.article item=item name=help_cat} -->
                    {if $smarty.foreach.help_cat.iteration < 4}
                    <li><a href="{$item.url}"  title="{$item.title|escape:html}" target="_blank">{$item.short_title}</a></li>
                    {/if}
                    <!-- {/foreach} -->
                    </ul>
                </dl>
                </div>
                {/if}
                <!-- {/foreach} -->  
            </div>
            <div class="help-cover">
                <div class="help-ctn">
                    <div class="help-ctn-mt">
                        <span>服务热线</span>
                        <strong>{$service_phone}</strong>
                    </div>
                    <div class="help-ctn-mb">
                        {if $kf_im_switch}

                        <a id="IM" IM_type="dsc" onclick="openWin(this)" href="javascript:;" class="btn-ctn"><i class="iconfont icon-csu"></i>咨询客服</a>
                        {else}
                            {if $basic_info.kf_type eq 1}
                            <a href="http://www.taobao.com/webww/ww.php?ver=3&touid={$basic_info.kf_ww}&siteid=cntaobao&status=1&charset=utf-8" class="btn-ctn" target="_blank"><i class="iconfont icon-csu"></i>{$lang.con_cus_service}</a>
                            {else}
                            <a href="http://wpa.qq.com/msgrd?v=3&uin={$basic_info.kf_qq}&site=qq&menu=yes" class="btn-ctn" target="_blank"><i class="iconfont icon-csu"></i>{$lang.con_cus_service}</a>
                            {/if}
                        {/if}
                    </div>
                </div>
                <div class="help-scan">
                    <ul class="tabs">
                    	<li class="curr">ECJia</li>
                        <li>ECTouch</li>
                    </ul>
                    <div class="code">
                    	<div class="code_tp"><img src="{$site_domain}{$ecjia_qrcode}" /></div>
                        <div class="code_tp" style="display:none;"><img src="{$site_domain}{$ectouch_qrcode}"></div>
                    </div>
                    <!--<div class="ico" ectype="switch" title="切换"><i class="iconfont icon-switch"></i></div>-->
                </div>
            </div>
        </div>
    </div>
    <div class="dsc-copyright">
        <div class="w w1200">
            <!-- {if $navigator_list.bottom} --> 
            <p class="copyright_links">
                <!-- {foreach name=nav_bottom_list from=$navigator_list.bottom item=nav} -->
                <a href="{$nav.url}" <!-- {if $nav.opennew eq 1} --> target="_blank" <!-- {/if} -->>{$nav.name}</a> 
                <!-- {if !$smarty.foreach.nav_bottom_list.last} --> 
                | 
                <!-- {/if} --> 
                <!-- {/foreach} --> 
            </p>
            <!-- {/if} -->
            <!--{if $img_links  or $txt_links }-->
            <p class="copyright_info">
                <!--开始图片类型的友情链接{foreach from=$img_links item=link}-->
                <a href="{$link.url}" target="_blank" title="{$link.name}"><img src="{$link.logo}" alt="{$link.name}" border="0" /></a>
                <!--结束图片类型的友情链接{/foreach}-->
                <!-- {if $txt_links} -->
                <!--开始文字类型的友情链接{foreach from=$txt_links item=link name=nolink}-->
                <a href="{$link.url}" target="_blank" title="{$link.name}">{$link.name}</a>
                <!-- {if !$smarty.foreach.nolink.last} --> 
                | 
                <!-- {/if} --> 
                <!--结束文字类型的友情链接{/foreach}-->
                <!-- {/if} -->
            </p>
            <!--{/if}-->
            <p style="display:none">{insert name='query_info'}</p>
            <p>&nbsp;</p>
            {if $stats_code}
                <p style="text-align:right; display:none;">{$stats_code}</p>
            {/if}
            <!--{if $partner_img_links  or $partner_txt_links }-->
            <p class="copyright_auth">
                <!--开始图片类型的合作伙伴链接{foreach from=$partner_img_links item=link}-->
                <a href="{$link.url}" target="_blank" title="{$link.name}"><img src="{$link.logo}" alt="{$link.name}" border="0" /></a>
                <!--结束图片类型的友情链接{/foreach}-->
                <!-- {if $txt_links} -->
                <!--开始文字类型的合作伙伴链接{foreach from=$partner_txt_links item=link name=nolink}-->
                <a href="{$link.url}" target="_blank" title="{$link.name}" class="mr0">{$link.name}</a>
                <!-- {if !$smarty.foreach.nolink.last} --> 
                | 
                <!-- {/if} --> 
                <!--结束文字类型的合作伙伴链接{/foreach}-->
                <!-- {/if} -->
            </p>    
            <!--{else}-->
            <p class="copyright_auth">&nbsp;</p>
            <!--{/if}-->
        </div>
    </div>
    
    <!--优惠券领取弹窗-->
    <div class="hide" id="pd_coupons">
        <span class="success-icon m-icon"></span>
        <div class="item-fore">
            <h3>{$lang.Coupon_redemption_succeed}</h3>
            <div class="txt ftx-03">{$lang.coupons_prompt}</div>
        </div>
    </div>
    
    <!--隐藏域-->
    <div class="hidden">
        <input type="hidden" name="seller_kf_IM" value="{$shop_information.is_IM}" rev="{$site_domain}" ru_id="{$smarty.get.merchant_id}" />
        <input type="hidden" name="seller_kf_qq" value="{$basic_info.kf_qq}" />
        <input type="hidden" name="seller_kf_tel" value="{$basic_info.kf_tel}" />
        <input type="hidden" name="user_id" value="{$smarty.session.user_id|default:0}" />
    </div>
</div>

<!-- {if $site_domain} -->
<script type="text/jscript" src="{$site_domain}js/suggest.js"></script>
<script type="text/jscript" src="{$site_domain}js/scroll_city.js"></script>
<script type="text/jscript" src="{$site_domain}js/utils.js"></script>
<!-- {else} -->
{insert_scripts files='suggest.js,scroll_city.js,utils.js'}
<!-- {/if} -->

<!-- {if $site_domain} -->
{if $area_htmlType neq 'goods' && $area_htmlType neq 'exchange'}
	<script type="text/javascript" src="{$site_domain}js/warehouse_area.js"></script>
{else}
	<script type="text/javascript" src="{$site_domain}js/warehouse.js"></script>
{/if}
<!-- {else} -->
{if $area_htmlType neq 'goods' && $area_htmlType neq 'exchange' && $area_htmlType neq 'presale' && $area_htmlType neq 'group_buy' }
    {insert_scripts files='warehouse_area.js'}
{else}
    {insert_scripts files='warehouse.js'}
{/if}
<!-- {/if} -->

{if $suspension_two}
<script>var seller_qrcode='<img src="{$site_domain}{$seller_qrcode_img}" alt="{$seller_qrcode_text}" width="164" height="164">'; //by wu</script>
{$suspension_two}
{/if}