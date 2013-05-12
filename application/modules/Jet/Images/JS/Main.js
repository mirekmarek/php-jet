dojo.require("dojo.data.ObjectStore");
dojo.require("dijit.Tree");
dojo.require("dojo.data.ItemFileWriteStore");

dojo.require("dojox.form.Uploader");
dojo.require("dojox.embed.Flash");
if(dojox.embed.Flash.available){
	dojo.require("dojox.form.uploader.plugins.Flash");
}else{
	dojo.require("dojox.form.uploader.plugins.IFrame");
}

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
	selected_parent_ID: null,

	initializeUI: function(){
		var _this = this;

		this.edit_area = this.getWidgetByID("edit_area");

		this.tree = this.getTree( "galleries_tree_area", "gallery", true );

		this.form = this.getForm( "gallery", this.getData("gallery_form_fields_definition"), {
			save_button: "gallery_save",
			onEdit: function( data ) {
				_this.edit_area.domNode.style.visibility = "visible";
				_this.selected_parent_ID = data.ID;
				_this.tree.openByID( data.ID );
				if(!data.parent_ID) {
					_this.trash.disable();
				} else {
					_this.trash.enable();
				}
			},
			onNew: function() {
				_this.edit_area.domNode.style.visibility = "visible";
			},
			beforeAdd: function(data) {
				data.parent_ID = _this.selected_parent_ID;

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

			_this.selected_parent_ID = item.ID+"";

			if(item.ID=="_root_") {
				_this.trash.disable();
				_this.edit_area.domNode.style.visibility = "hidden";
				_this.images_grid.setStore( _this.images_grid.store, null, null);
			} else {
				_this.trash.enable();
				_this.edit(item.ID);
				_this.images_grid.setStore( _this.images_grid.store, {gallery_ID: item.ID, thumbnail_max_size_w: 100, thumbnail_max_size_h:100}, null);
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
		this.addSignalCallback( this.module_name+"/updated", function(){ _this.treereload(); });
		this.addSignalCallback( this.module_name+"/deleted", function(){
			_this.treereload();
			_this.trash.disable();
			_this.edit_area.domNode.style.visibility = "hidden";
		});

		this.uploader = new dojox.form.Uploader({
			label:"Select file",
			multiple:true,
			uploadOnSelect:false,
			url:this.getRestURL("image")
		}, this.getNodeID("uploader_area"));

		this.images_grid = this.getDataGrid( "images_grid", this.getJsonRestStoreInstance("image") );

		//this.images_grid.struncture.0.cells[0][0]


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

	uploadImage: function() {
		this.uploader.upload();
	},

	formatterImage: function(a, b) {
		alert(a);
		alert(b);

		return "aa";
	}
});
