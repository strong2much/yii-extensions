Sortable extension
==========================

Данное расширение — делает возможным сортировку в GridView.

Требования
----------

* Yii 1.1.14 и выше (именно здесь тестировалось)
* YiiBooster 2.0.0 (с включенным 'fontAwesomeCss'=>true)

Установка
---------

* Распаковать в `protected/extensions`.
* Добавить следующее в [конфиг]:

```php
'import'=>array(
    ...
    'ext.sortable.*',
    ...
),
```

* Добавить колонку sorter(integer) в свою таблицу.
* Унаследуйте вашу модель от SortableActiveRecord

```php
class YourModel extends SortableActiveRecord
{

}
```

* Добавьте следующий код в ваш контроллер

```php
public function actions()
{
    return array(
        'move' => array(
            'class' => 'ext.sortable.SortableAction',
        )
    );
}
```

* В своем GridView замените TbButtonColumn на SelectableButtonColumn