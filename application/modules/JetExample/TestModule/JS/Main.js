dojo.require('dijit.ColorPalette');

Jet.require('Jet.modules.Module');
Jet.provide('Jet.module.TestModule');

Jet.require('Jet.Form');
Jet.require('Jet.Trash');


Jet.declare('Jet.module.JetExample\\TestModule.Main', [Jet.modules.Module], {
    module_name: 'JetExample\\TestModule',
    module_label: Jet.translate('Test Admin Module'),

    form: null,

	initialize: function(){
	},

    initializeUI: function(){
        this.form = new Jet.Form(this, 'test', this.getData('test_form_fields_definition'), {save_button:[]} );
        this.form.new();

        this.tree = this.getTree( 'test_tree_area', 'pages_tree', true );
        this.tree.onDblClick = function(item) {alert('DblClick: '+item.ID);};
        this.tree.onClick = function(item) {alert('Click: '+item.ID);};

        this.trash = new Jet.Trash(this, 'tree_trash', this.form.store, {
            source_widget_tree: this.tree
        });
        this.trash.itemAvatarCreator = function(item) {
            return '<div>'+item['name']+'</div>';
        }


    },

    formTest_getData: function() {
        console.debug( this.form.getData() );
    },

    formTest_showErrors: function() {
        for(var fn in this.form.fields ) {
            this.form.fields[fn].showError('Test error: '+fn);
        }
    },

    formTest_hideErrors: function() {
        for(var fn in this.form.fields ) {
            this.form.fields[fn].hideError();
        }
    },

    formTest_enable: function() {
        this.form.enable();
    },

    formTest_disable: function() {
        this.form.disable();
    },

    treeTest_reload: function() {
        this.tree.reload();
    },

    treeTest_expand: function() {
        this.tree.expandAll();
    },

    treeTest_collapse: function() {
        this.tree.collapseAll();
    },

    treeTest_openByID: function() {
        this.tree.openByID('page_1_3_2_1:site_1:en_US');
    }
});
