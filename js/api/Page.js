Page = {};

Page.test0 = function(callback) {
	$ape.call({'ClassName' : 'Page', 'function' : 'test0'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

Page.test1 = function(argument, callback) {
	$ape.call({'ClassName' : 'Page', 'function' : 'test1', 'arg0' : argument}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

Page.test2 = function(first, second, callback) {
	$ape.call({'ClassName' : 'Page', 'function' : 'test2', 'arg0' : first, 'arg1' : second}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

Page.getTitle = function(callback) {
	$ape.call({'ClassName' : 'Page', 'function' : 'getTitle'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

Page.TextContent = function(callback) {
	$ape.call({'ClassName' : 'Page', 'function' : 'TextContent'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

Page.Pages = function(callback) {
	$ape.call({'ClassName' : 'Page', 'function' : 'Pages'}, function(e) { if(typeof callback !== 'undefined') callback(e); } );
}

