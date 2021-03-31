<?
    use app\helpers\Format;
    use yii\helpers\Html;
    use yii\bootstrap\ActiveForm;
?>

<div class="drop-file-zone">
  <div class="plus">+</div>
</div>

<h1 class="title">Upload files</h1>

<? $a_form = ActiveForm::begin(['id' => 'file-upload-form', 'validateOnSubmit' => false]) ?>

    <div class="upload-step-1">
        <input type="button" class="btn-black choose-file-btn" value="Upload"><br>
        <span class="btn-comment">Max <?= Format::format_bytes(\Yii::$app->params['filesetMaxSize']); ?></span>
    </div>

    <div class="upload-step-2">
        <table class="file-list"></table>
        <input type="button" class="btn-outline-black clear-file-list-btn" value="Clear"><br/>
        <?= $a_form->field($form, 'set_password')->checkbox()->label('Set password', ['class' => 'set-password-checkbox-label']) ?>
        <?= $a_form->field($form, 'password', ['options' => ['class' => 'd-none']])->passwordInput(['class' => 'common-password-input password-input'])->label(false) ?>
        <div class="upload-progress-bar">
            <div class="filler"></div>
        </div>
        <br>
        <?= Html::submitButton('Upload', ['class' => 'btn-black submit-btn']) ?>
    </div>
    <?= $a_form->field($form, 'files[]')->fileInput(['class' => 'file-input', 'multiple' => 'multiple'])->label(false) ?>
<? ActiveForm::end() ?>

<script>
    var isUploadPage = true;
    var allowFileDrop = true;
    var fileSetMaxSize = <?= \Yii::$app->params['filesetMaxSize'] ?>;
</script>
