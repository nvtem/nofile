<?= Yii::$app->view->renderFile('@app/views/layouts/_head.php'); ?>

<? use yii\helpers\Url; ?>

<body>
    <? $this->beginBody() ?>
            <header class="admin-header">
                <h1 class="title">Admin area [<?= str_replace(['http://', 'https://'], '', Url::base(true)) ?>]</h1>
                <a href="<?= Url::to(['/site/admin-logout']) ?>" class="btn-outline-white logout-btn">Logout</a>
            </header>

            <main class="admin-main">
                <div class="inner">
                    <?= $content ?>
                </div>
            </main>
    <? $this->endBody() ?>
</body>

<? $this->endPage() ?>
