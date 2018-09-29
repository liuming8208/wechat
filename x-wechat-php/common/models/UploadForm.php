<?php

namespace common\models;

use yii\base\Model;

/**
 * 上传类
 */
class UploadForm extends Model
{
    public $file_name;

    public function rules()
    {
       return [
            [['file_name'],"file",'skipOnEmpty' => false,'extensions' => 'png,jpg,gif,xls,xlsx','checkExtensionByMimeType'=>false],
        ];
    }

    /**
     * 图片存放路径
     */
    public function uploadImg()
    {
        if ($this->validate() && in_array($this->file_name->extension,['png','jpg','gif']))
        {
            $save_path='upload/' . date("YmdHis").rand(1,100) . '.' . $this->file_name->extension;
            $this->file_name->saveAs($save_path);
            return $save_path;
        }
        return "";
    }

    /**
     * Excel存放路径
     */
    public function uploadExcel()
    {
        if ($this->validate() && in_array($this->file_name->extension,['xls','xlsx']))
        {
            $save_path='upload/' . date("YmdHis").rand(1,100) . '.' . $this->file_name->extension;
            $this->file_name->saveAs($save_path);
            return $save_path;
        }
        return "";
    }

    /**
     * 上传文件
     */
    public function uploadFile($tmp,$extension=null)
    {
        $save_path='upload/' . date("YmdHis").rand(1,100) . '.' . $tmp->extension;
        $tmp->saveAs($save_path);
        //文件扩展类型不为空
        if($extension && in_array($tmp->extension,$extension))
        {
            return ($tmp->name ? $save_path : "");
        }
        else
        {
            if(!$extension)
            {
                return ($tmp->name ? $save_path : "");
            }
        }
        return "";
    }

}