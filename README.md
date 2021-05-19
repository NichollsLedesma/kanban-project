<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

Yii 2 Advanced Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
developing complex Web applications with multiple tiers.

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-advanced.svg)](https://packagist.org/packages/yiisoft/yii2-app-advanced)
[![build](https://github.com/yiisoft/yii2-app-advanced/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-advanced/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

```
common
    config/              contains shared configurations
    mail/                contains view files for e-mails
    models/              contains model classes used in both backend and frontend
    tests/               contains tests for common classes    
console
    config/              contains console configurations
    controllers/         contains console controllers (commands)
    migrations/          contains database migrations
    models/              contains console-specific model classes
    runtime/             contains files generated during runtime
backend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains backend configurations
    controllers/         contains Web controller classes
    models/              contains backend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for backend application    
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
frontend
    assets/              contains application assets such as JavaScript and CSS
    config/              contains frontend configurations
    controllers/         contains Web controller classes
    models/              contains frontend-specific model classes
    runtime/             contains files generated during runtime
    tests/               contains tests for frontend application
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             contains frontend widgets
vendor/                  contains dependent 3rd-party packages
environments/            contains environment-based overrides
```
<br>
<p align="center">
    <h1 align="center">Yii2 Kanban project</h1>
</p>

# Trello borad
https://trello.com/b/9lrpAug0/avodah

# AdminLTE 3
https://adminlte.io/themes/v3/
https://www.yiiframework.com/extension/hail812/yii2-adminlte3

# Dragula
https://bevacqua.github.io/dragula/

# Kanban repo
https://github.com/NichollsLedesma/kanban-project

## Clone repo
1. git clone git@github.com:NichollsLedesma/kanban-project.git

## Build containers
2. sudo docker-compose -f docker-compose.yml up --build

## Install dependencies
3. cd kanban-project/
4. sudo docker exec -it yii2_kanban_php bash
5. composer update

## apply migrations with command 
6. sudo docker exec -it yii2_kanban_php bash
7. php init

### Adjust the components['db'] configuration in /path/to/kanban-project/common/config/main-local.php
8. 'dsn' => 'pgsql:host=pgsql;dbname=kanban_blocknerds'
 * also check your db's username & password

## apply migrations with command 
9. sudo docker exec -it yii2_kanban_php bash
10. php yii migrate

### Change the hosts file to point the domain to your server.
Windows: c:\Windows\System32\Drivers\etc\hosts

Linux: /etc/hosts

### Add the following lines:
127.0.0.1   frontend.test

127.0.0.1   backend.test

## Access APP
http://backend.test

http://frontend.test

## MailHog
http://frontend.test:8025