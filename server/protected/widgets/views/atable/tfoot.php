                </tbody>
            </table>
        </div>
    </div>
    
    <div class="panel-footer">
        <small>
            Всего
            <strong>
                <?php echo Yii::app()->numberFormatter->formatDecimal(Yii::app()->getController()->pages->itemCount)?>
                <?php echo Yii::t('app', 'запись | записи | записей', Yii::app()->getController()->pages->itemCount)?></strong>
        </small>
    </div>
    
</div>