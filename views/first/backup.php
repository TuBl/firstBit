<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Click the button to Initiate the backup procedure</h1>
        <!-- <a  class = "btn btn-primary" href= "/first/hello&message=test">Back Up</a> -->
        <?= Html::a('Back Up', ['first/backuppost'], ['class' => 'btn btn-primary']) ?>

    </div>
</div>
