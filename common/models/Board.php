<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class Board extends Model
{

    const EVENT_TEST = "channel";
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            // TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }

    // this should be inside User.php class.
    public function init()
    {

        $this->on(self::EVENT_TEST, [$this, 'move']);

        // first parameter is the name of the event and second is the handler. 
        // For handlers I use methods sendMail and notification
        // from $this class.
        parent::init(); // DON'T Forget to call the parent method.
    }

    // say, whenever new user registers, below method will send an email.
    public function move($event)
    {
        Yii::info($event->data);
    }
}
