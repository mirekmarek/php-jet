var JetAjaxForm = {
	xhr: null,

	submit: function( form_id, handlers ) {

		var form = document.getElementById(form_id);
		if(!form ) {
			alert('Unknown form '+form_id+'!');
			return;
		}

		if(!handlers) {
			handlers = {};
		}

		if(!handlers.onSuccess) {
			handlers.onSuccess = JetAjaxForm.defaultHandlers.onSuccess;
		}
		if(!handlers.onFormError) {
			handlers.onFormError = JetAjaxForm.defaultHandlers.onFormError;
		}

		if(!handlers.onError) {
			handlers.onError = JetAjaxForm.defaultHandlers.onError;
		}

		if(!handlers.showProgressIndicator) {
			handlers.showProgressIndicator = JetAjaxForm.defaultHandlers.showProgressIndicator;
		}
		if(!handlers.hideProgressIndicator) {
			handlers.hideProgressIndicator = JetAjaxForm.defaultHandlers.hideProgressIndicator;
		}
		if(!handlers.onProgress) {
			handlers.onProgress = JetAjaxForm.defaultHandlers.onProgress;
		}
		if(!handlers.onAccessDenied) {
			handlers.onAccessDenied = JetAjaxForm.defaultHandlers.onAccessDenied;
		}



		handlers.showProgressIndicator( form );
		JetAjaxForm.WYSIWYG.beforeSend( form );


		var form_data = new FormData(form);
		form_data.append('ajax', 'ajax');

		JetAjaxForm.xhr = new XMLHttpRequest();

		/*
		JetAjaxForm.xhr.upload.addEventListener('progress', function(e) {

			if (e.lengthComputable) {
				var percentage = Math.round((e.loaded/e.total)*100);

				handlers.onProgress( form, percentage );
			}
		});
		*/

		JetAjaxForm.xhr.onreadystatechange = function() {

			if( JetAjaxForm.xhr.readyState === XMLHttpRequest.DONE ) {
				handlers.hideProgressIndicator( form );

				if(JetAjaxForm.xhr.status === 200) {
					try {
						var response = JSON.parse(JetAjaxForm.xhr.responseText);
					} catch (e) {
						handlers.onError( form );
						return;
					}

					if(response.snippets) {
						JetAjaxForm.applySnippets(form, response.snippets);
					}

					if( response.result==='ok' ) {
						handlers.onSuccess(form, response.data);
					} else {
						handlers.onFormError(form, response.data);
					}

				} else {
					if(JetAjaxForm.xhr.status === 401) {
						handlers.onAccessDenied( form );
					} else {
						handlers.onError( form );
					}
				}
			}
		};

		JetAjaxForm.xhr.open("POST", form.action);
		JetAjaxForm.xhr.send(form_data);
	},

	applySnippets: function( form, snippets ) {
		for(var el_id in snippets) {

			JetAjaxForm.WYSIWYG.beforeApplySnippet( form );

			var snippet = snippets[el_id];

			document.getElementById(el_id).innerHTML = snippet;

			var parser = new DOMParser();
			var snippet_doc = parser.parseFromString(snippet, "text/html");
			var scripts = snippet_doc.getElementsByTagName('script');

			if(scripts) {
				for(var i=0;i<scripts.length;i++) {
					eval( scripts[i].innerText );
				}
			}
		}

	},

	WYSIWYG: {
		beforeSend: function( form ) {
			if(window['tinyMCE']) {
				tinyMCE.triggerSave();
			}
		},

		beforeApplySnippet: function( form ) {
			if(window['tinyMCE']) {
				var editor;
				for(var input_id in form.elements) {
					editor = tinymce.get(form.elements[input_id].id);
					if(editor) {
						tinymce.remove( editor );
					}
				}
			}
		}
	},

	defaultHandlers: {
		showProgressIndicator: function( form ) {
		},
		hideProgressIndicator: function( form ) {
		},

		onProgress: function( form, percent ) {
		},

		onSuccess: function( form, response_data ) {
		},

		onFormError: function( form, response_data ) {
		},

		onAccessDenied: function( form ) {
		},

		onError: function( form ) {
		}
	}

};
