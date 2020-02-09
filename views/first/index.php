<?php
use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap\Progress;

/* @var $this yii\web\View */
$this->title = 'My Yii Application';
// $this->registerJs(
//     "
//     $('#myButton').on('click', function() { $percentage++;  }",
//     View::POS_READY,
//     'my-button-handler'
// );

$this->registerJsFile(
    '@web/js/main.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

?>

<div class="site-index">
    <div class="jumbotron">
        <h2>Click the button to Initiate the backup procedure</h2>
        <button  id = "myButton" class = 'btn btn-primary'>Button</button>
        <div id = "progress" style = "display: none;">
            <h1>
            <?php echo Progress::widget([
                'percent' => 100,
                'barOptions' => ['class' => 'progress-bar-success'],
                'options' => ['class' => 'active progress-striped']
            ]);
            ?>
            </h1>
            <h1>Backing up your files...</h1>
        </div>
        <div id = "completed" style = "display: none;">
            <h1>Back up completed!!</h1>
        </div>
    </div>
</div>



