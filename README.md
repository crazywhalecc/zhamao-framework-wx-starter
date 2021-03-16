# zhamao-framework-starter
炸毛框架的快速开始模板，是炸毛框架开箱即用的项目，基于炸毛框架的支持微信公众号一键对接现有OneBot协议后端代码的炸毛框架兼容层。

此模板和 <https://github.com/zhamao-robot/zhamao-framework-starter> 相同，唯一的区别就是此脚手架附带了对微信公众号的被动消息回复的最基本的聊天回复功能。

## 用法
见 [与微信公众号（个人订阅号）对接炸毛框架，一次代码两处可用](https://framework.zhamao.xin/advanced/wechat-impl/)（还没写完，不着急）

## 启动
```bash
git clone https://github.com/zhamao-robot/zhamao-framework-wx-starter.git
cd zhamao-framework-wx-starter/
composer update
vendor/bin/start server
```

## 指令
```bash
vendor/bin/start server                 # 以默认模式启动
vendor/bin/start server --log-debug     # 以 debug 的日志启动
vendor/bin/start server --debug-mode    # 以 debug 调试模式启动
```

## Composer 速度太慢
可尝试使用国内镜像源：
```bash
# 仅把当前项目的源地址改为国内
composer config repo.packagist composer https://mirrors.aliyun.com/composer/

# 取消国内源地址
composer config --unset repos.packagist
```
