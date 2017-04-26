Ext.define('djem.view.formBuilder.Item', {
  extend: 'Ext.panel.Panel',
  alias: ['view.formBuilder.item', 'widget.formBuilder.item'],
  requires: ['djem.view.formBuilder.ItemController'],
  controller: 'formBuilder.item',
  cls: 'canvas',
  layout: { type: 'vbox', align: 'stretch' },
  flex: 6,
  // keyMap: 'onKeyMap',
  config: { currentItem: {} },
  listeners: {
    click: {
      element: 'el',
      fn: function(e) {
        Ext.each(Ext.query('.selected'), function(dom) { Ext.get(dom).removeCls('selected'); });
        var item = Ext.get(e.parentEvent.target);
        if (item.component) {
          // this.setConfig('currentItem', item);
          item.component.addCls('selected');
        }
      }
    }
  }
});