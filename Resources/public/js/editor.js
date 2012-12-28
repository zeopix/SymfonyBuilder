$(function(){

	$(".node a.dir").click(function(){
		if($(this).parent().find("> ul").hasClass('hide')){
			$(this).parent().find("> ul").removeClass('hide');
		}else{
			$(this).parent().find("> ul").addClass('hide');
		}
		
	});

})