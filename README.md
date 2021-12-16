# AluShop

E-shop selling ALU wheels written in Nette.

## Setup

For development it's needed to create local config.

Just create `config/local.neon` file with *parameters* section copied from `common.neon` and fill up right values of DB config etc.

## Database

To run database locally, just run:
```shell
docker-compose up -d
```

It will run mariadb container in detached mode on localhost:3306. 

See credentials in `docker-compose.yml` file.

Then, just run sql scripts in `migrations/` folder one after one (or just some of them if you have already run some of them).

## Dev

Run local php server to serve Nette app on port 8000:
```shell
php -S localhost:8000 -t www
```