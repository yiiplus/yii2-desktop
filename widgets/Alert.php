<?php
/**
 * yiiplus/yii2-desktop
 *
 * @category  PHP
 * @package   Yii2
 * @copyright 2018-2019 YiiPlus Ltd
 * @license   https://github.com/yiiplus/yii2-desktop/licence.txt Apache 2.0
 * @link      http://www.yiiplus.com
 */

namespace yiiplus\desktop\widgets;

use yii\bootstrap\Alert as BootstrapAlert;
use yii\bootstrap\Widget;

/**
 * Alert
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class Alert extends Widget
{
    // /**
    //  * @var array the alert types configuration for the flash messages.
    //  * This array is setup as $key => $value, where:
    //  * - $key is the name of the session flash variable
    //  * - $value is the array:
    //  *       - class of alert type (i.e. danger, success, info, warning)
    //  *       - icon for alert AdminLTE
    //  */
    // public $alertTypes = [
    //     'error' => [
    //         'class' => 'alert-danger',
    //         'icon' => '<i class="icon fa fa-ban"></i>',
    //     ],
    //     'danger' => [
    //         'class' => 'alert-danger',
    //         'icon' => '<i class="icon fa fa-ban"></i>',
    //     ],
    //     'success' => [
    //         'class' => 'alert-success',
    //         'icon' => '<i class="icon fa fa-check"></i>',
    //     ],
    //     'info' => [
    //         'class' => 'alert-info',
    //         'icon' => '<i class="icon fa fa-info"></i>',
    //     ],
    //     'warning' => [
    //         'class' => 'alert-warning',
    //         'icon' => '<i class="icon fa fa-warning"></i>',
    //     ],
    // ];

    // /**
    //  * @var array the options for rendering the close button tag.
    //  */
    // public $closeButton = [];


    // /**
    //  * @var boolean whether to removed flash messages during AJAX requests
    //  */
    // public $isAjaxRemoveFlash = true;
    
    // /**
    //  * Initializes the widget.
    //  * This method will register the bootstrap asset bundle. If you override this method,
    //  * make sure you call the parent implementation first.
    //  */
    // public function init()
    // {
    //     parent::init();

    //     $session = \Yii::$app->getSession();
    //     $flashes = $session->getAllFlashes();
    //     $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

    //     foreach ($flashes as $type => $data) {
    //         if (isset($this->alertTypes[$type])) {
    //             $data = (array) $data;
    //             foreach ($data as $message) {

    //                 $this->options['class'] = $this->alertTypes[$type]['class'] . $appendCss;
    //                 $this->options['id'] = $this->getId() . '-' . $type;

    //                 echo BootstrapAlert::widget([
    //                         'body' => $this->alertTypes[$type]['icon'] . $message,
    //                         'closeButton' => $this->closeButton,
    //                         'options' => $this->options,
    //                     ]);
    //             }
    //             if ($this->isAjaxRemoveFlash && !\Yii::$app->request->isAjax) {
    //                 $session->removeFlash($type);
    //             }
    //         }
    //     }
    // }

    public $alertTypes = [
        'success' => 1,
        'error' => 0
    ];

    public function init()
    {
        parent::init();

        $session = \Yii::$app->session;
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;
                foreach ($data as $i => $message) {
                    $this->view->registerJs(<<<js
$.modal.{$type}('{$message}');
js
                    );
                }
                $session->removeFlash($type);
            }
        }


    }
}
