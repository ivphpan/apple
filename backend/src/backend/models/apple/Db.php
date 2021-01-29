<?php

namespace backend\models\apple;

use \yii\db\ActiveRecord;

/**
 * Модель базы данных Яблок
 * 
 * @property int $datetimeCreate Время создания
 * @property int $datetimeGround Время падения
 * @property string $color Цвет
 * @property float $size Размер, остаток
 * @property int $isEat Доступно для еды
 * @property int $isOnTree Висит на дереве
 * @property int $isExpired Просрочено, испорчено
 * 
 * @property Model $apple
 */
class Db extends ActiveRecord
{

    public $apple;

    public static function tablename()
    {
        return '{{apple}}';
    }

    public function rules()
    {
        return [
            [['datetimeCreate', 'color', 'size'], 'required', 'on' => 'create'],
            [['datetimeGround'], 'required', 'on' => 'ground'],
            [['size'], 'required', 'on' => 'eat'],
            [['size'], 'eatCheck', 'on' => 'eat'],
        ];
    }

    public function eatCheck()
    {
        try {
            $this->apple->eat($this->size);
            $this->size = $this->apple->size;
        } catch (\Exception $e) {
            $this->addError('size', $e->getMessage());
        }
    }

    public function create($post)
    {
        $this->load($post, '');
        $this->apple = new \backend\models\apple\Model($this->color);
        $this->datetimeCreate = $this->apple->getDatetimeCreate();
        $this->size = $this->apple->size;
        $this->isOnTree = 1;
        $this->isEat = 0;
        $this->isExpired = 0;
        return $this->save();
    }

    public function ground()
    {
        $this->apple = new \backend\models\apple\Model($this->color);
        $this->apple->fallToGround();
        $this->datetimeGround = $this->apple->getDatetimeGround();
        $this->isOnTree = $this->apple->isOnTree();
        $this->isEat = $this->apple->isEat(0);
        return $this->save();
    }

    public function eat($post)
    {
        $this->apple = new \backend\models\apple\Model($this->color);
        $this->apple->setDatetimeGround($this->datetimeGround);
        $this->apple->setSize($this->size);
        $this->load($post, '');


        if ($this->validate()) {
            if ($this->size == 0) {
                $this->delete();
                return true;
            }
            return $this->save(false);
        }
        return false;
    }
}
