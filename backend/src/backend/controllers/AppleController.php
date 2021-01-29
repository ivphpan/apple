<?php

namespace backend\controllers;

use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class AppleController extends Controller
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
                        'actions' => ['index', 'create', 'ground', 'eat', 'expired'],
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

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionCreate()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $appleDb = new \backend\models\apple\Db;
        $appleDb->scenario = 'create';
        $appleDb->create(\Yii::$app->request->post());
        return [
            'data' => $appleDb,
            'errors' => $appleDb->getErrors(),
        ];
    }

    public function actionGround($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $appleModel = $this->loadModel($id);
        $appleModel->scenario = 'ground';
        $appleModel->ground();
        return [
            'data' => $appleModel,
            'errors' => $appleModel->getErrors(),
        ];
    }

    public function actionEat($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $appleModel = $this->loadModel($id);
        $appleModel->scenario = 'eat';
        $appleModel->eat(\Yii::$app->request->post());
        return [
            'data' => $appleModel,
            'errors' => $appleModel->getErrors(),
        ];
    }

    public function actionExpired($id)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $appleModel = $this->loadModel($id);
        $appleModel->delete();
        $appleModel->size = 0;
        return [
            'data' => $appleModel,
            'errors' => null,
        ];
    }

    private function loadModel($id)
    {
        $appleModel = \backend\models\apple\Db::findOne($id);
        if (!$appleModel) {
            throw new \yii\web\NotFoundHttpException('Яблоко не найдено');
        }
        return $appleModel;
    }
}
