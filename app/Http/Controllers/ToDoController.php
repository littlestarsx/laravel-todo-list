<?php

namespace App\Http\Controllers;

use App\Constants\StatusCode;
use App\Models\TaskModel;
use App\Services\TodoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $params = $request->all();
        Log::info('todo列表-接收参数', ['params' => $params]);
        try {
            //参数校验
            $rules = [
                'task_name' => 'filled',
                'is_complete' => 'filled|integer',
                'page' => 'filled|integer',
                'page_size' => 'filled|integer',
            ];
            $messages = [
                'filled' => ':attribute 参数不能为空',
                'integer' => ':attribute 参数必须为整数',
            ];
            $customAttributes = [
                'task_name' => '任务名称',
                'is_complete' => '完成状态',
                'page' => '分页',
                'page_size' => '分页条数',
            ];
            $validator = Validator::make($params, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = $validator->errors()->first();
                return doJsonResponse($this->result);
            }
            $paramList = $validator->validated();
            $paramList['user_id'] = $request->get('user_id');
            if (!isset($paramList['page'])) {
                $paramList['page'] = self::DEFAULT_PAGE;
            }
            if (!isset($paramList['page_size'])) {
                $paramList['page_size'] = self::DEFAULT_PAGE_SIZE;
            }
            Log::info('todo列表-过滤后参数', ['params' => $paramList]);
            $this->result['data'] = $todoService->index($paramList);
            return doJsonResponse($this->result);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Log::error('todo列表-service异常', [
                'code' => $code,
                'msg' => $msg,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->result['code'] = $code;
            $this->result['msg'] = $msg;
            return doJsonResponse($this->result);
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
        $params = $request->all();
        Log::info('todo详情-接收参数', ['params' => $params]);
        try {
            //参数校验
            $rules = [
                'id' => 'required|integer',
            ];
            $messages = [
                'required' => ':attribute 参数不能为空',
                'integer' => ':attribute 参数必须为整数',
            ];
            $customAttributes = [
                'id' => '任务ID',
            ];
            $validator = Validator::make($params, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = $validator->errors()->first();
                return doJsonResponse($this->result);
            }
            $paramList = $validator->validated();
            $paramList['user_id'] = $request->get('user_id');
            Log::info('todo详情-过滤后参数', ['params' => $paramList]);
            $this->result['data'] = $todoService->show($paramList);
            return doJsonResponse($this->result);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Log::error('todo详情-service异常', [
                'code' => $code,
                'msg' => $msg,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->result['code'] = $code;
            $this->result['msg'] = $msg;
            return doJsonResponse($this->result);
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
        $params = $request->all();
        Log::info('todo新增-接收参数', ['params' => $params]);
        try {
            //参数校验
            $rules = [
                'task_name' => 'required',
            ];
            $messages = [
                'required' => ':attribute 参数不能为空',
            ];
            $customAttributes = [
                'task_name' => '任务名称',
            ];
            $validator = Validator::make($params, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = $validator->errors()->first();
                return doJsonResponse($this->result);
            }
            $paramList = $validator->validated();
            $paramList['user_id'] = $request->get('user_id');
            Log::info('todo新增-过滤后参数', ['params' => $paramList]);
            $insertId = $todoService->store($paramList);
            $this->result['data']['id'] = $insertId;
            return doJsonResponse($this->result);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Log::error('todo新增-service异常', [
                'code' => $code,
                'msg' => $msg,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->result['code'] = $code;
            $this->result['msg'] = $msg;
            return doJsonResponse($this->result);
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
        $params = $request->all();
        Log::info('todo更新-接收参数', ['params' => $params]);
        try {
            //参数校验
            $rules = [
                'id' => 'required|integer',
                'task_name' => 'required',
            ];
            $messages = [
                'required' => ':attribute 参数不能为空',
                'integer' => ':attribute 参数必须为整数',
            ];
            $customAttributes = [
                'id' => '任务ID',
                'task_name' => '任务名称',
            ];
            $validator = Validator::make($params, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = $validator->errors()->first();
                return doJsonResponse($this->result);
            }
            $paramList = $validator->validated();
            $paramList['user_id'] = $request->get('user_id');
            Log::info('todo更新-过滤后参数', ['params' => $paramList]);
            $todoService->update($paramList);
            return doJsonResponse($this->result);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Log::error('todo更新-service异常', [
                'code' => $code,
                'msg' => $msg,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->result['code'] = $code;
            $this->result['msg'] = $msg;
            return doJsonResponse($this->result);
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
        $params = $request->all();
        Log::info('todo完成标记-接收参数', ['params' => $params]);
        try {
            //参数校验
            $rules = [
                'id' => 'required|integer',
                'is_complete' => 'required',
            ];
            $messages = [
                'required' => ':attribute 参数不能为空',
                'integer' => ':attribute 参数必须为整数',
            ];
            $customAttributes = [
                'id' => '任务ID',
                'is_complete' => '完成状态',
            ];
            $validator = Validator::make($params, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = $validator->errors()->first();
                return doJsonResponse($this->result);
            }
            $paramList = $validator->validated();
            $paramList['user_id'] = $request->get('user_id');
            if (!in_array($paramList['is_complete'], [TaskModel::IS_COMPLETE_0,TaskModel::IS_COMPLETE_1])) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = 'is_complete参数错误';
                return doJsonResponse($this->result);
            }
            Log::info('todo完成标记-过滤后参数', ['params' => $paramList]);
            $todoService->changeComplete($paramList);
            return doJsonResponse($this->result);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Log::error('todo完成标记-service异常', [
                'code' => $code,
                'msg' => $msg,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->result['code'] = $code;
            $this->result['msg'] = $msg;
            return doJsonResponse($this->result);
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
        $param = $request->all();
        Log::info('todo删除-接收参数', ['params' => $request->all()]);
        try {
            //参数校验
            $rules = [
                'id' => 'required|integer',
            ];
            $messages = [
                'required' => ':attribute 参数不能为空',
                'integer' => ':attribute 参数必须为整数',
            ];
            $customAttributes = [
                'id' => '任务ID',
            ];
            $validator = Validator::make($param, $rules, $messages, $customAttributes);
            if ($validator->fails()) {
                $this->result['code'] = StatusCode::PRAM_ERROR;
                $this->result['msg'] = $validator->errors()->first();
                return doJsonResponse($this->result);
            }
            $paramList = $validator->validated();
            $paramList['user_id'] = $request->get('user_id');
            Log::info('todo删除-过滤参数', ['params' => $paramList]);
            $todoService->delete($paramList);
            return doJsonResponse($this->result);
        } catch (\Exception $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
            Log::error('todo删除-service异常', [
                'code' => $code,
                'msg' => $msg,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            $this->result['code'] = $code;
            $this->result['msg'] = $msg;
            return doJsonResponse($this->result);
        }
    }

}
