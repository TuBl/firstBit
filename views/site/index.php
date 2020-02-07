<?php

/* @var $this yii\web\View */
use app\components\Component;
$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Click the button to Initiate the backup procedure</h1>
        <button type = "button" class = "btn btn-primary" onclick = "Component::create()">Back Up</button></div>
    </div>
</div>
