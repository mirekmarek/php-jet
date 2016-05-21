dojo.require('dojo.store.JsonRest');

dojo.declare('Jet.JsonRestTreeStore', [dojo.store.JsonRest], {

    target: '',
    children_arg: 'children',

    constructor: function( target, options ) {

        this.target = target;

        if(options) {
            dojo.mixin( this, options );
        }
    },

    mayHaveChildren: function(item) {
        return item[this.children_arg] ? true:false;
    },
    getChildren: function(item, onComplete, onError){
        if(!item[this.children_arg]) {
            onComplete();
            return;
        }

        var _this = this;

        if( item[this.children_arg].push!==undefined ) {
            onComplete(item[this.children_arg]);
        } else {
            this.get( item[this.identifier_arg] ).then( function(branch){

                item[_this.children_arg] = branch.items[0][_this.children_arg];
                onComplete(item.children);

            }, function(error){
                Jet.handleRequestError( error );

                onComplete([]);
            });
        }


    },
    getRoot: function(onItem, onError){
        var _this = this;

        this.get('').then( function( item ) {
            _this.identifier_arg = item.identifier;
            _this.label_arg = item.label;

            for(var i=0;i<item.items.length;i++) {
                if(i==0) {
                    _this.root = item.items[i];
                }
                onItem( item.items[i] );
            }

        }, function( error ) {
            Jet.handleRequestError( error );
            onError( error );
        } );
    },
    getLabel: function( item ){
        return item[this.label_arg];
    },
    getIdentity: function( item ){
        return item[this.identifier_arg];
    }

});