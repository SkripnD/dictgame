<?php $this->beginClip('modal-files-edit')?>
<!-- Modal -->
<div class="modal fade" id="modal-files-edit" tabindex="-1" role="dialog" aria-labelledby="modal-files-edit-label" aria-hidden="true">
   <div class="modal-dialog">
       <?php echo CHtml::beginForm('/admin/files/edit', 'post', ['class' => 'form'])?>
       <div class="modal-content">
           <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
               <h4 class="modal-title">Редактирование файла</h4>
           </div>
           <div class="modal-body">
               <?php echo CHtml::hiddenField('id')?>    
               <div class="form-group">
                   <?php echo CHtml::textField('title', null, [
                              'class' => 'form-control autofocus',
                              'placeholder' => 'Введите название'])?>
               </div>
           </div>
           <div class="modal-footer">
              <input type="submit" class="btn btn-info" value="Сохранить" />
              <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
           </div>
       </div>
       <?php echo CHtml::endForm()?>
   </div>
</div>
<?php $this->endClip()?>