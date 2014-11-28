    <div class="operations">
        <?php if(in_array('delete', $this->operations)):?>
        <a data-toggle="modal" href="#modal-delete" class="disabled btn btn-danger btn-xs" data-loading-text="Подождите...">
            удалить
        </a>
        <?php endif?>
        <?php if(in_array('setactive', $this->operations)):?>
        <button type="submit" name="setactive" class="disabled btn btn-success btn-xs" data-loading-text="Подождите...">
            сделать активными
        </button>
        <?php endif?>
        <?php if(in_array('setinvisible', $this->operations)):?>
        <button type="submit" name="setinvisible" class="disabled btn btn-warning btn-xs" data-loading-text="Подождите...">
            сделать неактивными
        </button><br />
        <?php endif?>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="modal-delete" tabindex="-1" role="dialog" aria-labelledby="modal-delete-label" aria-hidden="true">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                   <h4 class="modal-title">Внимание!</h4>
               </div>
               <div class="modal-body">
                   Вы действительно хотите удалить отмеченные записи?
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                  <button type="submit" name="delete-selected" class="btn btn-danger" data-loading-text="Подождите...">Удалить</button>
               </div>
           </div>
       </div>
    </div>

</form>