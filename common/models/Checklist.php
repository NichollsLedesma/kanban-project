<?php

namespace common\models;

use common\models\elastic\Checklist as ElasticChecklist;
use common\models\elastic\ElasticHelper;
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
 * @property int $card_id
 * @property int $owner_id
 * @property string $title
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 * @property bool $is_deleted
 * @property int $deleted_at
 */
class Checklist extends \yii\db\ActiveRecord
{

    const SCENARIO_AJAX_CREATE = 'ajax_create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'checklist';
    }


    public function scenarios()
    {
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
            [['card_id', 'owner_id', 'title'], 'required'],
            [['card_id', 'owner_id', 'order', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 100],
            ['title', 'match', 'pattern' => '/^[A-Za-z !.]{1,100}$/'],
            ['card_id', 'exist', 'targetClass' => Card::class, 'targetAttribute' => ['card_id' => 'id']],
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
            'card_id' => 'Card ID',
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

    public function getCard()
    {
        return $this->hasOne(Card::class, ['id' => 'card_id']);
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
        if (YII_ENV_TEST) {
            return true;
        }

        if ($insert) {
            return $this->createElasticDocument();
        }

        $doc = ElasticHelper::search(ElasticCard::class, ["uuid" => $this->uuid]);

        if (!$doc) {
            return $this->createElasticDocument();
        }

        $doc->setAttributes([
            'title' => $this->title,
            'card_id' => $this->card_id,
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
            'card_id' => $this->card_id,
            "owner_id" => $this->owner_id,
        ]);

        return true;
    }

    public function beforeSoftDelete()
    {
        // ElasticHelper::remove(ElasticCard::class, ["uuid" => $this->uuid]);

        $this->deleted_at = time(); // log the deletion date
        return true;
    }

    public function beforeRestore()
    {
        return $this->deleted_at > (time() - 3600); // allow restoration only for the records, being deleted during last hour
    }
}
