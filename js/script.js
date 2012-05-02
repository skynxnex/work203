$(function() {
	
	$('#searchbutton').click(function(event) {
		event.preventDefault();
		var text = $('#searchfield');
		console.log(text.val());
		text.val('');
		
		//spara ner sökningen i databasen
		
		//hämta info från olika sökmotorer
		
	});
	
});