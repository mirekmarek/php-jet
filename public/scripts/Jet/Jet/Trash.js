/**
 *
 *
 *
 * @copyright Copyright (c) 2011-2013 Miroslav Marek <mirek.marek.2m@gmail.com>
 * @license http://www.php-jet.net/php-jet/license.txt
 * @author Miroslav Marek <mirek.marek.2m@gmail.com>
 * @version <%VERSION%>
 *
 * @category Jet
 * @package js.Jet
 * @subpackage Trash
 */
dojo.declare("Jet.Trash", [], {
    /**
     * @var Jet.modules.Module
     */
    module_instance: null,

    ID: "",
    ID_property_name: "ID",
    only_delete_from_source_widget: false,

    store: null,

    source_widget_dnd: null,
    source_widget_dnd_type: null,
    source_widget_tree: null,
    source_widget_grid: null,

    itemAvatarCreator: null,

    button_widget: null,
    submit_button_widget: null,
    cancel_button_widget: null,

    trash_widget: null,
    confirm_dialog_widget: null,
    items_area_widget: null,

    _trash_content: [],

    afterDelete: function() {},

    constructor: function( module_instance, trash_ID, store, params ) {

        this.module_instance = module_instance;
        this.ID = trash_ID;
        this.store = store;


        dojo.mixin( this, params );


         var _this = this;
        this.button_widget = this._getWidget( "_button" );
        this.submit_button_widget = this._getWidget( "_submit_button" );
        this.cancel_button_widget = this._getWidget( "_cancel_button" );
        this.confirm_dialog_widget = this._getWidget( "_dialog" );
        this.items_area_widget = this._getNode( "_items_area" );

        if(this.source_widget_dnd_type) {
            this.trash_widget = new dojo.dnd.Target(
                                    this.module_instance.getWidgetID( this.ID + "_button" ),
                                    {accept: this.source_widget_dnd_type }
                                );
            this.trash_widget.onDropExternal = function(source, nodes) { _this._trashDrop(source, nodes); }
        }

        this.button_widget.onClick = function() { _this._trashClick(); }
        this.submit_button_widget.onClick = function() {_this.submit();}
        this.cancel_button_widget.onClick = function() {_this.cancel();}


    },

	_getWidget: function( widget_ID_suffix ) {
		var widget = this.module_instance.getWidgetByID( this.ID + widget_ID_suffix );
		if(!widget) {
			var widget_ID = this.module_instance.getWidgetID(this.ID + widget_ID_suffix);

			console.error("Trash: Unknown widget "+widget_ID+". Please check your HTML ...");
		}

		return widget;
	},

	_getNode: function( node_ID_suffix ) {
		var node = this.module_instance.getNodeByID( this.ID + node_ID_suffix );
		if(!node) {
			var node_ID = this.module_instance.getWidgetID(this.ID + node_ID_suffix);

			console.error("Trash: Unknown node "+node_ID+". Please check your HTML ...");
		}

		return node;
	},

    disable: function() {
        this.setDisabled(true);
    },

    enable: function() {
        this.setDisabled(false);
    },

    setDisabled: function( disabled ) {
        this.button_widget.setDisabled( disabled );
    },

    submit: function() {
        if(!this._trash_content) {
            this.submit_button_widget.cancel();
            this.confirm_dialog_widget.hide();

            return;
        }

        var _this = this;
        var IDs = [];
        for(var i=0; i<this._trash_content.length; i++) {
            IDs.push(this._trash_content[i][this.ID_property_name]);
        }

        if(this.only_delete_from_source_widget) {
            if(this.source_widget_tree) {
                console.log("Jet_Trash: it is not possible to delete elements of tree ...");
            }

            if(this.source_widget_dnd) {
                var new_data = [];

                var all_items = this.source_widget_dnd.getAllNodes();
                for(var c=0; c<all_items.length; ++c){
                    var data = this.source_widget_dnd.getItem(all_items[c].id).data;
                    var is_selected = false;

                    for(var i=0;i<IDs.length;i++) {
                        if(data[this.ID_property_name]==IDs[i]) {
                            is_selected = true;
                            break;
                        }
                    }

                    if(!is_selected) {
                        new_data.push(dojo.clone(data));
                    }
                }

                this.source_widget_dnd.selectAll();
                this.source_widget_dnd.deleteSelectedNodes();
                this.source_widget_dnd.clearItems();

                this.source_widget_dnd.insertNodes(false, new_data );
            }

            if(this.source_widget_grid) {
                var selected_items = this.source_widget_grid.selection.getSelected();
                if(selected_items.length){
                    dojo.forEach(selected_items, function(selected_item){
                        if(selected_item !== null){
                            _this.source_widget_grid.deleteItem(selected_item);
                        }
                    });
                }
            }

            this.cancel();

            if(this.afterDelete) {
                this.afterDelete(IDs);
            }

            return;
        }

        var i=0;

        var onDelete = function() {}
        onDelete = function() {
            i++;

            if(i>=IDs.length) {

                if(_this.source_widget_grid) {
                    _this.source_widget_grid.selection.clear();
                }

                if(_this.afterDelete) {
                    _this.afterDelete(IDs);
                }

                _this.cancel();
            } else {
                dojo.when(_this.store.remove(IDs[i]), onDelete );
            }
        };


        dojo.when(this.store.remove(IDs[i]), onDelete );

    },


    cancel: function(){
        this._trash_content = [];
        this.items_area_widget.innerHTML = '<div></div>';
        this.submit_button_widget.cancel();
        this.confirm_dialog_widget.hide();
    },

    _getTrashContent: function() {
        this._trash_content = [];

        if(this.source_widget_dnd) {

            var nodes = this.source_widget_dnd.getSelectedNodes();

            if(!nodes.length) {
                return;
            }

            for(var i=0; i<nodes.length; i++) {
                var item = this.source_widget_dnd.getItem(nodes[i].id).data;

                this._trash_content.push(item);
            }
        }

        if(this.source_widget_tree) {
            var selected_item = this.source_widget_tree.get("selectedItem");
            if(selected_item) {
                this._trash_content = [selected_item];
            }
        }

        if(this.source_widget_grid) {
            this._trash_content = this.source_widget_grid.selection.getSelected();
        }
    },

    _getAvatarsHTML: function() {
        var avatars_HTML = "";

        for(var i=0; i<this._trash_content.length; i++) {
            avatars_HTML += this.itemAvatarCreator(this._trash_content[i]);
        }

        return avatars_HTML;
    },

    _trashClick: function() {
        this._getTrashContent();

        this._showDialog();
    },

    _trashDrop: function(source, nodes) {

        this._trash_content = [];

        for(var i=0; i<nodes.length; i++) {
            var item = source.getItem(nodes[i].id).data;
            if(!data || !data[this.ID_property_name]) {
                continue;
            }

            this._trash_content.push(item);
        }

        this._showDialog();
    },

    _showDialog: function() {
        if(!this._trash_content.length) {
            return;
        }

        this.items_area_widget.innerHTML = this._getAvatarsHTML();
        this.confirm_dialog_widget.show();
    }
});