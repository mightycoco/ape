ErrorPage = {};

ErrorPage.test0 = function(callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'test0'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.test1 = function(arg0, callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'test1', 'arg0' : arg0}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

ErrorPage.test2 = function(arg0, arg1, callback) {
	$ape.call({'ClassName' : 'ErrorPage', 'function' : 'test2', 'arg0' : arg0, 'arg1' : arg1}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
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

