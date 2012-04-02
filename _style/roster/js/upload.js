$(document).ready(function() { 
    $('#photoimg').live('change', function() { 
        $("#preview").html('');
        $("#preview").html('<div class="loader">&nbsp;</div>');
        $("#imageform").ajaxForm({
            target: '#preview'
        }).submit();
    });
}); 
