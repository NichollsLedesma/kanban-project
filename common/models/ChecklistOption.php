<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii2tech\ar\softdelete\SoftDeleteQueryBehavior;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "checklist".
 *
 * @property int $id
 * @property string $uuid
 * @property int $checklist_id
 * @property int $owner_id
 * @property string $title
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property bool $is_deleted
 * @property int $deleted_at
 */
class ChecklistOption extends \yii\db\ActiveRecord
{

    const SCENARIO_AJAX_CREATE = 'ajax_create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist_option';
    }


    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_AJAX_CREATE] = ['checklist_id','title'];
        return $scenarios;
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
            [['checklist_id', 'owner_id', 'title'], 'required'],
            [['checklist_id', 'owner_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            ['title', 'match', 'pattern' => '/^[A-Za-z !.]{1,100}$/'],
            ['checklist_id', 'exist', 'targetClass' => Checklist::class, 'targetAttribute' => ['checklist_id' => 'id']],
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
            'checklist_id' => 'Checklist ID',
            'owner_id' => 'Owner ID',
            'title' => 'Title',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function find()
    {
        $query = parent::find();
        $query->attachBehavior('softDelete', SoftDeleteQueryBehavior::class);

        return $query->notDeleted();
    }

    public function getChecklist()
    {
        return $this->hasOne(Checklist::class, ['id' => 'checklist_id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->uuid = \thamtech\uuid\helpers\UuidHelper::uuid();
        }

        return parent::beforeSave($insert);
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
