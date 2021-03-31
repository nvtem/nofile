<?
    use yii\bootstrap\ActiveForm;
    use yii\helpers\Html;
?>

<?= Yii::$app->view->renderFile('@app/views/layouts/_head.php'); ?>

    <body>
        <? $this->beginBody() ?>
            <div class="login-box">
                <h1 class="title">Admin login</h1>
                <div class="body">
                    <? $a_form = ActiveForm::begin() ?>
                        <?= $a_form->field($form, 'password')->passwordInput(['class' => 'common-password-input password-input'])->label('Password', ['class' => 'input-label']) ?>
                        <?= Html::submitButton('Login', ['class' => 'btn-outline-black submit-btn']) ?>
                    <? $a_form->end() ?>
                </div>
            </div>
        <? $this->endBody() ?>
    </body>

<? $this->endPage() ?>