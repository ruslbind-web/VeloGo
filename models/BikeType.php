<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * BikeType model representing bicycle categories.
 *
 * @property string $code
 * @property string $name
 * @property string $label
 * @property string $icon_svg
 * @property string $description
 */
class BikeType extends ActiveRecord
{
    public static function tableName()
    {
        return 'bike_types';
    }

    public function rules()
    {
        return [
            [['code', 'name', 'label'], 'required'],
            [['code'], 'string', 'max' => 32],
            [['name', 'label', 'icon_svg'], 'string', 'max' => 64],
            [['description'], 'string'],
            [['code'], 'unique'],
        ];
    }

    public function getBikes()
    {
        return $this->hasMany(Bike::class, ['type_code' => 'code']);
    }
}
