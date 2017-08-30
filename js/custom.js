jQuery(document).ready(function($){
    $('#get_leiki_data').on('click', function () {
        var $post_url = $('#wp-leiki-api_blog_post_url').val();

        var data = {
            'action': 'wp_leiki_api_action',
            'post_url': $post_url,
            'method': 'iabtier2'
        };

        $.post(ajax_object.ajax_url, data, function(response) {
            $('#wp-leiki-api_leiki_preview_content_iabtier2').val(response);

            var data = {
                'action': 'wp_leiki_api_action',
                'post_url': $post_url,
                'method': 'focus100k'
            };

            $.post(ajax_object.ajax_url, data, function(response) {
                $('#wp-leiki-api_leiki_preview_content_focus100k').val(response);
            });
        });

    });
});