<?php
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Click the button to Initiate the backup procedure</h1>

        <?= Html::a('Go back', ['first/backupget'], ['class' => 'btn btn-primary']) ?>
  

    </div>
</div>
