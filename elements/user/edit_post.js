function editPost(id) {
    jQuery(function ($) {
        $.ajax({
            method: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'edit-post',
                nonce: myAjax.ajaxNonce,
                id: id,
            },
            success: function(response) {
                window.location.href = response;
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
};
