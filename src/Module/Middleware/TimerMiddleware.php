<?php

namespace Module\Middleware;

use Exception;
use ZM\Annotation\Http\HandleAfter;
use ZM\Annotation\Http\HandleBefore;
use ZM\Annotation\Http\HandleException;
use ZM\Annotation\Http\MiddlewareClass;
use ZM\Console\Console;
use ZM\Http\MiddlewareInterface;

/**
 * Class TimerMiddleware
 * 示例中间件：用于统计路由函数运行时间用的
 * @package Module\Middleware
 * @MiddlewareClass("timer")
 */
class TimerMiddleware implements MiddlewareInterface
{
    private $starttime;

    /**
     * @HandleBefore()
     * @return bool
     */
    public function onBefore() {
        $this->starttime = microtime(true);
        return true;
    }

    /**
     * @HandleAfter()
     */
    public function onAfter() {
        Console::info("Using " . round((microtime(true) - $this->starttime) * 1000, 2) . " ms.");
    }

    /**
     * @HandleException(\Exception::class)
     * @param Exception $e
     * @throws Exception
     */
    public function onException(Exception $e) {
        Console::error("Using " . round((microtime(true) - $this->starttime) * 1000, 2) . " ms but an Exception occurred.");
        throw $e;
    }
}
