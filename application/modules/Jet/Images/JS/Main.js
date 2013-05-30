dojo.require("dojo.data.ObjectStore");
dojo.require("dijit.Tree");
dojo.require("dojo.data.ItemFileWriteStore");
dojo.require("dijit.TooltipDialog");

dojo.require("dojox.grid.enhanced.plugins.DnD");


/*
dojo.require("dojox.form.Uploader");
dojo.require("dojox.embed.Flash");
if(dojox.embed.Flash.available){
	dojo.require("dojox.form.uploader.plugins.Flash");
}else{
	dojo.require("dojox.form.uploader.plugins.IFrame");
}
*/

Jet.require("Jet.modules.Module");
Jet.require("Jet.Form");
Jet.require("Jet.Trash");
Jet.require("Jet.Formatter");


window["Jet_module_Jet_Images_formatImage"] = function( thumbnail_URI ) {

	return "<div style=\"height: 100px;width: 100px;\"><img src=\""+thumbnail_URI+"\"/></div>";
}

Jet.declare("Jet.module.Jet\\Images.Main", [Jet.modules.Module], {
	module_name: "Jet\\Images",
	module_label: Jet.translate("Images"),
	selected_gallery_ID: null,

	initializeUI: function(){
		var _this = this;

		this.edit_area = this.getWidgetByID("edit_area");

		this.tree = this.getTree( "galleries_tree_area", "gallery", true );



		this.form = this.getForm( "gallery", this.getData("gallery_form_fields_definition"), {
			save_button: "gallery_save",
			onEdit: function( data ) {
				_this.edit_area.domNode.style.visibility = "visible";
				_this.selected_gallery_ID = data.ID;
				_this.tree.openByID( data.ID );
				if(!data.parent_ID) {
					_this.trash.disable();
				} else {
					_this.trash.enable();
				}
				_this.resetFilesSelection();
			},
			onNew: function() {
				_this.edit_area.domNode.style.visibility = "visible";
				_this.resetFilesSelection();
			},
			beforeAdd: function(data) {
				data.parent_ID = _this.selected_gallery_ID;

				return data;
			},
			afterAdd: function(response_data) {
				dojo.publish(_this.module_name+"/new");

				_this.edit( response_data.ID );
			},
			afterUpdate: function(response_data) {
				dojo.publish(_this.module_name+"/updated");
				_this.edit( response_data.ID );
			}
		} );

		this.tree.onClick = function(item) {
			if(_this.form.getIsSaving()) {
				return;
			}

			_this.selected_gallery_ID = item.ID+"";

			if(item.ID=="_root_") {
				_this.trash.disable();
				_this.edit_area.domNode.style.visibility = "hidden";
				_this.images_grid.setStore( _this.images_grid.store, null, null);
			} else {
				_this.trash.enable();
				_this.edit(item.ID);
				_this.images_grid.setStore( _this.images_grid.store, {gallery_ID: item.ID, thumbnail_max_size_w: 100, thumbnail_max_size_h:100}, null);
				_this.images_grid.selection.deselectAll();
			}

		};

		this.trash = new Jet.Trash(this, "gellery_trash", this.form.store, {
			source_widget_tree: this.tree,
			itemAvatarCreator: function(item) {
				return '<div>'+item["title"]+'</div>';
			},
			afterDelete: function() {
				dojo.publish(_this.module_name+"/deleted");
			}
		});
		this.trash.disable();


		this.addSignalCallback( this.module_name+"/new", function(){ _this.treereload(); });
		this.addSignalCallback( this.module_name+"/updated", function(){
			_this.treereload();
			_this.images_grid.reload();
		});
		this.addSignalCallback( this.module_name+"/deleted", function(){
			_this.treereload();
			_this.trash.disable();
			_this.edit_area.domNode.style.visibility = "hidden";
		});
		this.addSignalCallback( this.module_name+"/image/deleted", function(){
			_this.images_grid.reload();
		});


		this.images_grid = this.getDataGrid( "images_grid", this.getJsonRestStoreInstance("image") );


		this.images_grid.canSort = function(col) {
			return ( Math.abs(col)!=2 );
		}

		this.images_trash = new Jet.Trash(this, "images_trash", this.getJsonRestStoreInstance("image"), {
			source_widget_grid: this.images_grid,
			itemAvatarCreator: function(item) {
				return '<div style="width:50px; height: 50px;float: left;padding: 5px;">'
						+_this.getThumbnail(item, 50, 50)
						+'</div>';
			},
			afterDelete: function() {
				dojo.publish(_this.module_name+"/image/deleted");
			}
		});

		this.tree.dndController.checkAcceptance = function( source, nodes ) {
			if(source.dnd["grid"] && source.dnd["grid"]==_this.images_grid) {
				return true;
			}

			//TODO:
			console.debug( source, nodes );

			return false;
		};

		this.tree.dndController.onDndDrop = function(source, nodes, copy) {
			if(source.dnd["grid"] && source.dnd["grid"]==_this.images_grid) {
				var selected = source.grid.selection.getSelected();
				var images = [];
				for( var i=0; i<selected.length; i++ ) {
					images.push(selected[i]);
				}


				_this.copy_images_target_gallery_ID=this.targetAnchor.item.ID+"";

				if(images.length) {
					_this.copyImagesOpenDialog( images );
				}

				this.onDndCancel();

				return;
			}

			//TODO:
			console.debug( "???",source, nodes, copy );

			this.onDndCancel();
		};

		//this.images_grid.struncture.0.cells[0][0]


	},

	getThumbnail: function( image, max_width, max_height ) {
		var width = image.image_size_w;
		var height = image.image_size_h;

		if (width > height) {
			if (width > max_width) {
				height *= max_width / width;
				width = max_width;
			}
		} else {
			if (height > max_height) {
				width *= max_height / height;
				height = max_height;
			}
		}

		return '<img src="'+image.thumbnail_URI+'" width="'+width+'" height="'+height+'"/>';

	},

	copyImagesOpenDialog: function( selected_images ) {
		var prw_html = "";

		this.copy_images_selection = selected_images;

		for(var i=0;i<selected_images.length; i++ ) {
			var image = selected_images[i];


			prw_html += '<div style="width:50px; height: 50px;float: left;padding: 5px;">'
				+ this.getThumbnail( image, 50, 50 )
				+'</div>';
		}

		this.getNodeByID("copy_images_thb_area").innerHTML = prw_html;
		this.getWidgetByID("copy_image_dialog").show();
	},

	copyImages: function() {

		var _this = this;
		var data = {
			target_gallery_ID: this.copy_images_target_gallery_ID
		};

		var last = this.copy_images_selection.length-1;
		for(var i=0;i<this.copy_images_selection.length; i++ ) {
			if(i==last) {
				//COPY is not supported by dojo
				this.restPutAction( "copy_image", this.copy_images_selection[i].ID, data, function() {
					_this.getWidgetByID("copy_image_dialog").hide();

				}, "copy_image_button" );

			} else {
				//COPY is not supported by dojo
				this.restPutAction( "copy_image", this.copy_images_selection[i].ID, data );

			}
		}

	},

	treereload: function() {
		this.tree.reload();
	},

	treeExpand: function() {
		this.tree.expandAll();
	},

	treeCollapse: function() {
		this.tree.collapseAll();
	},

	edit: function( ID ) {
		this.form.edit(ID);
	},

	add: function() {
		this.form.new();
	},

	save: function() {
		this.form.save();
	},







	selectFiles: function() {
		this.getNodeByID("select_file").click();
	},

	uploadFiles: function() {
		var _this = this;

		for (var i=0; i<this.selected_files.length; i++) {
			var file = this.selected_files[i];

			file.uploading = false;
			file.uploading_percentage = 0;
			file.uploaded = false;
			file.upload_error_occurred = false;
			file.upload_error_message = "";

			this._updateFilesStatus();

			var xhr = new XMLHttpRequest();
			xhr.open("POST", this.getRestURL("image/"+this.selected_gallery_ID), false);

			xhr.addEventListener(
				"progress",
				function(event) {
					console.debug("progress", event, file.id);

					var percentage = 0;
					if(event.lengthComputable) {
						percentage = Math.round((event.loaded * 100) / event.total);
					}

					file.uploading = true;
					file.uploading_percentage = percentage;

					_this._updateFilesStatus();
				},
				false
			);

			xhr.addEventListener(
				"load",
				function(event) {
					console.debug("load", event, file.id);

					file.uploading = false;
					file.uploading_percentage = 100;
					file.uploaded = true;

					_this._updateFilesStatus();
				},
				false
			);

			xhr.addEventListener(
				"error",
				function(event) {
					console.debug("error", event, file.id);

					file.uploading = false;
					file.uploading_percentage = 100;
					file.uploaded = true;
					file.upload_error_occurred = true;
					//TODO: file.upload_error_message = "";

					_this._updateFilesStatus();
				},
				false
			);

			xhr.addEventListener(
				"abort",
				function(event) {
					console.debug("abort", event, file.id);

					file.uploading = false;
					file.uploading_percentage = 100;
					file.uploaded = true;
					file.upload_error_occurred = true;
					//TODO: file.upload_error_message = "";

					_this._updateFilesStatus();
				},
				false
			);



			var fd = new FormData();
			fd.append("file", file );
			xhr.send(fd);
		}
	},

	resetFilesSelection: function() {
		this.selected_files = [];
		this._updateFilesStatus();
	},

	filesSelectionUpdated: function(file_input) {

		for (var i=0; i<file_input.files.length; i++) {
			var new_file = file_input.files[i];

			var allready_selected = false;

			for (var c=0; c<this.selected_files.length; c++) {
				var selected_file = this.selected_files[c];
				if(new_file.name==selected_file.name) {
					allready_selected = true;
					break;
				}
			}

			if( allready_selected ) {
				continue;
			}

			new_file.id = this.selected_files.length+1;

			this.selected_files.push( new_file );
		}

		file_input.value = "";

		this._updateFilesStatus();
	},

	_updateFilesStatus: function() {
		var output = "";

		for (var i=0; i<this.selected_files.length; i++) {
			var file = this.selected_files[i];

				output +=
					'<tr>'
						+ '<td rowspan="3"></td>'
						+ '<td>'+file.name+'</td> '
						+ '<td align="right">'+ Jet.Formatter.FileSize(file.size) +'</td>'
					+'</tr>'
					+'<tr>'
						+ '<td colspan="2"><div id="file_'+file.id+'_progress_area"></div></td>'
					+'</tr>'
					+'<tr>'
						+ '<td colspan="2"><div id="file_'+file.id+'_message_area"></div></td>'
					+'</tr>';

		}

		this.getNodeByID("selected_files_area").innerHTML = '<table width="100%">' + output + '</table>';

		var files_upload_button = this.getWidgetByID("files_upload_button");
		var files_reset_button = this.getWidgetByID("files_reset_button");

		if(this.selected_files.length) {
			files_upload_button.attr("disabled", false);
			files_reset_button.attr("disabled", false);
		} else {
			files_upload_button.attr("disabled", true);
			files_reset_button.attr("disabled", true);
		}

		//this.resetFilesSelection();
		//dojo.publish(_this.module_name+"/updated");

	}
});
