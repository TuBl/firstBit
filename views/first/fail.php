<?php
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';

?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Something Went wrong... </h1>
        <h3>Click the button to try again.</h3>
        <?= Html::a('Go back', ['first/index'], ['class' => 'btn btn-primary']) ?>
  

    </div>
</div>
