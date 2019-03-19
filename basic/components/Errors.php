<?php

namespace app\components; 

class Errors 
{   
	// 全局的成功的提示
    const ERROR_CODE_OK = 0;
    const ERROR_MESSAGE_OK = "操作成功";
    
    // 全局的数据保存失败
    const ERROR_CODE_SAVE_FAIL = 1000;
    // 全局数据不存在
    const ERROR_CODE_DATA_NOT_EXIST = 1001;
    const ERROR_MESSAGE_DATA_NOT_EXIST = "数据不存在";
    // 全局请求参数缺失
    const ERROR_CODE_PARAMES_INCORECT = 1002;
    const ERROR_MESSAGE_PARAMES_INCORECT = "参数格式错误或者参数数据为空";
    // 全局token缺失
    const ERROR_CODE_TOKEN_MISS = 1003;
    const ERROR_MESSAGE_TOKEN_MISS = "token缺失";
    // 全局token不正确
    const ERROR_CODE_TOKEN_WRONG = 1004;
    // 全局没有权限
    const ERROR_CODE_NO_ACCESS = 1005;
    const ERROR_MESSAGE_NO_ACCESS = "您无权限进行该操作";
    // 全局用户名或者密码错误
    const ERROR_CODE_USERINFO_WRONG = 1006;
    const ERROR_MESSAGE_USERINFO_WRONG = "用户名或者密码错误";
    // 全局上传文件错误
    const ERROR_CODE_UPLOAD_FILE_FAIL = "上传失败";
    const ERROR_MESSAGE_UPLOAD_FILE_FAIL = "上传失败";




}