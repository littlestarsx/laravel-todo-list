<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskModel extends Model
{
    use HasFactory;

    //未删除
    const IS_DELETE_0 = 0;
    //已删除
    const IS_DELETE_1 = 1;
    //待完成
    const IS_COMPLETE_0 = 0;
    //已完成
    const IS_COMPLETE_1 = 1;

    protected $table = 'task';

    public $timestamps = false;

    /**
     * 条件获取列表总数
     * @param $where
     * @param array $field
     * @param string $order
     * @return array
     */
    public function getCountByCondition($where)
    {
        $result = self::query()
            ->when(isset($where['where']), function ($query) use($where){
                return $query->where($where['where']);
            })
            ->when(isset($where['whereIn']), function ($query) use($where) {
                foreach ($where['whereIn'] as $value) {
                    return $query->whereIn($value[0], $value[1]);
                }
            })
            ->count();
        return $result;
    }

    /**
     * 条件获取列表
     * @param $where
     * @param array $field
     * @param int $page
     * @param int $pageSize
     * @param string $order
     * @return array
     */
    public function getListByCondition($where, $field = ['*'], $page = 1, $pageSize = 10, $order = 'id desc')
    {
        $offset = ($page - 1) * $pageSize;
        $limit = $pageSize;
        $result = self::query()
            ->select($field)
            ->when(isset($where['where']), function ($query) use($where){
                return $query->where($where['where']);
            })
            ->when(isset($where['whereIn']), function ($query) use($where) {
                foreach ($where['whereIn'] as $value) {
                    return $query->whereIn($value[0], $value[1]);
                }
            })
            ->offset($offset)
            ->orderByRaw($order)
            ->limit($limit)
            ->get()
            ->toArray();
        return $result;
    }

    /**
     * 条件获取信息
     * @param $where
     * @param array $field
     * @return array
     */
    public function getInfoByCondition($where, $field = ['*'])
    {
        $result = [];
        $info = self::query()
            ->select($field)
            ->when(isset($where['where']), function ($query) use($where){
                return $query->where($where['where']);
            })
            ->when(isset($where['whereIn']), function ($query) use($where) {
                foreach ($where['whereIn'] as $value) {
                    return $query->whereIn($value[0], $value[1]);
                }
            })
            ->first();
        if (empty($info)) {
            return $result;
        }
        $result = $info->toArray();
        return $result;
    }

    /**
     * 条件更新数据
     * @param $where
     * @param $data
     * @return bool|int
     */
    public function updateByCondition($where, $data)
    {
        if (empty($where)) {
            return false;
        }
        $result = self::query()
            ->when(isset($where['where']), function ($query) use($where){
                return $query->where($where['where']);
            })
            ->when(isset($where['whereIn']), function ($query) use($where) {
                foreach ($where['whereIn'] as $value) {
                    return $query->whereIn($value[0], $value[1]);
                }
            })
            ->update($data);
        return $result;
    }

}
