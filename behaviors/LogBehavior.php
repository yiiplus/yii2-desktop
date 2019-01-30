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

namespace yiiplus\desktop\behaviors;

use Yii;
use yii\base\Application;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yiiplus\desktop\models\Log as DesktopLog;

/**
 * logbehavior
 *
 * @author liguangquan <liguangquan@163.com>
 * @since 2.0.0
 */
class LogBehavior extends Behavior
{
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'handle'
        ];
    }
    public function handle()
    {
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'log']);
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_INSERT, [$this, 'log']);
        Event::on(ActiveRecord::className(), ActiveRecord::EVENT_AFTER_DELETE, [$this, 'log']);
    }

    public function log($event)
    {
        if($event->sender instanceof DesktopLog || !$event->sender->primaryKey()) {
            return;
        }
        if ($event->name == ActiveRecord::EVENT_AFTER_INSERT) {
            $description = Yii::t('yiiplus/desktop', '%s新增了表%s %s:%s的%s');
        } elseif($event->name == ActiveRecord::EVENT_AFTER_UPDATE) {
            $description = Yii::t('yiiplus/desktop', '%s修改了表%s %s:%s的%s');
        } else {
            $description = Yii::t('yiiplus/desktop', '%s删除了表%s %s:%s的%s');
        }
        if (!empty($event->changedAttributes)) {
            $desc = '';
            foreach($event->changedAttributes as $name => $value) {
                $desc .= $name . ' : ' . $value . '=>' . $event->sender->getAttribute($name) . ',';
            }
            $desc = substr($desc, 0, -1);
        } else {
            $desc = '';
        }
        $userName = Yii::$app->user->identity->username;
        $tableName = $event->sender->tableSchema->name;
        $description = sprintf($description, $userName, $tableName, $event->sender->primaryKey()[0], is_array($event->sender->getPrimaryKey()) ? current($event->sender->getPrimaryKey()) : $event->sender->getPrimaryKey(), $desc);

        $route = Url::to();
        $userId = Yii::$app->user->id;
        $ip = ip2long(Yii::$app->request->userIP);
        $data = [
            'route' => $route,
            'description' => $description,
            'user_id' => $userId,
            'ip' => $ip
        ];
        $model = new DesktopLog();
        $model->setAttributes($data);
        $model->save();
    }
}