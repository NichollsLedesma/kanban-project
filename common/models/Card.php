<?php

namespace common\models;

use common\models\elastic\Card as ElasticCard;
use common\models\elastic\ElasticHelper;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use yii\helpers\VarDumper;

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

    const SCENARIO_AJAX_CREATE = 'ajax_create';
    const SCENARIO_AJAX_UPDATE = 'ajax_update';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'card';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_AJAX_CREATE] = ['title', 'description'];
        $scenarios[self::SCENARIO_AJAX_UPDATE] = ['title', 'description', 'color'];
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
            [['column_id', 'owner_id', 'title', 'description', 'order', 'color'], 'required'],
            [['column_id', 'owner_id', 'order', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            ['title', 'match', 'pattern' => '/^[A-Za-z !.]{1,100}$/'],
            [['color'], 'string', 'length' => 7],
            ['color', 'match', 'pattern' => '/^#(([a-f0-9]{3}){1,2})$/i'],
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

    public function beforeSave($insert)
    {
        /* data modifier, remove # from begin of color selector */
        if (isset($this->color)&&$this->color[0] == '#') {
            $this->color = substr($this->color, 1);
        }

        if ($insert) {
            $this->uuid = \thamtech\uuid\helpers\UuidHelper::uuid();
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
return;
        if ($insert) {
            return $this->createElasticDocument();
        }

        $doc = ElasticHelper::search(ElasticCard::class, ["uuid" => $this->uuid]);

        if (!$doc) {
            return $this->createElasticDocument();
        }

        $doc->setAttributes([
            'title' => $this->title,
            'order' => $this->order,
            'description' => $this->description,
            'column_id' => $this->column_id,
            'board_id' => $this->column["board_id"],
            'color' => $this->color,
            'owner_id' => $this->owner_id,
                ], false);
        $doc->save();

        return true;
    }

    private function createElasticDocument()
    {
        ElasticHelper::create(ElasticCard::class, [
            "title" => $this->title,
            "uuid" => $this->uuid,
            "owner_id" => $this->owner_id,
            'board_id' => $this->column["board_id"],
            "column_id" => $this->column_id,
            "description" => $this->description,
            "color" => $this->color,
            "order" => $this->order,
        ]);

        return true;
    }

    public function beforeSoftDelete()
    {
        ElasticHelper::remove(ElasticCard::class, ["uuid" => $this->uuid]);

        $this->deleted_at = time(); // log the deletion date
        return true;
    }

    public function beforeRestore()
    {
        return $this->deleted_at > (time() - 3600); // allow restoration only for the records, being deleted during last hour
    }

}
