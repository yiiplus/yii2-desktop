<?php
use yii\helpers\Html;

if (Yii::$app->controller->action->id === 'login') { 
    echo $this->render('main-login', ['content' => $content]);
} else {
    app\assets\AppAsset::register($this);
    yiiplus\desktop\DesktopAsset::register($this);
?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode(Yii::$app->name) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <?php echo $this->render('header.php'); ?>
            <?php echo $this->render('left.php'); ?>
            <?php echo $this->render('content.php', ['content' => $content]); ?>
        </div>
        <?php $this->endBody() ?>
        <?php if (isset($this->blocks['js'])): ?>
            <?= $this->blocks['js'] ?>
        <?php endif; ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>