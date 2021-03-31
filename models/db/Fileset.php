<?
namespace app\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\Url;
use ZipArchive;

class Fileset extends ActiveRecord {

    public static function tableName() {
        return 'filesets';
    }

    public function delete() {
        $this->delete_files_from_storage();
        parent::delete();
    }

    public function delete_files_from_storage() {
        foreach ($this->get_files_array() as $f) {
            unlink(Yii::getAlias('@file-storage') . '/' . $f['id']);
        }
    }

    public function generate_time_expiration() {
        $this->time_expiration = $this->time_uploaded + Yii::$app->params['filesetStorageTime'];
    }

    public function check_access_hash($hash) {
        return $hash === $this->access_hash;
    }

    public function check_password($password) {
        return password_verify($password, $this->access_hash);
    }

    public function set_files_array($files) {
        $this->files = serialize($files);
    }

    public function get_files_array() {
        return unserialize($this->files);
    }

    public function url($action, $set_access_hash = false, $absolute_url = false, $extra_params = []) {
        $route = '/fileset/' . $action;
        $params = [$route, 'id' => $this->id];

        if ($set_access_hash && $this->is_protected)
            $params['access-hash'] = $this->access_hash;

        $params = array_merge($params, $extra_params);

        return Url::to($params, $absolute_url);
    }

    public static function create($params) {
        if (!$form_files = UploadedFile::getInstances($params, "files"))
            return false;

        $size = array_reduce($form_files, function($size, $item) {
            return $size + $item->size;
        }, 0);

        if ($size > Yii::$app->params['filesetMaxSize'])
            return false;

        $fileset = new Fileset();
        $fileset->size = $size;

        $ip = Yii::$app->request->getRemoteIP();
        $ipstackResponse = file_get_contents(
          "http://api.ipstack.com/".
          $ip.
          "?access_key=".
          Yii::$app->params['ipstackAPIKey']
        );
        $country = json_decode($ipstackResponse)->country_code;
        $fileset->ip = $ip . ' [' . $country . ']';

        $fileset->time_uploaded = time();
        $fileset->generate_time_expiration();
        $fileset->save();

        $fileset_id = $fileset->id;
        $files = [];

        foreach ($form_files as $i =>$f) {
            $id = "${fileset_id}-${i}";
            array_push($files,
                [
                    'id' => $id,
                    'name' => $f->name,
                    'size' => $f->size
                ]
            );
            $f->saveAs(Yii::getAlias('@file-storage') . "/$id");
        }

        $fileset->set_files_array($files);

        if ($params->set_password === "1" && $params->password != '') {
            $fileset->is_protected = 1;
            $fileset->access_hash = password_hash($params->password, PASSWORD_DEFAULT, ['salt' => Yii::$app->params['hashSalt']]);
        } else {
            $fileset->is_protected = 0;
            $fileset->access_hash = '';
        }

        $fileset->save();

        return $fileset;
    }

    public function zip() {
        $zip_file_path = Yii::getAlias('@file-storage') . "/zip/$this->id.zip";

        if (!file_exists($zip_file_path)) {
            $zip_file = new \ZipArchive;
            $zip_file->open($zip_file_path, \ZipArchive::CREATE);

            $files = $this->get_files_array();

            foreach ($files as $f) {
                $file_path = Yii::getAlias('@file-storage') . "/" . $f['id'];
                $zip_file->addFile($file_path, $f['name']);
            }

            $zip_file->close();
        }

        return $zip_file_path;
    }
}
