<?

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\db\Fileset;

class FilesetController extends Controller {
    public function actionDeleteExpired() {
        $filesets = Fileset::find()->where('time_expiration < :now', [':now' => time()])->all();

        if (($count = count($filesets)) > 0) {
            $ids = [];
            foreach ($filesets as $f) {
                $f->delete();
                array_push($ids, $f->id);

                $zip_file = Yii::getAlias('@file-storage') . '/zip/' . $f->id . '.zip';
                if (file_exists($zip_file))
                    unlink($zip_file);
            }

            echo "Deleted ${count} fileset(s)\n";
            echo "[" . implode(', ', $ids) . "]\n";
        } else {
            echo "No expired filesets\n";
        }
    }
}