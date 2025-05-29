    $(document).ready(function() {
        $('#toggleLink').click(function(event) {
            event.preventDefault();
            $('#myImage').toggle();
            if ($('#myImage').is(':visible')) {
                 $('#toggleLink').html('<b>❌ Close Image</b>');
                 $('.tcvs-check').css('display', 'block')
             } else {
                 $('#toggleLink').html('<a href="#" id="toggleLink"><b>🪙 How to Read a Treasury Check</a>');
            }
        });
     });