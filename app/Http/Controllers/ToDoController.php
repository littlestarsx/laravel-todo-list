<?php

namespace App\Http\Controllers;

use App\Models\TaskModel;
use App\Services\TodoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToDoController extends Controller
{
    /**
     * 列表
     * @param Request $request
     * @param TodoService $todoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, TodoService $todoService)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $where = [];
            if ($request->has('user_id') && !empty($request->input('user_id'))) {
                $where['where'][] = ['user_id', '=', $request->input('user_id')];
            }
            if ($request->has('task_name') && !empty($request->input('task_name'))) {
                $where['where'][] = ['task_name', 'like', $request->input('task_name') . '%'];
            }
            if ($request->has('is_complete') && (in_array($request->input('is_complete'), [TaskModel::IS_COMPLETE_0, TaskModel::IS_COMPLETE_1]))) {
                $where['where'][] = ['is_complete', '=', $request->input('is_complete')];
            }
            $field = ['id', 'task_name', 'is_complete', 'complete_time'];
            $page = $request->has('page') ? $request->input('page') : 1;
            $pageSize = $request->has('page_size') ? $request->input('page_size') : 10;

            $result['data'] = $todoService->index($where, $field, $page, $pageSize);
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

    /**
     * 详情
     * @param Request $request
     * @param TodoService $todoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, TodoService $todoService)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $rules = [
                'id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result['code'] = -1;
                $result['msg'] = $validator->errors()->first();
                return response()->json($result);
            }
            $param = $validator->validated();
            $result['data'] = $todoService->show($param);
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

    /**
     * 新增
     * @param Request $request
     * @param TodoService $todoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, TodoService $todoService)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $rules = [
                'task_name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result['code'] = -1;
                $result['msg'] = $validator->errors()->first();
                return response()->json($result);
            }
            $param = $validator->validated();
            $insertId = $todoService->store($param);
            $result['data']['id'] = $insertId;
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

    /**
     * 更新
     * @param Request $request
     * @param TodoService $todoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, TodoService $todoService)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $rules = [
                'id' => 'required',
                'task_name' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result['code'] = -1;
                $result['msg'] = $validator->errors()->first();
                return response()->json($result);
            }
            $param = $validator->validated();
            $todoService->update($param);
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

    /**
     * 完成标记
     * @param Request $request
     * @param TodoService $todoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeComplete(Request $request, TodoService $todoService)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $rules = [
                'id' => 'required',
                'is_complete' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result['code'] = -1;
                $result['msg'] = $validator->errors()->first();
                return response()->json($result);
            }
            $param = $validator->validated();
            if (!in_array($param['is_complete'], [TaskModel::IS_COMPLETE_0,TaskModel::IS_COMPLETE_1])) {
                $result['code'] = -1;
                $result['msg'] = 'is_complete参数错误';
                return response()->json($result);
            }
            $todoService->changeComplete($param);
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

    /**
     * 删除
     * @param Request $request
     * @param TodoService $todoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, TodoService $todoService)
    {
        $result = [
            'code' => 200,
            'msg' => 'success',
            'data' => []
        ];
        try {
            $rules = [
                'id' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $result['code'] = -1;
                $result['msg'] = $validator->errors()->first();
                return response()->json($result);
            }
            $param = $validator->validated();
            $todoService->delete($param);
            return response()->json($result);
        } catch (\Exception $error) {
            $result['code'] = -1;
            $result['msg'] = $error->getMessage();
            return response()->json($result);
        }
    }

}
