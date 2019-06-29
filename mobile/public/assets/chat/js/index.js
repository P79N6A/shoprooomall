var dscmallKefu = {
    //
    socket : null,
    port : '',
    listen_route : '',
    inited : false,
    status : 0,
    protocol : 'ws',
    user : {
        user_id : null,
        user_name : null,
        avatar : '',
        user_type : '',
        goods_id : 0,
        store_id : -1
    },
    message : {
        type : null,
        to_id : null,
        msg : null
    },
    come_form : '',
    init : function(){
        if(this.inited) {
            return;
        }
        if(this.listen_route == '' || this.port == ''){
            alert('socket 端口未设置');
            return;
        }
        this.inited = true;
        dscmallKefu.come_form = this.IsPC() ? 'PC' : 'mobile';
        dscmallKefu.IsSsl();
        this.connect();
        dscmallEvent.init();
    },
    connect : function(){
        var that = this;
        if (typeof WebSocket == 'undefined') {
            alert('浏览器不支持，请更换浏览器使用');
            return ;
        }
        this.socket = new WebSocket(this.protocol + "://" + this.listen_route + ":" + this.port);

        //连接
        this.socket.onopen = function() {
            that.socket.send(that.json_decode({uid:that.user.user_id, name:that.user.user_name, type:'login', user_type:that.user.user_type, avatar:that.user.avatar, store_id: that.user.store_id,  origin: (that.IsPC()) ? 'pc' : 'phone'}));
        };

        // 接收消息
        this.socket.onmessage = function(e) {
            var info = that.json_encode(e.data);

            switch (info.message_type){
                case 'come': // 有客服登录
                    if(info.uid == that.user.user_id)return ;
                    dscmallEvent.init();
                    return;
                case 'leave': //有客服登出
                    if(info.uid == that.user.user_id || info.uid != '')return ;
                    return;
                case 'init':  //取得客服列表
                    if(info.msg) dscmallKefu.status = 1;
                    return;
                case 'come_msg':  //获取到消息
                    dscmallEvent.come_message(info);
                    return;
                case 'come_wait':  //待接入消息
                    dscmallEvent.come_message(info);
                    return;
                case 'robbed':  //获取被抢客户
                    dscmallEvent.get_robbed(info);
                    return;
                case 'user_robbed':  //通知用户已被接入
                    dscmallEvent.get_service(info);
                    return;
                case 'uoffline':  //用户已下线
                    dscmallEvent.uoffline(info.message);
                    return;
                case 'close_link':  //客服已断开
                    dscmallEvent.close_link(info);
                    return;
                case 'others_login':  //异地登录
                    dscmallEvent.others_login(info);
                    return;
                case 'change_service':  //切换客服
                    dscmallEvent.switch_service(info);
                    return;
            }

        };
        //关闭
        this.socket.onclose = function(){
            dscmallKefu.status = 0;
        };

        this.socket.onerror = function(e) {
        };

    },
    //发送通知
    sendinfomation : function(){
        this.socket.send(JSON.stringify(this.message));
    },
    //发送聊天
    sendmsg : function(){
        if(this.message.msg == ''|| this.message.msg == '<p><br></p>' || this.user == {} || this.user.user_id == '' || this.user.user_id == undefined){
            return;
        }
        this.socket.send(JSON.stringify(this.message));
        dscmallEvent.add_message({message:this.message.msg, name:dscmallKefu.user.user_name, time: dscmallKefu.SystemDate()}, 1);
    },
    //
    json_encode : function(data){
        return JSON.parse(data);
    },
    json_decode : function(data){
        return JSON.stringify(data);
    },
    setCookie : function(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    },
    getCookie : function(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) != -1) return c.substring(name.length, c.length);
        }
        return "";
    },
    IsPC : function() {
        var userAgentInfo = navigator.userAgent;
        var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod");
        var flag = true;
        for (var v = 0; v < Agents.length; v++) {
            if (userAgentInfo.indexOf(Agents[v]) > 0) {
                flag = false;
                break;
            }
        }
        return flag;
    },
    IsSsl : function(){
        dscmallKefu.protocol = ('https:' == document.location.protocol ? 'wss' : 'ws');
    },
    SystemDate : function(){
        var d = new Date();
        return dscmallKefu.p(d.getHours().toString()) +':'+ dscmallKefu.p(d.getMinutes().toString()) +':'+ dscmallKefu.p(d.getSeconds().toString());
    },
    p : function(num){
        return (Array(2).join(0) + num).slice(-2);
    }
};

//启动
$(function(){
    dscmallKefu.init();
});