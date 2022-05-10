<?php


namespace App\Logging;


use Illuminate\Log\Logger;
use Monolog\Formatter\LineFormatter;

class CustomizeFormatter
{
    /**
     * 自定义给定的日志实例
     *
     * @param Logger $logger
     * @return void
     */
    public function __invoke($logger)
    {
        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new LineFormatter(
                "[%datetime%] [%level_name%] [%use_time%] [%request_id%] [%module%] [%message%] [%context%] [%extra%]\n"
            ));
            $handler->pushProcessor(function ($records) {
                $records['use_time'] = date('Y-m-d H:i:s');
                $records['request_id'] = $_GET['request_id'] ?? 'PHP_' . uniqid(gethostname() . '_');
                $records['extra']['ip'] = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : '';
                $records['extra']['request_url'] = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : '';
                $records['extra']['http_referer'] = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
                return $records;
            });
        }
    }
}
