<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Rental model representing bike rental orders / reservations.
 *
 * @property int $id
 * @property string $rental_number
 * @property int $user_id
 * @property int $bike_id
 * @property int $start_station_id
 * @property int $end_station_id
 * @property string $tariff_type
 * @property string $start_time
 * @property string $end_time
 * @property int $duration_hours
 * @property string $pin_code
 * @property int $helmet_included
 * @property int $insurance_included
 * @property int $holder_included
 * @property float $base_price
 * @property float $addons_price
 * @property float $discount_amount
 * @property float $total_price
 * @property string $status
 * @property string $route_name
 * @property float $distance_km
 * @property string $created_at
 */
class Rental extends ActiveRecord
{
    public static function tableName()
    {
        return 'rentals';
    }

    public function rules()
    {
        return [
            [['rental_number', 'user_id', 'bike_id', 'start_station_id', 'start_time', 'base_price', 'total_price'], 'required'],
            [['user_id', 'bike_id', 'start_station_id', 'end_station_id', 'duration_hours', 'helmet_included', 'insurance_included', 'holder_included'], 'integer'],
            [['start_time', 'end_time', 'created_at'], 'safe'],
            [['base_price', 'addons_price', 'discount_amount', 'total_price', 'distance_km'], 'number'],
            [['tariff_type', 'status'], 'string'],
            [['rental_number', 'pin_code'], 'string', 'max' => 32],
            [['route_name'], 'string', 'max' => 128],
            [['rental_number'], 'unique'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBike()
    {
        return $this->hasOne(Bike::class, ['id' => 'bike_id']);
    }

    public function getStartStation()
    {
        return $this->hasOne(Station::class, ['id' => 'start_station_id']);
    }

    public function getEndStation()
    {
        return $this->hasOne(Station::class, ['id' => 'end_station_id']);
    }
}
