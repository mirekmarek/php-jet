dojo.require('dijit.form.SimpleTextarea');
dojo.require('dijit.form.MultiSelect');
dojo.require('dojox.grid.EnhancedGrid');
dojo.require('dojox.grid.enhanced.plugins.Pagination');
dojo.require('dojo.data.ObjectStore');

Jet.require('Jet.modules.Module');
Jet.require('Jet.Form');
Jet.require('Jet.Trash');
Jet.require('Jet.EditArea');

Jet.declare('Jet.module.JetExample.AdminUsers.Main', [Jet.modules.Module], {
    module_name: 'JetExample.AdminUsers',
    module_label: Jet.translate('Users'),

    form: null,
    trash: null,
    grid: null,
    edit_area: null,



    initializeUI: function(){
        this._initDialog();
        this._initForm();
        this._initGrid();
        this._initTrash();
        this._initSignals();
    },
    _initDialog: function() {
        this.edit_area = new Jet.EditArea(this, 'user_edit');
    },
    _initForm: function() {
        var _this = this;

        this.form = new Jet.Form(
            this,
            'user',
            this.getData('user_form_fields_definition'), {
                save_button:'user_save',
                afterAdd: function(response_data) {
                    dojo.publish(_this.module_name+'/new');
                    _this.edit(response_data.ID);
                },
                afterUpdate: function(data) {
                    _this.edit_area.setTitle(data.login);
                    dojo.publish(_this.module_name+'/updated');
                },
                beforeEdit: function( data ) {
                    _this.edit_area.open(Jet.translate('Loading ...'));
                },
                onEdit: function( data ) {
                    _this.edit_area.open(data.login);
                },
                onNew: function() {
                    _this.edit_area.open( Jet.translate('New User') );
                }
            } );
    },
    _initGrid: function() {
        var _this = this;

        this.grid = this.getDataGrid( 'users_grid', this.form.store, 'edit' );
    },
    _initTrash: function() {
        var _this = this;

        this.trash = new Jet.Trash(this, 'user_trash', this.form.store, {
            source_widget_grid: this.grid,
            itemAvatarCreator: function(item) {
                return '<div>'+item['login']+'</div>';
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
            Jet.translate('User is not saved! Do you really want to exit?'),
            function() {
                _this.edit_area.close();
            }
        );
    }

});
