<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "board".
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $entity_id
 * @property int $owner_id
 * @property string $title
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property UserEntity[] $userEntity
 * @property UserBoard[] $userBoards
 * @property User[] $users
 */
class Board extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'board';
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
            [['entity_id', 'owner_id', 'title'], 'required'],
            [['entity_id', 'owner_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            ['entity_id', 'exist', 'targetClass' => Entity::class, 'targetAttribute' => ['entity_id' => 'id']],
            ['owner_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
            [['entity_id', 'owner_id'], 'exist', 'targetClass' => UserEntity::class, 'targetAttribute' => ['entity_id' => 'entity_id', 'owner_id' => 'user_id']],
            ['title', 'string', 'max' => 100],
            ['title', 'match', 'pattern' => '/^[A-Za-z !.]{1,100}$/'],
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
            'entity_id' => 'Entity ID',
            'owner_id' => 'Owner ID',
            'title' => 'Title',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[UserBoards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserBoards()
    {
        return $this->hasMany(UserBoard::className(), ['board_id' => 'id']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_board', ['board_id' => 'id']);
    }

    public function getEntity()
    {
        return $this->hasOne(Entity::class, ['id' => 'entity_id']);
    }

    public function getColumns()
    {
        return $this->hasMany(Column::class, ['board_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->uuid = \thamtech\uuid\helpers\UuidHelper::uuid();
            $this->save();
        }

        return true;
    }

    public function beforeDelete()
    {
        $columns = $this->columns;

        foreach ($columns as $column) {
            $column->delete();
        }

        return parent::beforeDelete();
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
