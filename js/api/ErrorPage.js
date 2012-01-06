ErrorPage = {};

ErrorPage.test0 = function(callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'test0'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.test1 = function(argument, callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'test1', 'arg0' : argument}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.test2 = function(first, second, callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'test2', 'arg0' : first, 'arg1' : second}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.getTitle = function(callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'getTitle'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.TextContent = function(callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'TextContent'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.Pages = function(callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'Pages'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

