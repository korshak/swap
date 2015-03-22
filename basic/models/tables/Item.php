<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $price
 * @property string $description
 * @property string $created
 * @property string $updated
 *
 * @property ItemImage $id0
 */
class Item extends \yii\db\ActiveRecord
{
    public $images;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'price', 'description', 'user_id'], 'required'],
            [['price'], 'number'],
            [['description', 'created', 'updated'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['images'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(ItemImage::className(), ['item_id' => 'id']);
    }
}