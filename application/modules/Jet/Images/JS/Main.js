dojo.require("dojo.data.ObjectStore");
dojo.require("dijit.Tree");
dojo.require("dojo.data.ItemFileWriteStore");
dojo.require("dijit.TooltipDialog");

dojo.require("dojox.grid.enhanced.plugins.DnD");

Jet.require("Jet.modules.Module");
Jet.require("Jet.Form");
Jet.require("Jet.Trash");
Jet.require("Jet.Formatter");
Jet.require("Jet.MultiUploader");


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
				_this.images_grid.domNode.style.visibility = "visible";
				_this.selected_gallery_ID = data.ID;
				_this.reloadImages();
				_this.tree.openByID( data.ID );
				_this.images_trash.enable();
				_this.getWidgetByID("upload_images_button").attr("disabled", false);

				if(!data.parent_ID) {
					_this.trash.disable();
				} else {
					_this.trash.enable();
				}
			},
			onNew: function() {
				_this.images_grid.domNode.style.visibility = "hidden";
				_this.edit_area.domNode.style.visibility = "visible";
				_this.trash.disable();
				_this.images_trash.disable();
				_this.getWidgetByID("upload_images_button").attr("disabled", true);
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

			_this.images_trash.disable();
			_this.getWidgetByID("upload_images_button").attr("disabled", true);

			_this.selected_gallery_ID = item.ID+"";

			if(_this.selected_gallery_ID=="_root_") {
				_this.selected_gallery_ID = "_root_";
				_this.trash.disable();
				_this.edit_area.domNode.style.visibility = "hidden";
				_this.images_grid.domNode.style.visibility = "hidden";
			} else {
				_this.trash.enable();
				_this.edit(item.ID);
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
			_this.reloadImages();
		});
		this.addSignalCallback( this.module_name+"/image/upload", function(){
			_this.reloadImages();
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

		this.uploader = new Jet.MultiUploader(	"image/", this, "upload_images" );
		this.uploader.onUploadDone = function() {
			dojo.publish(_this.module_name+"/image/upload");
			//TODO:
			alert("DONE");
		};


	},

	reloadImages: function() {
		this.images_grid.setStore( this.images_grid.store, {gallery_ID: this.selected_gallery_ID, thumbnail_max_size_w: 100, thumbnail_max_size_h:100}, null);

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

	openUploadDialog: function() {
		this.uploader.reset();
		this.uploader.setRESTObjectName( "image/"+this.selected_gallery_ID );
		this.getWidgetByID('upload_image').show();
	}

});
