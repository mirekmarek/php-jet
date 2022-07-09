let JetAjaxForm = {
	xhr: null,

	submit: function( form_id, handlers, form_data ) {

		const form = document.getElementById(form_id);
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


		if(!form_data) {
			form_data = new FormData(form);
			form_data.append('ajax', 'ajax');
		}

		JetAjaxForm.xhr = new XMLHttpRequest();


		JetAjaxForm.xhr.upload.addEventListener('progress', function(e) {

			if (e.lengthComputable) {
				const percentage = Math.round((e.loaded / e.total) * 100);

				handlers.onProgress( form, percentage );
			}
		});


		JetAjaxForm.xhr.onreadystatechange = function() {

			if( JetAjaxForm.xhr.readyState === XMLHttpRequest.DONE ) {
				handlers.hideProgressIndicator( form );

				if(JetAjaxForm.xhr.status === 200) {
					let response;

					try {
						response = JSON.parse(JetAjaxForm.xhr.responseText);
					} catch (e) {
						handlers.onError( form );
						return;
					}

					if(response['snippets']) {
						JetAjaxForm.applySnippets(form, response['snippets']);
					}

					if( response['result']==='ok' ) {
						handlers.onSuccess(form, response['data']);
					} else {
						handlers.onFormError(form, response['data']);
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

	submitMultiUpload: function( form_id, file_field_name, handlers ) {
		if(!handlers) {
			handlers = JetAjaxForm.defaultHandlers;
		}

		const form = document.getElementById(form_id);
		if(!form ) {
			alert('Unknown form '+form_id+'!');
			return;
		}

		if(!form.elements[file_field_name+'[]']) {
			alert('File form field '+form_id+' : '+file_field_name+'[] doesn\'t exist!');
			return;
		}

		const file_field = form.elements[file_field_name+'[]'];
		const total_count = file_field.files.length;

		if(!total_count) {
			return;
		}

		let c=0;

		const upload = function( i ) {
			c++;

			if(c>total_count) {
				handlers.hideProgressIndicator();
				form.reset();

				return;
			}

			let file = file_field.files[i];

			handlers.onProgress = function ( percent ) {
				let info = '[ '+c+' / '+total_count+' ] '+file.name+' ... ';

				if(percent>0) {
					info += percent+'%';
				}

				$('#__progress_prc__').html( info );
			};



			let form_data = new FormData( form );

			form_data.delete(file_field.name)
			form_data.append(file_field.name, file);


			handlers.showProgressIndicator();

			let xhr = new XMLHttpRequest();


			xhr.upload.addEventListener('progress', function(e) {

				if (e.lengthComputable) {
					let percentage = Math.round((e.loaded/e.total)*100);

					handlers.onProgress( percentage );
				}
			});


			xhr.onreadystatechange = function() {

				if( xhr.readyState === XMLHttpRequest.DONE ) {

					if(xhr.status === 200) {
						let response;

						try {
							response = JSON.parse(xhr.responseText);
						} catch (e) {
							handlers.onError();
							return;
						}

						if(response['snippets']) {
							JetAjaxForm.applySnippets(form, response['snippets']);
						}

						if( response['result']==='ok' ) {
							handlers.onSuccess(response['data']);

							setTimeout(function () {
								upload( i+1 );
							}, 500);

						} else {
							handlers.hideProgressIndicator();
							handlers.onFormError(response['data']);
						}


					} else {
						handlers.hideProgressIndicator();

						if( xhr.status === 401) {
							handlers.onAccessDenied();
						} else {
							handlers.onError();
						}
					}
				}
			};

			xhr.open("POST", form.action);
			xhr.send(form_data);

		};

		upload(0);
	},


	applySnippets: function( form, snippets ) {
		JetAjaxForm.WYSIWYG.beforeApplySnippets( form );

		for(let el_id in snippets) {
			document.getElementById(el_id).innerHTML = snippets[el_id];
			JetAjaxForm.WYSIWYG.afterApplySnippet( form, snippets[el_id] );
		}

	},

	WYSIWYG: {
		beforeSend: function( form ) {
			if(window['tinyMCE']) {
				window['tinyMCE']['triggerSave']();
			}
		},

		beforeApplySnippets: function( form ) {
		},

		afterApplySnippet: function( form, snippet ) {
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
