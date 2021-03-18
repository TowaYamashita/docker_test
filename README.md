# title

todo_yamashita

# version

```
$ docker --version
Docker version 20.10.2, build 2291f61

$ docker-compose --version
docker-compose version 1.27.4, build 40524192

$ php --version
PHP 7.4.15 (cli) (built: Feb  4 2021 17:27:38) ( NTS )

$ composer --version
Composer version 1.10.15 2020-10-13 15:59:09

$ psql --version
psql (PostgreSQL) 12.5 (Debian 12.5-1.pgdg100+1)
```
# how to use

## 1. VSCodeにDockerの拡張機能を導入する

## 2. 初期設定

```
$ composer install
```

## 3. 以下のコマンドを打って、ビルトインサーバを起動する

```
$ php -S 0.0.0.0:80 -t /home/public/
```

## 4. ローカルのブラウザで以下のリンクにアクセスする

http://localhost:8080