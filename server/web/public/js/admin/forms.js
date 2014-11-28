/**
 * Submitting AJAX Forms
 * Usage: <form class="form">
 */
var forms = {

    ALERT_WARNING: 'warning',
    ALERT_DANGER:  'danger',
    ALERT_SUCCESS: 'success',
    ALERT_INFO:    'info',

    init: function() {
    
        $('.form').on('click', ':submit', function() {
            $(this).addClass('submitted');
        });

        $('.form').each(function() {
            var $form = $(this);

            $(this).ajaxForm({
            
                beforeSubmit: function(formData, form, options) {
                    $(form)
                        .find(':submit')
                        .prop('disabled', true)
                        .end()
                        .find('.submitted')
                        .button('loading')
                        .end()
                        .find('.alert')
                        .remove();

                    forms.clearErrors(form);

                    return true;
                },

                complete: function() {
                    $form.find('.submitted').button('reset');

                    $form
                        .find(':submit')
                        .prop('disabled', false)
                        .removeClass('submitted');
                },

                error: function() {
                    forms.callout(
                        $form,
                        'Извините, попробуйте позже',
                        forms.ALERT_DANGER,
                        'Критическая ошибка');
                },

                success: function(data, statusText, xhr, $form) {
                    if (data.redirect) {
                        document.location.href = data.redirect;
                    }
                    
                    if (data.reload) {
                        location.reload(true);
                    }

                    if (data.errors) {
                        if(data.model) {
                            forms.showModelErrors($form, data.model, data.errors);
                        } else {
                            forms.showErrors($form, data.errors);
                        }
                    }
                },
            });
        });
        
        $('form').on('click focus keydown', '.has-error', function() {
            $(this)
                .removeClass('has-error')
                .tooltip('destroy');
                
            var $form        = $(this).closest('.form');
            var $formWarning = $form.find('.forms-callout');
            var errorCount   = $('form .has-error').size();
            
            if (errorCount === 0) {
                $formWarning.one(
                    'webkitAnimationEnd mozAnimationEnd oAnimationEnd animationEnd', 
                    function() { 
                        $formWarning.slideUp(300, function() {
                            $(this).remove();
                        });
                    }
                );
                
                $formWarning.addClass('animated fadeOutUp');  
                
            } else {
                $formWarning
                    .find('.forms-error-count')
                    .text(errorCount);
            }
        });
        
        this.autoErrors();
    },
    
    autoErrors: function() {
        $('.auto-errors').each(function() {
            var $form = $(this).closest('form');
            
            $('.auto-errors li').each(function() {
                var field = $(this).data('model') ? 
                    $(this).data('model') + '_' + $(this).data('field') : 
                    $(this).data('field');
                    
                forms.showError(
                    $form, 
                    field,
                    $(this).text()
                );
            }); 
        });
    },
    
    showError: function($form, field, text) {
        var $field  = $form.find('#' + field);
        var trigger = $field.is(':focus') ? 'show' : 'hide';
            
        $field
            .closest('.form-group')
            .prop('title', text)
            .addClass('has-error')
            .tooltip({
                trigger: 'hover manual',
                placement: 'bottom'
            }).tooltip(trigger);
    },
    
    showModelErrors: function($form, model, errors) {
        $.each(errors, function(field, text) {
            var field = model + '_' + field;
            forms.showError($form, field, text);
        });
        
        forms.callout(
            $form,
            'Пожалуйста, исправьте ошибки!',
            forms.ALERT_WARNING,
            'Найдено ошибок: <span class="forms-error-count">' + _.size(errors) + '</span>');
    },
    
    
    showErrors: function($form, errors) {
        $.each(errors, function(field, text) {
            forms.showError($form, field, text);
        });
        
        forms.callout(
            $form,
            'Пожалуйста, исправьте ошибки!',
            forms.ALERT_WARNING,
            'Найдено ошибок: <span class="forms-error-count">' + _.size(errors) + '</span>');
    },

    clearErrors: function(form) {
        $(form)
            .find('.has-error')
            .removeClass('has-error')
            .tooltip('destroy');
    },
    
    alert: function($element, header, text, type) {
        if ($.isArray(text))
            text = '<ul>' + _.map(text, function(item) { 
                return '<li>' + item + '</li>'; 
            }).join('') + '</ul>';
            
        header = header ? '<strong>' + header + '</strong><br />' : '';
        type = type ? type : this.ALERT_DANGER;
            
        $element.next('.alert').remove();
        $('<div class="alert alert-' + type + ' animated fadeInDown" />')
            .html('<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>')
            .append(header)
            .append(text)
            .insertAfter($element);
    },

    callout: function($form, text, type, description) {
        $form.find('.forms-callout').remove();

        $('<div style="display:none" class="forms-callout" />')
            .addClass('bs-callout animated fadeInUp bs-callout-' + (type ? type : forms.ALERT_WARNING))
            .html('<h4>' + text + '</h4><p>' + description + '</p>')
            .insertBefore($form.find('.controls'))
            .fadeIn(100);
    }
};

$(function () {
    forms.init();
});
