<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $column_id
 * @property int $owner_id
 * @property string $title
 * @property string $description
 * @property int $order
 * @property string|null $color
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class,
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'is_deleted' => true
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['uuid', 'string', 'max' => 36],
            ['uuid', 'unique'],
            ['uuid', 'thamtech\uuid\validators\UuidValidator'],
            [['column_id', 'owner_id', 'title', 'description', 'order', 'color'], 'required'],
            [['column_id', 'owner_id', 'order', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            ['title', 'match', 'pattern' => '/^[A-Za-z !.]{1,100}$/'],
            [['color'], 'string', 'length' => 6],
            ['color', 'match', 'pattern' => '/^(([a-f0-9]{3}){1,2})$/i'],
            [['description'], 'string'],
            ['column_id', 'exist', 'targetClass' => Column::class, 'targetAttribute' => ['column_id' => 'id']],
            ['owner_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uuid' => 'Uuid',
            'column_id' => 'Column ID',
            'owner_id' => 'Owner ID',
            'title' => 'Title',
            'description' => 'Description',
            'order' => 'Order',
            'color' => 'Color',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getColumn()
    {
        return $this->hasOne(Column::class, ['id' => 'column_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->uuid = \thamtech\uuid\helpers\UuidHelper::uuid();
            $this->save();
        }

        return true;
    }

    public function beforeSoftDelete()
    {
        $this->deleted_at = time(); // log the deletion date
        return true;
    }

    public function beforeRestore()
    {
        return $this->deleted_at > (time() - 3600); // allow restoration only for the records, being deleted during last hour
    }
}
