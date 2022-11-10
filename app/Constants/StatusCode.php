<?php

namespace App\Constants;

class StatusCode
{
    const SUCCESS = 200; //成功
    const FAIL = -1; //失败

    const PRAM_ERROR = 300001; //参数错误

    //数据库错误类
    const DB_CONNECT_ERROR = 310000; //数据库连接失败
    const DB_INSERT_ERROR = 310001; //数据创建失败
    const DB_DELETE_ERROR = 310002; //数据删除失败
    const DB_UPDATE_ERROR = 310003; //数据更新失败
    const DB_QUERY_IS_NULL = 310004; //数据查询为空
}
