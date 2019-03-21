$(function(){
	$('.rmv-user-group').click(function(e){
		e.preventDefault();
		var proceed = confirm('Confirm remove user from group?');
		if( proceed ){
			
			var groupId = $(this).attr('data-groupid');
			var userId = $(this).attr('data-userid');
						
			var url = "/api/users/" + userId + "/groups/" + groupId;
					
			$.ajax({
				url: url,
				type: 'DELETE',
				contentType: "application/json",
				dataType: 'json'
			}).done(function(resp){
				if(resp.done){
					alert('Remove complete!');
					location.reload();
				}
			})
		}
	});
})
