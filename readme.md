本项目为 Lbb专项项目，使用方式为，下载laravel包，
配置.env环境数据库相关。
本项目使用的是laravel的auth用户认证，执行命令：`php artisan make:auth`

然后下载loid-frame包，下载好后命令执行:`php artisan loid:boot`
项目会自动创建相关数据表

项目有一项定时任务
详见vendor/jayson755/loid-module-lbb/autobearing.sh