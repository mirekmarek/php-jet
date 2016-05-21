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
 * @subpackage modules
 */

Jet.declare("Jet.modules.Module", [], {
    module_name: null,
    module_label: null,

    container: null,
	container_ID: null,

    IDs_prefix: null,

	signal_connections: null,

	constructor: function( container ){
		if( container ){
			this.container = container;
            this.container_ID = container.id;
			this.IDs_prefix = container.id + "_";
		}
		this.initialize();
	},

    destroy: function() {
        Jet.modules.destroyModuleInstance(
            this.getModuleName(),
            this.getContainerID()
        );
    },

	destructor: function(){
		this.disconnectSignals();
	},

	initialize: function(){

	},

	initializeUI: function(action, parameters){

	},

    getModuleName: function( escape_name ) {
        if(escape_name) {
            return this.module_name.replace(/\\/g, "\\\\");
        } else {
            return this.module_name;
        }
    },

    getContainerID: function() {
        return this.container_ID;
    },

    getModuleClass: function() {

         var offset = this.module_name.length+12;

        return this.declaredClass.substr( offset );
    },

    getMe: function() {
        if(this.container_ID) {
            return "Jet.modules.getModuleInstance( '"+this.getModuleName(true)+"', '"+this.container_ID+"', '"+this.getModuleClass()+"' )";
        } else {
            return "Jet.modules.getModuleInstance( '"+this.getModuleName(true)+"', '', '"+this.getModuleClass()+"' )";
        }
    },

	getWidgetID: function(ID) {
		return this.IDs_prefix ? this.IDs_prefix + ID : ID;
	},

	getNodeID: function(ID){
		return this.getWidgetID(ID);
	},

	getWidgetByID: function(ID){
		return dijit.byId(this.getWidgetID(ID));
	},

	getNodeByID: function(ID){
		return dojo.byId(this.getNodeID(ID));
	},

	getContainer: function(){
		return this.container;
	},

	getActionURL: function(service_type, action, path_fragments, GET_params ){
		return Jet.getActionURL( service_type, this.module_name, action, path_fragments, GET_params );
	},

	getAjaxActionURL: function(action, path_fragments, GET_params ){
        if(!GET_params) {
            GET_params = {};
        }
        if(this.container_ID && !GET_params["container_ID"]) {
            GET_params["container_ID"] = this.container_ID;
        }
		return Jet.getAjaxActionURL(this.module_name, action, path_fragments, GET_params );
	},

	getRestURL: function(object_name, path_fragments, GET_params ){
		return Jet.getRestURL(this.module_name, object_name, path_fragments, GET_params );
	},

    getData: function( data_ID, default_value ) {
        var d_textarea = dojo.byId(this.IDs_prefix+data_ID);
        if(!d_textarea) {
            return default_value;
        }

        return dojo.fromJson(d_textarea.value);
    },

    getJsonRestStoreInstance: function(object_name) {

        var store = new dojo.store.JsonRest({
            target: this.getRestURL(object_name) /*,
            sync: true */
        });


        store.handleError = function(error) {
            Jet.handleRequestError( error );
        };

        return store;
    },

    getCheckboxTree: function( place_at_widget_ID, object_name, root_label, options ) {
        dojo.require('cbtree.Tree');
        dojo.require('cbtree.models.TreeStoreModel');
        dojo.require('cbtree.models.ForestStoreModel');

        //alert( window["cbtree"]["Tree"] );

        Jet.dojoExtensions.handleExtensions();
        var store = new dojo.data.ItemFileWriteStore( {
            url: this.getRestURL(object_name )
        });

        if(options===undefined) {
            options = {};
        }

        if( options["checkboxAll"]===undefined ) options["checkboxAll"]=true;
        if( options["checkboxRoot"]===undefined ) options["checkboxRoot"]=true;
        if( options["checkboxState"]===undefined ) options["checkboxState"]=false;
        if( options["checkboxStrict"]===undefined ) options["checkboxStrict"]=true;
        if( options["allowMultiState"]===undefined ) options["allowMultiState"]=true;
        if( options["branchIcons"]===undefined ) options["branchIcons"]=true;
        if( options["nodeIcons"]===undefined ) options["nodeIcons"]=true;
        if( options["showRoot"]===undefined ) options["showRoot"]=true;

        var model = new cbtree.models.ForestStoreModel( {
            store: store,
            rootLabel: root_label,
            checkboxAll:  options["checkboxAll"],
            checkboxRoot: options["checkboxRoot"],
            checkboxState: options["checkboxState"],
            checkboxStrict: options["checkboxStrict"]
        });


        var tree = new cbtree.Tree( {
            checkBoxes: true,
            model: model,
            allowMultiState: options["allowMultiState"],
            branchIcons: options["branchIcons"],
            nodeIcons: options["nodeIcons"],
            showRoot: options["showRoot"]
        });

        tree.placeAt( this.getWidgetByID(place_at_widget_ID) );

        return tree;
    },

    getDataGrid: function(grid_widget_ID, rest_store, edit_method_name, ID_key, edit_column_index) {
        var grid = this.getWidgetByID(grid_widget_ID);

        if(!ID_key) {
            ID_key = 'ID';
        }

	    for(var y=0; y<grid.layout.cells.length; y++) {

		    var formatter = grid.layout.cells[y].formatter;
		    if(
			    !formatter ||
				typeof formatter!='string'
		    ) {
			    continue;
		    }

		    grid.layout.cells[y].formatter = this[formatter];
	    }



	    var store = new dojo.data.ObjectStore({objectStore: rest_store });
        var _this = this;

        grid.onRowDblClick = function(e) {
            _this[edit_method_name](e.grid.getItem(e.rowIndex).ID);
        }

        //grid.changePageSize(25);
        grid.setStore( store, null, null );
        grid.reload = grid.render;

        if(edit_method_name) {
            if(edit_column_index===undefined) {
                edit_column_index = 1;
            }

            grid.layout.cells[edit_column_index].formatter = function(val, idx, c) {
                var item = c.grid.getItem(idx);

                var edit = _this.getMe()+"."+edit_method_name+"('"+item[ID_key]+"')";


                return '<a href="#" onClick="'+edit+'">'+val+'</a>';
            };
        }


        return grid;
    },

    getTree: function(place_at_widget_ID, object_name, enable_DnD ) {
        Jet.require('Jet.JsonRestTreeStore');

        var store = new Jet.JsonRestTreeStore( this.getRestURL(object_name) );

        var tree_params = {
            model: store,
            showRoot: true,
            persist: false
        };

        if(enable_DnD) {
           tree_params.dndController = dijit.tree.dndSource;
        }


	    widget = this.getWidgetByID(place_at_widget_ID);
	    if(widget) {
		    var tree = new dijit.Tree(tree_params );
		    tree.placeAt(widget);
	    } else {
		    var tree = new dijit.Tree(tree_params, this.getNodeID(place_at_widget_ID) );
	    }


        return tree;
    },

    getForm: function( object_name, fields_definition, params ) {
        return new Jet.Form(this, object_name, fields_definition, params );
    },

	addSignalCallback: function( signal_name, callback ) {

		if(!this.signal_connections) {
			this.signal_connections = {};
		}

		if(!this.signal_connections[signal_name]) {
			this.signal_connections[signal_name] = [];
		}

		signal_connection = dojo.subscribe( signal_name, callback );

		this.signal_connections[signal_name].push(signal_connection);

		return signal_connection;
	},

	disconnectSignals: function() {
		if(!this.signal_connections) {
			return;
		}

		for(var signal_name in this.signal_connections) {
			for(var i=0; i<this.signal_connections[signal_name].length; i++) {
				dojo.disconnect( this.signal_connections[signal_name][i] );
			}
		}
	},


	restPutAction: function( object_name, object_ID, data, on_response, busy_button_ID ) {
		this.restAction("put", object_name, object_ID, data, on_response, busy_button_ID);
	},

	restPostAction: function( object_name, object_ID, data, on_response, busy_button_ID ) {
		this.restAction("add", object_name, object_ID, data, on_response, busy_button_ID);
	},

	restDeleteAction: function( object_name, object_ID, data, on_response, busy_button_ID ) {
		this.restAction("delete", object_name, object_ID, data, on_response, busy_button_ID);
	},

	restAction: function( operation, object_name, object_ID, data, on_response, busy_button_ID ) {
		var store = this.getJsonRestStoreInstance(object_name);

		var _this = this;

        if(object_ID.substr(-1)!='/') {
            object_ID+='/';
        }

		var cancel_busy_button = function() {
			if(!busy_button_ID) {
				return;
			}

			if( busy_button_ID.push!==undefined ) {
				for(var i=0; i<busy_button_ID.length;i++) {
					var bb = _this.getWidgetByID( busy_button_ID[i] );

					if(!bb) {
						console.error( 'Unknown busy button: '+busy_button_ID[i] );
					} else {
						bb.cancel();
					}

				}
			} else {
				var bb = _this.getWidgetByID( busy_button_ID );

				if(!bb) {
					console.error( 'Unknown busy button: '+busy_button_ID );
				} else {
					bb.cancel();
				}
			}

		}

		store.handleError = function(error) {
			Jet.handleRequestError( error );
			cancel_busy_button();
		};

		store[operation]( data, { id: object_ID }).then(
			function(data) {
				cancel_busy_button();

				if(on_response) {
					on_response(data);
				}
			},
			function(error) {
                Jet.handleRequestError( error );
				cancel_busy_button();
			}
		);

	},

});