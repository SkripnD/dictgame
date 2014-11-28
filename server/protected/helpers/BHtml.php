<?php

/**
 * Helper for easy integration with Bootstrap v3
 */
class BHtml
{
    public static function __callStatic($name, $arguments)
    {
        $label = '';
        
        if (is_object($arguments[0])) {
            $label = '
            <label class="control-label" for="'.CHtml::activeId($arguments[0], $arguments[1]).'">
                '.$arguments[0]->getAttributeLabel($arguments[1]).'
            </label><br />';
        }
        
        return
        '<div class="form-group">
            '.$label.'
            '.call_user_func_array(['CHtml', $name], $arguments).'
        </div>';
    }
    
    /**
     * Displays a summary of validation errors for one or several models.
     * @param mixed $model the models whose input errors are to be displayed. This can be either
     * a single model or an array of models.
     * @param string $header a piece of HTML code that appears in front of the errors
     * @param string $footer a piece of HTML code that appears at the end of the errors
     * @param array $htmlOptions additional HTML attributes to be rendered in the container div tag.
     * A special option named 'firstError' is recognized, which when set true, will
     * make the error summary to show only the first error message of each attribute.
     * If this is not set or is false, all error messages will be displayed.
     * This option has been available since version 1.1.3.
     * @return string the error summary. Empty if no errors are found.
     * @see CModel::getErrors
     * @see errorSummaryCss
     */
    public static function errorSummary($model, $header = null, $footer = null, $htmlOptions = [])
    {
        $content = '';
        
        if ($model->hasErrors()) {
            $content .= '<ul class="auto-errors" style="display: none">';
            
            foreach ($model->getErrors() as $field => $errors) {
                foreach ($errors as $error) {
                    if ($error != '') {
                        $content .= '<li data-model="'.get_class($model).'" data-field="'.$field.'">'.$error.'</li>';
                    }
                }
            }
            
            $content .= '</ul>';
        }
        
        $content .= CHtml::errorSummary($model, $header, $footer, $htmlOptions);
        
        return $content;
    }
    
    /**
     * Generates a drop down list for a model attribute.
     * If the attribute has input error, the input field's CSS class will
     * be appended with {@link errorCss}.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @param string $link link label
     * @see CHtml::activeDropDownList
     */
    public static function activeDropDownList($model, $attribute, $data, $htmlOptions = [], $link = null)
    {
        $label = $model->getAttributeLabel($attribute);
        $label = $link ? CHtml::link($label, $link) : $label;
        
        return
        '<div class="form-group">
            <label class="control-label" for="'.CHtml::activeId($model, $attribute).'">
                '.$label.'
            </label>
            '.CHtml::activeDropDownList($model, $attribute, $data, $htmlOptions).'
        </div>';
    }

    /**
     * Generates a text field input for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see CHtml::activeCheckBox
     */
    public static function activeCheckBox($model, $attribute, $htmlOptions = [])
    {
        return
        '<div class="checkbox">
            <label class="control-label" for="'.CHtml::activeId($model, $attribute).'">
                '.CHtml::activeCheckBox($model, $attribute, $htmlOptions).'
                '.$model->getAttributeLabel($attribute).'
            </label>
        </div>';
    }
    
    /**
     * Generates a text field input for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see CHtml::activeTextField
     */
    public static function activeDateField($model, $attribute, $htmlOptions = [])
    {
        return
        '<div class="form-group" style="width: 200px">
            <label class="control-label" for="'.CHtml::activeId($model, $attribute).'">
                '.$model->getAttributeLabel($attribute).'
            </label>
            <div class="input-group">
                '.CHtml::activeTextField($model, $attribute, $htmlOptions).'
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
            </div>
        </div>';
    }
}
