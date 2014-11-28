<tr data-id="<?php echo $row->{$this->primary}?>">
    
    <?php if(isset($this->primary) && count($this->operations)):?>
    <td><?php echo CHtml::checkBox('id[]', false, ['value' => $row->{$this->primary}])?></td>
    <?php endif;?>
    
    <?php $i = 0; foreach($this->fields as $field => $value): $i++;
                  $fieldValue = e(strip_tags(ATable::getAttribute($row, $field, $value)))?>
    
    <td>

        <?php if(isset($this->statuses[$field][$fieldValue])):?>
        <span class="label label-<?php echo $this->statuses[$field][$fieldValue]['class']?>">
            <?php echo $this->statuses[$field][$fieldValue]['title']?>
        </span>
        <?php else:?>
        
        <?php if($i == 1 && !is_object($value['closure'])):?>
        <a class="link" href="<?php echo url(Url::withoutAction().'/edit', ['id' => $row->{$this->primary}])?>">
            <?php echo $fieldValue?>
        </a>
        <?php else:?>
        
            <?php if(is_object($value['closure'])):?>
                <!-- closure -->
                <?php echo $value['closure']->__invoke($row)?>

            <?php elseif($fieldValue == null):?>
                <!-- null value -->
            <?php elseif(Date::validateDateTime($fieldValue)):?>
                <!-- date -->
                <?php echo Format::date($fieldValue)?>
                
            <?php elseif(validate('CUrlValidator', $fieldValue)):?>
                <!-- url -->
                <?php echo CHtml::link($fieldValue, $fieldValue)?>
                
            <?php elseif(validate('CEmailValidator', $fieldValue)):?>  
                <!-- email -->  
                <?php echo CHtml::mailto($fieldValue, $fieldValue)?>
            
        <?php else:?>
            <!-- text -->
            <?php echo $fieldValue?>
            
        <?php endif?>
        
        <?php endif?>
            
        <?php endif?>
             
    </td>
    
    <?php endforeach?>
    
    <?php if($this->pos):?>
    <td class="pos">
        <?php if(param(pagesParams(), 'order') == 'pos'):?>
        <a data-mode="up" href="<?php echo url($this->operationsAction)?>">
            <span class="glyphicon glyphicon-circle-arrow-up"></span></a>
        <a data-mode="down" href="<?php echo url($this->operationsAction)?>">
            <span class="glyphicon glyphicon-circle-arrow-down"></span></a>
        <?php endif?>
    </td>
    <?php endif?>
    
    <?php if($this->linktoview && isset($this->primary)):?>
    <td>
        <a href="<?php echo e(str_replace('{id}', $row->{$this->primary}, $this->linktoview))?>">
            <span class="glyphicon glyphicon-search">&nbsp;</span>
        </a>
    </td>
    <?php endif?>
        
</tr>