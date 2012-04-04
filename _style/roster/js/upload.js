$(document).ready(function() { 
    $('#photoimg').live('change', function() { 
        $("#preview").html('');
        $("#preview").html('<div class="loader">&nbsp;</div>');
        $("#form").ajaxForm({
            target: '#preview',
            url: 'image/'
        }).submit();
    });
}); 
