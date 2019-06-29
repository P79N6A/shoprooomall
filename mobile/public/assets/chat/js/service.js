
    /** 客服消息数量控制 */
    $(".warp-left-user ul li").click(function(){
        $(this).addClass('active').siblings().removeClass('active')
    });
    if($(".header-right .paidui-num").html()=='0'){
        $(".header-right .paidui-num").css("display","none")
    }else{
        $(".header-right .paidui-num").css("display","block")
    }
    /** 客服消息数量控制 */
        //
    jQuery(".warpper-right-setting").slide({ trigger: "click" });
    $(".j-add-reply").click(function () {
        $(".add-reply-con").append("<li class=\"active\">" +
            "<i class=\"iconfont icon-bianji warp-right-reply-icon\"></i>" +
            "<div class=\"warp-right-reply\">" +
            "<div class=\"warp-right-reply-text\">" +
            "<span>您好，请问有什么可以帮助的？</span>" +
            "<div class=\"warp-right-reply-btn\">" +
            "<button class=\"confirm\">确认</button>" +
            "<button class=\"remove\">删除</button>" +
            "</div>" +
            "</div>" +
            "<div class=\"warp-right-reply-textarea\">" +
            "<textarea>您好，请问有什么可以帮助的？</textarea>" +
            "<div class=\"warp-right-reply-btn\">" +
            "<button class=\"confirm\">确认</button>" +
            "<button class=\"remove\">删除</button>" +
            "</div>" +
            "</div>" +
            "</div>" +
            "</li>");
    });
    $(".j-wait-show").click(function () {
        $(".j-wait-access").animate({ "top": "10%" }, 400).addClass("show");
        $(".mask").addClass("show");
        $(".j-modal-close").click(function () {
            $(this).parents(".modal").animate({ "top": "5%" }, 100);
            $(this).parents(".modal").removeClass("show");
            $(".mask").removeClass("show");
        });
    });
    /** 切换客户状态 */
    $(".j-state").click(function () {
        $(".state-list").show();
        $(".mask-state").show();
    });
    $(".mask-state").click(function () {
        $(".state-list").hide();
        $(".mask-state").hide();
    });

    document.oncontextmenu=new Function('event.returnValue=false;');
    $(".slide-button").click(function () {
        $(this).toggleClass("active");
    });

var chathistory = {
    issearch : false,
    init : function(){
        this.gethistory();

    },
    gethistory : function(){
        //聊天记录
        var page = 1;
        $(".j-history-list").click(function () {
            if(dscmallEvent.target_service.uid == '' || dscmallEvent.target_service.uid == undefined) return;

            $(".warpper-right-loca").toggleClass("active");
            if(dscmallEvent.history_is_open == true){
                dscmallEvent.history_is_open = false;
                return;
            }
            dscmallEvent.history_is_open = true;
            chathistory.issearch = false;
            // 恢复历史记录
            $('.jw-history-list-search').remove();
            $('.history-list').show();
            page = 1;

            //获取数据
            chathistory.gethistory_data(page);
            chathistory.openpickr();

        });
        //跑到第一页
        $('.j-user-history .first').click(function(){
            if(chathistory.issearch) return;
            page = 1;
            chathistory.gethistory_data(page);
        });
        //跑到上一页
        $('.j-user-history .prev').click(function(){
            if(chathistory.issearch) return;

            page -= 1;

            if(page <= 0){
                page = 1;
                return;
            }

            chathistory.gethistory_data(page);
        });
        //跑到下一页
        $('.j-user-history .next').click(function(){
            if(chathistory.issearch) return;

            page += 1;

            if(page > dscmallEvent.history_total_page) {
                page = dscmallEvent.history_total_page;
                return;
            }

            chathistory.gethistory_data(page);
        });
        //跑到最后一页
        $('.j-user-history .last').click(function(){
            if(chathistory.issearch) return;

            page = dscmallEvent.history_total_page;
            chathistory.gethistory_data(page);
        });

        //搜索聊天记录
        $('.j-search-message-list').click(function(){
            chathistory.issearch = true;

            var keyword = $(this).siblings('input').val();
            if(keyword == '')return;
            chathistory.gethistory_data(1, keyword);
        });
        //返回
        $('.locl-list').on('click', '.j-cancel-histtory', function(){
            $('#jw-history-list-two').remove();
            $('#jw-history-list').show();
            chathistory.openpickr();
            chathistory.issearch = false;

        });
        //查看10条记录
        $('.locl-list').on('click', '#jw-history-list-two a.watchmsg', function(){
            var mid = $(this).attr('mid');

            $.ajax({
                url : chathistory.config.searchurl,
                type : 'post',
                data : {mid : mid},
                success : function(data){
                    $('#jw-history-list').empty();
                    $('#jw-history-list-two').remove();
                    $('#jw-history-list').css('display', 'block');

                    var history_ele = dscmallEvent.element.history_ele;

                    for(var i in data.list){
                        $(history_ele).attr('current', (data.list[i].current == '1') ? '1' : '');

                        $(history_ele).find('a.watchmsg').css('display', 'none');
                        $(history_ele).find('b').text(data.list[i].from_user_id);
                        $(history_ele).find('span').text(data.list[i].add_time);
                        $(history_ele).find('.text').html(data.list[i].message).text();
                        $('#jw-history-list').prepend($(history_ele).clone());
                    }
                    $('.locl-list').scrollTop($('#jw-history-list li[current=1]').offset()['top'] - $('.locl-list').height() - $('#jw-history-list li[current=1]').height());
                    chathistory.openpickr();
                }
            });
            chathistory.issearch = false;

        });
    },
    gethistory_data : function(page, keyword, time){
        page = page || 1;
        //获取历史记录数据
        var history_ele = dscmallEvent.element.history_ele;
        $.ajax({
            url : chathistory.config.url,
            type : 'post',
            data : {
                uid : dscmallKefu.user.user_id,
                tid : dscmallEvent.target_service.uid,
                page : page,
                keyword : keyword,
                time : time
            },
            async : false,
            success : function(data){

                if(chathistory.issearch){
                    //搜索创建新的弹窗
                    $('#jw-history-list-two').remove();  //去除原有搜索

                    var search_history_ele = $('#jw-history-list').clone();
                    $(search_history_ele).attr('id', 'jw-history-list-two');
                    $(search_history_ele).show();
                    $('#jw-history-list').hide();
                    $(search_history_ele).addClass("jw-history-list-search");
                    $(search_history_ele).empty();

                    for(var i in data.list){
                        $(history_ele).find('a.watchmsg').attr('mid', data.list[i].id);
                        $(history_ele).find('a.watchmsg').css('display', 'block');
                        $(history_ele).find('b').text(data.list[i].from_user_id);
                        $(history_ele).find('span').text(data.list[i].add_time);
                        $(history_ele).find('.text').html(data.list[i].message).text();
                        $(search_history_ele).prepend($(history_ele).clone());
                    }
                    $(search_history_ele).prepend("<a class='j-cancel-histtory'>返回</a>");
                    $('#jw-history-list').parent('.locl-list').append(search_history_ele);
                    chathistory.closepickr();
                }else{
                    dscmallEvent.history_total_page = data.total;

                    $('#jw-history-list').empty();

                    for(var i in data.list){
                        $(history_ele).find('a.watchmsg').css('display', 'none');
                        $(history_ele).find('b').text(data.list[i].from_user_id);
                        $(history_ele).find('span').text(data.list[i].add_time);
                        $(history_ele).find('.text').html(data.list[i].message).text();
                        $('#jw-history-list').prepend($(history_ele).clone());
                    }
                }

            }
        });

    },
    closepickr : function(){
        $('#enableNextSeven').attr('data-wrap', 'false');
        $('.flatpickr').flatpickr();
    },
    openpickr : function(){
        $('#enableNextSeven').attr('data-wrap', 'true');
        $('.flatpickr').flatpickr();
    }
};

