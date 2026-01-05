<?php
namespace app\models;

use yii\db\ActiveRecord;

class Achievement extends ActiveRecord
{
    public static function tableName()
    {
        return 'achievements';
    }
}

