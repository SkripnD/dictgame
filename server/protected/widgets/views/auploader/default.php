<div class="auploader auploader-trigger-<?php echo $this->uid?> form-group js-fileapi-wrapper" data-owner-type="<?php echo $this->uid?>">

    <div class="uploader" 
        data-url="<?php echo $this->url?>" <?php echo $this->trigger !== null ? 'data-trigger="'.$this->trigger.'"' : ''?> 
        data-accept="<?php echo isset($this->rules['mimetypes']) ? implode(', ', $this->rules['mimetypes']) : null?>" 
        data-preview-tag='<?php echo $this->previewTag?>'
        data-crop="<?php echo $this->crop?>"
        data-multiple="<?php echo $this->multiple?>" data-auto="<?php echo $this->auto?>"
        data-image-size='<?php echo isset($this->rules['imagesizes']) ? json_encode($this->rules['imagesizes']) : null?>'>
    
        <div class="form-inline">
        
            <?php echo CHtml::hiddenField('csrf-token', Yii::app()->request->csrfToken)?>
            
            <?php if($this->watermark):?>
                <?php echo CHtml::hiddenField('Files[watermark]', 0)?>   
            <?php endif?>  
            
            <?php if($this->inputTitle):?>
            <div class="form-group">
                <?php echo CHtml::textField('Files[title]', null,
                           ['class' => 'form-control', 
                            'placeholder' => 'Введите название'])?>                  
            </div>
            <?php endif?>
            
            <div class="form-group">  
                <?php if($this->label && !empty($this->labelText)):?>
                    <?php echo CHtml::label($this->labelText, $this->id, ['class' => 'control-label'])?><br />
                <?php endif?>
                <span class="js-browse btn btn-default" id="<?php echo $this->id?>">
                    <i class="glyphicon glyphicon-upload"></i>
                    <input type="file" name="filedata">
                </span>
                <?php if($this->crop):?>
                <a href="#_" class="cropper-control cropper-finish btn btn-warning">Сохранить</a>
                <a href="#_" class="cropper-control cropper-reset btn btn-danger">Отменить</a>
                <?php endif?>
                <?php if(!empty($this->value) && $this->showRemove):?>
                <span class="btn btn btn-default remove"><span class="glyphicon glyphicon-remove"></span></span>
                <?php endif?>
            </div>
            
        </div>
        
        <blockquote>
            <small>
                <?php echo $this->description?>
                <?php if($this->watermark):?>
                <a href="#_" class="psevdo watermark" data-set="false">Не добавлять водяной знак</a>
                <?php endif?>
            </small>
        </blockquote>      
        
        <div class="js-upload">
            <div class="progress progress-success">
    
                <div class="bar progress">
                    <div class="js-progress progress-bar progress-bar-success" role="progressbar">
                       <span class="sr-only">Загрузка…</span>
                    </div>
                </div>
             
            </div>
            <span class="text-info">Загружено (<span class="js-size"></span>)</span>
        </div>
        
    </div>
        
    <?php if($this->files !== null):?>
    <ul class="files list-unstyled multisort" data-multisort="files">
        <?php foreach($this->files as $file):?>
            <?php echo $this->getController()->renderPartial(
                      '/files/templates/gallery', 
                       ['model' => $file]
                  )?>
        <?php endforeach?>
    </ul>
    <?php endif?>
    
    <?php echo CHtml::hiddenField($this->name, $this->value, ['class' => 'auploader-input'])?>    
    <div class="preview"></div>
    
</div>