<?php
use yii\helpers\Html;
use yii\web\View;
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

// echo '<pre>';
// var_dump($percentage);
// echo '<pre>';


?>

<div>
<button class="btn" id = "myButton">Button</button>
<div>
<h1>
<?php echo $percentage?>
</h1>
</div>
</div>

