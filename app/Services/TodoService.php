<?php

namespace App\Services;

use App\Models\TaskModel;
use Illuminate\Support\Facades\Log;

class TodoService
{
    /**
     * 列表
     * @param $paramList
     * @return array
     */
    public function index($paramList)
    {
        $field = ['id', 'task_name', 'is_complete', 'complete_time'];
        $where = [];
        $where['where'][] = ['user_id', '=', $paramList['user_id']];
        if (isset($paramList['task_name']) && !empty($paramList['task_name'])) {
            $where['where'][] = ['task_name', 'like', $paramList['task_name'] . '%'];
        }
        if (isset($paramList['is_complete']) && (in_array($paramList['is_complete'], [TaskModel::IS_COMPLETE_0, TaskModel::IS_COMPLETE_1]))) {
            $where['where'][] = ['is_complete', '=', $paramList['is_complete']];
        }
        $taskModel = new TaskModel();
        $where['where'][] = ['is_delete', '=', TaskModel::IS_DELETE_0];
        $count = $taskModel->getCountByCondition($where);
        $list = $taskModel->getListByCondition($where, $field, $paramList['page'], $paramList['page_size']);
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
     * 详情
     * @param $param
     * @return array
     */
    public function show($param)
    {
        $taskModel = new TaskModel();
        $where = [];
        $where['where'][] = ['id', '=', $param['id']];
        $where['where'][] = ['user_id', '=', $param['user_id']];
        $where['where'][] = ['is_delete', '=', TaskModel::IS_DELETE_0];
        $field = ['id', 'task_name', 'is_complete', 'complete_time'];
        $result = $taskModel->getInfoByCondition($where, $field);
        if (!empty($result)) {
            $result['complete_time'] = is_null($result['complete_time']) ? '' : $result['complete_time'];
        }
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
