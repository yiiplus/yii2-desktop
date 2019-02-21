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

namespace yiiplus\desktop\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yiiplus\desktop\models\BizRule as MBizRule;
use yiiplus\desktop\components\RouteRule;
use yiiplus\desktop\components\Configs;

/**
 * Description of BizRule
 */
class BizRule extends Model
{
    /**
     * @var string name of the rule
     */
    public $name;

    /**
     * Returns the validation rules for attributes.
     *
     * @return array validation rules
     */
    public function rules()
    {
        return [
            [['name'], 'safe']
        ];
    }

    /**
     * Returns the list of all attribute names of the model.
     *
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('yiiplus/desktop', '名称'),
        ];
    }

    /**
     * Search BizRule
     * 
     * @param array $params 搜索条件
     * 
     * @return \yii\data\ActiveDataProvider|\yii\data\ArrayDataProvider
     */
    public function search($params)
    {
        /* @var \yii\rbac\Manager $authManager */
        $authManager = Configs::authManager();
        $models = [];
        $included = !($this->load($params) && $this->validate() && trim($this->name) !== '');
        foreach ($authManager->getRules() as $name => $item) {
            if ($name != RouteRule::RULE_NAME && ($included || stripos($item->name, $this->name) !== false)) {
                $models[$name] = new MBizRule($item);
            }
        }

        return new ArrayDataProvider([
            'allModels' => $models,
        ]);
    }
}
