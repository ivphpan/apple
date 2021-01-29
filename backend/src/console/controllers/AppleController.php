<?php

namespace console\controllers;

use \yii\console\Controller;


class Test
{

    public function __toString()
    {
        return [
            'name' => 'Test',
        ];
    }
}

class AppleController extends Controller
{

    public function actionTest()
    {
    }

    public function actionIndex()
    {
        $apple = new \backend\models\apple\Model('green', 0.85);
        echo 'Цвет яблока: ' . $apple->color . ", его остаток: {$apple->size}\r\n";
        $apple->fallToGround();
        try {
            $apple->eat(25);
            echo 'Съели 25% яблока, осталось :' . $apple->size . "\r\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\r\n";
        }
        try {
            $apple->eat(50);
            echo 'Съели 50% яблока, осталось :' . $apple->size . "\r\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\r\n";
        }
        try {
            $apple->eat(25);
            echo 'Съели 25% яблока, осталось :' . $apple->size . "\r\n";
        } catch (\Exception $e) {
            echo $e->getMessage() . "\r\n";
        }
    }
}
