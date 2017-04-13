Ext.define('djem.view.formBuilder.FormBuilder', {
  extend: 'Ext.panel.Panel',
  alias: ['widget.djem.formBuilder'],
  requires: ['djem.view.formBuilder.FormBuilderController'],
  controller: 'FormBuilderController',
  items: [{

    layout: { type: 'hbox', align: 'stretch' },
    flex: 1,
    items: [
      {
        layout: { type: 'vbox', align: 'stretch' },
        flex: 1,
        items: [{ xtype: 'formBuilder.component' }, { xtype: 'formBuilder.property' }]
      },
      { xtype: 'formBuilder.item' }
    ]
  }],
  listeners: { click: { element: 'el', fn: 'clickItem' } }
  // listeners: { click: function(e) { console.log('SELECTUNG'); } }

  // listeners: { 'selected': { element: 'el', fn: function() { console.log('SELECTUNG'); } } }

  // init: function() {
  //   var me = this;

  // }

});