/**
 * RBAC & Roles
 */
var roles = {

    init: function() {

        $('.roles-view').on('click', function() {
            $('#' + $(this).attr('href').split('#')[1]).next().show();
        });
        
        $('.roles-item').on('click', '.roles-edit', function() {
            $(this).next().toggle();
        });
        
        $('.roles-item').on('click', '.operations-item', function() {
            var $input = $(this).find('input');
            
            $(this)
                .removeClass('allow-true')
                .removeClass('allow-false');  
            
            if ($input.val().length) {
                $input.removeAttr('value');
                $(this).find('.ico').hide();   
                $(this).addClass('allow-false');        
            } else {
                $input.val($(this).data('operation'));
                $(this).find('.ico').show();
                $(this).addClass('allow-true');  
            }
        });
        
        $('.roles-all-deny').click(function() {
            $('.operations-item:not(.allow-false)', $(this).closest('table').find('tbody')).click();
        });
        
        $('.roles-all-allow').click(function() {
            $('.operations-item:not(.allow-true)', $(this).closest('table').find('tbody')).click();
        });
    },
};

$(function () {
    roles.init();
});