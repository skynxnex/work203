$(function() {
	
	$('#searchbutton').click(function(event) {
		event.preventDefault();
		var text = $('#searchfield');
		console.log(text.val());
		
		// ajax start
		$('#load-gif').css('background-image', 'url(img/ajax-loader.gif)');
		
		newSearch();
		saveSearch(text.val());
		text.val('');
		
		//ajax done
		$('#load-gif').css('background-image', '');
		
	});
	
	$('#savedSearches').click(function() {
		getSearches();
	});
	
	$('#update').click(function(event) {
		event.preventDefault();
		getSearches();		
	});
	
});

function getSearches() {
	$.post("api/?/getSearches",
        function(data) {
        	$('#saved .tab-inner-content').html(data.getsearch);
        }, "json");
}

function newSearch() {
	console.log("doing new search");
}

function saveSearch() {
	console.log("saving search");
	
}
