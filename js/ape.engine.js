(function($){
    $.ape_engine = function(options) {
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
		var id = -1;
        var base = this;

        base.init = function(){
            base.options = $.extend({},$.ape_engine.defaultOptions, options);
            // Put your initialization code here
        };
		
		base.call = function(e, callback) {
			e.ID = base.id;
			var data = encodeURI(JSON.stringify(e));
			$.post(base.baseURL + "ape/call", "json="+data, function(result) {
				var object = JSON.parse(result);
				object.ClassData = JSON.parse(object.ClassData);
				callback(object);
			});
		}

        // Run initializer
        base.init();
    };

    //$.ape_engine.defaultOptions = {
    //    radius: "20px"
    //};

})(jQuery);
$ape = new $.ape_engine();