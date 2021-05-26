<?php

namespace common\jobs;

/**
 * Class JobRabbitQueue.
 */
class JobRabbitQueue extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        echo "attend queue";
    }
}
