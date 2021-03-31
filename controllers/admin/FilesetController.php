<?

namespace app\controllers\admin;

use app\models\db\Fileset;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;

class FilesetController extends Controller {
    public $layout = 'admin_area.php';

    private $fileset;

    public function behaviors() {
        return [
            'check_access' => [
                'class' => AccessControl::className(),
                'only' => ['list', 'delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin']
                    ],
                ],
            ],
            'check_for_existence' => [
                'class' => AccessControl::className(),
                'only' => ['delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function($action, $rule) {
                            return (bool) $this->fileset;
                        }
                    ],
                ],
                'denyCallback' => function($rule, $action) {
                    return $this->redirect(['/admin/fileset/list']);
                }
            ]
        ];
    }

    public function beforeAction($action) {
        if (in_array($action->id, ['delete'])) {
            $id = Yii::$app->request->get('id', null);
            $this->fileset = Fileset::findOne($id);
        }

        return parent::beforeAction($action);
    }

    public function actionList($order_by = 'id', $order_dir = '^') {
        $query = Fileset::find();
        $count_query = clone $query;
        $pages = new Pagination(['totalCount' => $count_query->count(), 'pageSize' => 100]);

        if (!in_array($order_by, ['id', 'ip', 'time_uploaded', 'size', 'is_protected']))
            $order_by = 'id';

        $sort = ($order_dir === 'v') ? SORT_DESC : SORT_ASC;

        $filesets = $query->addOrderBy([$order_by => $sort])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('list', ['filesets' => $filesets, 'pages' => $pages]);
    }

    public function actionDelete($id) {
        $fileset = Fileset::findOne($id);
        $fileset->delete();
        return $this->redirect(Yii::$app->request->referrer);
    }
}