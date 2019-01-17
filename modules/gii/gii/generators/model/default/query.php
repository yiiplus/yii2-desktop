<?php
$modelFullClassName = $modelClassName;
if ($generator->ns !== $generator->queryNs) {
    $modelFullClassName = '\\' . $generator->ns . '\\' . $modelFullClassName;
}

$username = Yii::$app->user->identity->username;
echo "<?php\n";
?>
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    <?= $username ?> <<?= $username ?>@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

namespace <?= $generator->queryNs ?>;

/**
 * This is the ActiveQuery class for [[<?= $modelFullClassName ?>]].
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    <?= $username ?> <<?= $username ?>@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->queryBaseClass, '\\') . "\n" ?>
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * 查询全部
     *
     * @return <?= $modelFullClassName ?>[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * 查询单条
     *
     * @return <?= $modelFullClassName ?>|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
