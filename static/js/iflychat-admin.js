
jQuery(document).ready(function(){
    jQuery('[name = "iflychat_show_popup_chat"]').change(function() {
        if (jQuery('[name = "iflychat_show_popup_chat"]').val() == '3' || jQuery('[name = "iflychat_show_popup_chat"]').val() == '4') {
            jQuery('.textBox').fadeIn();

        }
        else {
            jQuery('.textBox').fadeOut();
        }
    });jQuery('[name = "iflychat_show_popup_chat"]').change();
});