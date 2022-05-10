<?php

namespace App\Services;

use App\Models\TaskModel;

class TodoService
{
    /**
     * 列表
     * @param $param
     */
    public function index($where, $field, $page, $pageSize)
    {
        $taskModel = new TaskModel();
        $where['where'][] = ['is_delete', '=', TaskModel::IS_DELETE_0];
        $count = $taskModel->getCountByCondition($where);
        $list = $taskModel->getListByCondition($where, $field, $page, $pageSize);
        if (!empty($list)) {
            array_walk($list, function($value, $key) use(&$list){
                $list[$key]['complete_time'] = is_null($value['complete_time']) ? '' : $value['complete_time'];
            });
        }
        $result = [
            'count' => $count,
            'list' => $list
        ];
        return $result;
    }

    /**
     * 保存
     * @param $param
     * @return int
     * @throws \Exception
     */
    public function store($param)
    {
        $data = [
            'user_id' => $param['user_id'] ?? 0,
            'task_name' => $param['task_name'],
            'is_delete' => TaskModel::IS_DELETE_0,
            'is_complete' => TaskModel::IS_DELETE_0,
        ];
        $insertId = TaskModel::query()->insertGetId($data);
        if (!$insertId) {
            throw new \Exception('保存失败');
        }
        return $insertId;
    }

    /**
     * 更新
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public function update($param)
    {
        $where = [];
        $where['where'][] = ['id', '=', $param['id']];
        $data = [
            'task_name' => $param['task_name'],
        ];
        $taskModel = new TaskModel();
        $updateResult = $taskModel->updateByCondition($where, $data);
        if (!$updateResult) {
            throw new \Exception('更新失败');
        }
        return true;
    }

    /**
     * 完成标记
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public function changeComplete($param)
    {
        $where = [];
        $where['where'][] = ['id', '=', $param['id']];
        $data = [
            'is_complete' => $param['is_complete'],
        ];
        if ($param['is_complete'] == TaskModel::IS_COMPLETE_1) {
            $data['complete_time'] = date('Y-m-d H:i:s');
        }
        $taskModel = new TaskModel();
        $updateResult = $taskModel->updateByCondition($where, $data);
        if (!$updateResult) {
            throw new \Exception('更改状态失败');
        }
        return true;
    }

    /**
     * 删除
     * @param $param
     * @return bool
     * @throws \Exception
     */
    public function delete($param)
    {
        $where = [];
        $where['where'][] = ['id', '=', $param['id']];
        $data = [
            'is_delete' => TaskModel::IS_DELETE_1,
        ];
        $taskModel = new TaskModel();
        $updateResult = $taskModel->updateByCondition($where, $data);
        if (!$updateResult) {
            throw new \Exception('删除失败');
        }
        return true;
    }

}
