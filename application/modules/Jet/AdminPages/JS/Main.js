dojo.require("dojo.data.ObjectStore");
dojo.require("dijit.Tree");
dojo.require("dojo.data.ItemFileWriteStore");

Jet.require("Jet.modules.Module");
Jet.require("Jet.Form");
Jet.require("Jet.Trash");
Jet.require("Jet.EditArea");

Jet.declare("Jet.module.Jet\\AdminPages.Main", [Jet.modules.Module], {
    module_name: "Jet\\AdminPages",
    module_label: Jet.translate("Web Pages"),
    selected_page: null,

    initializeUI: function(){
        var _this = this;

        this.edit_area = this.getWidgetByID("pages_edit_area");

        this.tree = this.getTree( "pages_tree_area", "page", true );

        this.form = this.getForm( "page", this.getData("page_form_fields_definition"), {
            save_button: "page_save",
            onEdit: function( data ) {
                _this.edit_area.domNode.style.visibility = "visible";
                _this.selected_page = data;
                _this.tree.openByID( _this._getPageID(data) );
                _this.form.fields.layout.setSelectOptions(data.layouts_scope);
                if(data.ID=="_homepage_") {
                    _this.form.fields.URL_fragment.disable();
                    _this.trash.disable();
                } else {
                    _this.form.fields.URL_fragment.enable();
                    _this.trash.enable();
                }
            },
            onNew: function() {
                _this.form.fields.URL_fragment.enable();
                _this.edit_area.domNode.style.visibility = "visible";
            },
            beforeAdd: function(data) {
                data.site_ID = _this.selected_page.site_ID;
                data.locale = _this.selected_page.locale;
                data.parent_ID = _this.selected_page.ID;

                return data;
            },
            afterAdd: function(response_data) {
                dojo.publish(_this.module_name+"/new");

                _this.edit( _this._getPageID(response_data) );
            },
            afterUpdate: function(response_data) {
                dojo.publish(_this.module_name+"/updated");
                _this.edit( _this._getPageID(response_data) );
            }
        } );

        this.tree.onClick = function(item) {
            if(_this.form.getIsSaving()) {
                return;
            }
            _this.edit(item.ID);
        }

        this.trash = new Jet.Trash(this, "pages_trash", this.form.store, {
            source_widget_tree: this.tree,
            itemAvatarCreator: function(item) {
                return '<div>'+item["name"]+'</div>';
            },
            afterDelete: function() {
                dojo.publish(_this.module_name+"/deleted");
            }
        });
        this.trash.disable();


        dojo.subscribe(this.module_name+"/new", function(){ _this.treereload(); });
        dojo.subscribe(this.module_name+"/updated", function(){ _this.treereload(); });
        dojo.subscribe(this.module_name+"/deleted", function(){
                _this.treereload();
                _this.trash.disable();
                _this.edit_area.domNode.style.visibility = "hidden";
        });


    },

    _getPageID: function( page_data ) {
        return page_data.ID+":"+page_data.site_ID+":"+page_data.locale
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
    }
});
