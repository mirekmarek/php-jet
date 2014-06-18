dojo.require('dijit.form.SimpleTextarea');
dojo.require('dojox.grid.EnhancedGrid');
dojo.require('dojox.grid.enhanced.plugins.Pagination');
dojo.require('dojo.data.ObjectStore');
dojo.require('dijit.Tree');
dojo.require('dojo.data.ItemFileWriteStore');

Jet.require('Jet.modules.Module');
Jet.require('Jet.Form');
Jet.require('Jet.Trash');
Jet.require('Jet.EditArea');

Jet.declare('Jet.module.JetExample.AdminRoles.Main', [Jet.modules.Module], {
    module_name: 'JetExample.AdminRoles',
    module_label: Jet.translate('Roles'),

    form: null,
    trash: null,
    grid: null,
    edit_area: null,

    privileges_scope: null,


    initializeUI: function(){
        this._initDialog();
        this._initForm();
        this._initGrid();
        this._initTrash();
        this._initSignals();
    },

    _initDialog: function() {
        this.edit_area = new Jet.EditArea(this, 'role_edit');
    },
    _initForm: function() {
        var _this = this;
        var form_definition = this.getData('role_form_fields_definition');

        this.privileges_scope = this.getData('role_privileges_scope');

        for(var privilege in this.privileges_scope) {
            var privilege_data = this.privileges_scope[privilege];

            form_definition['/privileges/'+privilege+'/values'].type='CheckBoxTree';
            form_definition['/privileges/'+privilege+'/values'].tree_instance = this.getCheckboxTree('role_access_'+privilege_data.privilege+'_pane', 'privilege_values_scope/'+privilege, privilege_data.label);
        }

        this.form = new Jet.Form(
                this,
                'role',
                form_definition,
                {
                    save_button:'role_save',
                    afterAdd: function(response_data) {
                        dojo.publish(_this.module_name+'/new');
                        _this.edit(response_data.ID);
                    },
                    afterUpdate: function(data) {
                        _this.edit_area.setTitle(data.name);
                        dojo.publish(_this.module_name+'/updated');
                    },
                    beforeEdit: function( data ) {
                        _this.edit_area.open(Jet.translate('Loading ...'));
                    },
                    onEdit: function( data ) {
                        _this.edit_area.open(data.name);
                    },
                    onNew: function() {
                        _this.edit_area.open( Jet.translate('New Role') );
                    }
                } );
    },
    _initGrid: function() {
        this.grid = this.getDataGrid( 'roles_grid', this.form.store, 'edit' );
    },
    _initTrash: function() {
        var _this = this;
        this.trash = new Jet.Trash(this, 'role_trash', this.form.store, {
            source_widget_grid: this.grid,
            itemAvatarCreator: function(item) {
                return '<div>'+item['name']+'</div>';
            },
            afterDelete: function() {
                dojo.publish(_this.module_name+'/deleted');
            }
        });
    },
    _initSignals: function() {
        var _this = this;
	    this.addSignalCallback(this.module_name+'/new', function(){ _this.reloadGrid(); });
	    this.addSignalCallback(this.module_name+'/updated', function(){ _this.reloadGrid(); });
	    this.addSignalCallback(this.module_name+'/deleted', function(){ _this.reloadGrid(); });
    },


    reloadGrid: function() {
        this.grid.reload();
    },

    add: function(ID) {
        this.form.new();
    },

    edit: function(ID) {
        this.form.edit(ID);
    },

    save: function() {
        this.form.save();
    },

    close: function() {
        var _this = this;

        this.form.cancelCheck(
            Jet.translate('Warning!'),
            Jet.translate('Role is not saved! Do you really want to exit?'),
            function() {
                _this.edit_area.close();
            }
        );
    }
});
