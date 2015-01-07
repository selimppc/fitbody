var grid = {
	_grid : {},
	extend : function extend(Child, Parent) {
		var F = function() {};
		F.prototype = Parent.prototype;
		Child.prototype = new F();
		Child.prototype.constructor = Child;
		Child.superclass = Parent.prototype;
	},
	column : {}
};
/**
 * Get grid
 *
 * @param id
 * @return {*}
 */
grid.get = function (id) {
	return this._grid[id];
};

grid.deleteSelected = function (gridID) {
	this.get(gridID).deleteSelected();
};

/**
 * Create grid object
 *
 * @param config
 * @return {*}
 */
grid.create = function (config) {
	if (this._grid.hasOwnProperty(config.id)) {
		return this._grid[config.id];
	}
	return this._grid[config.id] = new function () {

		/**
		 * ID
		 * @type {*}
		 */
		this.id = config.id;

		/**
		 *
		 */
		this.url = document.location.href;

		this.$cont = $('#' + config.id);

		/**
		 *
		 * @type {*|jQuery|HTMLElement}
		 */
		this.$ = $('#table' + config.id);

		/**
		 * Current grid configuration
		 *
		 * @type {*} object
		 */
		this.config = config;

		/**
		 * Grid columns
		 * @type {Object}
		 */
		this.columns = {};

		/**
		 * Add grid column
		 * @param config
		 */
		this.addColumn = function (config) {
			var column = new grid.column[config.type]();
			column.setGrid(this).init(config);
			this.columns[config.name] = column;
		};

		/**
		 * Update column
		 *
		 * @param column
		 * @param cell
		 */
		this.updateColumn = function () {
			var selected = this._getCheckedRows();
			if(selected.length == 0)
				alert('No rows selected');
			else
				window.location.href = this.config.update_uri+'?id='+selected[0];
		};

		this.selectRow = function (cell) {
			if(this.config['edit_action']) {
				var input = $(cell).find('input[name^=autoId]');
				if(!this.config.editable)
					window.location.href = this.config['update_uri']+'?id='+input.val();
			}
//			if(!this.config.multiple) {
//				var checkedInputs = this.$.find('input[name^=autoId][checked=checked]');
//				checkedInputs.each(function () {
//					$(this).removeAttr('checked').change();
//				});
//			}
//			var input = $(cell).find('input[name^=autoId]');
//			if($(input).attr('checked'))
//				$(input).removeAttr('checked');
//			else
//				$(input).attr('checked','checked');
//			$(cell).find('input[name^=autoId]').change();

		};
		//  DELETE SELECTED

		/**
		 * Delete selected
		 */
		this.deleteSelected = function () {
			var rows = this._getCheckedRows();
			if (rows.length > 0) {
				if (this._deleteSelectedConfimation()) {
					var self = this;
					var data = {
						'ids' : JSON.stringify(rows),
						'rt' : 'deleteSelected'
					};
					this.ajax(data, function () {
						self.updateGrid();
					});
				}
			}
		};

		this._getCheckedRows = function () {
			var rows = [];
			this.$.find('td.group-checkbox-column input[name^=autoId]:checked').each(function () {
				rows.push(this.value);
			});
			return rows;
		};

		/**
		 * Show delition prompt
		 */
		this._deleteSelectedConfimation = function () {
			return confirm('Are you sure want delete selected items?');
		};

		this.updateGrid = function () {
			$('#' + this.id).yiiGridView('update', { url : this.url});
		};

		//  YII grid view events
		this.yiiGridBeforeUpdate = function (id, options) {
			if (options.hasOwnProperty('url')) {
				this.url = options.url;
			}
		};

		this.yiiGridAfterUpdate = function (id, options) {
			this.$ = $('#table' + this.id);
			this.$cont = $('#' + this.id);
			if (options.hasOwnProperty('url')) {
				this.url = options.url;
			}
			for (var name in this.columns) {
				this.columns[name].clearCells();
			}
		};

		this.ajax = function (data, fnSuccess, fnError) {
			var url = document.location.href;
			data.ajax = this.id;
			var xhr = $.post(url, data, 'json')
			xhr.success(function (response) {
				if (fnSuccess) {
					fnSuccess(response);
				}
			});
			xhr.error = (function (error) {
				if (fnError) {
					fnError(error);
				}
			});
		};

		this.addRow = function (url,e) {
			if(!this.config.editable)
				window.location.href = url;
			else {
				grid.popup.show(this.config.id,e);
			}

		}
	};
};

/**
 * Column cell
 *
 * @param $element
 * @param column
 */
grid.column.cell = function ($element, column) {

	/**
	 * Cell id
	 */
	this.id;

	/**
	 * Cell dom element
	 * @type {*}
	 */
	this.element = $element;

	/**
	 * Cell value before change
	 */
	this.oldValue;

	/**
	 * Cell value
	 */
	this.value;

	/**
	 * Grid column
	 * @type {*}
	 */
	this.column = column;

	this.init = function (config) {
		if (config) {
			if (config['value']) {
				this.value = config['value'];
				console.log('ok');
			}
		}
	};

	this.backupVal = function (val) {
		this.oldValue = val || this.value;
	};
};
grid.column.abstract = function () {

	/**
	 *
	 */
	this.cells;

	/**
	 * Id
	 * @type {*}
	 */
	this.id;

	/**
	 * Name
	 * @type {*}
	 */
	this.name;

	/**
	 *
	 */
	this.type;

	/**
	 * Old value
	 */
	this.oldValue;

	/**
	 * New value
	 */
	this.value;

	/**
	 *
	 */
	this.element;

	/**
	 * Config
	 * @type {*}
	 */
	this.config;

	/**
	 *
	 */
	this.grid;
};
grid.column.abstract.prototype.init = function (config) {
	this.config = config;
	this.id = config.id;
	this.name = config.name;
	this.type = config.type;
	this.cells = {};
	this.run();
};
/**
 * Return current column id
 * @return {*}
 */
grid.column.abstract.prototype.getId = function () {
	return this.id;
};

/**
 * Return current column name
 * @return {*}
 */
grid.column.abstract.prototype.getName = function () {
	return this.name;
};

/**
 * Return current column config
 * @return {*}
 */
grid.column.abstract.prototype.getConfig = function () {
	return this.config;
};

/**
 * Set column grid (owner)
 *
 * @param grid
 * @return {*}
 */
grid.column.abstract.prototype.setGrid = function (grid) {
	this.grid = grid;
	return this;
};

grid.column.abstract.prototype.getCellId = function (element) {
	return $(element).parents('td:first').attr('grid-td-id')
};

/**
 * Return cell for DOM element
 *
 * @param element
 * @return {*}
 */
grid.column.abstract.prototype.getCell = function (element) {
	var id = this.getCellId(element);
	if (this.cells.hasOwnProperty(id)) {
		return this.cells[id];
	}
	return this.initCell(element);
};

/**
 * Create and confirure new cell for DOM element
 *
 * @param element
 * @param value
 * @return {*}
 */
grid.column.abstract.prototype.initCell = function (element, value) {
	var id = this.getCellId(element);
	this.cells[id] = new grid.column.cell($(element), this);
	this.cells[id].init({
		value : value
	});
	return this.cells[id];
};

/**
 * Remove all stored cells for current column
 */
grid.column.abstract.prototype.clearCells = function () {
	this.cells = {};
};

/**
 * Update column cell value
 *
 * @param element
 * @param value
 */
grid.column.abstract.prototype.update = function (element, value) {
	var cell = this.getCell(element);
	cell.backupVal();
	cell.value = value;
	this.grid.updateColumn(this, cell);
};

grid.column.abstract.prototype.onSuccess = function () {};
grid.column.abstract.prototype.onError = function () {};

grid.column.text = function () {};
grid.extend(grid.column.text,  grid.column.abstract);

grid.column.text.prototype.run = function (config) {
	var selector = '#' + this.grid.id + ' input[name="' + this.config['fieldName'] + '"]';
	var self = this;
	$(document).on('focus.' + this.grid.id, selector, function () {
		self.initCell(this, this.value);
	});
	$(document).on('change.' + this.grid.id, selector, function () {
		self.update(this, this.value);
	});
};

grid.popup = {

	showed: {},

	show: function (id,e) {
		var popup = $('#edit_popup_'+id);
		if(popup.length) {
			var c = grid.popup._getLeftTop(e,popup);
			$(popup)
				.show()
				.addClass('in')
				.css(c.css)
				.addClass(c.class);

			grid.popup.showed[id] = true;
		}
	},
	/**
	 * close edit popup
	 * @param id
	 */
	close: function (id) {
		var popup = $('#edit_popup_'+id);
		$(popup).hide().removeClass('in');
		popup
			.find('form')
			.find('input[type=text],input[type=password],input[type=date],input[type=email],textarea,select')
			.each(function () {
				$(this).val('');
				$(this)
					.parent('div.f_error')
					.removeClass('f_error')
					.find('.label-error-msg')
					.hide()
					.text('');
			}
		);
	},

	/**
	 * show error
	 * @param id
	 * @param errorMsg
	 */
	showError: function (id,errorMsg) {
		$('#'+id+'_popup_error').text(errorMsg).show();
		setTimeout(function () {
			$('#'+id+'_popup_error').text('').hide();
		},2000);
	},

	/**
	 * get the left and top, depending on the element that you clicked and the window size
	 * @param e clicked element
	 * @private
	 */
	_getLeftTop: function (e,popup) {
		var buttonOffsetTop = e.offset().top;
		var buttonOffsetLeft = e.offset().left;
		var popupHeight = $(popup).height();
		var popupWidth = $(popup).width();
		var ret = {css: {left: 0,top: 0},class: 'top'};
		if($(window).width() < popupWidth) {
			ret.class = '';
			ret.css = {top:2,left:2};
		} else {
			if(popupHeight < buttonOffsetTop) {
				ret = {
					css: {
						left: buttonOffsetLeft-popupWidth/2+e.width()/2,
						top: buttonOffsetTop-popupHeight
					},
					class: 'top'
				};
			} else {
				ret.class = 'bottom';
				ret.css = {
					top: buttonOffsetTop + e.height(),
					left: buttonOffsetLeft - popupWidth/2 + e.width()/2
				};
			}
		}
		if(ret.css.top < 2) {
			ret.css.top = 2;
			ret.class = '';
		}
		if(ret.css.left < 2) {
			ret.css.left = 2;
			ret.class = '';
		}
		return ret;
	}
};
$(function () {
	$(document).keyup(function (e) {
		if(e.keyCode == 27) {
			for(var i in grid.popup.showed) {
				grid.popup.close(i);
			}
		}
	}).click(function (e) {
		e = e || window.event;
		var el = e.target || e.srcElement;
		if(!$(el).parents('div[id^=edit_popup_]').length) {
			for(var i in grid.popup.showed) {
				grid.popup.close(i);
			}
		}
	});
});