$(function() {
	
	$('#searchbutton').click(function(event) {
		event.preventDefault();
		var text = $('#searchfield');
		console.log(text.val());
		text.val('');
		
		$('#load-gif').css('background-image', 'url(img/ajax-loader.gif)');
		//spara ner sökningen i databasen
		
		//hämta info från olika sökmotorer
		
		// när hämtning är klar ta bort ajax-loader
		
	});
	
	$('#savedSearches').click(function() {
		// ladda in sparade sökningar från db
	});
	
});