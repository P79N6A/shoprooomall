$(function(){
	$(".tab-left dd").click(function(){
		$(this).parent().siblings().children("dd").removeClass("current");
		$(this).addClass("current").siblings().removeClass("current");
	})
})