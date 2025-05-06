/**
 * ManiyaTech
 *
 * @author        Milan Maniya
 * @package       ManiyaTech_HomepageBannerSlider
 */

define([
   'underscore',
   'Magento_Ui/js/grid/columns/select'
], function (_, Column) {
   'use strict';

   return Column.extend({
         defaults: {
         bodyTmpl: 'ManiyaTech_HomepageBannerSlider/ui/grid/cells/status'
      },
      getStatusColor: function (row) 
      {
         if (row.status == '1') 
         {
            return 'enabled';
         } else if(row.status == '0') 
         {
            return 'disabled';
         }
         return '#303030';
      }
   });
});