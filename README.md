# Admin-board

Описание проекта в Confluence здесь: https://confluence.bank131.ru/pages/viewpage.action?pageId=62900760

В этой документации описанны основные моменты по проекту Admin Board

## Запуск

### Требования
- Docker
- PHP 8.1*
- Composer 2*

\* Необходимы для использования локального компосера или локального исполнения ```/bin/console```
Важно! Demo и prod окружения должны разворачиваться только на отдельных воркерах согласно pci dss требованиям.

### Подготовка
Для работы c composer вам необходимо войти наш Container Registry, для этого:
- Получите токен с правами read_registry в [в гитлабе](https://gitlab.bank131.ru/-/profile/personal_access_tokens)
- Войдите в Container Registry используя ваш логин и полученный токен как пароль
  - docker login registry.bank131.ru

### Настройка composer
Для работы можно использовать как локальный composer, так и заранее подготовленный composer в докере

#### Настройка локального компосера
```shell
composer config -g repositories.bank131-private-packagist composer https://packagist.bank131.ru
composer config -g repositories.bank131-toran-proxy composer https://toran-proxy.bank131.ru/repo/packagist/
composer config -g repositories.packagist false
```

#### Алиас для запуска заранее заготовленного composer

***Linux***
```shell
alias composer-131='docker run --rm --interactive --tty --volume $HOME/.ssh/known_hosts:$HOME/.ssh/known_hosts:ro  --volume $(pwd):/var/www --volume $SSH_AUTH_SOCK:/ssh-auth.sock --volume /etc/passwd:/etc/passwd:ro --volume /etc/group:/etc/group:ro --env SSH_AUTH_SOCK=/ssh-auth.sock --user $(id -u):$(id -g) --net=host registry.bank131.ru/131/dev-tools/php/8.1-composer:latest'
```
***Mac Os***
```shell
alias composer-131='docker run --rm --interactive --tty --volume $HOME/.ssh/known_hosts:$HOME/.ssh/known_hosts:ro  --volume $(pwd):/var/www --volume $SSH_AUTH_SOCK:/ssh-auth.sock --env SSH_AUTH_SOCK=/ssh-auth.sock --net=host registry.bank131.ru/131/dev-tools/php/8.1-composer:latest'
```

#### Запуск

1. Установите зависимости
```shell
composer install
```
2. Замените ```fastcgi_pass 127.0.0.1:9000;``` на ```fastcgi_pass admin-board_php-fpm:9000;``` 
в файле ```/dockerfiles/nginx/conf.d/admin-board```


3. Запустите контейнер
```shell
docker-compose up -d;
```

## Основная информация

Проект построен на основе библиотеки [EasyAdmin](https://symfony.com/bundles/EasyAdminBundle/current/index.html), 
всю необходимую информацию по работе проекта можно найти там.

Все контройлеры crud сущностей расположены в папке ```/src/Controller```, 
контройлеры подключаемых в другие сущности сущностей находятся в папке ```/src/Controller/CascadeCrudControllers```

Для авторизации используется Security бандл, в качестве провайдера банковский ldap