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
use yii\data\ActiveDataProvider;
use yiiplus\desktop\models\Menu as MenuModel;

/**
 * Menu represents the model behind the search form about [[\yiiplus\desktop\models\Menu]].
 */
class Menu extends MenuModel
{

    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'parent', 'order'], 'integer'],
            [['name', 'route', 'parent_name'], 'safe'],
        ];
    }

    /**
     * 场景
     *
     * @return Model
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * 搜索
     *
     * @param  array $params
     *
     * @return \yii\data\ActiveDataProvider
     */
    public function search($params)
    {
        $query = MenuModel::find()
            ->from(MenuModel::tableName() . ' t')
            ->joinWith(['menuParent' => function ($q) {
            $q->from(MenuModel::tableName() . ' parent');
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['menuParent.name'] = [
            'asc' => ['parent.name' => SORT_ASC],
            'desc' => ['parent.name' => SORT_DESC],
            'label' => 'parent',
        ];
        $sort->attributes['order'] = [
            'asc' => ['parent.order' => SORT_ASC, 't.order' => SORT_ASC],
            'desc' => ['parent.order' => SORT_DESC, 't.order' => SORT_DESC],
            'label' => 'order',
        ];
        $sort->defaultOrder = ['order' => SORT_ASC];

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            't.id' => $this->id,
            't.parent' => $this->parent,
        ]);

        $query->andFilterWhere(['like', 'lower(t.name)', strtolower($this->name)])
            ->andFilterWhere(['like', 't.route', $this->route])
            ->andFilterWhere(['like', 'lower(parent.name)', strtolower($this->parent_name)]);

        return $dataProvider;
    }
}
