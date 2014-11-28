<a href="<?php echo url('', array_merge(pagesParams(), ['order' => $field, 'ordermode' => param(pagesParams(), 'order') == $field && param(pagesParams(), 'ordermode') == 'asc' ? 'desc' : 'asc']))?>" class="<?php echo param(pagesParams(), 'order') == $field ? 'active' : ''?>">

    <?php echo $caption?>
    
    <?php if(param(pagesParams(), 'order') == $field):?>
    <span class="glyphicon glyphicon-arrow-<?php echo param(pagesParams(), 'ordermode') == 'desc' ? 'down' : 'up'?>"></span>
    <?php endif?>
    
</a>