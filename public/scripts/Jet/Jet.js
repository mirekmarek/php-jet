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
 */
if( typeof dojo == "undefined"){
	throw new Error("Dojo is required!");
}

var Jet = {
    base_request_URI: "",
    base_URI: "",
    modules_URI: "",
    service_type_path_fragments_map: {},

    SERVICE_TYPE_AJAX: "AJAX",
    SERVICE_TYPE_REST: "REST",

    _loaded_components: {},
    _initialized: false,

    front_controller_module_name: null,

    error_message_401: Jet.translate("Sorry, but you do not have permission for this operation."),
    error_message_unknown_error: Jet.translate("Sorry, but the error occurred."),

    _hasResource: dojo._hasResource,

    declare: function( class_name, super_class, props){
        return dojo.declare(class_name, super_class, props);
    },

    provide: function( resource_name ){
        return dojo.provide( resource_name );
    },

    setOptions: function(options){
        if(options){
            dojo.mixin(this, options);
        }
    },

    initialize: function(){
        if(this._initialized){
            return;
        }

        dojo.addOnLoad(function(){
            Jet.onLoad();
        });

        this._initialized = true;
    },


    addOnLoad: function( callback ){
        dojo.connect(this, "onLoad", callback);
    },

    onLoad: function(){
        if(this.front_controller_module_name) {
            try {
                    this.front_controller_module_instance = this.modules.getModuleInstance(this.front_controller_module_name);
                    this.front_controller_module_instance.initializeUI();
            } catch(e){
                console.error("Error loading UI! "+e);
            }
        }
    },

    getFrontController: function() {
        return this.front_controller_module_instance;
    },

    getFrontControllerModuleName: function() {
        return this.front_controller_module_name;
    },

    require: function( component ){

        if( this._loaded_components[component] ){
            return true;
        }


        var component_parts = component.split(".");
        if(!component_parts.length){
            console.error("Empty component required!");
            return false;
        }

        var component_prefix = component_parts[0];

        if( component_prefix != "Jet" ) {
            console.error("Only Jet components can be loaded by Jet.require!");
            return false;
        }

        var uri = "";

        if(
           component_parts.length >= 3 &&
           component_parts[1] == "module"
        ) {
            var module_name = component_parts.slice(2, -1).join('.');
            var module_class = component_parts.slice(-1).join('.');

            uri = this.modules_URI + module_name+'/'+module_class;
        } else {
            uri = this.base_URI + component_parts.join("/") + '.js';
        }



        try{
            var contents;
            var error_message;
            dojo.xhrGet(
                {
                    url: uri,
                    sync: true,
                    load: function(text){
                        contents = text;
                    },
                    error: function(error, io_args){
                        error_message = "Failed loading component "+component+"\n\n URI: "+uri+"\n\n HTTP Error: ";
                        switch(io_args.xhr.status){
                            case 404:
                                error_message += "The requested page was not found (404)";
                                break;
                            case 500:
                                error_message += "The server reported an error (500)";
                                break;
                            case 401:
                                error_message += "You need to authenticate (401)";
                                break;
                            default:
                                error_message += "Unknown error";
                        }
                    }

                });

                if(error_message) {
                    throw new Error(error_message);
                }

                if(!dojo.isIE) {
                    contents += "\r\n//@ sourceURL=" + uri;
                }

                try {
                    var value = dojo.eval(contents);
                } catch(e) {

                    error_message = "Failed loading component "+component+"\n\n URI: "+uri+"\n\n Parse error (eval):\n"+e;
                    throw new Error(error_message);
                }

        } catch(e) {
            console.error(e);
            throw e;
        }

        this._loaded_components[component] = true;

        return true;
    },


    getActionURL: function(service_type, module_name, action, path_fragments, GET_params ){
        var URL = this.base_request_URI;


        URL += this.service_type_path_fragments_map[service_type]+"/"+module_name + "/"+action + "/";

        if(path_fragments){
                if(!path_fragments["push"]){
                    path_fragments = path_fragments.split("/");
                }

                for(var i=0;i<path_fragments.length;i++) {
                    path_fragments[i] = encodeURIComponent( path_fragments[i] );
                }

                URL += path_fragments.join("/");
        }

        if(GET_params){
            URL += "?" + dojo.objectToQuery(GET_params);
        }

        return URL;
    },

    getRestURL: function(module_name, object_name, path_fragments, GET_params ){
        return this.getActionURL( this.SERVICE_TYPE_REST, module_name, object_name, path_fragments, GET_params );
    },

    getAjaxActionURL: function(module_name, action, path_fragments, GET_params ){
        return this.getActionURL( this.SERVICE_TYPE_AJAX, module_name, action, path_fragments, GET_params );
    },

    alert: function( message, title, onOk ) {
        var dialog = dijit.byId("jet_alert_dialog");

        if(!dialog) {
            console.log("Dialog 'jet_alert_dialog' not found. Using browser alert() ");
            alert(message);
            if(onOk) onOk();
        } else {
            dojo.byId("jet_alert_dialog_msg_area").innerHTML = message;

            if(!title) {
                title = "";
            }

            dialog.attr("title", title);

            dijit.byId("jet_alert_dialog_ok_button").onClick = function() {
                if(onOk) {
                    onOk();
                }
                dialog.hide();
            };
            dialog.show();
        }
    },

    confirm: function( message, title, onYes, onNo ) {
        var dialog = dijit.byId("jet_confirm_dialog");

        if(!dialog) {
            console.log("Dialog 'jet_confirm_dialog' not found. Using browser confirm() ");
            if(confirm(message)) {
                if(onYes) onYes();
            } else {
                if(onNo) onNo();
            }
        } else {
            dojo.byId("jet_confirm_dialog_msg_area").innerHTML = message;
            if(!title) title = "";
            dialog.attr("title", title);

            dijit.byId("jet_confirm_dialog_yes_button").onClick = function() {
                if(onYes) onYes();
                dialog.hide();
            };
            dijit.byId("jet_confirm_dialog_no_button").onClick = function() {
                if(onNo) onNo();
                dialog.hide();
            };
            dialog.show();
        }
    },

    modules: {
        NO_CONTAINER_ID: "",

        instances_of_modules: {},

        require: function( module_name, module_class){
            if(!module_class) {
                module_class = "Main";
            }

            return Jet.require( "Jet.module."+module_name+"."+module_class );
        },

        getModuleInstance: function(module_name, container_ID, module_class){


            var has_container_ID = !!container_ID;
            if(!has_container_ID){
                container_ID = this.NO_CONTAINER_ID;
            }

            if(!module_class) {
                module_class = "Main";
            }

            var instances_of_modules_key = module_name+"."+module_class;

            if(!this.instances_of_modules[instances_of_modules_key]){
                this.instances_of_modules[instances_of_modules_key] = {};
            }

            if(this.instances_of_modules[instances_of_modules_key][container_ID]){
                return this.instances_of_modules[instances_of_modules_key][container_ID];
            }


            if(this.require(module_name, module_class)){

                var module_class_i = eval( 'Jet.module.'+module_name+'.'+module_class );

                if(!module_class_i) {
                    return false;
                }

                if(has_container_ID){
                    this.instances_of_modules[instances_of_modules_key][container_ID] = new module_class_i(container_ID);
                } else {
                    this.instances_of_modules[instances_of_modules_key][container_ID] = new module_class_i();
                }

                this.instances_of_modules[instances_of_modules_key][container_ID].initialize();
                return this.instances_of_modules[instances_of_modules_key][container_ID];

            }
            return false;
        },

        destroyModuleInstance: function(module_name, container_ID, module_class){
            if(!container_ID){
                container_ID = this.NO_CONTAINER_ID;
            }

            if(!module_class) {
                module_class = "Main";
            }
            var instances_of_modules_key = module_name+"."+module_class;


            if(
                !this.instances_of_modules[instances_of_modules_key] ||
                !this.instances_of_modules[instances_of_modules_key][container_ID]
            ){
                return false;
            }

            Jet.modules.instances_of_modules[instances_of_modules_key][container_ID].destructor();

            delete Jet.modules.instances_of_modules[instances_of_modules_key][container_ID];
            Jet.modules.instances_of_modules[instances_of_modules_key][container_ID] = undefined;

            var new_data = {};

            var count = 0;
            for(var _container_ID in Jet.modules.instances_of_modules[instances_of_modules_key]) {
                if(Jet.modules.instances_of_modules[instances_of_modules_key][_container_ID]!==undefined) {
                    new_data[_container_ID] = Jet.modules.instances_of_modules[instances_of_modules_key][_container_ID];
                    count++;
                }
            }

            if(count==0) {
                delete Jet.modules.instances_of_modules[instances_of_modules_key];
                Jet.modules.instances_of_modules[instances_of_modules_key] = undefined;

                var new_data = {};
                for(var i_instances_of_modules_key in Jet.modules.instances_of_modules) {
                    if(Jet.modules.instances_of_modules[i_instances_of_modules_key]!==undefined) {
                        new_data[i_instances_of_modules_key] = Jet.modules.instances_of_modules[i_instances_of_modules_key];
                    }

                }
                Jet.modules.instances_of_modules = new_data;
            } else {
                Jet.modules.instances_of_modules[instances_of_modules_key] = new_data;
            }

            //Jet.modules.instances_of_modules[instances_of_modules_key][container_ID] = undefined;
            return true;
        },

        getModuleInstanceByContainerID: function( container_ID ){
            for(var module_name in this.instances_of_modules){
                if( this.instances_of_modules[module_name][container_ID] ){
                    return this.instances_of_modules[module_name][container_ID];
                }
            }
            return false;
        }

    },

    handleRequestError: function( error ) {
        var front_controller = this.getFrontController();

        if(front_controller && front_controller.handleRequestError) {
            front_controller.handleRequestError( error );
            return;
        }

        switch(error.response.status) {
            case 401:
                Jet.alert( this.error_message_401 );
                break;
            default:
                Jet.alert( this.error_message_unknown_error+'<br/><br/><div style="width: 800px;height: 500px;overflow: auto;"><pre>'+error.response.text+'</pre></div>' );
                break;
        }

    }


};



Jet.setOptions( window['Jet_config'] ? window['Jet_config'] : {});
if(Jet.auto_initialize){
    Jet.initialize();
}


