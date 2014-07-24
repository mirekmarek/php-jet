dojo.require("dijit.ProgressBar");

dojo.declare("Jet.MultiUploader", [], {
	/**
	 * @var Jet.modules.Module
	 */
	module_instance: null,

	ID: "",

	error_message_unknown_error: Jet.translate("Sorry, but the error occurred."),

	onUploadDone: function() {},
	getPostData: function() {},

	constructor: function( REST_object_namge, module_instance, umploader_ID, params ) {
		this.getObject = REST_object_namge;

		this.module_instance = module_instance;
		this.ID = umploader_ID;

		this.selected_files = [];

		dojo.mixin( this, params );

		this.select_files_button = this._getWidget( "_select_files_button" );
		this.files_input = this._getNode("_files");
		this.selected_files_area = this._getNode("_selected_files_area");
		this.upload_button = this._getWidget("_upload_button");
		this.reset_button = this._getWidget("_reset_button");

		this.files_ID_preffix = this.module_instance.getNodeID("_selected_files_");

		var _this = this;

		this.files_input.onchange = function( event ) {
			_this._filesSelectionUpdated( this, event);
		};

		this.select_files_button.onClick = function() {
			_this.selectFiles();
		};

		this.upload_button.onClick = function() {
			_this.upload();
		};

		this.reset_button.onClick = function() {
			_this.reset();
		};

	},

	_getWidget: function( widget_ID_suffix ) {
		var widget = this.module_instance.getWidgetByID( this.ID + widget_ID_suffix );
		if(!widget) {
			var widget_ID = this.module_instance.getWidgetID(this.ID + widget_ID_suffix);

			console.error("MultiUploader: Unknown widget "+widget_ID+". Please check your HTML ...");
		}

		return widget;
	},

	_getNode: function( node_ID_suffix ) {
		var node = this.module_instance.getNodeByID( this.ID + node_ID_suffix );
		if(!node) {
			var node_ID = this.module_instance.getWidgetID(this.ID + node_ID_suffix);

			console.error("MultiUploader: Unknown node "+node_ID+". Please check your HTML ...");
		}

		return node;
	},

	setRESTObjectName: function( REST_object_namge ) {
		this.REST_object_namge = REST_object_namge;

	},

	selectFiles: function() {
		this.files_input.click();
	},

	_filesSelectionUpdated: function( file_input, event ) {
		if(!this.selected_files.length) {
			this.reset();
		}

		for(var i=0; i<file_input.files.length; i++) {
			var new_file = file_input.files[i];
			var already_selected = false;

			for (var c=0; c<this.selected_files.length; c++) {
				var selected_file = this.selected_files[c];
				if(new_file.name==selected_file.name) {
					already_selected = true;
					break;
				}
			}

			if( already_selected ) {
				continue;
			}


			this.selected_files.push( this.getNewFileItemInstance(new_file) );
		}

		file_input.value = "";
		this._showFilesList();
	},

	upload: function() {
		if(!this.selected_files.length) {
			this.select_files_button.attr("disabled", false);
			this.upload_button.attr("disabled", true);
			this.reset_button.attr("disabled", true);

			this.onUploadDone();

			return;
		}

		this.select_files_button.attr("disabled", true);
		this.upload_button.attr("disabled", true);
		this.reset_button.attr("disabled", true);


		var _this = this;


		var file = this.selected_files[0];

		file.setUploading();

		var xhr = new XMLHttpRequest();

		var upload = xhr.upload;


		upload.addEventListener("progress",
			function(event) {
				var percentage = 0;
				if(event.lengthComputable) {
					percentage = Math.round((event.loaded * 100) / event.total);
				}

				file.updateProgress(percentage);
			},
			false
		);

		upload.addEventListener(
			"load",
			function() {

				file.setUploaded();

				_this.selected_files.shift();
				_this.upload();
			},
			false
		);

		xhr.onreadystatechange = function() {
			if(this.readyState==4 && this.status!=200) {
				if(this.status==500) {
					Jet.alert( _this.error_message_unknown_error+"<br/><br/><pre>"+this.responseText+"</pre>" );

				} else {
					var error_data = dojo.fromJson(this.responseText);

					file.setError( error_data.error_msg, error_data.error_code );
				}

			}
		};


		xhr.open("POST", this.module_instance.getRestURL(this.REST_object_namge) );
		var fd = new FormData();
		fd.append("file", file );

		var POST_data = this.getPostData();


		if(POST_data) {
			for(var k in POST_data) {
				fd.append( k, POST_data[k] );
			}
		}

		xhr.send(fd);
	},


	reset: function() {
		this.selected_files = [];
		this._showFilesList();
	},


	_showFilesList: function() {

		if(this.selected_files.length) {
			var output = this.selected_files_area.innerHTML;

			for(var i=0; i<this.selected_files.length; i++) {
				if(!this.selected_files[i]["item_node_ID"]) {
					output += this.itemFormatter( this.selected_files[i] );
				}
			}

			this.selected_files_area.innerHTML = output;

			for( i=0; i<this.selected_files.length; i++) {
				var file = this.selected_files[i];

				if(!file.uploading) {
					continue;
				}

				if(!file["progress_bar_instance"]) {
					file.progress_bar_instance = new dijit.ProgressBar(
													{},
													file.progress_area_node_ID
												);
				}

				file.progress_bar_instance.update( {progress: file.uploading_percentage } );

			}


			this.upload_button.attr("disabled", false);
			this.reset_button.attr("disabled", false);
		} else {
			this.selected_files_area.innerHTML = "";

			this.upload_button.attr("disabled", true);
			this.reset_button.attr("disabled", true);
		}

	},

	getNewFileItemInstance: function( new_file ) {

		var _this = this;


		new_file.id = this.selected_files.length+1;
		new_file.is_image = false;
		new_file.uploading = false;
		new_file.uploaded = false;
		new_file.uploading_percentage = 0;
		new_file.upload_error_occurred = false;
		new_file.upload_error_message = "";
		new_file.upload_error_code = "";


		new_file.getProgressBar = function() {
			if(!this["progress_bar_instance"]) {
				this.progress_bar_instance = new dijit.ProgressBar(
					{},
					this.progress_area_node_ID
				);
			}

			return this.progress_bar_instance;

		};

		new_file.scrollTo = function() {
			var files_area_coords = dojo.coords(_this.selected_files_area);
			var item_coords = dojo.coords( dojo.byId(new_file.item_node_ID) );

			var to = item_coords.y - files_area_coords.y;

			_this.selected_files_area.scrollTop = _this.selected_files_area.scrollTop + to;

		};

		new_file.updateProgress = function( percentage ) {
			this.scrollTo();
			this.uploading_percentage = percentage;

			this.getProgressBar().update( {progress: this.uploading_percentage } )
		};

		new_file.setUploading = function() {
			this.scrollTo();
			this.uploading = true;
			this.updateProgress(0);
			dojo.addClass(this.item_node_ID, "jet-uploader-file-item-box-in-progress");
		};

		new_file.setUploaded = function() {

			this.scrollTo();
			this.uploaded = true;
			this.uploading = false;
			this.updateProgress(100);
			this.getProgressBar().destroy();

			dojo.removeClass(this.item_node_ID, "jet-uploader-file-item-box-in-progress");
			dojo.addClass(this.item_node_ID, "jet-uploader-file-item-box-uploaded");
		};

		new_file.setError = function( error_message, error_code ) {

			this.scrollTo();
			this.upload_error_occurred = true;
			this.upload_error_message = error_message;
			this.upload_error_code = error_code;


			dojo.removeClass(this.item_node_ID, "jet-uploader-file-item-box-uploaded");
			dojo.removeClass(this.item_node_ID, "jet-uploader-file-item-box-in-progress");
			dojo.addClass(this.item_node_ID, "jet-uploader-file-item-box-error");

			dojo.byId( this.message_area_node_ID ).innerHTML = this.upload_error_message;
		};

		new_file.is_image = (new_file.type.match(/image.*/));

		if(new_file.is_image) {
			var file_read = new FileReader();
			file_read.onload = (function(file) {
				return function(env) {
					file.file_avatar_src = env.target.result;

					if(dojo.byId(file.item_image_ID)) {
						dojo.byId(file.item_image_ID).src = file.file_avatar_src;
					}
				}
			})(new_file);

			file_read.readAsDataURL(new_file);

		}

		return new_file;
	},

	itemFormatter: function(file) {
		file.item_node_ID = this.files_ID_preffix+file.id+'_item';
		file.item_image_ID = this.files_ID_preffix+file.id+'_image';
		file.progress_area_node_ID = this.files_ID_preffix+file.id+'_progress_area';
		file.message_area_node_ID = this.files_ID_preffix+file.id+'_message_area';

		var result = '';

        result += '<div id="'+file.item_node_ID+'" class="jet-uploader-file-item-box">';
        result += '<div class="jet-uploader-file-item-icon-box">';
        if(file.is_image) {
            result += ( file["file_avatar_src"] ? '<img src="'+file.file_avatar_src+'" class="jet-uploader-file-item-icon" id="'+file.item_image_ID+'"/>' : '<img class="jet-uploader-file-item-icon" id="'+file.item_image_ID+'"/>' );
        }
        result += '</div>';
        result += '<div class="jet-uploader-file-item-file-name">'+file.name+'</div> ';
        result += '<div class="jet-uploader-file-item-file-size">'+ Jet.Formatter.FileSize(file.size) +'</div>';
        result += '<div class="jet-uploader-file-item-progress-bar-area" id="'+file.progress_area_node_ID+'"></div>';
        result += '<div class="jet-uploader-file-item-message-area" id="'+file.message_area_node_ID+'"></div>';
        result += '</div>';


		return result;
	}

});