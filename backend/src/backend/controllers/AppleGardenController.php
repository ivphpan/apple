<?php

namespace backend\controllers;

use backend\models\Apple;
use \yii\web\Controller;
use yii\filters\AccessControl;

class AppleGardenController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $appleModels = \backend\models\apple\Db::find()->all();
        return $this->render('index', [
            'appleModels' => $appleModels,
        ]);
    }
}
