<?
    use yii\helpers\Html;
    use app\assets\AppAsset;
    use app\assets\ResetCssAsset;
    use app\assets\JqueryFormPluginAsset;

    JqueryFormPluginAsset::register($this);
    ResetCssAsset::register($this);
    AppAsset::register($this);

    $this->beginPage();
?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <? $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode(Yii::$app->name) ?></title>
    <? $this->head() ?>
</head>