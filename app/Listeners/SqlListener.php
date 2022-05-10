<?php

namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\Log;

class SqlListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QueryExecuted $event
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        if (env("APP_DEBUG") && in_array(env("APP_ENV"), ['production', 'test', 'local'])) {
            try {
                $sql = str_replace("?", "'%s'", $event->sql);
                $log = vsprintf($sql, $event->bindings);
                $log = '[' . date('Y-m-d H:i:s') . '] ' . $log . ', [sql执行时间]：' . $event->time . 'ms' . PHP_EOL;
            } catch (\Exception $e) {
                $sql = $event->sql;
                $bindings = $event->bindings;
                $sql = explode('?', $sql);
                $newSql = '';
                for ($i = 0; $i < count($sql); $i++) {
                    if (array_key_exists($i, $bindings)) {
                        $newSql .= $sql[$i] . " " . $bindings[$i] . " ";
                    } else {
                        $newSql .= $sql[$i];
                    }
                }
                $log = '[' . date('Y-m-d H:i:s') . '] ' . $newSql . ', [sql执行时间]：' . $event->time . 'ms' . PHP_EOL;
            }
            Log::channel('sql')->info($log);
        }
    }
}
