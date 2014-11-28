<?php if(Yii::app()->user->hasFlash('success')):?>
    <?php echo Yii::app()->user->getFlash('success')?>
    
<?php elseif(Yii::app()->user->hasFlash('errors')): 
      $errors = Yii::app()->user->getFlash('errors');?>
    <?php if(is_array($errors)):?>
        <?php foreach($errors as $field => $error):?>
            <?php foreach($error as $text):?>
                <?php echo $text?><br />
            <?php endforeach?>
        <?php endforeach?>
    <?php else:?>
        <?php echo $errors?>
    <?php endif?>
    
<?php elseif(isset($model) && $model->hasErrors()):?>
    <?php echo CHtml::errorSummary($model, '')?>
<?php endif?>