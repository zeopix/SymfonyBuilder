$(function(){

	$(".node a.dir").click(function(){
		if($(this).parent().find("> ul").hasClass('hide')){
			$(this).parent().find("> ul").removeClass('hide');
			$(this).find("b").removeClass('icon-folder-close');
			$(this).find("b").addClass('icon-folder-open');
		}else{
			$(this).parent().find("> ul").addClass('hide');
			$(this).find("b").removeClass('icon-folder-open');
			$(this).find("b").addClass('icon-folder-close');
		}
		
	});

})