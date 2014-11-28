/**
 * Global settings/functions
 */
var global = {

    init: function() {
        this.settings();
        this.binding();
    },

    settings: function() {

        $.ajaxSetup({
            type: "POST",
            dataType: "json",
        });
        
        $(document).ajaxSend(function(event, jqXHR, settings) {
            NProgress.start();
        });
        
        $(document).ajaxComplete(function(event, jqXHR, settings) {
            NProgress.done();
        });
        
        $(document).ajaxError(function(event, jqxhr, settings, exception) {
            console.log(jqxhr.responseText);
        });
        
        // Adding a csrf-token in the request
        $.ajaxPrefilter(function(options, originalOptions, jqXHR) {
            if ((originalOptions.type !== undefined && 
                 originalOptions.type.toLowerCase() == 'post') ||
                (options.type !== undefined && options.type.toLowerCase() == 'post')) {
                var data = originalOptions.data;

                if (originalOptions.data !== undefined) {
                    if (Object.prototype.toString.call(originalOptions.data) === '[object String]') {
                        data = $.deparam(originalOptions.data);
                    }
                } else {
                    data = {};
                }

                try {
                    options.data = $.param($.extend(data, {
                        'csrf-token': $('meta[name="csrf-token"]').attr('content')
                    }));
                    
                } catch (e) {

                }
            }
        });
    },

    binding: function() {
    
        $('.bs-sidebar').on('click', 'a', function() {
            if ($(this).next('ul').length) {
                var $ul = $(this).next('.nav');
                
                $('.bs-sidebar li .nav').hide();
                $ul.toggle();
            }
        });

        $('.date').datetimepicker({
            autoclose: true,
            minuteStep: 5,
            minView: 2,
            language: 'ru',
        });
        
        $('.datetime').datetimepicker({
            autoclose: true,
            minuteStep: 5,
            language: 'ru',
        });
        
        $('.tooltip').tooltip();
        
        // Auto hide elements
        if ($('.autohide').length) {
            setTimeout(function() {
                $('.autohide').slideUp(300, function() {
                    $(this).remove();
                });
            }, 3000);
        }
        
        // Manual multi sorting
        if ($('.multisort').length) {
            $('.multisort').sortable({
                opacity: 0.8,
                cursor: 'move',

                update: function(event, ui) {
                    if ($(this).data('multisort')) {
                        $.post('/admin/' + $(this).data('multisort') + '/operations', {
                            ids: $(this).sortable("toArray"),
                            multisort: true
                        });
                    }
                }

            }).disableSelection();
        }
        
        // Autofocus
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]:first, .autofocus:first').focus();
        });
        
        /** Cloning
            Usage: 
            <div class="clone-block">
                <div class="clone-item">Item</div>
                <a class="clone">Add</a>
            </div>
        */
        $('.clone-block').on('click', '.clone', function() {
            var $block = $(this).closest('.clone-block');
            $block
                .find('.clone-item:eq(0)')
                .clone().show()
                .insertAfter($block.find('.clone-item:last'));
        });
        
        /** Remove item
            Usage: 
            <ul>
                <li>
                    Item<a class="remove" data-remove-closest="li">Del</a>
                </li>
            </ul>
        */
        $(document).on('click', '.remove', function() {
            $(this).closest($(this).data('remove-closest')).remove();
        });
        
        // Disable "enter" in modal
        $('.modal.void input').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
            }
        });
    },
};

$(function () {
    global.init();
});

/**
 * Russian translation for bootstrap-datepicker
 * Victor Taranenko <darwin@snowdale.com>
 */
;(function($) {
    $.fn.datetimepicker.dates['ru'] = {
    	days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
    	daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб", "Вск"],
    	daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
    	months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    	monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
    	today: "Сегодня",
    	suffix: [],
    	meridiem: []
    };
}(jQuery));

/**
 * An extraction of the deparam method from Ben Alman's jQuery BBQ
 * https://github.com/chrissrogers/jquery-deparam
 */
(function(h){h.deparam=function(i,j){var d={},k={"true":!0,"false":!1,"null":null};h.each(i.replace(/\+/g," ").split("&"),function(i,l){var m;var a=l.split("="),c=decodeURIComponent(a[0]),g=d,f=0,b=c.split("]["),e=b.length-1;/\[/.test(b[0])&&/\]$/.test(b[e])?(b[e]=b[e].replace(/\]$/,""),b=b.shift().split("[").concat(b),e=b.length-1):e=0;if(2===a.length)if(a=decodeURIComponent(a[1]),j&&(a=a&&!isNaN(a)?+a:"undefined"===a?void 0:void 0!==k[a]?k[a]:a),e)for(;f<=e;f++)c=""===b[f]?g.length:b[f],m=g[c]=
f<e?g[c]||(b[f+1]&&isNaN(b[f+1])?{}:[]):a,g=m;else h.isArray(d[c])?d[c].push(a):d[c]=void 0!==d[c]?[d[c],a]:a;else c&&(d[c]=j?void 0:"")});return d}})(jQuery);