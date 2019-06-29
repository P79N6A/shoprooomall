var handler = function (e) { //禁止浏览器默认行为
    e.preventDefault();
};
$(function ($) {
    // 路由埋点
    window.BuryingPoint = window.sessionStorage.getItem('burying_point');
    window.sessionStorage.setItem('burying_point', window.location.href);

    var cityTop;
    $(".con-filter-div .swiper-scroll").css("max-height", $(window).height());
    if ($(".swiper-scroll").hasClass("swiper-scroll")) { //滚动相关js
        swiper_scroll();
    }

    /*点击关闭顶部层*/
    $(".ect-header-banner i.icon-guanbi1").click(function () {
        $(".ect-header-banner").hide();
    });

    /*判断文本框是否有值显示隐藏清空按钮*/
    var input_texts = $(".j-input-text");
    var is_nulls = $(".j-text-all").find(".j-is-null");
    var is_yanjing = $(".j-text-all").find(".j-yanjing");
    input_texts.bind('focus', function () {
        is_nulls.removeClass('active');
        //$(this).parents(".j-text-all").addClass("active").siblings().removeClass("active");//开启后 文本框获得焦点即可改变下边框颜色
        if ($(this).val() != "") {
            $(this).siblings('.j-is-null').addClass('active');
        }
    });
    input_texts.bind('input', function () {
        if ($(this).val() == "") {
            $(this).siblings('.j-is-null').removeClass('active');
        } else {
            $(this).siblings('.j-is-null').addClass('active');
        }
    });

    /*点击清空标签文本框内容删*/
    is_nulls.click(function () {
        $(this).siblings(".j-input-text").val("");
        $(this).siblings(".j-input-text").focus();
    });
    /*密码框点击切换普通文本*/
    is_yanjing.click(function () {
        input_text_atr = $(this).siblings(".input-text").find(".j-input-text");
        if (input_text_atr.attr("type") == "password" && $(this).hasClass("disabled")) {
            input_text_atr.attr("type", "text");
        } else {
            input_text_atr.attr("type", "password");
        }
        input_text_atr.focus();
        $(this).toggleClass("disabled");
    });
    /*三种模式商品列表切换*/
    var sequence = ["icon-icon-square", "icon-pailie", "icon-viewlist"];
    var p_l_product = ["product-list-big", "product-list-medium", "product-list-small"];
    $(".j-a-sequence").click(function () {
        var icon_sequence = $(this).find("i").attr("data");
        var len = sequence.length;
        var key = icon_sequence;
        icon_sequence++;
        if (icon_sequence >= len) {
            icon_sequence = 0;
        }
        /*更换排序列表图标class*/
        $(this).find(".iconfont").removeClass(sequence[key]).addClass(sequence[icon_sequence]);
        $(this).find(".iconfont").attr("data", icon_sequence);
        /*更换商品列表class*/
        $(".j-product-list").removeClass(p_l_product[key]).addClass(p_l_product[icon_sequence]);
        $(".j-product-list").attr("data", icon_sequence);
    });
    /*搜索店铺商品切换*/
    $(".j-search-check").click(function () {
        if ($(this).attr("data") == 1) {
            $(this).attr("data", 2).find("span").html("商品");
            $("input[name=type_select]").val(2);
        } else {
            $(this).attr("data", 1).find("span").html("店铺");
            $("input[name=type_select]").val(1);
        }
    });

    /*手风琴下拉效果*/
    $(".j-sub-menu").hide();
    $(".j-get-city-one, .select-two").on('click', 'a.j-menu-select', function () {
        $(this).next(".j-sub-menu").slideToggle().siblings('.j-sub-menu').slideUp();
        $(this).toggleClass("active").siblings().removeClass("active");
        var scorll_swiper = new Swiper('.swiper-scroll', {
            scrollbar: false,
            direction: 'vertical',
            slidesPerView: 'auto',
            mousewheelControl: true,
            freeMode: true
        });
    });
    /*多选并限制个数  －  ［商品筛选将值传给em标签］  */
    var ischecked = true;
    var filter_attr = [];
    $(".j-get-limit .ect-select").not(".j-checkbox-all").click(function () {
        get_text = $(this).parents(".j-get-limit");
        s_t_em_value = get_text.prev(".select-title").find(".t-jiantou em"); //获取需要改变值的em标签
        checked = $(this).find("label").hasClass("active");
        ischecked = $(this).parents(".j-get-limit").attr("data-istrue");
        var s_t_em_text = "",
                s_get_label_num = 0,
                brand = "";
        var active_jiantou = get_text.prev(".j-menu-select").find(".j-t-jiantou");
        active_jiantou.addClass("active");
        if (get_text.find(".j-checkbox-all label").hasClass("active")) { //当点击非j-checkbox-all的时候删除其选中状态
            get_text.find(".j-checkbox-all label").removeClass("active");
        }
        if (ischecked == "true") {
            $(this).find("label").toggleClass("active");
        }
        if (checked) {
            $(this).find("label").removeClass("active");
            $(this).parents(".j-get-limit").attr("data-istrue", "true")
        }
        if (ischecked == "false") {
            d_messages("筛选最多不能超过5个");
        }
        s_get_label = get_text.find("label.active"); //获取被选中label
        s_get_label_num = s_get_label.length;
        if (s_get_label_num <= 0) {
            active_jiantou.removeClass("active");
            $(".j-checkbox-all label").addClass("active");
            s_t_em_text = $(this).siblings(".j-checkbox-all").find("label").text() + "、";
        }
        if (s_get_label_num >= 5) {
            $(this).parents(".j-get-limit").attr("data-istrue", "false")
        } else {
            //			$(".div-messages").removeClass("active");
            $(this).parents(".j-get-limit").attr("data-istrue", "true")
        }
        s_get_label.each(function () {
            s_t_em_text += $(this).text() + "、";
            //add by wanglu
            if ($(this).parents("ul").hasClass("brand")) {
                brand += $(this).parent("li").attr("data-brand") + ",";
            }
        });
        s_t_em_value.text(s_t_em_text.substring(0, s_t_em_text.length - 1));
        //add by wanglu
        if (brand != "") {
            brand = brand.substring(0, brand.length - 1);
            $("input[name=brand]").val(brand);
        }
        //全部的属性
        var tmp_json = {};
        tmp_json.key = get_text.attr("data-key") || -1;
        var tmp_val = '';
        get_text.find("label").each(function () {
            if ($(this).parents("ul").hasClass("filter_attr")) {
                if ($(this).hasClass('active')) {
                    tmp_val += $(this).parent("li").attr("data-attr") + ',';
                }
            }
        });
        tmp_json.val = tmp_val.substring(0, tmp_val.length - 1);
        filter_attr[tmp_json.key] = tmp_json;

        if (filter_attr) {
            var filter_attr_str = "";
            for (i in filter_attr) {
                if (typeof (filter_attr[i].val) != undefined && filter_attr[i].val != "") {
                    filter_attr_str += filter_attr[i].key + '-' + filter_attr[i].val + '.';
                }
            }
            if (filter_attr_str) {
                filter_attr_str = filter_attr_str.substring(0, filter_attr_str.length - 1);
            }
            $("input[name=filter_attr]").val(filter_attr_str);
        }

    });
    $(".j-checkbox-all").click(function () {
        checkbox_all = $(this).find("label"); //获取值为“全部”的label
        s_t_em_value = $(this).parent().prev(".select-title").find(".t-jiantou em"); //获取需要改变值的em标签
        checkbox_all_text = $(this).find("label").text();
        if (!checkbox_all.hasClass("active")) {
            $(this).find("label").addClass("active").parents(".ect-select").siblings().find("label").removeClass("active");
            s_t_em_value.text(checkbox_all_text); //将calss为j-checkbox-all的label的值赋值给需要改变的em标签
            $(this).parent(".j-get-limit").prev(".select-title").find(".t-jiantou").removeClass("active");
            $(this).parents(".j-get-limit").attr("data-istrue", "true")
        }
        //全部的属性
        var tmp_json = {};
        tmp_json.key = $(this).parent().attr("data-key") || -1;
        var tmp_val = '';
        $(this).parent().find("label").each(function () {
            if ($(this).parent("ul").hasClass("filter_attr")) {
                if ($(this).hasClass('active')) {
                    tmp_val += $(this).parent("li").attr("data-attr") + ',';
                }
            }
        });
        tmp_json.val = tmp_val.substring(0, tmp_val.length - 1);
        filter_attr[tmp_json.key] = tmp_json;

        if (filter_attr) {
            var filter_attr_str = "";
            for (i in filter_attr) {
                if (typeof (filter_attr[i].val) != undefined && filter_attr[i].val != "") {
                    filter_attr_str += filter_attr[i].key + '-' + filter_attr[i].val + '.';
                }
            }
            if (filter_attr_str) {
                filter_attr_str = filter_attr_str.substring(0, filter_attr_str.length - 1);
            }
            $("input[name=filter_attr]").val(filter_attr_str);
        }
        // add by wanglu
        $("input[name=brand]").val(0);
    });
    /*筛选按钮中清空选项*/
    $(".j-filter-reset").click(function () {
        $(".con-filter-div label").removeClass("active");
        $(".j-checkbox-all label").addClass("active");
        $(".j-radio-switching").removeClass("active");
        $(".j-menu-select .j-t-jiantou").removeClass("active");
        $(".j-menu-select .j-t-jiantou em").text("全部");
        $(".j-filter-city span.text-all-span").css("color", "#555");
        $(".j-filter-city span.text-all-span").text("请选择");

        $("#slider-range a:first").css("left", 0);
        $("#slider-range a:last").css("left", "100%");
        $(".ui-widget-header").css({
            "left": 0,
            "width": "100%"
        });
        var price_range = $(".price-range-label").attr("data-min") + '~' + $(".price-range-label").attr("data-max");
        $("#slider-range-amount").text(price_range);

        $(this).parents(".j-get-limit").attr("data-istrue", true);
        ischecked = true;
        //add by wanglu
        $(".j-checkbox-all label").each(function () {
            $(this).parents("ul").prev().find("span").removeClass("active");
            $(this).parents("ul").prev().find("span em").text($(this).text());
        });
        $("input[name=brand]").val(0);
        $("input[name=filter_attr]").val(0);
        $("input[name=isself]").val(0);
        $("input[name=price_min]").val($(".price-range-label").attr("data-min"));
        $("input[name=price_max]").val($(".price-range-label").attr("data-max"));
        $("input[name=isself]").val(0);
    });
    /*多选*/
    $(".j-get-more .ect-select").click(function () {
        if (!$(this).find("label").hasClass("active")) {
            $(this).find("label").addClass("active");
            if ($(this).find("label").hasClass("label-all")) {
                $(".j-select-all").find(".ect-select label").addClass("active");
            }
            //商品列表页筛选
            if ($(this).hasClass("list-select")) {
                if ($(this).hasClass("hasgoods")) {
                    $("input[name=hasgoods]").val(1);
                }
                if ($(this).hasClass("promotion")) {
                    $("input[name=promotion]").val(1);
                }
            }
        } else {
            $(this).find("label").removeClass("active");
            if ($(this).find("label").hasClass("label-all")) {
                $(".j-select-all").find(".ect-select label").removeClass("active");
            }
            //商品列表页筛选
            if ($(this).hasClass("list-select")) {
                if ($(this).hasClass("hasgoods")) {
                    $("input[name=hasgoods]").val(0);
                }
                if ($(this).hasClass("promotion")) {
                    $("input[name=promotion]").val(0);
                }
            }
        }
    });
    /*多选只点击单选按钮 - 全选，全不选*/
    $(".j-get-i-more .j-select-btn").click(function () {
        if ($(this).parents(".ect-select").hasClass("j-flowcoupon-select-disab")) {
            d_messages("同商家只能选择一个", 2);
        } else {
            is_select_all = true;
            if ($(this).parent("label").hasClass("label-this-all")) {
                if (!$(this).parent("label").hasClass("active")) {
                    $(this).parents(".j-get-i-more").find(".ect-select label").addClass("active");
                } else {
                    $(this).parents(".j-get-i-more").find(".ect-select label").removeClass("active");
                }
            }

            if (!$(this).parent("label").hasClass("label-this-all") && !$(this).parent("label").hasClass("label-all")) {
                $(this).parent("label").toggleClass("active");
                is_select_this_all = true;
                select_this_all = $(this).parents(".j-get-i-more").find(".ect-select label").not(".label-this-all");

                select_this_all.each(function () {
                    if (!$(this).hasClass("active")) {
                        is_select_this_all = false;
                        return false;
                    }
                })
                if (is_select_this_all) {
                    $(this).parents(".j-get-i-more").find(".label-this-all").addClass("active");
                } else {
                    $(this).parents(".j-get-i-more").find(".label-this-all").removeClass("active");
                }
            }

            var select_all = $(".j-select-all").find(".ect-select label");
            select_all.each(function () {
                if (!$(this).hasClass("active")) {
                    is_select_all = false;
                    return false;
                }
            });
            if (is_select_all) {
                $(".label-all").addClass("active");
            } else {
                $(".label-all").removeClass("active");
            }
        }
    });

    /*单选*/
    //$(".j-get-one .ect-select").click(function() {
    /*hu把事件绑定到boby*/
    $('body').on('click', '.j-get-one .ect-select', function () {
        get_tjiantou = $(this).parent(".j-get-one").prev(".select-title").find(".t-jiantou");
        $(this).find("label").addClass("active").parent(".ect-select").siblings().find("label").removeClass("active");
        get_tjiantou.find("em").text($(this).find("label").text());
        if ($(this).hasClass("j-checkbox-all")) {
            get_tjiantou.removeClass("active");
        } else {
            get_tjiantou.addClass("active");
        }
        if ($(this).parents("show-goods-attr")) { //赋值给goods-attr
            s_get_label = $(".show-goods-attr .s-g-attr-con").find("label.active"); //获取被选中label
            var get_text = '';
            s_get_label.each(function () {
                get_text += $(this).text() + "、";
            });
            //商品数量显示
            var goods_number = $("#goods_number").val();
            goods_number = parseInt(goods_number) ? parseInt(goods_number) : 1;
            get_text = get_text + goods_number + "个";
            $(".j-goods-attr").find(".t-goods1").text(get_text);
        }
    });
    /*自提点赋值*/
    $(".j-flow-site .ect-select").click(function () {
        site_h4_text = $(this).find("h4").text();

        $(this).parents(".j-goods-site-li").find(".t-goods1 span").text(site_h4_text);
    });

    /*单选consignee*/
    $(".j-get-consignee-one label").click(function () {
        $(this).addClass("active").parents(".flow-checkout-adr").siblings().find("label").removeClass("active");
    });

    /*选择收货人信息*/
    $(".j-flow-get-consignee .flow-checkout-adr").click(function () {
        $(this).addClass("active").siblings(".flow-checkout-adr").removeClass("active");
    });

    /*商品详情所在地区*/
    $(".j-get-city-one .ect-select").click(function () {
        city_span = $(".j-filter-city span.text-all-span");
        city_txt = $(".j-city-left li.active").text() + " " + $(this).parents(".j-sub-menu").prev(".j-menu-select").find("label").text() + " " + $(this).find("label").text();
        $(".j-get-city-one").find(".ect-select label").removeClass("active");
        $(this).find("label").addClass("active");
        city_span.text(city_txt);
        if ($(".j-filter-city span.text-all-span").hasClass("j-city-scolor")) {
            $(".j-filter-city span.text-all-span").css("color", "#ec5151");
        }
        $("body").removeClass("show-city-div");
        $("html,body").animate({
            scrollTop: cityTop
        }, 0);
    });
    /*商品详情仓库选择*/
    $(".j-get-depot-one .ect-select").click(function () {
        city_span = $(".j-filter-depot span.text-all-span");
        city_txt = $(this).find("label").text();
        $(".j-get-depot-one").find(".ect-select label").removeClass("active");
        $(this).find("label").addClass("active");
        city_span.text(city_txt);
        if ($(".j-filter-depot span.text-all-span").hasClass("j-city-scolor")) {
            $(".j-filter-depot span.text-all-span").css("color", "#ec5151");
        }
        $("body").removeClass("show-depot-div");
        $("html,body").animate({
            scrollTop: cityTop
        }, 0);
    });
    //城市切换效果
    $("#sidebar").on("click", "li", function () {
        $("#sidebar li").removeClass("active");
        $(this).addClass("active");
    })

    /*订单提交页面单选赋值*/
    $(".s-g-list-con .j-get-one .ect-select").click(function () {
        dist_span = $(this).find("label>dd").html();
        t_goods1 = $(this).parents(".j-show-get-val").find(".t-goods1"); //需要获取弹出层em标签
        t_goods1.html(dist_span);

    });

    /*商品详情 红心*/
    /*$(".j-heart").click(function() {
     $(this).toggleClass("active");
     });*/

    /*发票赋值*/
    $(".flow-receipt .r-btn-submit").click(function () {
        if ($("body").hasClass("show-receipt-div")) {
            document.removeEventListener("touchmove", handler, false);
            $("body").removeClass("show-receipt-div");
            f_r_title = $(".flow-receipt-title .j-input-text").val();
            f_r_cont = $(".flow-receipt-cont .active").text();
            f_r_type = $(".flow-receipt-cont .active").attr('data-type');
            if (f_r_title == "") {
                f_r_title = "个人";
            }
            receipt_title = $(this).parents(".j-f-c-receipt").find(".receipt-title");
            receipt_name = $(this).parents(".j-f-c-receipt").find(".receipt-name");
            receipt_title.text(f_r_title);
            receipt_name.text(f_r_cont);
            //隐藏域赋值 by wanglu
            if (f_r_title && f_r_cont != '不开发票') {
                $.get(ROOT_URL + "index.php?m=flow&a=change_needinv", {
                    need_inv: 1,
                    inv_type: '',
                    inv_payee: f_r_title,
                    inv_content: f_r_cont
                }, function (result) {
                    $("#ECS_ORDERTOTAL").html(result.content);
                    $("#amount").html(result.amount);
                }, 'json')
                $("#inv_type").val(f_r_type);
                $("#ECS_NEEDINV").val(1);
            } else {
                $("#ECS_NEEDINV").val(0);
            }
            $("#ECS_INVPAYEE").val(f_r_title);
            $("#ECS_INVCONTENT").val(f_r_cont);
            return false;
        }
    });
    /*红包赋值*/
    $(".flow-coupon .c-btn-submit").click(function () {
        if ($("body").hasClass("show-coupon-div")) {
            document.removeEventListener("touchmove", handler, false);
            $("body").removeClass("show-coupon-div");
            coupon_list = $(this).parents(".flow-coupon").find(".ect-select label.active");
            coupon_text = $(this).parents(".j-f-c-s-coupon").find(".t-goods1 .coupon-text");
            coupon_price = $(this).parents(".j-f-c-s-coupon").find(".t-goods1 .coupon-price");

            //更改为单选  by wanglu
            if (coupon_list.length > 1) {
                d_messages("一次只能使用一张红包");
                return false;
            }
            if (coupon_list.length <= 0 && $("#ECS_BONUS").val() == 0) {
                return false;
            }
            var bonus = coupon_list.length <= 0 ? 0 : coupon_list.attr("data-bonus");
            $.get(ROOT_URL + "index.php?m=flow&a=change_bonus", {
                bonus: bonus
            }, function (result) {
                if (result.error) {
                    d_messages(obj.error);
                    try {
                        $("#ECS_BONUS").val(0);
                    } catch (ex) {
                    }
                } else {
                    if (result.bonus_id) {
                        $("#ECS_BONUS").val(result.bonus_id);
                    }
                    if (result.content) {
                        $("#ECS_ORDERTOTAL").html(result.content);
                    }
                    //总价
                    if (result.amount != undefined) {
                        $("#amount").html(result.amount);
                    }
                }
            }, 'json');
            if (coupon_list.length <= 0) {
                coupon_text.text("不使用红包");
                coupon_price.text("");
            } else {
                coupon_text.text("优惠金额");
                coupon_price.text("¥" + parseInt(coupon_list.attr("data-money")) + ".00");
            }
            return false;
        }
    });

    /*优惠券赋值*/
    $(".flow-coupon .cou-btn-submit").click(function () {
        if ($("body").hasClass("show-coupon-div-1")) {
            document.removeEventListener("touchmove", handler, false);
            $("body").removeClass("show-coupon-div-1");

            coupon_list = $(this).parents(".flow-coupon").find(".ect-select label.active");
            coupon_text = $(this).parents(".j-f-c-s-coupon-1").find(".t-goods1 .coupon-text");
            coupon_price = $(this).parents(".j-f-c-s-coupon-1").find(".t-goods1 .coupon-price");
            //更改为单选  by wanglu
            if (coupon_list.length > 1) {
                d_messages("一次只能使用一张优惠券");
                return false;
            }
            if (coupon_list.length <= 0 && $("#ECS_COUPONT").val() == 0) {
                return false;
            }
            var cou_id = coupon_list.length <= 0 ? 0 : coupon_list.attr("data-coupont");
            $.get(ROOT_URL + "index.php?m=flow&a=change_coupont", {
                cou_id: cou_id
            }, function (result) {
                if (result.error) {
                    d_messages(obj.error);
                    try {
                        $("#ECS_COUPONT").val(0);
                    } catch (ex) {
                    }
                } else {
                    if (result.cou_id) {
                        $("#ECS_COUPONT").val(result.cou_id);
                    }
                    if (result.content) {
                        $("#ECS_ORDERTOTAL").html(result.content);
                    }
                    //总价
                    if (result.amount != undefined) {

                        $("#amount").html(result.amount);
                    }
                }
            }, 'json');
            if (coupon_list.length <= 0) {
                coupon_text.text("不使用优惠券");
                coupon_price.text("");
            } else {
                coupon_text.text("优惠金额");
                coupon_price.text("¥" + parseInt(coupon_list.attr("data-money")) + ".00");
            }
            return false;
        }
    });

    /*=======================================================*/

    /*点击弹出搜索层*/
    $(".j-search-input").click(function () {
        $(".j-input-text").val("");
        $("input[name=type_select]").val(2);
        $("body").addClass("show-search-div");
    });
    /*关闭搜索层*/
    $(".j-close-search").click(function () {
        $("body").removeClass("show-search-div");
    });
    /*城市筛选单选city*/
    $(".j-filter-city").click(function () {
        cityTop = $(window).scrollTop();
        $("body").addClass("show-city-div");
    });

    /*点击弹出仓库筛选*/
    $(".j-filter-depot").click(function () {
        cityTop = $(window).scrollTop();
        $("body").addClass("show-depot-div");
    });
    /*点击筛选弹出层*/
    $(".j-s-filter").click(function () {
        cityTop = $(window).scrollTop();
        $("body").addClass("show-filter-div");
    });
    /*点击关闭筛选弹出层*/
    $(".j-close-filter-div").click(function () {
        if ($(".filter-site-div").hasClass("show")) {
            document.removeEventListener("touchmove", handler, false);
            $(this).parent(".filter-site-div").removeClass("show");
            return false;
        }
        if ($("body").hasClass("show-city-div")) {
            $("body").removeClass("show-city-div");
            $("html,body").animate({
                scrollTop: cityTop
            }, 0);

            return false;
        }
        if ($("body").hasClass("show-filter-div")) {
            $("body").removeClass("show-filter-div");
            $("html,body").animate({
                scrollTop: cityTop
            }, 0);

            return false;
        }
        if ($("body").hasClass("show-depot-div")) {
            $("body").removeClass("show-depot-div");
            $("html,body").animate({
                scrollTop: cityTop
            }, 0);
            return false;
        }
    });
    /*点击切换－滑动选择按钮*/
    $(".j-radio-switching").click(function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(this).attr("data", 0);
            $("input[name=isself]").val(0);
        } else {
            $(this).addClass("active");
            $(this).attr("data", 1);
            $("input[name=isself]").val(1);
        }
    });
    /*点击弹出层 － 订单提交页面自提点*/
    $(".j-goods-site-li").click(function () {
        document.addEventListener("touchmove", handler, false);
        $(this).find(".filter-site-div").addClass("show");
    });
    /*点击弹出层 － 订单提交页优惠券*/
    $(".j-f-c-s-coupon").click(function () {
        $("body").addClass("show-coupon-div");
    });
    /*点击弹出层 － 订单提交页优惠券*/
    $(".j-f-c-s-coupon-1").click(function () {
        document.addEventListener("touchmove", handler, false);
        $("body").addClass("show-coupon-div-1");
    });
    /*发票弹出*/
    $(".j-f-c-receipt").click(function () {
        document.addEventListener("touchmove", handler, false);
        $("body").addClass("show-receipt-div");
    });


    /*弹出层方式*/
    $(".j-show-div").click(function () {
        document.addEventListener("touchmove", handler, false);
        $(this).find(".j-filter-show-div").addClass("show");
        $(".mask-filter-div").addClass("show");
    });
    /*评价星级*/
    $(".j-evaluation-star .evaluation-star").click(function () {
        var star_num = $(this).index() + 1;
        $(".j-evaluation-star .evaluation-star").removeClass("active");
        for (var j = 0; j <= star_num; j++) {
            $(".j-evaluation-star .evaluation-star").eq(j).addClass("active");
        }
        $(".j-evaluation-value").val(star_num + 1);
        console.log(star_num);
    });
    /*关闭弹出层*/
    $(".mask-filter-div,.show-div-guanbi").click(function () {

        if ($(".j-filter-show-div").hasClass("show")) {
            $(".j-filter-show-div").removeClass("show");
        }
        if ($(".j-filter-show-list").hasClass("show")) {
            $(".j-filter-show-list").removeClass("show");
        }
        if ($(".shopping-menu").hasClass("nav-active")) {
            $(".shopping-menu").removeClass("nav-active");
        }
        if ($(".shopping-menu").hasClass("position-active")) {
            $(".shopping-menu").removeClass("position-active");
        }
        $(".mask-filter-div").removeClass("show");
        document.removeEventListener("touchmove", handler, false);
        //商品属性
        var get_text = '';
        s_get_label = $(".show-goods-attr .s-g-attr-con").find("label.active"); //获取被选中label
        if (s_get_label.length > 0) {
            s_get_label.each(function () {
                get_text += $(this).text() + "、";
            });
        }
        //商品数量显示
        var goods_number = $("#goods_number").val();
        goods_number = parseInt(goods_number) ? parseInt(goods_number) : 1;
        get_text = get_text + goods_number + "个";
        $(".j-goods-attr").find(".t-goods1").text(get_text);
        event.stopPropagation();
    });

    /*点击弹出层 商品列表区域弹出层*/
    $(".j-show-list").click(function () {
        document.addEventListener("touchmove", handler, false);
        $(".j-filter-show-list").addClass("show");
        $(".mask-filter-div").addClass("show");
    });
    /*购物车点击展开优惠说明*/
    $(".flow-have-cart .j-icon-show").click(function () {
        $(this).parents(".g-promotion-con").toggleClass("active");
    })
    /*购物车悬浮按钮编辑状态*/
    $(".f-cart-filter-btn .span-bianji").click(function () {
        $(".f-cart-filter-btn").addClass("active");
    })
    $(".f-cart-filter-btn .j-btn-default").click(function () {
        $(".f-cart-filter-btn").removeClass("active");
    })

    /*数字增减*/
    $(".div-num-disabled").find("input").attr("readonly", true);
    /*	$(".div-num a").click(function() {
     if (!$(this).parent(".div-num").hasClass("div-num-disabled")) {
     if ($(this).hasClass("num-less")) {
     num = parseInt($(this).siblings("input").val());
     min_num = parseInt($(this).attr("data-min-num"));
     if (num > min_num) {
     num -= 1;
     $(this).siblings("input").val(num);
     } else {
     d_messages("不能小于最小数量");
     }
     return false;
     }
     if ($(this).hasClass("num-plus")) {
     num = parseInt($(this).siblings("input").val());
     max_num = parseInt($(this).attr("data-max-num"));
     if (num < max_num) {
     num += 1;
     $(this).siblings("input").val(num);
     } else {
     d_messages("不能大超过最大数量");
     }
     return false;
     }
     } else {
     d_messages("该商品不能增减");
     }
     });
     $(".div-num input").bind("change", function() {
     num = parseInt($(this).val());
     max_num = parseInt($(this).siblings(".num-plus").attr("data-max-num"));
     min_num = parseInt($(this).siblings(".num-less").attr("data-min-num"));
     if (num > max_num) {
     $(this).val(max_num);
     d_messages("不能大超过最大数量");
     return false;
     }
     if (num < min_num) {
     $(this).val(min_num);
     d_messages("不能小于最小数量");
     return false;
     }
     });*/

    /*订单提交页*/
    $(".j-flow-checkout-pro span.t-jiantou").click(function () {
        $(this).parents(".flow-checkout-pro").toggleClass("active");
    })
    /*文本框获得焦点下拉*/
    $(".text-all-select .j-input-text").focus(function () {
        $(this).parents(".text-all-select").find(".text-all-select-div").show();
    });
    $(".text-all-select-div li").click(function () {

        text_select = $(this).text();
        $(this).parents(".text-all-select").find(".j-input-text").val(text_select);
        $(this).parents(".text-all-select").find(".text-all-select-div").hide();
        return false;
    });
    /*悬浮菜单点击显示*/
    $(".filter-menu-title").click(function () {
        $(".filter-menu").toggleClass("active");
    });
    /*礼包*/
    $(".t-jiantou-gift").click(function () {
        $(".gift-list-box").toggleClass("active");
        $(".t-jiantou-gift i").toggleClass("active");
    });
    /*店铺街*/
    $(".j-s-nav-select").click(function () {
        if (!$(".shopping-menu").hasClass("nav-active")) {
            $(this).addClass("active").siblings().removeClass("active");
            document.addEventListener("touchmove", handler, false);
            $(".shopping-menu").addClass("nav-active");
            $(".shopping-menu").removeClass("position-active distance-active");
            $(".mask-filter-div").addClass("show");
        } else {
            $(".shopping-menu").removeClass("nav-active");
            document.removeEventListener("touchmove", handler, false);
            $(".mask-filter-div").removeClass("show");
        }
    });

    $(".j-s-position-select").click(function () {
        if (!$(".shopping-menu").hasClass("position-active")) {
            $(this).addClass("active").siblings().removeClass("active");
            $(".shopping-menu").addClass("position-active");
            $(".shopping-menu").removeClass("nav-active distance-active");
            $(".mask-filter-div").addClass("show");
        } else {
            $(".shopping-menu").removeClass("position-active");
            $(".mask-filter-div").removeClass("show");
        }
    });
    $(".j-s-distance-select").click(function () {
        if ($(".mask-filter-div").hasClass("show")) {
            $(".mask-filter-div").removeClass("show");
        }
        if (!$(".shopping-menu").hasClass("distance-active")) {
            $(this).addClass("active").siblings().removeClass("active");
            $(".shopping-menu").addClass("distance-active");
            $(".shopping-menu").removeClass("position-active nav-active");
            $("mask-filter-div").addClass("show");
        } else {
            $(".shopping-menu").removeClass("distance-active");
            $("mask-filter-div").removeClass("show");
        }
    });

    //店铺街分类赋值
    $(".shopping-nav-con a").click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        $(".shopping-menu").removeClass("nav-active");
        $(".j-s-nav-select").find("span").text($(this).text());
    });
    if ($(".j-shopping-pro-list").hasClass("j-shopping-pro-list")) {
        $(window).scroll(function () {
            shopping_menu_h = $(".j-shopping-menu").outerHeight();
            shopping_menu_t = $(".j-shopping-pro-list").offset().top - $(document).scrollTop();
            if (shopping_menu_t <= shopping_menu_h) {
                $(".j-shopping-list").addClass("active");
            } else {
                $(".j-shopping-list").removeClass("active");
            }
        });
    }
    $(".j-menu-fixed>ul>li").click(function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
        } else {
            $(this).addClass("active").siblings().removeClass("active");
        }
    });

    /*评价星级*/
    $(".j-evaluation-star .evaluation-star").click(function () {
        var star_num = $(this).index();
        $(".j-evaluation-star .evaluation-star").removeClass("active");
        for (var j = 0; j <= star_num; j++) {
            $(".j-evaluation-star .evaluation-star").eq(j).addClass("active");
        }
        $(".j-evaluation-value").val(star_num + 1);
    });
    /*text-area1 文本框限制文字个数 － 实时监控*/
    if ($(".text-area1").hasClass("text-area1")) {
        $(".text-area1").each(function () {
            $(this).find("span").text($(this).find("textarea").attr("maxlength"));
        });
    }
    $(".text-area1 textarea").bind("input", function () {
        count_span = $(this).siblings("span");
        max_length = $(this).attr("maxlength");
        textarea_length = $(this).val().length;
        if (max_length - textarea_length < 0) {
            count_span.text(0);
        } else {
            count_span.text(max_length - textarea_length);
        }

    });

    /*页面向上滚动js*/

    $(".filter-top").click(function () {
        $("html,body").animate({
            scrollTop: 0
        }, 200);
    });

    $(window).scroll(function () {
        var prevTop = 0,
                currTop = 0;
        currTop = $(window).scrollTop();
        win_height = $(window).height() * 2;
        if (currTop >= win_height) {
            $(".filter-top").stop().fadeIn(200);
        } else {
            $(".filter-top").stop().fadeOut(200);
        }
        //prevTop = currTop; //IE下有BUG，所以用以下方式
        setTimeout(function () {
            prevTop = currTop
        }, 0);
    });

    $('#loading').hide();
    /*关联商品*/
    $(".my-com-nav1").click(function () {
        $(".my-com-nav1").siblings(".ect-select").find("label").removeClass("active")
        $(this).siblings(".ect-select").find("label").addClass("active");
    });

    /*菜单点击添加样式*/
    $(function () {
        $('.oncle-color').click(function () {
            for (var i = 0; i < $('.oncle-color').size(); i++) {
                if (this == $('.oncle-color').get(i)) {
                    $('.oncle-color').eq(i).children('a').addClass('active');
                } else {
                    $('.oncle-color').eq(i).children('a').removeClass('active');
                }
            }
        })
    })
})

function swiper_scroll() {
    var scorll_swiper = new Swiper('.swiper-scroll', {
        scrollbar: false,
        direction: 'vertical',
        slidesPerView: 'auto',
        mousewheelControl: true,
        freeMode: true
    });
}

//领取优惠卷
function receivebonus(id) {
    $.ajax({
        type: "GET",
        url: ROOT_URL + "index.php?m=coupont&a=getcoupon",
        data: {
            cou_id: id
        },
        success: function (data) {
            data = eval("(" + data + ")");
            if (data.code) {
                layer.open({
                    content: '请登录后领取',
                    btn: ['立即登录', '取消'],
                    shadeClose: false,
                    yes: function () {
                        window.location.href = ROOT_URL + 'index.php?m=user&c=login';
                    },
                    no: function () {
                    }
                });
            } else {
                d_messages(data.msg);
            }
        }
    });
}

function d_messages(content, position) { //消息弹出层
    var style_text = "";
    position = arguments[1] ? arguments[1] : 2;
    if (position == 1) { //顶部弹出
        style_text = "border:none; background: rgba(0,0,0,.7); color:#fff; max-width:100%; top:0; position:fixed; left:0; right:0; border-radius:0;";
    }
    if (position == 2) { //页面中间弹出
        style_text = "border:none; background: rgba(0,0,0,.7); color:#fff; max-width:90%; min-width:1rem; margin:0 auto; border-radius:.8rem;";
    }
    layer.open({
        style: style_text,
        type: 0,
        anim: 3,
        content: content,
        shade: false,
        time: 2
    })
}

function d_messages_btn(content, btn1, btn2) { //确定取消弹出层
    layer.open({
        content: content,
        btn: [btn1, btn2],
        shadeClose: false,
        yes: function () {
        },
        no: function () {
        }
    });
}
$(function ($) {

    /*开店流程分类*/
    $(".my-com-nav2").click(function () {
        my_com_nav2 = $(this).siblings(".ect-select").find("label");
        j_open_two_select = $(".j-open-two-select").find(".ect-select label");
        j_open_two_select_all = $(".j-open-two-select-all").find(".ect-select label");
        if (my_com_nav2.hasClass("active")) {
            my_com_nav2.removeClass("active");
        } else {
            my_com_nav2.addClass("active");
        }

        j_open_two_select.each(function () {
            if (!$(this).hasClass("active")) {
                j_open_two_select_all.removeClass("active");
                return false;
            } else {
                j_open_two_select_all.addClass("active");
            }
        });
        return false;
    });
    $(".my-com-nav-one").click(function () {
        j_open_two_select_all = $(".j-open-two-select-all").find(".ect-select label");
        j_open_two_select = $(".j-open-two-select").find(".ect-select label");
        if (!j_open_two_select_all.hasClass("active")) {
            j_open_two_select_all.addClass("active");
            j_open_two_select.addClass("active");
        } else {
            j_open_two_select_all.removeClass("active");
            j_open_two_select.removeClass("active");
        }

    });

});

$(function ($) {
    /*分销商店铺分享按钮*/
    $(".user-shop-fx").click(function () {
        $(".shopping-prompt").addClass("active");
    });
    $(".shopping-prompt").click(function () {
        $(".shopping-prompt").removeClass("active");
    });

    /*详情和购物车头部js*/
    $(".icon-13caidan,.j-search-input").click(function () {
        $(".goods-scoll-bg").addClass("active");
        if (!$(".goods-nav").hasClass("active")) {
            $(".goods-nav").addClass("active");
            $(".goods-scoll-bg").addClass("active");
            return false;
        } else {
            $(".goods-nav").removeClass("active");
            $(".goods-scoll-bg").removeClass("active");
            return false;
        }
    });
    $(".goods-scoll-bg").click(function () {
        $(".goods-scoll-bg").removeClass("active");
        $(".goods-nav").removeClass("active");
    });
    //详情导航滚动隐藏
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0) {
            $(".goods-scoll-bg").removeClass("active");
            $(".goods-nav").removeClass("active");
        }
        ;
    });
    //详情相册弹框
    $(".j-goods-box").click(function () {
        document.addEventListener("touchmove", handler, false);
        $(".goods-banner").addClass("active");
        $(".goods-bg-box").addClass("active");

    });
    $(".goods-bg-box").click(function () {
        document.removeEventListener("touchmove", handler, false);
        $(".goods-banner").removeClass("active");
        $(".goods-bg-box").removeClass("active");

    });
    /*地址选择 -s*/
    /*search-address*/
    $(".j-search-address").click(function () {
        $(".ec-fresh-bg").addClass("active");
        $(".t-search-footer").addClass("active");
    });
    $(".ec-fresh-bg").click(function () {
        $(".ec-fresh-bg").removeClass("active");
        $(".t-search-footer").removeClass("active");
    });

    $('.n-goods-shop-list-nav li').on('click', function (e) {
        var category = $(this).attr('category');
        var index = $(".n-goods-shop-list-nav li").index(this);
        $(this).siblings().removeClass("active");
        $('.shopping-abs .swiper-slide a').removeClass("active");

        $(".div" + category).addClass("active");

        swiper_nav.slideTo(index, 1000, false); //切换到第一个slide，速度为1秒

        infinite.onload('where=' + category + '&type=1');
        var swiper = new Swiper('.j-g-s-p-con', {
            scrollbarHide: true,
            slidesPerView: 'auto',
            centeredSlides: false,
            grabCursor: true

        });
    })
    if ($("#province_id").length > 0) {
        getAddress();
    }
});
//五级联动地址
function getAddress() {
    function checkPageSelectAddress(checkPage, addressPage, selectAddressBtn, addressText, fnc, urlFileName, addressFrom) {
        var addressFrom = addressFrom || null;
        var urlFileName = urlFileName || ['parent_id=1', 'parent_id=', 'parent_id=', 'parent_id=', 'parent_id='];
        var chooseAddressPage = document.getElementById(addressPage);
        var checkPage = document.getElementById(checkPage);
        var selectAddressBtn = document.getElementById(selectAddressBtn);
        var urlPrefix = ROOT_URL + "index.php?m=region&a=address&";
        var address = [{
                name: '',
                id: '',
                liIndex: 0
            }, {
                name: '',
                id: '',
                liIndex: 0
            }, {
                name: '',
                id: '',
                liIndex: 0
            }, {
                name: '',
                id: '',
                liIndex: 0
            }, {
                name: '',
                id: '',
                liIndex: 0
            }]
        var Num = 0;
        var headAddressUl = chooseAddressPage.querySelector('#headAddressUl');
        var headAddressLi = headAddressUl.children;
        var addressContentDiv = chooseAddressPage.querySelector('#addressContentDiv');
        var addressUl = addressContentDiv.children;
        var addressHTML = '';
        var addressText = document.getElementById(addressText);
        var w;
        var addressContainer = document.getElementById('addressContainer');
        var headFix = document.querySelector('.head-fix');
        var goBack = document.querySelector('#goBack');
        var flag = true;
        var myNum = 0;
        var connectAddressLi = function (data, num) {
            var innerH = '';
            w = addressUl[0].offsetWidth;
            if (num) {
                headAddressLi[parseInt(num) - 1].innerHTML = address[parseInt(num) - 1].name;
            }
            ;

            if (!data.addressList.length) {
                for (var i = parseInt(num); i < 5; i++) {
                    address[i].name = '';
                    address[i].id = 0;
                }
                ;
                addressHTML = address[0].name.trim() + address[1].name.trim() + address[2].name.trim() + address[3].name.trim() + address[4].name.trim();
                for (var i = 0; i < 5; i++) {
                    globalObj.address[i].name = address[i].name.trim();
                    globalObj.address[i].id = address[i].id;
                }
                ;
                headAddressLi[num - 1].innerHTML = '\u8bf7\u9009\u62e9';
                window.location.hash = '#checkpage';
            } else {
                headAddressLi[num].innerHTML = '\u8bf7\u9009\u62e9';
                for (var i = 0; i < 5; i++) {
                    headAddressLi[i].classList.remove('head-address-li');
                }
                ;
                headAddressLi[num].classList.add('head-address-li');

                for (var i = 0; i < data.addressList.length; i++) {
                    innerH += '<li dataId=' + data.addressList[i].id + ' liIndex=' + (i + 1) + '>' + data.addressList[i].name + '</li>'
                }
                ;
                addressUl[num].innerHTML = innerH;

                addressContentDiv.style.transform = 'translate(' + -w * num + 'px,0px) translateZ(0px)';
                addressContentDiv.style.WebkitTransform = 'translate(' + -w * num + 'px,0px) translateZ(0px)';
            }
        }

        var connectAddressLi2 = function (data, num) {

            var innerH = '';

            w = document.documentElement.clientWidth;
            var liIndex = 0;

            if (num) {
                headAddressLi[parseInt(num) - 1].innerHTML = globalObj.address[parseInt(num) - 1].name;
            }
            ;

            headAddressLi[num].innerHTML = '\u8bf7\u9009\u62e9';
            for (var i = 0; i < 5; i++) {
                headAddressLi[i].classList.remove('head-address-li');
            }
            ;
            headAddressLi[num].classList.add('head-address-li');

            for (var i = 0; i < data.addressList.length; i++) {
                innerH += '<li dataId=' + data.addressList[i].id + ' liIndex=' + (i + 1) + '>' + data.addressList[i].name + '</li>';
                if (data.addressList[i].id == globalObj.address[num].id) {
                    liIndex = i + 1;
                }
                ;
            }
            ;
            addressUl[num].innerHTML = innerH;
            addressUl[num].children[parseInt(liIndex - 1)].classList.add('checked-color');
            addressUl[num].children[parseInt(liIndex - 1)].innerHTML = addressUl[num].children[parseInt(liIndex - 1)].innerHTML + '<span class="check-pic"></span>';
            address[num].liIndex = liIndex;
            myNum++;

        }

        var loadList = function (url, cb) {

            $.ajax({
                type: 'get',
                url: url,
                dataType: 'json',
                async: false,
                beforeSend: function () {

                },
                success: function (data) {
                    if (flag) {
                        connectAddressLi(data, Num);
                    } else {
                        connectAddressLi2(data, myNum);
                        if (cb)
                            cb();
                    }
                },
                error: function (xhr, type) {

                }
            });
        };

        var goChooseAddressPage = function () {

            if (!Num && !globalObj.address[0].name) {
                checkPage.style.display = 'none';

                loadList(urlPrefix + urlFileName[0]);

                chooseAddressPage.style.display = 'block';
            } else if (!Num && globalObj.address[0].name) {
                flag = false;
                myNum = 0;
                loadList(urlPrefix + urlFileName[0], function () {
                    checkPage.style.display = 'none';

                    if (globalObj.address[3].name) {

                        loadList(urlPrefix + urlFileName[1] + globalObj.address[0].id, function () {
                            loadList(urlPrefix + urlFileName[2] + globalObj.address[1].id, function () {
                                loadList(urlPrefix + urlFileName[3] + globalObj.address[2].id, function () {
                                    loadList(urlPrefix + urlFileName[4] + globalObj.address[3].id, myCallBack);
                                });
                            });

                        });

                    } else if (globalObj.address[3].name) {
                        loadList(urlPrefix + urlFileName[1] + globalObj.address[0].id, function () {
                            loadList(urlPrefix + urlFileName[2] + globalObj.address[1].id, function () {
                                loadList(urlPrefix + urlFileName[3] + globalObj.address[2].id, myCallBack);
                            });
                        });

                    } else if (globalObj.address[2].name) {
                        loadList(urlPrefix + urlFileName[1] + globalObj.address[0].id, function () {
                            loadList(urlPrefix + urlFileName[2] + globalObj.address[1].id, myCallBack);
                        });

                    } else if (globalObj.address[1].name) {
                        loadList(urlPrefix + urlFileName[1] + globalObj.address[0].id, myCallBack);
                    }
                });

                function myCallBack() {
                    for (var i = 0; i < 5; i++) {
                        address[i].id = globalObj.address[i].id;
                        address[i].name = globalObj.address[i].name;
                    }
                    ;
                    addressContentDiv.style.transform = 'translate(' + -w * (myNum - 1) + 'px,0px) translateZ(0px)';
                    addressContentDiv.style.WebkitTransform = 'translate(' + -w * (myNum - 1) + 'px,0px) translateZ(0px)';
                    flag = true;

                    chooseAddressPage.style.display = 'block';
                }

            } else {
                checkPage.style.display = 'none';
                chooseAddressPage.style.display = 'block';
            }

            setTimeout(function () {
                addressContentDiv.style.height = (document.documentElement.clientHeight - 89) + 'px';
            }, 600);

            for (var i = 0; i < 5; i++) {
                (function (index) {
                    addressUl[index].onclick = function (e) {
                        var oEvent = e || event;
                        var oSrc = oEvent.srcElement || oEvent.target;
                        if (oSrc.getAttribute('liindex')) {
                            if (oSrc.className.indexOf('check-pic') != -1) {
                                oSrc = oSrc.parentNode;
                            }
                            ;
                            if (parseInt(address[index].liIndex) && this.children[parseInt(address[index].liIndex) - 1] && this.children[parseInt(address[index].liIndex) - 1].className.indexOf('checked-color') != -1) { //宸茬粡鏈夐�涓殑浜�鍏堝垹鍓嶄竴娆￠�涓殑鏍峰紡
                                this.children[parseInt(address[index].liIndex) - 1].classList.remove('checked-color');
                                this.children[parseInt(address[index].liIndex) - 1].innerHTML = this.children[parseInt(address[index].liIndex) - 1].innerHTML.replace('<span class="check-pic"></span>', '')

                            }
                            ;
                            oSrc.classList.add('checked-color');

                            address[index].name = oSrc.innerHTML;
                            oSrc.innerHTML = oSrc.innerHTML + '<span class="check-pic"></span>';
                            address[index].id = oSrc.getAttribute('dataId');
                            address[index].liIndex = oSrc.getAttribute('liIndex');
                            Num = index + 1;
                            if (index == 4) {
                                addressHTML = address[0].name.trim() + address[1].name.trim() + address[2].name.trim() + address[3].name.trim() + address[4].name.trim();
                                for (var i = 0; i < 5; i++) {
                                    globalObj.address[i].name = address[i].name.trim();
                                    globalObj.address[i].id = address[i].id.trim();
                                }
                                ;
                                window.location.hash = '#checkpage';

                            } else {

                                loadList(urlPrefix + urlFileName[Num] + address[index].id);
                                // addressUl[Num].scrollTop = 0;

                            }
                        }
                        ;

                    }
                })(i);
            }

            headAddressUl.onclick = function (e) {

                var oEvent = e || event;
                var oSrc = oEvent.srcElement || oEvent.target;
                var headNum;

                if (parseInt(oSrc.getAttribute('mytitle')) >= 0) {
                    if (oSrc.innerHTML != '' && oSrc.innerHTML != '\u8bf7\u9009\u62e9') {
                        headNum = oSrc.getAttribute('mytitle');
                        oSrc.innerHTML = '\u8bf7\u9009\u62e9';
                        // addressUl[headNum].scrollTop = 0;
                        for (var i = parseInt(headNum) + 1; i < 5; i++) {
                            headAddressLi[i].innerHTML = '';
                        }
                        ;
                        for (var i = 0; i < 5; i++) {
                            headAddressLi[i].classList.remove('head-address-li');
                        }
                        ;
                        headAddressLi[headNum].classList.add('head-address-li');
                        addressContentDiv.style.transform = 'translate(' + -w * headNum + 'px,0px) translateZ(0px)';
                        addressContentDiv.style.WebkitTransform = 'translate(' + -w * headNum + 'px,0px) translateZ(0px)';

                    }
                    ;
                }
                ;
            }
        }

        var goButtonPage = function () {
            // window.location.hash = '#checkpage';
            checkPage.style.display = 'block';
            chooseAddressPage.style.display = 'none';
            selectAddressBtn.onclick = function () {
                window.location.hash = '#chooseAddressPage';
            }
            if (addressHTML) {
                addressText.innerHTML = addressHTML;
            }

            if (globalObj.address[0].name) {
                fnc();
            }
            ;

        }

        var changeAddressHash = function () {
            globalObj.reNum++;

            if (globalObj.reNum > 1) {
                try {
                    var reNum = localStorage.getItem("reNum");
                    localStorage.setItem('reNum', ++reNum);
                } catch (e) {

                }
            }

            var hashContent = window.location.hash;
            if (hashContent == '#chooseAddressPage') {
                goChooseAddressPage();
            } else if (hashContent == '') {
                goButtonPage();

                if (addressFrom == "orderForWanshan") {
                    setTimeout(function () {
                        addressFrom = "";
                        window.location.hash = '#chooseAddressPage';

                    }, 100);

                }
            } else {
                goButtonPage();
            }
        }

        var windowLoadHashchange = function () {
            changeAddressHash();
        }
        window.addEventListener('load', windowLoadHashchange, false);
        window.addEventListener('hashchange', windowLoadHashchange, false);
    }
    $(function () {
        $("#addressHome").click(function () {
            var checklen = $('#addressContentDiv .checked-color').length;
            var titIndex = $('#headAddressUl li').eq(2).html();
            var url = "addressname.html";
            var mycount = '';
            if (checklen > 2 && titIndex) {
                for (var i = 0; i < $('.address-ul').length; i++) {
                    var id = $('.address-ul').eq(i).find('.checked-color').attr('dataid');
                    if (i == 0 && id) {
                        mycount += '#' + id
                    } else if (id) {
                        mycount += '-' + id;
                    }
                    ;
                }
                ;
                url += mycount;
            }
            ;
            window.location.href = url;
        });
        if ($("#addressHome").length > 0) {
            if ($("#addressHome")[0].style.display.indexOf('block') != -1) {
                $("#addressContentDiv").css("paddingBottom", "35px");
            } else {
                $("#addressContentDiv").css("paddingBottom", "0px");
            }
        }

    });

    //页面内
    //选择收货地址 点击后颜色样式变化
    var oSelectAddressBtn = document.querySelector('#selectAddressBtn');
    oSelectAddressBtn && oSelectAddressBtn.addEventListener('click', function () {
        this.querySelector('#addressLabelId').classList.remove('empty-tip-placeholder');
        //四级地址埋点
        var timer4Point = setTimeout(buryPoint4FourLeverl, 300);
        function buryPoint4FourLeverl() {
            var oAddressContentDiv = document.querySelector('#addressContentDiv');
            var arrRegionUl = oAddressContentDiv.querySelectorAll('ul.address-ul');
            var event_id;
            buryAreaChooseByUl(arrRegionUl[0], 0);

            function buryAreaChooseByUl(targetUl, type) {
                if (targetUl) {
                    var arrLi = targetUl.querySelectorAll('li');
                    if (arrLi && arrLi.length) {
                        for (var rul = 0, rulLen = arrLi.length; rul < rulLen; rul++) {
                            (function (idx, type) {
                                //arrLi[idx].addEventListener('click',addPoint,false);
                                arrLi[idx].onclick = addPoint;

                                function addPoint() {
                                    switch (type) {
                                        case 0:
                                            event_id = 'MMyJD_FourthAddProvince';
                                            break;
                                        case 1:
                                            event_id = 'MMyJD_FourthAddCity';
                                            break;
                                        case 2:
                                            event_id = 'MMyJD_FourthAddCounty';
                                            break;
                                        case 3:
                                            event_id = 'MMyJD_FourthAddVillage';
                                            break;
                                    }
                                    //pingClick("true", event_id, "","","","");

                                    setTimeout(function () {
                                        if (++type <= 5) {
                                            buryAreaChooseByUl(arrRegionUl[type], type);
                                        }
                                    }, 300)
                                    //arrLi[idx].removeEventListener('click',addPoint,false);
                                    arrLi[idx].onclick = null;
                                }
                                ;
                            })(rul, type);
                        }
                    }
                }
            }
            clearTimeout(timer4Point);
        }
        //五级地址返回按钮特殊化绑定
        var timer4Back = setTimeout(toBindBackFn4FourLevel, 500);

        function toBindBackFn4FourLevel() {
            var objBack = document.querySelector('.choose-address-page .jd-index-header-icon-back');
            objBack.onclick = gobackfn4FourLevel;

            function gobackfn4FourLevel() {
                if (localStorage && localStorage.getItem('reNum')) {
                    var reNum = localStorage.getItem('reNum');
                    localStorage.removeItem('reNum');
                    if (window.location.hash == "#chooseAddressPage") {
                        window.history.go(-parseInt(reNum) + 1);
                    } else {
                        window.history.go(-parseInt(reNum));
                    }
                }
                objBack.onclick = null;
            }
            clearTimeout(timer4Back);
        }
    });
    var globalObj = {
        address: [{
                name: '',
                id: ''
            }, {
                name: '',
                id: '',
            }, {
                name: '',
                id: ''
            }, {
                name: '',
                id: ''
            }, {
                name: '',
                id: ''
            }],
        reNum: 0
    };
    if ($("#selectAddressBtn").val("region-data")) {
        globalObj.address[0].name = $("#provinceNameIgnoreId").val() ? $("#provinceNameIgnoreId").val() : "";
        globalObj.address[1].name = $("#cityNameIgnoreId").val() ? $("#cityNameIgnoreId").val() : "";
        globalObj.address[2].name = $("#areaNameIgnoreId").val() ? $("#areaNameIgnoreId").val() : "";
        globalObj.address[3].name = $("#townNameIngoreId").val() ? $("#townNameIngoreId").val() : "";
        globalObj.address[4].name = $("#villageNameIngoreId").val() ? $("#villageNameIngoreId").val() : "";
        globalObj.address[0].id = document.getElementById("province_id").value;
        globalObj.address[1].id = document.getElementById("city_id").value;
        globalObj.address[2].id = document.getElementById("district_id").value;
        globalObj.address[3].id = document.getElementById("town_id").value;
        globalObj.address[4].id = document.getElementById("village_id").value;
    }

    checkPageSelectAddress("checkPage", "chooseAddressPage", "selectAddressBtn", "addressLabelId", callBackFn, null, '$addressFrom');
    function callBackFn(option) {
        var provinceObj = globalObj.address && globalObj.address.length > 0 ? globalObj.address[0] : null;
        var cityObj = globalObj.address && globalObj.address.length > 1 ? globalObj.address[1] : null;
        var areaObj = globalObj.address && globalObj.address.length > 2 ? globalObj.address[2] : null;
        var townObj = globalObj.address && globalObj.address.length > 3 ? globalObj.address[3] : null;
        var villageObj = globalObj.address && globalObj.address.length > 4 ? globalObj.address[4] : null;

        /**
         * 给省、市、县和镇的id赋值。
         */
        var idProvince = provinceObj && provinceObj.id ? provinceObj.id : 0;
        var idCity = cityObj && cityObj.id ? cityObj.id : 0;
        var idArea = areaObj && areaObj.id ? areaObj.id : 0;
        var idTown = townObj && townObj.id ? townObj.id : 0;
        var idVillage = villageObj && villageObj.id ? villageObj.id : 0;

        document.getElementById("province_id").value = idProvince;
        document.getElementById("city_id").value = idCity;
        document.getElementById("district_id").value = idArea;
        document.getElementById("town_id").value = idTown;
        document.getElementById("village_id").value = idVillage;
        if (document.getElementById("goods_id")) {
            if (document.getElementById("user_id")) {
                var user_id = document.getElementById("user_id").value;
            }
            var goods_id = document.getElementById("goods_id").value;
            var url = ROOT_URL + 'index.php?m=goods&a=instock';
            $.get(url, {id: goods_id, province: idProvince, city: idCity, district: idArea, street: idTown, user_id: user_id}, function (res) {
                if (res.isRegion == 0) {
                    if (confirm(res.message))
                    {
                        var district_id = document.getElementById('district_id');
                        district_id.value = res.district;
                        location.href = ROOT_URL + 'index.php?m=user&a=address_list';
                    }
                    else {
                        location.reload();
                    }
                } else {
                    location.reload();
                }
                return false;

            }, 'json');
        }
    }
}



/*微筹js*/
function adv_index() {
    if ($(window).scrollTop() > 150) {
        $(".goods-fixed").addClass("active");
        $(".goods-left-jiat").addClass("active");
        $(".goods-header-nav-box").addClass("active");
    } else {
        $(".goods-fixed").removeClass("active");
        $(".goods-left-jiat").removeClass("active");
        $(".goods-header-nav-box").removeClass("active");
    }
    //详情导航滚动隐藏
    $(window).scroll(function () {
        if ($(window).scrollTop() > 0) {
            $(".goods-scoll-bg").removeClass("active");
            $(".goods-nav").removeClass("active");
        }
        ;
    });
}
;

$(function ($) {
    adv_index();
    $(window).scroll(function () {
        adv_index();
    });
    $(".icon-gengduo").click(function () {
        $(".goods-scoll-bg").addClass("active");
        if (!$(".goods-nav").hasClass("active")) {
            $(".goods-nav").addClass("active");
            $(".goods-scoll-bg").addClass("active");
            return false;
        } else {
            $(".goods-nav").removeClass("active");
            $(".goods-scoll-bg").removeClass("active");
            return false;
        }
    });
    $(".goods-scoll-bg").click(function () {
        $(".goods-scoll-bg").removeClass("active");
        $(".goods-nav").removeClass("active");
    });
//详情点击js
    $(".j-show-div-1").click(function () {
        document.addEventListener("touchmove", handler, false);
        $(".j-filter-show-div").addClass("show");
        $(".mask-filter-div").addClass("show");
    });

    $(".ka-order-btn").click(function () {
        $(this).siblings(".ka-mo").addClass("active");
        $(".mask-filter-div-box").addClass("active")
    });
    $(".ma-icon").click(function () {
        $(this).parents(".ka-mo").removeClass("active");
        $(".mask-filter-div-box").removeClass("active");
    });
    $(".mask-filter-div-box").click(function () {
        $(".ka-mo").removeClass("active");
        $(".mask-filter-div-box").removeClass("active");
    });
/*拼团规则*/
    $(".j-goods-guize-box").click(function(){
            $(".goods-show-box,.goods-mn-jiantou .icon-moreunfold").toggleClass("active");                    
    });  
    /*拼团分享*/
$(".j-fengxiang-box").click(function(){
                                document.addEventListener("touchmove", handler, false);
                            $(".fengxiang-img-box").addClass("active");
                            $(".fengxing-bg").addClass("active");
                        });
                            $(".fengxing-bg").click(function(){
                                document.removeEventListener("touchmove", handler, false);
                            $(".fengxiang-img-box").removeClass("active");
                            $(".fengxing-bg").removeClass("active");
                        }); 
                          
 //首页js 
     //详情导航滚动隐藏
    $(window).scroll(function () {
        if ($(window).scrollTop() > 100) {
            $(".index-header").addClass("active");
            $(".banner-search").addClass("active");
            $(".n-input-text").addClass("active");
            $(".search-logo").addClass("active");
            $(".isxiaoxi1color").addClass("active");
            $(".ect-header-banner").addClass("active");
        }else{
            $(".banner-search").removeClass("active");
            $(".index-header").removeClass("active");
            $(".n-input-text").removeClass("active");
            $(".search-logo").removeClass("active");
             $(".isxiaoxi1color").removeClass("active");
             $(".ect-header-banner").removeClass("active");
        }
    });       
});

