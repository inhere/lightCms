# lightCms

a php micro CMS application 

## web 应用

## console 应用

直接运行 `php bin/console`

```
Usage:
  bin/console [route|command] [arg1=value1 arg2=value ...] [-v|-h ...]

Example:
  bin/console test
  bin/console home/index
  bin/console test -e=ali // 设定环境配置,默认环境是pdt. 将会加载 config/console/{env} 来覆盖默认配置

```

### 环境配置

拷贝根目录的环境配置`.env.example`，并重命名为 `.env`

当前环境就会以 `.env` 覆盖默认配置内容
