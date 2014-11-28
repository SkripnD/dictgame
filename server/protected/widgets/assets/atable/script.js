$(function()
{
    $('.widget-atable .search-params').on('click', 'a', function ()
    {
        $('.widget-atable .search-param-header .title').text($(this).text());
        $('.widget-atable #searchParam').val($(this).data('param'));
        $('.widget-atable #search').prop('placeholder', 'Поиск ' + $(this).text().toLowerCase());
    });

    $('.widget-atable tbody').on('click', 'tr td', function ()
    {
        if (!$(this).find('input').length) {
        
            var $link = $(this).closest('tr').find('a.link');
            
            if ($link.length) {
                $(this).off('click');
                $link[0].click();
            }
        }
    });
    
    $('.widget-atable tbody').on('click', '.pos a', function()
    {
        var mode = $(this).data('mode');
        var id   = $(this).closest('tr').data('id');
        
        $.post($(this).attr('href'), { move: true, mode: mode, id: id }, function (data) {
            location.reload(true);
        });
        
        return false;
    });
    
    $('body').on('click', '.widget-atable tbody td input:checkbox', function ()
    {
        if ($(this).is(':checked')) {
            $(this).closest('tr').addClass('active');
        } else {
            $(this).closest('tr').removeClass('active');
        }
        
        if ($('.widget-atable td input:checked').size()) {
            $('a, button', '.operations').removeClass('disabled');
        } else {
            $('a, button', '.operations').addClass('disabled');
        }
    });

    $('body').on('click', '.widget-atable thead th input:checkbox', function ()
    {
        if ($(this).is(':checked'))
        {
            $('a, button', '.operations').removeClass('disabled');
            $(this)
                .closest('table')
                .find('tbody input:checkbox')
                .prop('checked', true)
                .closest('tr')
                .addClass('active');
        }
        else
        {
            $('a, button', '.operations').addClass('disabled');
            $(this)
                .closest('table')
                .find('tbody input:checkbox')
                .prop('checked', false)
                .closest('tr')
                .removeClass('active');
        }
    });
});