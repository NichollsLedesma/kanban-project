<?php

namespace common\models;

use common\models\elastic\Column as ElasticColumn;
use common\models\elastic\ElasticHelper;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "column".
 *
 * @property int $id
 * @property string|null $uuid
 * @property int $board_id
 * @property int $owner_id
 * @property string $title
 * @property int $order
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 */
class Column extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    const SCENARIO_AJAX_CREATE = 'ajax_create';

    public static function tableName()
    {
        return 'column';
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_AJAX_CREATE] = ['title'];
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
            [['board_id', 'owner_id', 'order', 'title'], 'required'],
            [['board_id', 'owner_id', 'order', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            ['board_id', 'exist', 'targetClass' => Board::class, 'targetAttribute' => ['board_id' => 'id']],
            ['owner_id', 'exist', 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
            [['board_id', 'owner_id'], 'exist', 'targetClass' => UserBoard::class, 'targetAttribute' => ['board_id' => 'board_id', 'owner_id' => 'user_id']],
            [['title'], 'string', 'max' => 100],
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
            'board_id' => 'Board ID',
            'owner_id' => 'Owner ID',
            'title' => 'Title',
            'order' => 'Order',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getBoard()
    {
        return $this->hasOne(Board::class, ['id' => 'board_id']);
    }

    public function getCards()
    {
        return $this->hasMany(Card::class, ['column_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->uuid = \thamtech\uuid\helpers\UuidHelper::uuid();
        }

        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            return  $this->createElasticDocument();
        }

        $doc = ElasticHelper::search(ElasticColumn::class, ["uuid" => $this->uuid]);

        if (!$doc) {
            return  $this->createElasticDocument();
        }

        $doc->setAttributes([
            'title' => $this->title,
        ], false);
        $doc->save();

        return true;
    }

    private function createElasticDocument()
    {
        ElasticHelper::create(ElasticColumn::class, [
            "title" => $this->title,
            "uuid" => $this->uuid,
            "owner_id" => $this->owner_id,
            "board_id" => $this->board_id,
        ]);

        return true;
    }

    public function beforeDelete()
    {
        $cards = $this->cards;

        foreach ($cards as $card) {
            $card->delete();
        }

        return parent::beforeDelete();
    }

    public function beforeSoftDelete()
    {
        ElasticHelper::remove(ElasticColumn::class, ["uuid" => $this->uuid]);

        $this->deleted_at = time(); // log the deletion date
        return true;
    }


    public function beforeRestore()
    {
        return $this->deleted_at > (time() - 3600); // allow restoration only for the records, being deleted during last hour
    }
}
