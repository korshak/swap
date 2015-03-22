<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;
use app\models\tables\ItemImage;

/* @var $this yii\web\View */
/* @var $model app\models\tables\Item */
/* @var $form yii\widgets\ActiveForm */

/** @var app\models\tables\User $userIdentity */
$userIdentity = Yii::$app->user->identity;
$itemImages = [];

foreach ($model->getImages() as $image) {
    $itemImages[] = Html::img($image->src);
}
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php if (!$model->isNewRecord) : ?>
        <?= $form->field($model, 'images')->widget(FileInput::classname(), [
            'pluginOptions' => [
                'uploadUrl' => Url::to(['/item/file-upload']),
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => 'Select Photo',
                'maxFileCount' => 4,
                'initialPreview' => [
                    Html::img("/images/moon.jpg", ['class' => 'file-preview-image', 'alt' => 'The Moon', 'title' => 'The Moon']),
                    Html::img("/images/earth.jpg", ['class' => 'file-preview-image', 'alt' => 'The Earth', 'title' => 'The Earth']),
                ],
                'uploadExtraData' => [
                    'user_id' => $userIdentity->id,
                    'item_id' => $model->id
                ],
            ],
            'options' => [
                'multiple' => true,
                'accept' => 'image/*',
            ],
        ]); ?>
    <?php endif ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => 11]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
