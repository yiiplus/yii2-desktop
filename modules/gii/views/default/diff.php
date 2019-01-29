<?php
/**
 * 慧诊
 *
 * PHP version 7
 *
 * @category  PHP
 * @package   Yii2
 * @author    zhouyang <zhouyang@himoca.com>
 * @copyright 2017-2019 北京慧诊科技有限公司
 * @license   https://www.huizhen.com/licence.txt Licence
 * @link      http://www.huizhen.com
 */

?>
<div class="default-diff">
    <?php if ($diff === false): ?>
        <div class="alert alert-danger">Diff is not supported for this file type.</div>
    <?php elseif (empty($diff)): ?>
        <div class="alert alert-success">Identical.</div>
    <?php else: ?>
        <div class="content"><?= $diff ?></div>
    <?php endif; ?>
</div>
