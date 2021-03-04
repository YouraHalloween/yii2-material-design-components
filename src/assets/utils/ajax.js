/**
 * 
 * @param {array|JQuery Form} config
 */
function ajax(config = {}) {	
	let _done;
	let _fail;
	let _complete;
	let _ajaxParams;
	
	init(config);

	function init(config) {
		if (config instanceof jQuery) {
			form(config);
		} else {
			_ajaxParams = config;
		}
	}

	/**
	 * Выполнятся в случае успешного выполнения запроса
	 * @param {callback} fn
	 */
	function done(fn) {
		_done = fn;
		return this;
	}

	/**
	 * Выполнятся в случае ошибки в запросе
	 * @param {callback} fn 
	 */
	function fail(fn) {
		_fail = fn;
		return this;
	}

	/**
	 * Выполняется не зависимо от результата
	 * @param {callback} fn
	 */
	function complete(fn) {
		_complete = fn;
		return this;
	}

	/**
	 * Устанавливает параметры для ajax запроса
	 * @param {Object} ajaxParams 
	 */
	function params(ajaxParams) {
		_ajaxParams = ajaxParams;
		return this;
	}

	/**
	 * Отправляет ajax запрос
	 * @param {Object} ajaxParams 
	 */
	function send(ajaxParams, method = 'GET') {
		if (ajaxParams) {
			_ajaxParams = ajaxParams;
		}

		_ajaxParams.method = method;

		if (!_ajaxParams.dataType) {
			_ajaxParams.dataType = 'json';
		}
		
		$.ajax(_ajaxParams).done((data) => {
			if (_done) {
				_done(data);
			}
		}).fail((data) => {
			if (_fail) {
				_fail(data);
			} else {
				console.log(data.responseText);
			}
		}).always((data) => {
			if (_complete) {
				_complete(data);
			}
		});
	}

	function get(ajaxParams) {		
		send(ajaxParams, 'GET');
	}

	function post(ajaxParams) {		
		send(ajaxParams, 'POST');
	}

	function json(ajaxParams, method = 'GET') {
		ajaxParams['dataType'] = 'json';
		send(ajaxParams, method);
	}

	function postJson(ajaxParams) {		
		json(ajaxParams, 'POST');
	}

	function form(frm) {
		var data = frm.serialize();		
		params({
			'url': frm.attr('action'),
			'data': data
		});
		return this;
	}

	return Object.freeze({
		done,
		fail,
		complete,
		params,
		send,
		get,
		post,
		json,
		postJson,
		form
	});
};

export { ajax };