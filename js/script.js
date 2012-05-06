$(function() {
	
	$('#searchbutton').click(function(event) {
		event.preventDefault();
		var text = $('#searchfield');
		$(this).ajaxStart(function() {
			$('#load-gif').css('background-image', 'url(img/ajax-loader.gif)');
		});
		newSearch(text.val());
		text.val('');
		$(this).ajaxStop(function() {
			$('#load-gif').css('background-image', '');
		});
	});
});

function getSearches() {
	$.post("api/?/getSearches",
        function(data) {
        	$('#saved .tab-inner-content').html(data.getsearch);
        }, "json");
}

function newSearch(search) {
	$.post("api/?/liveSsearches",
		{ searchfield: search },
        function(data) {
        	$('#all .tab-inner-content').html('<ul id="allul" class="unstyled"></ul>');
        	$('#google .tab-inner-content').html('<ul id="googleul" class="unstyled"></ul>');
        	$('#bing .tab-inner-content').html('<ul id="bingul" class="unstyled"></ul>');
        	$('#yahoo .tab-inner-content').html('<ul id="yahooul" class="unstyled"></ul>');
        	$.each(data.google, function(index, value) {
        		$('#googleul').append("<li><a href='http://"+value+"'>"+value+"</a></li>");
        	});
        	$.each(data.bing, function(index, value) {
        		$('#bingul').append("<li><a href='http://"+value+"'>"+value+"</a></li>");
        	});
        	$.each(data.yahoo, function(index, value) {
        		$('#yahooul').append("<li><a href='http://"+value+"'>"+value+"</a></li>");
        	});
        	$.each(data.total, function(index, value) {
        		$('#allul').append("<li><a href='http://"+value.search_result+"'>"+value.search_result+"</a></li>");
        	});
        }, "json");
}
