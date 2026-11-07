<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Station model representing bike rental stations.
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $address
 * @property int $capacity
 * @property int $is_active
 * @property string $created_at
 */
class Station extends ActiveRecord
{
    public static function tableName()
    {
        return 'stations';
    }

    public function rules()
    {
        return [
            [['code', 'name', 'address'], 'required'],
            [['capacity', 'is_active'], 'integer'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 128],
            [['address'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    public function getBikes()
    {
        return $this->hasMany(Bike::class, ['current_station_id' => 'id']);
    }
}
