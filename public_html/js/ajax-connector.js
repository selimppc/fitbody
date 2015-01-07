/**
 * Data types
 *
 * @type {Object}
 */
var requestDataType = {
	ENCODED : 1,
	UNKNOWN : 0,
	NOTENCODED : -1
};

/**
 * Ajax request to server
 *
 * @constructor
 */
var AjaxRequest = function () {};

/**
 * Server response
 *
 * @type {null}
 * @private
 */
AjaxRequest.prototype._response = null;

/**
 * Real send data
 *
 * @type {Object}
 * @private
 */
AjaxRequest.prototype._request = {
	data : null,
	controller : null,
	isEncodedData : requestDataType.UNKNOWN,
	method : null
};

/**
 * Controller to invoke
 * @type {null}
 * @private
 */
AjaxRequest.prototype._controller = null;

/**
 * Method in controller to invoke
 *
 * @type {null}
 * @private
 */
AjaxRequest.prototype._method = null;

/**
 * Tels if we are wait server response
 *
 * @type {Boolean}
 * @private
 */
AjaxRequest.prototype._stateAwaiting = false;

/**
 * Config for awaiting checker
 * if set to true, you can make next request only if previous request was completed, else requests will be sent by queue
 *
 * @type {Boolean}
 */
AjaxRequest.prototype.checkAwaitingState = true;

/**
 * Error handler
 * @param error
 */
AjaxRequest.prototype.onError = function (error) {console.log('Server return error: ' + error);};

/**
 * Success handler
 * @param response
 */
AjaxRequest.prototype.onSuccess = function (response) {};

/**
 * Server script to handle request
 *
 * @type {String}
 * @private
 */
AjaxRequest.prototype._server = '/ajax.html';

/**
 * XmlHttpRequest Handler
 */
AjaxRequest.prototype._xhr = null;

/**
 * User Parameters to send
 */
AjaxRequest.prototype.parameters = null;

/**
 * User parameters not encoded via JSON.stringify
 */
AjaxRequest.prototype.rawParameters = null;

/**
 * Check if server return error
 * @type {Boolean}
 */
AjaxRequest.prototype.isError = false;

/**
 * Check if request was sent
 *
 * @type {Boolean}
 */
AjaxRequest.prototype.isSended = false;

/**
 * Initialize
 *
 * @private
 */
AjaxRequest.prototype._init = function (controller, method, parameters) {
	this.scope = this;
	this.setController(controller);
	this.setParameters(parameters);
	this.setMethod(method);
};

/**
 * Set error handler
 *
 * @param fn
 * @return {*}
 */
AjaxRequest.prototype.error = function (fn) {
	this.onError = fn;
	return this;
};

/**
 * Set success handler
 *
 * @param fn
 * @return {*}
 */
AjaxRequest.prototype.listen = function (fn) {
	this.onSuccess = fn;
	return this;
};

/**
 * Request scope
 *
 * @type {*}
 */
AjaxRequest.prototype.scope = null;

/**
 * Set scope
 *
 * @param scope
 * @return {*}
 */
AjaxRequest.prototype.setScope = function (scope) {
	this.scope = scope || this;
	return this;
};

/**
 * Set method to invoke
 *
 * @param method
 * @return {*}
 */
AjaxRequest.prototype.setMethod = function (method) {
	this._method = method;
	return this;
};

/**
 * Set parameters
 *
 * @param parameters
 * @param encode
 * @return {*}
 */
AjaxRequest.prototype.setParameters = function (parameters, encode) {
	this.rawParameters = parameters;
	encode = encode || false;
	if (encode) {
		parameters = JSON.stringify(parameters);
		this._request.isEncodedData = requestDataType.ENCODED;
	}
	switch (typeof parameters) {
		case 'undefined':
		case 'boolean':
		case 'number':
			this._request.isEncodedData = requestDataType.NOTENCODED;
			break;

		case 'string':
			this._request.isEncodedData = requestDataType.UNKNOWN;
			break;

		case 'object':
			parameters = JSON.stringify(parameters);
			this._request.isEncodedData = requestDataType.ENCODED;
			break;
	}
	this.parameters = parameters;
	return this;
};

/**
 * Alias of AjaxRequest.setParameters
 *
 * @param data
 * @param encode
 */
AjaxRequest.prototype.data = function (data, encode) {
	return this.setParameters(data, encode);
};

/**
 * Set controller
 *
 * @param controller
 * @return {*}
 */
AjaxRequest.prototype.setController = function (controller) {
	this._controller = controller;
	return this;
};

/**
 * Get controller type
 *
 * @return Number
 */
AjaxRequest.prototype.getControllerType = function () {
	return this._controllerType;
};
//
///**
// * Set controller type
// *
// * @param type
// * @return {*}
// */
//AjaxRequest.prototype.setControllerType = function (type) {
//	if (ajaxRequestType.hasOwnProperty(type)) {
//		var _type = ajaxRequestType[type];
//	} else {
//		var _type;
//		for (var typeName in ajaxRequestType) {
//			if (ajaxRequestType[typeName] == type) {
//				_type = type;
//				break;
//			}
//		}
//		if (!type) {
//			type = ajaxRequestType.FRONT;
//		}
//	}
//	this._request.controllerType = this._controllerType = _type;
//	return this;
//};

/**
 * Check if error
 *
 * @return {Boolean|Function}
 */
AjaxRequest.prototype.isError = function () {
	return this.isError;
};

/**
 * Check if request was sent
 *
 * @return {Boolean|Function}
 */
AjaxRequest.prototype.isSended = function () {
	return this.isSended;
};

/**
 * Return last response
 *
 * @return {*}
 */
AjaxRequest.prototype.getResponse = function () {
	return this._response;
};

/**
 * Return request data with controllers and other settings
 *
 * @return {Object}
 */
AjaxRequest.prototype.getRequest = function () {
	return this._request;
};

/**
 * return request data for server handle
 *
 * @return {*}
 */
AjaxRequest.prototype.getRequestData = function () {
	return this._request.data;
};

/**
 * return request data for server handle not encoded via JSON.stringify
 *
 * @return {*}
 */
AjaxRequest.prototype.getRequestDataRaw = function () {
	return this.rawParameters;
};

/**
 * Send request
 */
AjaxRequest.prototype.send = function () {
	if (this.checkAwaitingState) {
		if (this._stateAwaiting) {
			this.onError.call(this.scope, 'Previous request not completed, please wait and try again or turn off checkAwaitingState');
			return this;
		}
		this._stateAwaiting = true;
	}
	var self = this;
	this._request.controller = this._controller;
	this._request.method = this._method;
	this._request.data = this.parameters;
	this._request.controllerType = this._controllerType;
	this._xhr = $.post(this._server, this._request, function (response) {
		if (self.checkAwaitingState) {
			self._stateAwaiting = false;
		}
		self._response = response;
		if (response.error) {
			self.onError.call(self.scope, response.errorMsg);
			return;
		}
		self.onSuccess.call(self.scope, response.data);
	}, 'json').error(function (XMLHttpRequest, textStatus, errorThrown) {
			if (self.checkAwaitingState) {
				self._stateAwaiting = false;
			}
			self.onError.call(self.scope, 'Request error');
		});
	return this;
};

/**
 * Create front request
 *
 * @param controller
 * @param method
 * @param parameters
 */
function request(controller, method, parameters) {
	this._init(controller, method, parameters);
}
request.prototype = AjaxRequest.prototype;

/**
 *
 *
 * @param controller
 * @param method
 * @param parameters
 * @param controllerType can be undefined, default using front controller
 * @constructor
 */
function Call(controller, method, parameters, controllerType) {
	controllerType = controllerType || RT.FRONT;
	this._init(controller, method, parameters);
}
Call.prototype = AjaxRequest.prototype;