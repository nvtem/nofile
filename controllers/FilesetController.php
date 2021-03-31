<?

namespace app\controllers;

use app\models\db\Fileset;
use app\models\forms\FilesetPasswordEntryForm;
use app\models\forms\FilesetUploadForm;

use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\filters\AccessControl;

class FilesetController extends Controller {

    public $layout = 'user_area.php';

    private $fileset;
    private $access_hash;

    public function behaviors() {
        return [
            'check_for_existence' => [
                'class' => AccessControl::className(),
                'only' => ['download', 'download-file', 'download-as-zip'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function($rule, $access) {
                            return (bool) $this->fileset;
                        }
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    throw new HttpException(404, 'file not found');
                }
            ],

            'check_access_hash' => [
                'class' => AccessControl::className(),
                'only' => ['download-file', 'download-as-zip'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function($rule, $access) {
                            if ($this->fileset->is_protected)
                                return $this->fileset->check_access_hash($this->access_hash);
                            else
                                return true;
                        },
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    $this->redirect(['/fileset/download', 'id' => Yii::$app->request->get('id')]);
                }
            ],

            'check_access_hash_or_password' => [
                'class' => AccessControl::className(),
                'only' => ['download'],
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function($rule, $access) {
                            if ($this->fileset->is_protected) {
                                $form = new FilesetPasswordEntryForm();

                                if ($this->fileset->check_access_hash($this->access_hash))
                                    return true;
                                elseif ($form->load($_POST) && $form->validate() && $this->fileset->check_password($form->password))
                                    return $this->redirect($this->fileset->url('download', true));
                                else
                                    return false;
                            } else {
                                return true;
                            }
                        },
                    ]
                ],
                'denyCallback' => function($rule, $action) {
                    $form = new FilesetPasswordEntryForm();
                    if (isset($_POST['password']))
                        $form->addError('password', 'Wrong password');
                    echo $this->render('/fileset/download_need_password', ['fileset' => $this->fileset, 'form' => $form]);
                    exit();
                }
            ]
        ];
    }

    public function beforeAction($action) {
        $id = Yii::$app->request->get('id', null);

        $this->fileset = Fileset::findOne($id);
        $this->access_hash = Yii::$app->request->get('access-hash', null);

        return parent::beforeAction($action);
    }

    public function actionUpload() {
        $form = new FilesetUploadForm;

        if (($form->load($_POST)) && $form->validate()) {
            $this->fileset = Fileset::create($form);

            if ($this->fileset) {
                Yii::$app->session->setFlash('files_uploaded');
                return "OK:" . $this->fileset->url('download', true);
            } else {
                return 'ERR';
            }
        } else {
            return $this->render('/fileset/upload', ['form' => $form]);
        }
    }

    public function actionDownload($id) {
        return $this->render('download', ['fileset' => $this->fileset]);
    }

    public function actionDownloadAsZip($id) {
        $zip_file_path = $this->fileset->zip();
        return Yii::$app->response->sendFile($zip_file_path, "$id.zip");
    }

    public function actionDownloadFile($id, $file_no) {
        $files = $this->fileset->get_files_array();
        if (!isset($files[$file_no]))
            throw new HttpException(404, 'file not found');

        $file_id = $files[$file_no]['id'];
        $file_name = $files[$file_no]['name'];

        return Yii::$app->response->sendFile(Yii::getAlias('@file-storage') . "/$file_id", $file_name);
    }
}