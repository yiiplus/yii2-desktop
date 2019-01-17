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
<div class="default-view-results">
    <?php
    if ($hasError) {
        echo '<div class="alert alert-danger">There was something wrong when generating the code. Please check the following messages.</div>';
    } else {
        echo '<div class="alert alert-success">' . $generator->successMessage() . '</div>';
    }
    ?>
    <pre><?= nl2br($results) ?></pre>
</div>
