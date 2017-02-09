<?php

use dosamigos\chartjs\ChartJs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var array $data */
/** @var int $period */

$this->title = 'Application';
?>
<div class="site-index" style="width: 800px; height: 400px; padding: 20px;">

    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => '/']) ?>
    <?= Html::textInput('period', $period) ?>
    <?= Html::submitButton('Отправить') ?>
    <?php ActiveForm::end() ?>

    <?= ChartJs::widget([
        'type' => 'line',
        'data' => [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'label' => "Percent",
                    'fill' => false,
                    'lineTension' => 0.1,
                    'borderColor' => "rgba(75,192,192,1)",
                    'data' => $data['data'],
                ]
            ],
        ],
    ]);
    ?>
</div>
