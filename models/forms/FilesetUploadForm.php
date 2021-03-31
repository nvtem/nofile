<?

namespace app\models\forms;

use yii\base\Model;

class FilesetUploadForm extends Model
{
    public $files;
    public $set_password;
    public $password;

    public function formName() {
        return '';
    }

    public function rules() {

        return [
            [['files', 'set_password', 'password'], 'safe'],
        ];
    }
}