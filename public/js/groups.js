$(function(){
	$('.rmv-group').click(function(e){
		e.preventDefault();
		var proceed = confirm('Confirm remove this group?');
		
		if( proceed ){
			var groupId = $(this).attr('data-groupId');
			var url = "/api/groups/" + groupId;
					
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
});