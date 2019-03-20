$(function(){
	$("#form_user_group").submit(function(e){
		e.preventDefault();
		
		var url = "/api/groups";
		var data = {
			group_name: $("#group_name").val()
		};
		
		$.post(url, data, function(resp){
			console.log(resp);
		}, 'json')
		
	});
});