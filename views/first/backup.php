<?php
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';

//script to query for build % every 1 sec
// Yii::$app->clientScript->registerCoreScript('jquery');
// Yii::$app->clientScript->registerScript('ajax-percentage','
//    var interval = 1000;
//    setInterval(function() { $.ajax(
//         type: "GET",
//         url: '.Yii::app()->createUrl('first/percentage', array('id'=>$item->id)).',
//         success: function (percents) {
//             // you have got your percents, so you can now assign it to progressbar value here
//         }
//  )}, interval);
// ');
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Click the button to Initiate the backup procedure</h1>
        <!-- <button id = "myButton" class = "btn btn-primary">Btn</button> -->
        <?= Html::a('Back Up', ['first/backuppost'], ['class' => 'btn btn-primary']) ?>
  

    </div>
</div>
