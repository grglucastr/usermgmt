$(function(){
	$(".delete").click(function(e){
		
		var proceed = confirm('Are you sure you want to delete this user?');
		
		if( proceed ){
			var id = $(this).attr("data-id");
			var url = "/users/" + id + "/delete";	
			$.post(url, function(resp){
				if(resp.done){
					alert('User Deleted!');
					location.href="/users";
				}
			}, 'json');
		}
	});
});