<div class="row">
     
    <?php if($this->showButtonAdd):?>
    <div class="col-md-1">
        <a href="<?php echo url(Url::withoutAction().'/edit')?>" class="btn btn-default btn-sm">Добавить</a>
    </div>
    <?php endif?>
    
    <?php if($this->showSearch):?>
        <?php echo CHtml::beginForm(url('', pagesParams(null, ['search', 'searchParam'])), 'get')?>
        <div class="col-md-5">
            <div class="input-group">
            
                <?php if($this->searchAutocomplete === false):?>
                    <?php echo CHtml::textField('search', param(pagesParams(), 'search', ''),
                               ['class' => 'form-control input-sm',
                                'placeholder' => $this->searchPlaceholder])?>
                <?php else:?>
                    <?php $this->widget('zii.widgets.jui.CJuiAutoComplete', [
                        'name' => 'search',
                        'value' => param(pagesParams(), 'search', ''),
                        'source' => "js:function(request, response) {
                            $.post('".$this->searchAutocompleteUrl."', {
                                term: request.term, param: $('#searchParam').val()
                            }, response);
                        }",
                        'htmlOptions' => [
                            'class' => 'form-control input-sm',
                            'placeholder' => $this->searchPlaceholder
                        ],
                    ])?>
                <?php endif?>
                                 
                <div class="input-group-btn">
                               
                    <?php if(count($this->searchParams)):?> 
                    <a class="btn btn-default btn-sm dropdown-toggle search-param-header" data-toggle="dropdown">
                        <span class="title">
                            <?php echo param(pagesParams(), 'searchParam') ?
                                       $this->searchParams[pagesParams('searchParam')] :
                                       $this->searchParams[key($this->searchParams)]?>
                        </span>
                        <span class="caret"></span>
                    </a>
                    <?php endif?>
                    
                    <button class="btn btn-default btn-sm" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                    
                    <?php if(count($this->filters)):?>
                    <a class="btn btn-default btn-sm <?php echo param(pagesParams(), 'filter') ? 'active' : ''?>" href="<?php echo url('', array_merge(pagesParams(null, $this->filtersNames), ['filter' => param(pagesParams(), 'filter') ? 0 : 1]))?>">
                        <span class="glyphicon glyphicon-filter"></span>
                    </a>
                    <?php endif?>
                    
                    <?php if(count($this->searchParams)):?> 
                    <ul class="dropdown-menu search-params">
                        <?php foreach($this->searchParams as $pId => $pTitle):?>
                        <li><a href="#_" data-param="<?php echo $pId?>"><?php echo $pTitle?></a></li>
                        <?php endforeach?>
                    </ul>
                    <?php reset($this->searchParams);
                          echo CHtml::hiddenField('searchParam', 
                               param(pagesParams(), 'searchParam') ?: key($this->searchParams))?>
                    <?php endif?>
                                    
                </div>
                          
            </div>
        </div>
        <?php echo CHtml::endForm()?>  
    <?php endif?>
    
    <div class="col-md-6">
    <?php foreach($this->filtersButton as $name => $filter):
          $active = param(pagesParams(), $name, null);
          $globalParams = pagesParams();
  
          unset($globalParams[$name]);?>
          
        <div class="btn-group btn-group-sm pull-right" style="padding-left: 10px">
            <a href="<?php echo url('', array_merge($globalParams))?>" class="btn btn-default <?php echo $active === null ? 'active' : ''?>">Все</a>
            <?php foreach($filter as $title => $params):?>
            <a href="<?php echo url('', array_merge($globalParams, $params))?>" class="btn btn-default <?php echo $active.'_' == $params[$name].'_' ? 'active' : ''?>"><?php echo e($title)?></a>
            <?php endforeach?>
        </div>
    
    <?php endforeach?>
    </div>
    
</div>

<?php if(count($this->filters) && param(pagesParams(), 'filter')):?>

<?php echo CHtml::beginForm(url('', pagesParams(null, array_merge($this->filtersNames, ['filter']))), 'get')?>
           
    <?php echo CHtml::hiddenField('filter', true)?>
    
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body panel-table">
                    <?php foreach($this->filters as $name => $filter):?>
                    <div class="col-xs-3" style="padding-top: 5px">
                    <?php echo CHtml::dropDownList($name, param(pagesParams(), $name),
                                                   $filter['data'], 
                                                   ['class' => 'form-control small', 
                                                    'empty' => $filter['empty'],
                                                    'onchange' => 'this.form.submit()'])?>
                    </div>
                    <?php endforeach?>
                 </div>
            </div>
        </div>
    </div>

<?php echo CHtml::endForm()?>  

<?php endif?>