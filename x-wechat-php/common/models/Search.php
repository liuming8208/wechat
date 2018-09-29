<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class Search extends Model
{

    public $rows;
    public $page;

    public function rules()
    {
        return [
            ['rows','integer'],
            ['page','integer'],
            ['rows','default','value'=>20],
            ['page','default','value'=>1],
        ];
    }

    public function __construct($page=1,$rows=20)
    {
        $this->page=$page;
        $this->rows=$rows;
    }

    /**
     * 获取分页数据列表
     */
    public function getList($query)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => intval($this->rows),
                'page'=>intval($this->page-1),
            ],
        ]);

        return ['total'=>$dataProvider->getTotalCount(),'rows'=>$dataProvider ->getModels(),'status'=>0];

    }
}