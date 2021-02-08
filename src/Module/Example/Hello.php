<?php

namespace Module\Example;

use ZM\Annotation\Http\Middleware;
use ZM\Annotation\Swoole\OnCloseEvent;
use ZM\Annotation\Swoole\OnOpenEvent;
use ZM\Annotation\Swoole\OnRequestEvent;
use ZM\ConnectionManager\ConnectionObject;
use ZM\Console\Console;
use ZM\Annotation\CQ\CQCommand;
use ZM\Annotation\Http\RequestMapping;
use ZM\Event\EventDispatcher;
use ZM\Utils\ZMUtil;

/**
 * Class Hello
 * @package Module\Example
 * @since 2.0
 */
class Hello
{
    /**
     * 使用命令 .reload 发给机器人远程重载，注意将 user_id 换成你自己的 QQ
     * @CQCommand(".reload",user_id=627577391)
     */
    public function reload() {
        ctx()->reply("重启中...");
        ZMUtil::reload();
    }

    /**
     * @CQCommand("我是谁")
     */
    public function whoami() {
        $user = ctx()->getRobot()->getLoginInfo();
        return "你是" . $user["data"]["nickname"] . "，QQ号是" . $user["data"]["user_id"];
    }

    /**
     * 向机器人发送"你好啊"，也可回复这句话
     * @CQCommand(match="你好",alias={"你好啊","你是谁"})
     */
    public function hello() {
        return "你好啊，我是由炸毛框架构建的机器人！";
    }

    /**
     * 一个简单随机数的功能demo
     * 问法1：随机数 1 20
     * 问法2：从1到20的随机数
     * @CQCommand("随机数")
     * @CQCommand(pattern="*从*到*的随机数")
     * @return string
     */
    public function randNum() {
        // 获取第一个数字类型的参数
        $num1 = ctx()->getNumArg("请输入第一个数字");
        // 获取第二个数字类型的参数
        $num2 = ctx()->getNumArg("请输入第二个数字");
        $a = min(intval($num1), intval($num2));
        $b = max(intval($num1), intval($num2));
        // 回复用户结果
        return "随机数是：" . mt_rand($a, $b);
    }

    /**
     * 中间件测试的一个示例函数
     * @RequestMapping("/httpTimer")
     * @Middleware("timer")
     */
    public function timer() {
        return "This page is used as testing TimerMiddleware! Do not use it in production.";
    }

    /**
     * 默认示例页面
     * @RequestMapping("/index")
     * @RequestMapping("/")
     */
    public function index() {
        return "Hello Zhamao!";
    }

    /**
     * 使用自定义参数的路由参数
     * @RequestMapping("/whoami/{name}")
     * @param $param
     * @return string
     */
    public function paramGet($param) {
        return "Hello, ".$param["name"];
    }

    /**
     * 在机器人连接后向终端输出信息
     * @OnOpenEvent("qq")
     * @param $conn
     */
    public function onConnect(ConnectionObject $conn) {
        Console::info("机器人 " . $conn->getOption("connect_id") . " 已连接！");
    }

    /**
     * 在机器人断开连接后向终端输出信息
     * @OnCloseEvent("qq")
     * @param ConnectionObject $conn
     */
    public function onDisconnect(ConnectionObject $conn) {
        Console::info("机器人 " . $conn->getOption("connect_id") . " 已断开连接！");
    }

    /**
     * 阻止 Chrome 自动请求 /favicon.ico 导致的多条请求并发和干扰
     * @OnRequestEvent(rule="ctx()->getRequest()->server['request_uri'] == '/favicon.ico'",level=200)
     */
    public function onRequest() {
        EventDispatcher::interrupt();
    }

    /**
     * 框架会默认关闭未知的WebSocket链接，因为这个绑定的事件，你可以根据你自己的需求进行修改
     * @OnOpenEvent("default")
     */
    public function closeUnknownConn() {
        Console::info("Unknown connection , I will close it.");
        server()->close(ctx()->getConnection()->getFd());
    }
}
