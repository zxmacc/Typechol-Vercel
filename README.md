# Vercel-Typecho
Vercel 免费部署 Typecho 博客 | Vercel Free Deploy Typecho Blog

建议 fork 仓库后改为私有仓库，以免泄露自己的数据库用户名和密码

或者使用读取环境变量的方式读取数据库信息
```php
/** 定义数据库参数 */
$db = new Typecho_Db($_ENV["ADAPTER_NAME"], $_ENV["PREFIX"]);
$db->addServer(array (
  'host' => $_ENV["HOST"],
  'user' => $_ENV["USERNAME"],
  'password' => $_ENV["PASSWORD"],
  'charset' => $_ENV["CHARSET"],
  'port' => $_ENV["PORT"],
  'database' => $_ENV["DATABASE"],
  'engine' => $_ENV["ENGINE"],
), Typecho_Db::READ | Typecho_Db::WRITE);
Typecho_Db::set($db);
```

# 仓库来源
https://github.com/lete114/Vercel-Typecho
