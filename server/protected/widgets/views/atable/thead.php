<?php echo CHtml::beginForm([$this->operationsAction], 'post', ['class' => 'widget-atable-form form'])?>

    <div class="panel panel-default">
        <div class="panel-body panel-table" <?php echo !Yii::app()->getController()->pages->itemCount ? 'style="display:none"' : ''?>>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                <tr>
                
                    <?php if(Yii::app()->getController()->pages->itemCount):?>
                      
                    <?php if(isset($this->primary) && count($this->operations)):?>
                    <th width="20px"><input type="checkbox"></th>
                    <?php endif?>
                    
                    <?php foreach($this->fields as $field => $caption):?>
                        <th>
                            <?php if(in_array($field, $this->sortExclude)):?>
                                <?php echo $caption['label']?>
                            <?php else:?>
                                <?php echo $this->sort(['field' => $caption['fieldSource'], 'caption' => $caption['label']])?>
                            <?php endif?>
                        </th>
                    <?php endforeach?>
                    
                    <?php if($this->pos):?>
                    <th width="100px">
                        <?php echo $this->sortForce(['field' => 'pos', 'caption' => 'Позиция', 'forcemode' => 'desc'])?>
                    </th>
                    <?php endif?>
                    
                    <?php if($this->linktoview && isset($this->primary)):?>
                    <th width="30px"></th>
                    <?php endif?>
                    
                    <?php endif?>
                  
                </tr>
                </thead>
                <tbody>