# lightCms

a php micro CMS application 

## console 应用

直接运行 `php bin/app`

```
Usage:
  bin/app.php [route|command] [arg1=value1 arg2=value ...] [-v|-h ...]

Example:
  bin/app.php test
  bin/app.php home/index
  bin/app.php test -e=ali // 设定环境配置,默认环境是pdt. 将会加载 config/console/{env} 来覆盖默认配置

```

## web 应用

### 环境配置

拷贝需要的环境配置`config/{env}`到项目根目录，并重命名为 `.env`

当前环境就会以 `.env.inc` 覆盖 `config/config.php` 的默认配置内容
