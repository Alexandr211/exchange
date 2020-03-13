///* global console*/
$(document).ready(function() {
    "use strict";
    // 10 sec interval проверяется не в консоли а во вкладке Сеть, т.к. PJAX
    setInterval(function(){
        $('#reload_rates').click();
    }, 10000);

    setInterval(function(){
        $('#reload_orders').click();
    }, 10000);
 //   console.log('test');
});