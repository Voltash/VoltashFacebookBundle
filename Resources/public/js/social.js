
var fbController = {

    inviteFriends: function (title, text, max) {
        $.post(Routing.generate('fb_app_invite'), {}, function(response) {
            var data = {
                method: 'apprequests',
                title: title,
                message: text,
                exclude_ids: response,
                display: 'iframe',
                max_recipients: max
            };
            FB.ui(data, function (responce) {
            if (responce) {
                $.post(Routing.generate('fb_app_save_invite'), { to: responce.to }, function() {
                    window.location.href = location.search;
                });
            }
            });

        }, 'json');
        return false;
    },

    postToWall: function(title, desc, img, url) {
        var data = {
            method:'feed',
            name: title,
            picture: img,
            link: url,
            //caption:'<b>Samsung Galaxy SIII</b>',
            description: desc,
            display:'iframe'
        };
        FB.ui(data, function (response) {
            if (response) {

            }
        });
        return false;

    },

    postImageToWall: function(title, desc, img, url) {
        var wallPost = {
            message : desc,
            url: img
        };
        FB.api('/me/photos?width=500', 'post', wallPost , function(response) {
            if (!response || response.error) {

            } else {
                $('.wallpost_popup').fadeIn(200);
                setTimeout("$('.wallpost_popup').fadeOut(200)", 5000);
            }
        });
        return false;
    }

};