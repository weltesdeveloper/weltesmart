 // setup the dialog
$("#dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 200,
        closeOnEscape: false,
        draggable: false,
        resizable: false,
        buttons: {
                'Yes, Keep Working': function(){
                        $(this).dialog('close');
                },
                'No, Logoff': function(){
                        // fire whatever the configured onTimeout callback is.
                        // using .call(this) keeps the default behavior of "this" being the warning
                        // element (the dialog in this case) inside the callback.
                        $.idleTimeout.options.onTimeout.call(this);
                }
        }
});

// cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
var $countdown = $("#dialog-countdown");
var idleValue = $('#idle-value').val();
// start the idle timer plugin
$.idleTimeout('#dialog', 'div.ui-dialog-buttonpane button:first', {
        idleAfter: idleValue,
        pollingInterval: 2,
        keepAliveURL: 'keepalive.php',
        serverResponseEquals: 'OK',
        onTimeout: function(){
                window.location = "../index.php";
        },
        onIdle: function(){
                $(this).dialog("open");
        },
        onCountdown: function(counter){
                $countdown.html(counter); // update the counter
        }
});
