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
 * @subpackage Form
 */
dojo.require("dojo.store.JsonRest");
dojo.require("dojo.date.stamp");

dojo.declare("Jet.Form", [], {
    /**
     *
     * @var Jet.Form.Field.*
     */
    fields: null,

    /**
     * @var Jet.modules.Module
     */
    module_instance: null,

    /**
     * @var string
     */
    object_name: "",

    /**
     * @var null|string|array
     */
    save_button: null,

    /**
     * @var string|null
     */
    _fields_IDs_prefix: null,

    /**
     * @var null|string
     */
    current_ID: null,

    /**
     * @var null|object
     */
    current_data: null,

    /**
     * @var dojo.store.JsonRest
     */
    store: null,

    _current_error_messages: null,

    _changed: false,
    _data_set_in_progress: false,
    _is_saving: false,

    save_warning_css_class: "saveWarning",
    error_css_class: "formFieldError",

    error_message_401: Jet.translate("Sorry, but you do not have permission for this operation."),
    error_message_unknown_error: Jet.translate("Sorry, but the error occurred."),

    beforeAdd: function (data) { return data; },
    beforeUpdate: function (data) { return data; },
    afterAdd: function(data) {},
    afterUpdate: function(response_data) {},
    beforeEdit: function() {},
    onEdit: function(data) {},
    beforeNew: function() {},
    onNew: function() {},
    onChanged: function() {},
    onValidationError: function() {},
    onSaveError: function( error ) {},

    constructor: function( module_instance, object_name, fields_definition, params ) {
        this._current_error_messages = {};
        this.module_instance = module_instance;
        this.object_name = object_name;

        this._fields_IDs_prefix = module_instance.IDs_prefix;


        if(params) {
            dojo.mixin(this, params);
        }

        this.setObjectName(object_name);

        this._data_set_in_progress = true;
        this.fields = {};
        for(var name in fields_definition) {
            this.addField(name, fields_definition[name]);
        }
        this._data_set_in_progress = false;

    },

    setObjectName: function( object_name ) {
        if(this.store) {
            delete this.store;
        }

        this.store = this.module_instance.getJsonRestStoreInstance(object_name);
        this.store.handleError = function(error) {
            console.log( "Error: ", error );
        };

    },

    getField: function(name) {
        if(!this.fields[name]) {
            console.error("Unknown form field "+this.object_name+":"+name);
        }

        return this.fields[name];

    },

    addField: function(name, definition_data) {
        var _this = this;

        if(this.fields[name]) {
            this.removeField(name);
        }


        var ID = definition_data.ID;
        var class_name = "Jet.Form.Field."+definition_data.type;

        this.fields[name] = eval("new "+class_name+"(this, ID, name, definition_data)");
        this.fields[name].connectOnChangeAction( function() { _this.setChanged(true);} )


        return this.fields[name];
    },

    removeField: function(name) {
        this.fields[name].disconnectOnChangeAction();
        delete this.fields[name];
    },

    getDataSetInProgress: function() {
        return this._data_set_in_progress;
    },

    getData: function() {
        this.hideFieldErrorMessages();
        
        var data = {};
        var is_valid = true;

        for(var name in this.fields) {
            var field = this.fields[name];
            if(!field.getIsValid()) {
                is_valid = false;
            }
            this._setData( data, name, field.getValue() );
        }

        if(!is_valid) {
            this.onValidationError();
            return false;
        }

        return data;
    },

    resetData: function() {
        this._data_set_in_progress = false;
        this.setChanged(false);
        this._data_set_in_progress = true;
        this.hideFieldErrorMessages();

        this.current_ID = null;
        this.current_data = null;

        for(var name in this.fields) {
            this.fields[name].resetValue();
        }
        var _this = this;
        setTimeout(function() {
            _this._data_set_in_progress = false;
        }, 10);
    },

    setData: function(ID, data) {
        this._data_set_in_progress = false;
        this.setChanged(false);
        this._data_set_in_progress = true;
        this.hideFieldErrorMessages();

        this.current_ID = ID;
        this.current_data = data;

        for(var name in this.fields) {
            var field = this.fields[name];
            field.setValue( this._getData(data, name, field.default_value) );
        }

        var _this = this;
        setTimeout(function() {
            _this._data_set_in_progress = false;
        }, 10);
    },

    edit: function( ID ) {
        var _this = this;
        this.disable();
        this.hideFieldErrorMessages();
        this.resetData();
        this.beforeEdit();


        this.store.get(ID).then(function(data) {
            _this.enable();
            _this.setData( ID, data );
            _this.onEdit( data );
        });
    },

    new: function() {
        this.beforeNew();
        this.resetData();
        this.onNew();
    },

    save: function() {
        this.disable();
        this.hideFieldErrorMessages();

        var data = this.getData();
        if(!data) {
            this.enable();
            this.cancelSaveButton();
            return;
        }

        var _this = this;

        this._is_saving = true;

        if(this.current_ID) {
            data = this.beforeUpdate(data);

            this.store.put(data, {id: this.current_ID}).then(
                function(response_data) {
                    _this.afterUpdate(response_data);
                    _this.enable();
                    _this.cancelSaveButton();
                    _this._changed = false;
                    _this._is_saving = false;
                },
                function(error) {
                    _this._is_saving = false;
                    _this.handleError(error);
                }
            );
        } else {
            data = this.beforeAdd(data);

            this.store.add(data).then(
                function( response_data, data ) {
                    _this.afterAdd(response_data, data);
                    _this.enable();
                    _this.cancelSaveButton();
                    _this._changed = false;
                    _this._is_saving = false;
                },
                function(error) {
                    _this._is_saving = false;
                    _this.handleError(error);
                }
            );
        }
    },

    handleError: function(error) {
        this.enable();
        this.cancelSaveButton();
        switch(error.response.status) {
            case 400:
                var error_data = dojo.fromJson( error.response.text );

                for(var field in error_data.error_data) {
                    var message = error_data.error_data[field];
                    if(field=="__common_message__") {
                        Jet.alert( message );
                    } else {
                        this.showFieldErrorMessage(field, message);
                    }
                }
            break;
            case 401:
                Jet.alert( this.error_message_401 );
            break;
            default:
                Jet.alert( this.error_message_unknown_error+"<br/><br/><pre>"+error.response.text+"</pre>" );
            break;
        }
        this.onSaveError( error );
    },

    disable: function() {
        this.disableSaveButton();
        for(var name in this.fields) {
            this.fields[name].disable();
        }
    },

    enable: function() {
        this.enableSaveButton();
        for(var name in this.fields) {
            this.fields[name].enable();
        }
    },

    cancelSaveButton: function() {
        if(!this.save_button) {
            return;
        }

        var _this = this;
        if( this.save_button.push!==undefined ) {
            setTimeout(function() {
                for(var i=0; i<_this.save_button.length;i++) {
                    var button = _this.module_instance.getWidgetByID(_this.save_button[i]);

                    if(button.cancel) {
                        button.cancel();
                    }
                }
            }, 10);
        } else {
            setTimeout(function() {
                var button = _this.module_instance.getWidgetByID(_this.save_button);

                if(button.cancel) {
                    button.cancel();
                }
            },10);
        }
    },

    disableSaveButton: function() {
        if(!this.save_button) {
            return;
        }

        if( this.save_button.push!==undefined ) {
            for(var i=0; i<this.save_button.length;i++) {
                this.module_instance.getWidgetByID(this.save_button[i]).set("disabled", true);
            }
        } else {
            this.module_instance.getWidgetByID(this.save_button).set("disabled", true);
        }
    },

    enableSaveButton: function() {
        if(!this.save_button) {
            return;
        }

        if( this.save_button.push!==undefined ) {
            for(var i=0; i<this.save_button.length;i++) {
                this.module_instance.getWidgetByID(this.save_button[i]).set("disabled", false );
            }
        } else {
            this.module_instance.getWidgetByID(this.save_button).set("disabled", false);
        }
    },

    showFieldErrorMessage: function( field, message ) {
        this.fields[field].showError(message);
    },

    hideFieldErrorMessages: function() {
        for(var filed in this._current_error_messages) {
            this.fields[filed].hideError();
        }
        this._current_error_messages = {};
    },

    setChanged: function( status ) {
        if(this._data_set_in_progress) {
            return;
        }

        if(this._changed===status) {
            return;
        }


        this._changed = status;

        if( this.save_button.push!==undefined ) {
            for(var i=0; i<this.save_button.length;i++) {
                this._setButtonSaveWarning(this.save_button[i], status);
            }
        } else {
            this._setButtonSaveWarning(this.save_button, status);
        }

        this.onChanged();
    },

    _setButtonSaveWarning: function( button_ID, status ) {
        var btn = dijit.byId( this._fields_IDs_prefix+button_ID );

        if(status) {
            dojo.addClass(btn.domNode, this.save_warning_css_class);
            dojo.addClass(btn.titleNode, this.save_warning_css_class);
            dojo.addClass(btn.containerNode, this.save_warning_css_class);
        } else {
            dojo.removeClass(btn.domNode, this.save_warning_css_class);
            dojo.removeClass(btn.titleNode, this.save_warning_css_class);
            dojo.removeClass(btn.containerNode, this.save_warning_css_class);
        }
    },

    getChanged: function(){
        return this._changed;
    },

    getIsSaving: function() {
        return this._is_saving;
    },

    cancelCheck: function( title, question, on_OK ) {
        if(this.getIsSaving()) {
            return;
        }

        if(this.getChanged()) {
            Jet.confirm(
                question,
                title,
                on_OK
            );
        } else {
            on_OK();
        }

    },

    _getData: function( data, key, default_value ) {
        var result = undefined;

        if(key[0]=="/") {
            var path = key.split("/");
            result = data;

            for(var i=1; i<path.length; i++) {
                var path_item =  path[i];
                if(result[path_item]===undefined || result[path_item]===null) {
                    return default_value;
                }
                result = result[path_item];
            }
            return result;
        }

        result = data[key];
        if(result===undefined || result===null) {
            return default_value;
        }

        return result;
    },

    _setData: function( data, key, value ) {
        if(key[0]!="/") {
            var tg = data;
        } else {
            var path = key.split("/");
            var tg = data;

            for(var i=1; i<path.length; i++) {
                var path_item =  path[i];
                if(i==(path.length-1)) {
                    key = path_item;
                    break;
                }
                if(!tg[path_item]) {
                    tg[path_item] = {};
                }
                tg = tg[path_item];
            }
        }
        if(dojo.isObject(value) && !dojo.isArray(value)) {
            for(var s_key in value) {
                if(!s_key) {
                    tg[key] = value[s_key];
                } else {
                    tg[key+s_key] = value[s_key];
                }
            }
        } else {
            tg[key] = value;
        }
    }
});

dojo.declare("Jet.Form.Field", [], {
    ID: "",
    name: "",
    default_value:"",
    required:false,
    error_messages:{},
    data: null,

    _error_node: null,

    constructor: function(form, ID, name, data) {
        this.form = form;
        this.ID = ID;
        this.name = name;
        this.default_value = data["default_value"];
        this.required = data["required"];
        this.data = data;
    },

    _getElement: function(ID) {
        var element = dijit.byId(ID);
        if(!element) {
            console.error("Form: Unknown element "+this.ID+". Please check your HTML ...");
        }

        return element;
    },

    setValue: function(value) {
        this._getElement(this.ID).set("value", value);
    },

    getValue: function() {
        return this._getElement(this.ID).get("value");
    },

    resetValue: function() {
        this.setValue(this.default_value);
    },

    getIsValid: function() {
        var el = this._getElement(this.ID);

        if(el["isValid"]===undefined) {
            return true;
        }

        if(!el.isValid()) {
            this.showErrorIsNotValid();
            return false;
        }

        return true;
    },

    connectOnChangeAction: function( action ) {
        this.ohChange_handler = dojo.connect( this._getElement(this.ID), "onChange", action );
    },

    disconnectOnChangeAction: function() {
        dojo.disconnect(this.ohChange_handler);
    },

    showError: function(message) {
        this._createErrorNode(message);
        dojo.place(this._error_node, this._getElement(this.ID).domNode,"before" );
    },

    _createErrorNode: function(message) {
        this.form._current_error_messages[this.name] = message;

        if(this._error_node) {
            dojo.destroy(this._error_node);
        }
        this._error_node = dojo.create("div");
        this._error_node.className = this.form.error_css_class;
        this._error_node.innerHTML = message;
    },

    hideError: function() {
        if(this._error_node) {
            delete this.form._current_error_messages[this.name];
            this.form._current_error_messages[this.name] = undefined;

            dojo.destroy(this._error_node);
            this._error_node = null;
        }
    },

    showErrorIsNotValid: function() {
        this.showError(this.data.error_messages["invalid_value"]);
    },

    focus: function() {
        this._getElement(this.ID).focus();

    },

    disable: function() {
        this._getElement(this.ID).set("disabled", true);
    },

    enable: function() {
        this._getElement(this.ID).set("disabled", false);
    }

});

dojo.declare("Jet.Form.Field.Input", [Jet.Form.Field], {});
dojo.declare("Jet.Form.Field.Float", [Jet.Form.Field], {});
dojo.declare("Jet.Form.Field.Int", [Jet.Form.Field], {});
dojo.declare("Jet.Form.Field.MultiSelect", [Jet.Form.Field], {});
dojo.declare("Jet.Form.Field.Select", [Jet.Form.Field], {
    setSelectOptions: function(options) {
        dojo.require("dojo.store.Memory");

        var items = [];
        for(var ID in options) {
            items.push( { id:ID, name:options[ID] } );
        }

        var options = new dojo.store.Memory({data:items});
        this._getElement(this.ID).set("store", options);

    }
});
dojo.declare("Jet.Form.Field.Textarea", [Jet.Form.Field], {});

dojo.declare("Jet.Form.Field.WYSIWYG", [Jet.Form.Field], {
    WYSIWYG_initialized: false,
    connect_onChange: null,

    setValue: function(value) {
        this._initialize();
        Jet_WYSIWYG.setContent(this.ID, value);
    },

    getValue: function() {
        this._initialize();
        return Jet_WYSIWYG.getContent(this.ID);
    },

    getIsValid: function() {
        return true;
    },

    connectOnChangeAction: function( action ) {
        if(this.WYSIWYG_initialized) {
            Jet_WYSIWYG.addOnChange( this.ID, action );
        } else {
            this.connect_onChange = action;
        }
    },

    disconnectOnChangeAction: function() {
    },

    showError: function(message) {
        this._createErrorNode(message);
        dojo.place(this._error_node, dojo.byId(this.ID),"before" );
    },

    disable: function() {
        Jet_WYSIWYG.disable( this.ID );
    },

    enable: function() {
        Jet_WYSIWYG.enable( this.ID );
    },


    _initialize: function()  {
        if(this.WYSIWYG_initialized) {
            return;
        }
        Jet_WYSIWYG.init( this.ID, this.data.editor_config_name, this.connect_onChange );
        this.WYSIWYG_initialized = true;
    }
});

dojo.declare("Jet.Form.Field.Hidden", [Jet.Form.Field], {
    setValue: function(value) {
        var element = dojo.byId(this.ID);
        if(!element) {
            console.error("Form: Unknown element "+this.ID+". Please check your HTML ...");
        }
        element.value = value;
    },

    getValue: function() {
        return dojo.byId(this.ID).value;
    },

    getIsValid: function() {
        return true;
    },

    disable: function() {},
    enable: function() {},
    showError: function(message) {},
    hideError: function() {},
    connectOnChangeAction: function(action) {}
});

dojo.declare("Jet.Form.Field.CheckBoxTree", [Jet.Form.Field], {
    setValue: function(value) {
        this.data.tree_instance.setChecked(value);
    },

    getValue: function() {
        return this.data.tree_instance.getChecked();
    },

    getIsValid: function() {
        return true;
    },

    showError: function(message) {
        this._createErrorNode(message);
        dojo.place(this._error_node, this.data.tree_instance.domNode,"before" );
    },

    hideError: function() {
        dojo.destroy(this._error_node);
        this._error_node = null;
    },
    disable: function() {

	    var traverse = function(node) {
		    node._checkbox.set("disabled", true);
		    node.set("enabled", false);
		    node.set("disabled", true);

		    var children = node.getChildren();
		    for(var i in children) {
			    traverse( children[i] );

		    }
	    }

	    var _tree = this.data.tree_instance;

	    _tree.model.store.fetch({
		    onComplete: function() {
			    traverse( _tree.rootNode);
		    }
	    });
    },

    enable: function() {

	    var traverse = function(node) {
		    node._checkbox.set("disabled", false);
		    node.set("enabled", true);
		    node.set("disabled", false);

		    var children = node.getChildren();
		    for(var i in children) {
			    traverse( children[i] );

		    }
	    }

	    var _tree = this.data.tree_instance;

	    _tree.model.store.fetch({
		    onComplete: function() {
			    traverse( _tree.rootNode);
		    }
	    });
    },

    connectOnChangeAction: function(action) {
        this.ohChange_handler = dojo.connect( this.data.tree_instance.model, "onCheckboxChange", action );
    }
});


dojo.declare("Jet.Form.Field.Checkbox", [Jet.Form.Field], {
    setValue: function(value) {
        this._getElement(this.ID).set("checked", value);
    },

    getValue: function() {
        return this._getElement(this.ID).get("checked");
    }
});
dojo.declare("Jet.Form.Field.Password", [Jet.Form.Field], {
    setValue: function(value) {
        this.resetValue();
    },

    getValue: function() {
        var check_pwd = dijit.byId(this.ID+"_check");
        if(check_pwd) {
            return {
                "": this._getElement(this.ID).get("value"),
                "_check": this._getElement(this.ID+"_check").get("value")
            }
        } else {
            return this._getElement(this.ID).get("value");
        }

    },

    resetValue: function() {
        this._getElement(this.ID).set("value", "");
        var check_pwd = dijit.byId(this.ID+"_check");
        if(check_pwd) {
            check_pwd.set("value", "");
        }
    },

    getIsValid: function() {

        var check_pwd = dijit.byId(this.ID+"_check");
        if(check_pwd) {
            var val = this._getElement(this.ID).get("value");
            var ch_val = this._getElement(this.ID+"_check").get("value");

            if(!val && !ch_val) {
                return true;
            }

            if(val!=ch_val) {
                this.showError(this.data.error_messages["check_not_match"]);
                return false;
            }

            return true;
        } else {
            return true;
        }

    },

    connectOnChangeAction: function(action) {
        this.ohChange_handler = dojo.connect( this._getElement(this.ID), "onChange", action );
        var check_pwd = dijit.byId(this.ID+"_check");
        if(check_pwd) {
            this.ohChange_handler_check_pwd = dojo.connect( check_pwd, "onChange", action );
        } else {
            this.ohChange_handler_check_pwd = null;
        }
    },

    disconnectOnChangeAction: function() {
        dojo.disconnect(this.ohChange_handler);
        if(this.ohChange_handler_check_pwd) {
            dojo.disconnect(this.ohChange_handler_check_pwd);
        }
    },

    disable: function() {
        this._getElement(this.ID).set("disabled", true);
        var check_pwd = dijit.byId(this.ID+"_check");
        if(check_pwd) {
            check_pwd.set("disabled", true);
        }
    },

    enable: function() {
        this._getElement(this.ID).set("disabled", false);
        var check_pwd = dijit.byId(this.ID+"_check");
        if(check_pwd) {
            check_pwd.set("disabled", false);
        }
    }

});

dojo.declare("Jet.Form.Field.Date", [Jet.Form.Field], {
    setValue: function(value) {
        this._getElement(this.ID).set("value", dojo.date.stamp.fromISOString(value));
    },

    getValue: function() {
        var date = this._getElement(this.ID).get("value");
        if(!date) {
            return null;
        }
        return dojo.date.stamp.toISOString(date, {selector:"date"});
    }
});

dojo.declare("Jet.Form.Field.DateTime", [Jet.Form.Field], {
    setValue: function(value) {
        this._getElement(this.ID).set("value", dojo.date.stamp.fromISOString(value));
        this._getElement(this.ID+"_time").set("value", dojo.date.stamp.fromISOString(value, "THH:mm:ss"));
    },

    getValue: function() {
        var date = this._getElement(this.ID).get("value");
        if(!date) {
            return { "": "", "_time":""};
        }
        var time = this._getElement(this.ID+"_time").get("value");
        if(!time) {
            return { "": "", "_time":""};
        }

        return {
            "": dojo.date.stamp.toISOString(date, {selector:"date"}),
            "_time": dojo.date.stamp.toISOString(time, {selector:"time"})
        }
    },

    disable: function() {
        this._getElement(this.ID).set("disabled", true);
        this._getElement(this.ID+"_time").set("disabled", true);
    },

    enable: function() {
        this._getElement(this.ID).set("disabled", false);
        this._getElement(this.ID+"_time").set("disabled", false);
    },

    connectOnChangeAction: function(action) {
        this.ohChange_handler = dojo.connect( this._getElement(this.ID), "onChange", action );
        this.ohChange_handler_time = dojo.connect( this._getElement(this.ID+"_time"), "onChange", action );
    },

    disconnectOnChangeAction: function() {
        dojo.disconnect(this.ohChange_handler);
        dojo.disconnect(this.ohChange_handler_time);
    }
});

dojo.declare("Jet.Form.Field.RadioButton", [Jet.Form.Field], {
    setValue: function(value) {
        for(var key in this.data.select_options) {
            this._getElement(this.ID+"_"+key).set("checked",key==value);
        }
    },

    getValue: function() {
        for(var key in this.data.select_options) {
            if(this._getElement(this.ID+"_"+key).get("checked")) {
                return key;
            }
        }

        return null;
    },

    getIsValid: function() {
        return true;
    },

    connectOnChangeAction: function( action ) {
        this.ohChange_handler = {};
        for(var key in this.data.select_options) {
            this.ohChange_handler[key] = dojo.connect( this._getElement(this.ID+"_"+key), "onChange", action );
        }
    },

    disconnectOnChangeAction: function() {
        for(var key in this.data.select_options) {
            dojo.disconnect(this.ohChange_handler[key]);
        }
    },

    showError: function(message) {
        this._createErrorNode(message);

        for(var key in this.data.select_options) {
            dojo.place(this._error_node, dojo.byId(this.ID+"_"+key),"before" );
            break;
        }
    },

    disable: function() {
        for(var key in this.data.select_options) {
            this._getElement(this.ID+"_"+key).set("disabled",true);
        }
    },

    enable: function() {
        for(var key in this.data.select_options) {
            this._getElement(this.ID+"_"+key).set("disabled",false);
        }
    }
});
