<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Course */

$this->title = '修改 : ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '课外活动管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="activity-update">

    <h3 align="center"><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
