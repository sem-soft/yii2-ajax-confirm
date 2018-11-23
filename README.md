# Submit button widget with ajax asking for confirm displaying
## Install by composer
composer require sem-soft/yii2-ajax-confirm
## Or add this code into require section of your composer.json and then call composer update in console
"sem-soft/yii2-ajax-confirm": "~1.0"
## Description
Implements the logic of submit button widget with server-side confirmation message or without them.
Used when additional data verification is required on the server side, and not just a standard confirmation of the action.
## Basic usage
Example of configuration in view:
```php
    <?php $form = ActiveForm::begin([
        'id' => 'report_import_form',
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
        'enableAjaxValidation' => false,
    ]); ?>
        ...
        <?= $form->errorSummary(($model), [
            'class' => 'alert alert-error'
        ]); ?>
        ...
         <?= SubmitButtonWidget::widget([
             'form' => $form,
             'confirmRoute' => ['exists'],
             'content' => 'Загрузить',
             'options' => [
                 'class' => 'btn btn-success btn-block',
            ]
         ]);?>
    <?php ActiveForm::end(); ?>
```
Example of action for check:
```php
  ...
  public function actionExists()
 {
     Yii::$app->response->format = Response::FORMAT_JSON;

     ...

     $form = new FinanceReportImportForm();

     ...

     if ($existReport = FinanceReport::findOne([
         'field1' => $form->field1,
         'field2' => $form->field2
     ])) {
         $question = "Отчет по {$existReport->field1} кварталу {$existReport->field2} отчетного года уже существует и будет перезаписан. Продолжить импорт?";
     } else {
         $question = "Выполнить импорт?";
     }

     ...

     return [
         'can' => $existReport ? false : true,
         'question' => $question
     ];
 }
...
```