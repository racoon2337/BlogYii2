<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model{

    public $image;

    public function rules()
    {
        //Валидация на js. Хуйня, проверяет расширение в назавании файла, а не сам тип
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' =>'jpg,png']
            ];
    }

    public function uploadFile(UploadedFile $file, $currentImage){

        $this->image = $file;

        if($this->validate()){

            $this->deleteCurrentImage($currentImage);

            $filename = $this->generateFilename();
            $file->saveAs(Yii::getAlias('@web') . 'uploads/' . $filename);
            return $filename;
        }
    }

    private function getFolder(){
        return Yii::getAlias('@web') . 'uploads/';
    }

    private function generateFilename(){
        return strtolower(md5(uniqid($this->image->baseName)) . '.' . $this->image->extension);
    }

    public function deleteCurrentImage($currentImage){
        if(is_file($this->getFolder() . $currentImage)){
            unlink($this->getFolder() . $currentImage);
        }
    }
}