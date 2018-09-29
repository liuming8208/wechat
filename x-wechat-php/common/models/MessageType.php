<?php

namespace common\models;

class MessageType
{
    public static $SUCCESS="成功";
    public static $FAIL="失败";
    public static $INSERT_SUCCESS="添加成功";
    public static $UPDATE_SUCCESS="更新成功";

    public static $PARAMS_ERROR="参数错误";
    public static $TYPE_ERROR="格式错误";
    public static $DATA_NULL="数据不存在";

    public static $FILE_NOT_EXISTS="文件不存在";
    public static $OLD_PASSWORD_ERROR="原密码错误";
    public static $TWO_PASSWORD_ERROR="两次密码不一致";

    public static $EXIST_CLICK_OPEN_ID="请勿重复点赞";
    public static $ACCESS_TOKEN_NULL="ACCESS_Token为空";

    public static $THANKS="谢谢参与";

}