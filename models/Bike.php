<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * Bike model representing rental bicycles.
 *
 * @property int $id
 * @property string $slug
 * @property string $name
 * @property string $type_code
 * @property string $type_label
 * @property int $price_hour
 * @property int $price_day
 * @property string $size
 * @property string $image
 * @property string $description
 * @property float $rating
 * @property int $reviews_count
 * @property string $badge
 * @property string $range_km
 * @property string $weight
 * @property string $brakes
 * @property string $frame
 * @property string $status
 * @property int $current_station_id
 */
class Bike extends ActiveRecord
{
    public static function tableName()
    {
        return 'bikes';
    }

    public function rules()
    {
        return [
            [['name', 'type_code', 'price_hour', 'price_day', 'description'], 'required', 'message' => 'Заполните обязательное поле.'],
            [['price_hour', 'price_day', 'reviews_count', 'current_station_id'], 'integer'],
            [['rating'], 'number'],
            [['description'], 'string'],
            [['slug'], 'string', 'max' => 64],
            [['slug'], 'unique', 'message' => 'Велосипед с таким идентификатором (slug) уже существует.'],
            [['name', 'brakes', 'frame'], 'string', 'max' => 128],
            [['type_code', 'status', 'size'], 'string', 'max' => 32],
            [['type_label', 'badge', 'range_km', 'weight'], 'string', 'max' => 64],
            [['image'], 'string', 'max' => 255],
            [['image'], 'default', 'value' => 'images/voltride.jpg'],
            [['status'], 'default', 'value' => 'available'],
            [['size'], 'default', 'value' => 'M'],
            [['rating'], 'default', 'value' => 5.0],
            [['reviews_count'], 'default', 'value' => 0],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'slug' => 'Символьный код (slug)',
            'name' => 'Название модели',
            'type_code' => 'Категория (тип)',
            'type_label' => 'Название категории (лейбл)',
            'price_hour' => 'Цена в час (₽)',
            'price_day' => 'Цена в сутки (₽)',
            'size' => 'Размер рамы',
            'image' => 'Путь к фото',
            'description' => 'Описание',
            'rating' => 'Рейтинг',
            'reviews_count' => 'Кол-во отзывов',
            'badge' => 'Бейдж / плашка',
            'range_km' => 'Запас хода',
            'weight' => 'Вес',
            'brakes' => 'Тормозная система',
            'frame' => 'Материал рамы',
            'status' => 'Статус',
            'current_station_id' => 'Текущая станция',
        ];
    }

    public function beforeValidate()
    {
        if (empty($this->slug) && !empty($this->name)) {
            $trans = Inflector::slug($this->name);
            $this->slug = !empty($trans) ? $trans : 'bike-' . time();
        }

        if (empty($this->type_label) && !empty($this->type_code)) {
            $typeNames = [
                'electro' => 'ЭЛЕКТРОБАЙК E-BIKE',
                'city' => 'ГОРОДСКОЙ КОМФОРТ',
                'mtb' => 'ГОРНЫЙ MTB',
                'family' => 'СЕМЕЙНЫЙ КРУИЗЕР',
                'gravel' => 'ГРАВИЙНЫЙ / ШОССЕ',
            ];
            $this->type_label = $typeNames[$this->type_code] ?? mb_strtoupper($this->type_code);
        }

        return parent::beforeValidate();
    }

    public function getType()
    {
        return $this->hasOne(BikeType::class, ['code' => 'type_code']);
    }

    public function getStation()
    {
        return $this->hasOne(Station::class, ['id' => 'current_station_id']);
    }

    public function getRentals()
    {
        return $this->hasMany(Rental::class, ['bike_id' => 'id']);
    }

    /**
     * @return array List of all available bikes (mapped for frontend views)
     */
    public static function getAll()
    {
        try {
            $rows = self::find()->orderBy(['id' => SORT_ASC])->asArray()->all();
            if (!empty($rows)) {
                $list = [];
                foreach ($rows as $row) {
                    $list[] = [
                        'id' => $row['slug'],
                        'db_id' => $row['id'],
                        'name' => $row['name'],
                        'type' => $row['type_code'],
                        'typeLabel' => $row['type_label'],
                        'priceHour' => (int)$row['price_hour'],
                        'priceDay' => (int)$row['price_day'],
                        'size' => $row['size'],
                        'image' => $row['image'],
                        'desc' => $row['description'],
                        'rating' => (string)$row['rating'],
                        'reviewsCount' => $row['reviews_count'] . ' отзывов',
                        'badge' => $row['badge'] ?: 'Доступен',
                        'range' => $row['range_km'] ?: 'Без батареи',
                        'weight' => $row['weight'] ?: '14 кг',
                        'brakes' => $row['brakes'] ?: 'Дисковые',
                        'frame' => $row['frame'] ?: 'Алюминий',
                        'status' => $row['status'],
                    ];
                }
                return $list;
            }
        } catch (\Throwable $e) {
            Yii::error('Bike::getAll database query failed: ' . $e->getMessage());
        }

        return self::getDefaultBikes();
    }

    public static function getDefaultBikes()
    {
        return [];
    }
}
