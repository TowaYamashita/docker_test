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

## 1. VSCodeにDockerの拡張機能(ms-vscode-remote.remote-containers)を導入する

## 2. git cloneで落としてからVSCode > Open a Remote Window > Remote Containers: Reopen in Container

```
$ git clone https://github.com/TowaYamashita/todo_yamashita.git
```

## 3. VSCode内でターミナルを開き、スクリプトを実行する

```
$ ./startup.sh
```

## 3. ローカルのブラウザで以下のリンクにアクセスする

> http://localhost:8080