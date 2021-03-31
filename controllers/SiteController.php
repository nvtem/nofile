<?

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii;
use app\models\forms\AdminLoginForm;
use app\models\User;

class SiteController extends Controller {

    public $layout = 'user_area.php';

    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors() {
        return [
            'check-access' => [
                'class' => AccessControl::className(),
                'only' => ['admin-logout'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin']
                    ]
                ]
            ]
        ];
    }

    public function actionAdminLogin() {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin())
            return $this->redirect(['/admin/fileset/list']);

        $form = new AdminLoginForm;

        if ($form->load($_POST) && $form->validate()) {
            $identity = User::findIdentity(1);
            if ($identity && $identity->validatePassword($form->password)) {
                Yii::$app->user->login($identity);
                return $this->redirect(['/admin/fileset/list']);
            } else {
                $form->addError('password', 'Wrong password');
                return $this->renderPartial('/site/admin_login', ['form' => $form]);
            }
        } else {
            return $this->renderPartial('/site/admin_login', ['form' => $form]);
        }
    }

    public function actionAdminLogout() {
        Yii::$app->user->logout();
        return $this->redirect(['admin-login']);
    }
}