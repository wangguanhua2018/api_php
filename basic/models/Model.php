<?php

namespace app\models;

use Yii;
/**
 * 公共模型类
 */
class Model extends \yii\db\ActiveRecord
{   
	// 取列表的时候默认取第一页的数据
    const COMMON_DEFAULT_PAGE = 1;
    // 取列表的时候默每页取10条数据
    const COMMON_DEFAULT_ROWS = 10;
    /**
     * 通过查询对象获取带有分页信息的列表数据
     * @param array  查询的客户端参数信息
     * @param object $query
     */
    public function getPageListOnQuery($params, $query)
    {
        // 根据当前的页数，算出当前页的偏移量
        $page = isset($params['page'])  && !empty($params['page'])  ? $params['page']  : self::COMMON_DEFAULT_PAGE;
        $nums = isset($params['limit']) && !empty($params['limit']) ? $params['limit'] : self::COMMON_DEFAULT_ROWS;
        $offset  = ($page - 1) * $nums;
        
        // 查询符合条件的总记录数
        $count = $query->count();
        // 分页处理
        $query->offset($offset)->limit($nums);
        
         // 获取数据列表
        $rows = $query->all();

        // 符合条件的最大页数
        $max = ceil($count/$nums);
        
        // 组装固定的返回格式
        $result['list']            = $rows;
        $result['page']['count']   = (int)$count;
        $result['page']['max']     = (int)$max;
        $result['page']['limit']   = (int)$nums;
        $result['page']['current'] = (int)$page;

        return $result;
    }
    /**
     * 获取模型的第一条错误信息
     */
    public function getModelError($model) 
    {
        $errors = $model->getErrors();    
        if (!is_array($errors)) {
            return true;
        }
        
        $firstError = array_shift($errors);
        if (!is_array($firstError)) {
            return true;
        }
        
        return array_shift($firstError);
    }
}
