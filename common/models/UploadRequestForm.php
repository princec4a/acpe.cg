<?php

namespace common\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * UploadForm is the model behind the upload form.
 */
class UploadRequestForm extends Model
{
    /**
     * @var UploadedFile file attribute
     */
    public $file;

    /**
     * @var UploadedFile file attribute
     */
    public $fileLetter;

    /**
     * @var int id attribute
     */
    public $id;

    /**
     * @var int signed attribute
     */
    public $signed;

    /**
     * @var int signed attribute
     */
    public $status;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file', 'fileLetter', 'status', 'id'], 'required'],
            ['signed', 'required' , 'requiredValue' => 1, 'message' => \Yii::t('app','Please tick to certify signature')],
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'pdf'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'file' => \Yii::t('app', 'Autorisation'),
            'fileLetter' => \Yii::t('app', 'Lettre'),
            'id' => \Yii::t('app', 'id'),
            'signed' => \Yii::t('app', 'signed'),
            'status' => \Yii::t('app', 'status'),
        ];
    }
}

?>