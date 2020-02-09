<?php
/**
 * Email: srdjandrakul@gmail.com
 * Date: 8/10/2019
 * Time: 20:54
 */

use yii\helpers\Html;
use app\assets\VueAssets;

/* @var $this \yii\web\View */
/* @var $content string */

VueAssets::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?= $this->render('_nav'); ?>

    <div class="container">
        <?= $content; ?>
    </div>
</div>

<?= $this->render('_footer'); ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>