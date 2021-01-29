<?php

namespace backend\models\apple;

class Model
{
    private $datetimeCreate = 0;
    private $datetimeGround = 0;
    private $data = [
        'color' => '',
        'size' => 1
    ];

    public function __construct($color)
    {
        $this->colorSet($color);
        $this->datetimeCreate = date("Y-m-d H:i:s", mt_rand(strtotime("2000-01-01 00:00:00"), time()));
    }

    private function colorSet($color)
    {
        $this->data['color'] = $color;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        return null;
    }

    public function getDatetimeCreate()
    {
        return $this->datetimeCreate;
    }

    public function datetimeCreateSet($datetimeCreate)
    {
        $this->datetimeCreate = $datetimeCreate;
    }

    public function getDatetimeGround()
    {

        return $this->datetimeGround;
    }

    public function setDatetimeGround($datetimeGround)
    {
        $this->datetimeGround = $datetimeGround;
    }

    public function setSize($size)
    {
        $this->data['size'] = $size;
    }

    public function isOnTree()
    {
        return $this->datetimeGround == 0;
    }

    public function isExpired()
    {
        return strtotime($this->datetimeGround) > 0
            && strtotime($this->datetimeGround) + (60 * 60 * 5) < time();
    }

    public function isEat($percent)
    {
        return $this->data['size'] - $percent >= 0;
    }

    public function eat($percent)
    {
        if ($this->isOnTree()) {
            throw new \Exception('Съесть нельзя, яблоко на дереве');
        }

        if ($this->isExpired()) {
            throw new \Exception('Съесть нельзя, яблоко испорчено');
        }
        $percent = $percent * 0.01;
        if (!$this->isEat($percent)) {
            throw new \Exception('Съесть нельзя, кусок слишком большой ' . $percent);
        }

        $this->data['size'] -= number_format($percent, 2, '.', '');
    }

    public function fallToGround()
    {
        $this->datetimeGround = date('Y-m-d H:i:s');
    }

    public static function createFromDb($appleDb)
    {
        $apple = new static($appleDb->color);
        $apple->datetimeCreateSet($appleDb->datetimeCreate);
        $apple->sizeSet($appleDb->size);
        return $apple;
    }
}
