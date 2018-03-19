define(function (require) {

	var elgg = require('elgg');

	var $ = require('jquery');
	require('caret');
	require('atwho');

	var Ajax = require('elgg/Ajax');

	require('elgg/ready');

	var emoji = require('mentions/emoji');

	var atWhoFilter = function (query, callback) {
		var ajax = new Ajax();
		ajax.path('mentions/search/entities', {
			data: {
				query: query
			}
		}).done(callback);
	};

	var hashtagFilter = function (query, callback) {
		var ajax = new Ajax();
		ajax.path('mentions/search/tags', {
			data: {
				query: query
			}
		}).done(callback);
	};

	var config = {
		'@': {
			at: '@',
			headerTpl: null,
			displayTpl: '<li class="mentions-picker-item"><span rel="icon" style="background-image: url(${icon})"></span><span>${name}</span></li>',
			insertTpl: '${atwho-at}[${guid}:${name}]',
			callbacks: {
				remoteFilter: atWhoFilter
			},
			limit: 20
		},
		'#': {
			at: '#',
			headerTpl: null,
			displayTpl: '<li class="mentions-picker-item"><span rel="hashtag">#</span><span>${name}</span></li>',
			insertTpl: '${atwho-at}${name}',
			callbacks: {
				remoteFilter: hashtagFilter
			},
			limit: 20
		},
		':': {
			at: ':',
			headerTpl: null,
			displayTpl: '<li class="mentions-picker-item"><span rel="emoji">${char}</span><span>${name}</span></li>',
			insertTpl: '${char}',
			limit: 20,
			data: emoji
		}
	};

	var configPlain = elgg.trigger_hook('mentions', 'config', {}, config);

	var configHtml = $.extend(true, {}, configPlain);
	configHtml['@'].insertTpl = '<a href="${url}" rel="mention" data-guid="${guid}">${name}</a><span hidden>' + configPlain['@'].insertTpl + '</span>';

	var initCke = function () {
		elgg.register_hook_handler('prepare', 'ckeditor', function (hook, type, params, CKEDITOR) {
			CKEDITOR.on('instanceReady', function (event) {
				var editor = event.editor;

				function init(editor) {
					if (editor.mode !== 'source') {
						editor.document.getBody().$.contentEditable = true;
						$(editor.document.getBody().$)
							.atwho('setIframe', editor.window.getFrame().$)
							.atwho(configHtml['@'])
							.atwho(configHtml['#'])
							.atwho(configHtml[':']);
					} else {
						$(editor.container.$).find(".cke_source")
							.atwho(configPlain['@'])
							.atwho(configPlain['#'])
							.atwho(configPlain[':']);
					}

				}

				editor.on('mode', init);
				init(editor);

			});

			return CKEDITOR;
		});

		initCke = elgg.nullFunction;
	};

	return function (selectors) {
		$(selectors)
			.atwho(configPlain['@'])
			.atwho(configPlain['#'])
			.atwho(configPlain[':']);

		initCke();
	};
});
