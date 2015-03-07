<?php

namespace app\models\tables;

use Yii;

/**
 * This is the model class for table "item_image".
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $src
 *
 * @property Item $item
 */
class ItemImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'item_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'src'], 'required'],
            [['item_id'], 'integer'],
            [['src'], 'string'],
            [['item_id'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'src' => 'Src',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }
}