<?php
/**
 * SortableAction
 *
 * @package  App.components
 * @author   Denis Tatarnikov <tatarnikovda@gmail.com>
 */
class SortableAction extends CAction
{
    /**
     * Model's class name
     * @var string
     */
    private $_modelClass;

    /**
     * The currently loaded data model instance.
     * @var CActiveRecord
     */
    private $_model;

    /**
     * The main action that handles the file upload request.
     */
    public function run( )
    {
        if(isset($_GET['id']) && isset($_GET['direction'])) {
            $direction = isset($_GET['direction']) ? $_GET['direction'] : '';

            $model = $this->loadModel($_GET['id']);

            if($model && ($direction == 'up' || $direction == 'down')) {
                $sorter = $model->sorter;

                if($direction == 'up') {
                    if($sorter > 1) {
                        $sql = 'UPDATE '.$model->tableName().' SET sorter="'.$sorter.'" WHERE sorter < "'.($sorter).'" ORDER BY sorter DESC LIMIT 1';
                        Yii::app()->db->createCommand($sql)->execute();
                        $model->sorter--;
                        $model->save(false);
                    }
                } elseif($direction == 'down') {
                    $maxSorter = Yii::app()->db->createCommand()
                        ->select('MAX(sorter) as maxSorter')
                        ->from($model->tableName())
                        ->queryScalar();

                    if($sorter < $maxSorter){
                        $sql = 'UPDATE '.$model->tableName().' SET sorter="'.$sorter.'" WHERE sorter > "'.($sorter).'" ORDER BY sorter ASC LIMIT 1';
                        Yii::app()->db->createCommand($sql)->execute();
                        $model->sorter++;
                        $model->save(false);
                    }
                }
            }
        }

        if(!Yii::app()->request->isAjaxRequest){
            $this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable or specified id.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param integer $id model record id
     * @throws CHttpException
     *
     * @return CActiveRecord active model record
     */
    protected function loadModel($id = null)
    {
        if ($this->_model === null) {
            $id = $id ? $id : $_GET['id'];
            if ($id !== null) {
                $this->_model = call_user_func(array($this->getModelClass(), 'model'))->findByPk($id);
            }
            if ($this->_model === null) {
                throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
            }
        }
        return $this->_model;
    }

    /**
     * Returns controller's model's class name
     *
     * @return string model's class name
     */
    public function getModelClass()
    {
        if (!isset($this->_modelClass)) {
            $class = get_class($this->controller);
            $this->_modelClass = preg_replace('/Controller/', '', $class);
        }
        return $this->_modelClass;
    }

}