<?php
/**
 *
 * @package  App.components
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */
class SortableButtonColumn extends TbButtonColumn
{
    /**
     * @var integer min sorter value
     */
    public $minSorter;

    /**
     * @var integer max sorter value
     */
    public $maxSorter;

    /**
     * @var array the HTML options for the data cell tags.
     */
    public $htmlOptions=array('class'=>'button-column','style'=>'width:80px;text-align:right;');

    /**
     * @var string the template that is used to render the content in each data cell.
     */
    public $template='{up} {down} {view} {update} {delete}';

    /**
     * @var string the up button icon (defaults to 'arrow-up').
     */
    public $upButtonIcon = 'arrow-up';
    /**
     * @var string the down button icon (defaults to 'arrow-down').
     */
    public $downButtonIcon = 'arrow-down';

    /**
     * @var string url for the up button.
     */
    public $upButtonUrl='Yii::app()->controller->createUrl("move",array("id"=>$data->primaryKey,"direction"=>"up"))';
    /**
     * @var string url for the down button.
     */
    public $downButtonUrl='Yii::app()->controller->createUrl("move",array("id"=>$data->primaryKey,"direction"=>"down"))';

    /**
     * @var string the label for the up button. Defaults to "Move up".
     */
    public $upButtonLabel;
    /**
     * @var string the label for the down button. Defaults to "Move down".
     */
    public $downButtonLabel;

    /**
     * @var array the HTML options for the up button tag.
     */
    public $upButtonOptions=array('class'=>'up');
    /**
     * @var array the HTML options for the down button tag.
     */
    public $downButtonOptions=array('class'=>'down');

    /**
     *### .initDefaultButtons()
     *
     * Initializes the default buttons (view, update and delete).
     */
    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();

        if($this->upButtonLabel===null)
            $this->upButtonLabel=Helper::t('Move up');
        if($this->downButtonLabel===null)
            $this->downButtonLabel=Helper::t('Move down');

        foreach(array('up','down') as $id)
        {
            $buttonClick = <<<EOD
function() {
    $.ajax({
		url: $(this).attr('href'),
		type: "GET",
		success: function(){
			$("#{$this->grid->id}").yiiGridView('update');
		},
		error: function(XHR) {
			alert(XHR.responseText);
		}
	});
	return false;
}
EOD;
            $button=array(
                'label'=>$this->{$id.'ButtonLabel'},
                'url'=>$this->{$id.'ButtonUrl'},
                'icon'=>$this->{$id.'ButtonIcon'},
                'options'=>$this->{$id.'ButtonOptions'},
                'click'=>$buttonClick,
            );

            if(isset($this->buttons[$id]))
                $this->buttons[$id]=array_merge($button,$this->buttons[$id]);
            else
                $this->buttons[$id]=$button;
        }

        if(isset($this->minSorter))
            $this->buttons['up']['visible'] = '$data->sorter > '.$this->minSorter;
        if(isset($this->maxSorter))
            $this->buttons['down']['visible'] = '$data->sorter < '.$this->maxSorter;
    }
}