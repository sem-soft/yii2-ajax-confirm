<?php
/**
 * Файл класса SubmitButtonAsset.php
 *
 * @author Samsonov Vladimir <vs@chulakov.ru>
 */

namespace sem\confirm;

use yii\web\AssetBundle;

class SubmitButtonAsset extends AssetBundle
{

    /**
     * @var array
     */
    public $css = [];

    /**
     * @var array
     */
    public $js = [
        'js/script.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
    }
}