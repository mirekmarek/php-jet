Jet.require('Jet.modules.Module');
Jet.require('Jet.dojoExtensions');

Jet.declare('Jet.module.JetExample\\AdminUIManager.Main', [Jet.modules.Module], {
    module_name: 'JetExample\\AdminUIManager',
    module_label: Jet.translate('Default Admin UI'),

	initialize: function(){
	},

    initializeUI: function(){
    },

    openModule: function(module_name, tab_ID, controller_action, on_load ){

        var tab_container = dijit.byId('_jet_modules_tabs_');

        if(
            !tab_ID ||
            !dijit.byId(tab_ID)
        ) {

            var tab_params = {
                title: Jet.translate('Loading ...'),
                closable: true
            };

            if(tab_ID){
                tab_params['id'] = tab_ID;
            }
            tab_params['style'] = 'padding:0px;margin:0px;';

            var tmp_tab = new dijit.layout.ContentPane(tab_params);
            tab_container.addChild(tmp_tab);
            tab_ID = tmp_tab.id;
        }

        var tab = dijit.byId(tab_ID);
        tab_container.selectChild(tab_ID);

        var module_instance = Jet.modules.getModuleInstance(module_name, tab_ID);
        if(!module_instance){
            console.error('Failed to open module \''+module_name+'\' (tab ID:\''+tab_ID+'\') Module not found?')
            return false;
        }


        var on_load_signal_connection = dojo.connect(tab, 'onLoad', function(){

            dojo.disconnect(on_load_signal_connection);
            tab.focusWidget();
            module_instance.initializeUI();
            tab.set('title', module_instance.module_label);

            if(on_load) {
                on_load(module_instance);
            }

        });


        var destroy_signal_connection = dojo.connect(tab, 'destroy', function(){
            dojo.disconnect(destroy_signal_connection);
            Jet.modules.destroyModuleInstance(module_name, tab_ID);
        });

        if(!controller_action) {
            controller_action = 'default';
        }

        var GET_params = { container_ID: tab_ID };
        var URL = module_instance.getAjaxActionURL( controller_action, [], GET_params);

        tab.set('href', URL);

        return module_instance;
    }
});
