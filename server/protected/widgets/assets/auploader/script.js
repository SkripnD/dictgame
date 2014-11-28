var auploader = {

    TEXT_CROP: '<span class="text-warning">Включен режим кропа. Вы можете обрезать изображение перед сохранением</span>',
    
    init: function () {        
        $('.auploader .uploader').on('click', '.remove', function () {
            $(this)
                .closest('.auploader')
                .find('.auploader-input')
                .val('')
                .keyup();
        });
        
        $('.auploader').each(function () {
            $(document).on('auploader-trigger-' + $(this).data('owner-type'), function (e, data) {
                $('.' + e.type).find('.auploader-input').val(data).keyup();
            });
        });
        
        $('.auploader').on('click', '.watermark', function () {
            if ($(this).data('set')) {
                $(this).data('set', false).text('НЕ добавлять водяной знак');
                $(this).closest('.uploader').find('#Files_watermark').val(0);
            } else {
                $(this).data('set', true).text('Добавлять водяной знак');
                $(this).closest('.uploader').find('#Files_watermark').val(1);
            }
        });
        
        $(document).on('click', '.auploader .files-edit', function () {
            var $fileInfo = $(this).closest('li');
            var title = $fileInfo.find('.files-title').text();
            var id = $fileInfo.data('id');
            
            $('#modal-files-edit #id').val(id);
            $('#modal-files-edit #title').val($.trim(title));
        });
        
        $('#modal-files-edit').on('click', 'input:submit', function () {
            var $modal = $(this).closest('.modal');
            
            $('.auploader .files #' + $modal.find('#id').val() + ' .files-title')
                .text($modal.find('#title').val());
                
            $modal.modal('hide');
        });
        
        $(document).on('keyup', '.auploader-input', this.preview);
        $('.auploader-input').keyup();

        this.reinitialize();
    },
    
    reinitialize: function () {
        $('.auploader .uploader').each(function () {
            $(this).fileapi({
                url: $(this).data('url'),
                multiple: $(this).data('multiple'),
                autoUpload: $(this).data('crop') ? false : $(this).data('auto'),
                accept: $(this).data('accept'),
                imageSize: $(this).data('image-size'),
                duplicate: true,
                elements: {
                    size: '.js-size',
                    active: {
                        show: '.js-upload',
                        hide: '.js-browse'
                    },
                    progress: '.js-progress'
                },

                onSelect: auploader.select,
                onFileComplete: auploader.complete,
            });
        });
    },

    select: function (evt, data) {
        var file = data.files[0];

        $(this).find('input, select').each(function (){
            $(this).fileapi('option', $(this).attr('name'), $(this).val());
        });

        if (!auploader.validationFile(this, evt, data)) {
            return false;
        }
        
        if ($(this).data('crop')) {
            auploader.cropper($(this), file); 
        } 
    },
    
    cropper: function ($obj, file)
    { 
        $obj.data('cropper', true);
        
        $auploader = $obj.closest('.auploader');
        $control   = $obj.find('.cropper-control');
        $desc      = $obj.find('blockquote small');
        $preview   = $auploader.find('.preview');
        $remove    = $auploader.find('.remove');
        
        var oldText = $desc.html();
        
        $desc.html(auploader.TEXT_CROP);
        $preview.hide();
        $remove.hide();
        
        $obj
            .find('.cropper')
            .remove()
            .end()
            .append('<div class="cropper"></div>');
        
        $('.cropper-finish, .cropper-reset').on('click', function () {
            if ($(this).hasClass('cropper-finish')) {
                $obj.fileapi('upload');
            }
            
            $obj.find('.cropper').remove();
            $obj.data('cropper', false);
            
            $preview.show();
            $remove.show();
            $desc.html(oldText);
            $control.hide();
        });
                       
        $('.cropper').cropper({
            file: file,
        	bgColor: '#fff',
        	maxSize: [700, 700],
        	minSize: [300, 300],
        	aspectRatio: 0,
        	onSelect: function (coords) {
        	    $obj.fileapi('crop', file, coords);
        	}
        });
        
        $control.show();
    },

    validationFile: function (obj, evt, data)
    {
        $(obj).next('.alert').remove();
        
        if (data.other.length) {
            var errors = data.other[0].errors;
    
            if (errors) {
                var rules = evt.widget.options.imageSize;
                var opt = evt.widget.options;
                var error = '';
    
                $.each(errors, function (errorType, val) {
                    switch (errorType) {
                    case 'maxSize':
                        error = error + 'Превыше макс. размер файла<br />';
                        break;
                    case 'maxFiles':
                        error = error + 'Макс. кол-во файлов ' + opt.maxFiles + '<br />';
                        break;
                    case 'minWidth':
                        error = error + 'Мин. ширина ' + rules.minWidth + 'px<br />';
                        break;
                    case 'minHeight':
                        error = error + 'Мин. высота ' + rules.minHeight + 'px<br />';
                        break;
                    case 'maxWidth':
                        error = error + 'Макс. ширина ' + rules.maxWidth + 'px<br />';
                        break;
                    case 'maxHeight':
                        error = error + 'Макс. высота ' + rules.maxHeight + 'px<br />';
                        break;
                    }
                });
    
                auploader.alert(obj, error);
                
                return false;
            }
        }
        
        return true;
    },

    alert: function (element, text) {
        $('<div style="display:none" />')
            .addClass('alert alert-danger')
            .html(text)
            .insertAfter($(element))
            .fadeIn(100);
    },

    complete: function (evt, uiEvt) {
        var data = uiEvt.result;

        if (data.error) {
            auploader.alert(this, data.error);
        } else {
            if (trigger = $(this).data('trigger')) {
                $(document).trigger(trigger, data);
            } else {
                auploader.addList(this, data);
            }
        }
    },

    addList: function (element, data) {
        $(element).closest('.auploader').find('.files').append(data);
    },
    
    preview: function () {        
        var tag = $(this).closest('.auploader').find('.uploader').data('preview-tag');
        if (tag.length) {
            var $preview = $(this).next('.preview');
            $preview.empty();
            
            if ($(this).val().length) {
                $preview.html(tag.replace('{file}', $(this).val()));
            }
        }
    }
}

$(function () {
    auploader.init();
});