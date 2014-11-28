<strong>Вход через «<?php echo $social->title?>»</strong><br />

<?php echo CHtml::beginForm('', 'post')?>

<label>Укажите свой email</label>
<?php echo CHtml::textField('email', $email, ['placeholder' => 'Введите email'])?> 
<button type="submit" name="submit">Войти</button>

<?php echo CHtml::endForm()?>

<i><?php echo $this->renderPartial('/shared/flash')?></i>