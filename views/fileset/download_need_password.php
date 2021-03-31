<?
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h1 class="title">Files are protected. Enter password:</h1>

<? $a_form = ActiveForm::begin(['id' => 'password-entry-form', 'action' => $fileset->url('download')]) ?>

<?= $a_form->field($form, 'password')->passwordInput(['class' => 'common-password-input password-input'])->label(false) ?>
<?= Html::submitButton("OK", ['class' => 'btn-black submit-btn']) ?>

<? ActiveForm::end() ?>

