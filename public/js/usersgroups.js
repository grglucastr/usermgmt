$(function(){
	$('.rmv-user-group').click(function(e){
		e.preventDefault();
		var proceed = confirm('Confirm remove user from group?');
		if( proceed ){
			var url = $(this).attr('href');
			location.href = url;
		}
	});
})
