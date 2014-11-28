<a href="<?php echo url('', array_merge(pagesParams(), ['order' => $field, 'ordermode' => $forcemode]))?>" class="<?php echo param(pagesParams(), 'order') == $field ? 'active' : ''?>">

    <?php echo $caption?>   

</a>