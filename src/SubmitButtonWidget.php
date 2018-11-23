<?php
/**
 * Файл класса SubmitButtonWidget.php
 *
 * @author Samsonov Vladimir <vs@chulakov.ru>
 */

namespace sem\confirm;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Реализует логику работы виджета кнопки отпарвки формы с подтверждением.
 * Используется при необходимости дополнительной проверка данных на стороне сервера, а не просто стандартное подтверждение действия.
 * Использует JS-событие Yii2 формы beforeSubmit и ajax-ом запрашивает необходимость демонстрации подтверждения действия пользователю,
 * прерывая отправку формы до подтверждения (если оно необходимо).
 *
 * Пример конфигурации виджета в представлении:
 * ```php
 *      <?= SubmitButtonWidget::widget([
 *          'form' => $form,
 *          'confirmRoute' => ['exists'],
 *          'content' => 'Загрузить',
 *          'options' => [
 *              'class' => 'btn btn-success btn-block',
 *          ]
 *      ]);?>
 * ```
 * Пример метода-действи контроллера:
 * ```php
 *   ...
 *   public function actionExists()
 *  {
 *      Yii::$app->response->format = Response::FORMAT_JSON;
 *
 *      ...
 *
 *      $form = new FinanceReportImportForm();
 *
 *      ...
 *
 *      if ($existReport = FinanceReport::findOne([
 *          'field1' => $form->field1,
 *          'field2' => $form->field2
 *      ])) {
 *          $question = "Отчет по {$existReport->field1} кварталу {$existReport->field2} отчетного года уже существует и будет перезаписан. Продолжить импорт?";
 *      } else {
 *          $question = "Выполнить импорт?";
 *      }
 *
 *      ...
 *
 *      return [
 *          'can' => $existReport ? false : true,
 *          'question' => $question
 *      ];
 *  }
 * ...
 * ```
 */
class SubmitButtonWidget extends Widget
{

    /**
     * @var ActiveForm
     */
    public $form;

    /**
     * @see Html::submitButton()
     * @var string
     */
    public $content = 'Submit';

    /**
     * @see Html::submitButton()
     * @var array
     */
    public $options = [];

    /**
     * @var string|array|null маршрут для действия проверки необходимости отображения подтверждения.
     */
    public $confirmRoute;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!$this->form || !$this->form instanceof ActiveForm) {
            throw new InvalidConfigException("Объект формы (yii\widgets\ActiveForm) 'form' должен быть задан");
        }

    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!empty($this->form->options['class'])) {
            $this->form->options['class'] .= ' ajax-confirmation';
        } else {
            $this->form->options['class'] = 'ajax-confirmation';
        }

        $this->form->options['data-confirm_url'] = Url::to(
            $this->confirmRoute ? $this->confirmRoute : $this->form->action
        );

        SubmitButtonAsset::register($this->getView());

        return Html::submitButton($this->content, $this->options);
    }

}