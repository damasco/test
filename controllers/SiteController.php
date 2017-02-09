<?php

namespace app\controllers;

use app\services\ClientData;
use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @param int $period
     * @return string
     */
    public function actionIndex($period = 10)
    {
        $data = (new ClientData($period))->run();
        return $this->render('index', [
            'data' => $data,
            'period' => $period
        ]);
    }
}
