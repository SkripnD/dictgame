<?php if(Yii::app()->user->hasFlash('alert')):?>
    <?php echo Yii::app()->user->getFlash('alert')?>
<?php endif?>