# Laravel Educational Management

**使用文档见 [docs/readme.md](./docs/readme.md)**

生产环境地址：
- 项目地址：<https://laravel-edu.herokuapp.com/>
  - 测试账号，密码均为 `secret`
    - tom.student@jerry.com 
    - tom.teacher@jerry.com 
    - tom.system_admin@jerry.com
    - 更多详见 database/seeds/config.php UserSeeder 部分
- Admin：<https://laravel-edu.herokuapp.com/admin>
  - 测试账号 admin:admin
- API Spec：<https://laravel-edu.herokuapp.com/api>
- API doc：<https://laravel-edu-api-doc.herokuapp.com/>

## Requirements

- PHP 7.3

## Features

- 后端
    - OpenAPI
    - PHPUnit 单元测试/集成测试
      - migrations
      - seeders
      - factories

- 前端 (front-end 子目录)
    - Element UI + Vue.js
    - Cypress 集成测试

## 开发环境部署

```bash
# clone 仓库
git clone git@github.com:zhwei/laravel-edu.git
cd laravel-edu


# 后端环境（默认监听 8000 端口，如果修改端口需要同步修改前端 front-end/.env.development 配置文件）
composer init-development
composer serve
# 访问 http://127.0.0.1:8000/api  查看 api spec

# 前端（默认监听 8080 端口，如果修改端口需要同步修改后端 .env.local 配置文件）
cd front-end
yarn install
yarn serve
```

## 生产环境 (docker)

```bash
docker build . -t laravel-edu
docker --rm -it -p 8000:8000 run laravel-edu
```

## Heroku 部署备忘

- [Building Docker Images with heroku.yml](https://devcenter.heroku.com/articles/build-docker-images-heroku-yml)

## 常用命令

- 构建缓存（路由、配置文件等）：`composer build`
- 清理构建缓存（路由、配置文件等）：`composer clean`
- 生成 ide 自动补全文件：`composer ide`
- 重置数据库：`composer reset-database`

## todo

- admin 中的 relation 编辑
- pusher 权限判定
- 前端调用接口时缺少 loading 提示
- 提升前端继承测试覆盖度
- 后端 line 接口缺少 mock 测试
