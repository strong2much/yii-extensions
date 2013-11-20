<?php
/**
 * ActiveRecord with sorter column.
 * Sortable column should be named as 'sorter'!!!
 *
 * @package  App.components
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */
class SortableActiveRecord extends CActiveRecord
{
    /**
     * Returns the default named scope that should be implicitly applied to all queries for this model.
     */
    public function defaultScope()
    {
        return CMap::mergeArray(
            parent::defaultScope(),
            array(
                'order'=>"sorter ASC",
            )
        );
    }

    /**
     * This method is invoked before saving a record (after validation, if any).
     *
     * @return boolean whether the saving should be executed. Defaults to true.
     */
    protected function beforeSave()
    {
        if($this->isNewRecord) {
            $maxSorter = Yii::app()->db->createCommand()
                ->select('MAX(sorter) as maxSorter')
                ->from($this->tableName())
                ->queryScalar();
            $this->sorter = $maxSorter+1;
        }

        return parent::beforeSave();
    }

    /**
     * This method is invoked after deleting a record.
     */
    protected function afterDelete()
    {
        parent::afterDelete();

        if(isset($this->sorter)) {
            $ids = Yii::app()->db->createCommand()
                ->select('id')
                ->from($this->tableName())
                ->order('sorter ASC')
                ->queryColumn();
            $i = 1;
            foreach($ids as $id){
                Yii::app()->db->createCommand()
                    ->update($this->tableName(),array('sorter'=>':sorter'),'id=:id',array(':sorter'=>$i,':id'=>$id));
                $i++;
            }
        }
    }

    /**
     * @return integer Max sorter value
     */
    public static function getMaxSorter()
    {
        $class = get_called_class();
        $model = new $class;
        $maxSorter = Yii::app()->db->createCommand()
            ->select('MAX(sorter) as maxSorter')
            ->from($model->tableName())
            ->queryScalar();

        return $maxSorter;
    }

    /**
     * @return integer Min sorter value
     */
    public static function getMinSorter()
    {
        $class = get_called_class();
        $model = new $class;
        $minSorter = Yii::app()->db->createCommand()
            ->select('MIN(sorter) as minSorter')
            ->from($model->tableName())
            ->queryScalar();

        return $minSorter;
    }
}