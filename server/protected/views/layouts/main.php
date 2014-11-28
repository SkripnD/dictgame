<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <title><?php echo e(strip_tags($this->pageTitle)) . ' / ' . Yii::app()->name?></title>
    <meta name="description" content="<?php echo e($this->description)?>" />
    <meta name="keywords" content="<?php echo e($this->keywords)?>" />
    
</head>
<body>

    <?php echo $content?>
                             
</body>
</html>