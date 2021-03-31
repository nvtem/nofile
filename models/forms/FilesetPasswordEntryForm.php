<?

namespace app\models\forms;

use yii\base\Model;

class FilesetPasswordEntryForm extends Model
{
    public $password;

    public function rules() {
        return [
            [['password'], 'required'],
        ];
    }

    public function formName() {
        return '';
    }
}