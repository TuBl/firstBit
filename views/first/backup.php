<?php
use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
// $this->registerJs(
//     "$('#myButton').on('click', function() { alert('Button clicked!'); });",
//     View::POS_READY,
//     'my-button-handler'
// );
?>
<div class="site-index">
    <div class="jumbotron">
        <h1>Click the button to Initiate the backup procedure</h1>
        <!-- <button id = "myButton" class = "btn btn-primary">Btn</button> -->
        <?= Html::a('Back Up', ['first/backuppost'], ['class' => 'btn btn-primary']) ?>
  

    </div>
</div>
