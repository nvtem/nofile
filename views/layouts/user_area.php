<?= Yii::$app->view->renderFile('@app/views/layouts/_head.php'); ?>

    <body>
        <? $this->beginBody() ?>
            <header class="header">
                <div class="inner">
                    <a class="logo" href="<?= Yii::$app->getHomeUrl() ?>">nofile</a>
                    <a class="show-about-box-btn" href="#">about</a>
                </div>
            </header>

            <main class="main">
                <div class="inner">
                    <?= $content ?>
                </div>
            </main>

        <?= Yii::$app->view->renderFile('@app/views/pieces/_about_box.php'); ?>
        <? $this->endBody() ?>
    </body>
</html>

<? $this->endPage() ?>
