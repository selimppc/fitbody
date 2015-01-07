var o = new Observer;

function Observer() {
	this.fns = [];
}
Observer.prototype = {
	listen : function(fn, thisObj) {
		this.fns.push({'fn' : fn, 'this' : thisObj});
	},
	remove : function(fn) {
		this.fns = this.fns.filter(
			function(el) {
				if ( el !== fn ) {
					return el;
				}
			}
		);
	},
	notify : function(o) {
		for (var key in this.fns) {
			var scope = this.fns[key]['this'] || window;
			this.fns[key]['fn'].call(scope, o);
		}
	}
};