Symfony Boilerplate application
=========


```
alias d_composer='docker-compose exec fpm php -d memory_limit=-1 /usr/local/bin/composer $1'
alias d_console='docker-compose exec fpm bin/console $1'
alias d_php='docker-compose exec fpm $1'
alias d_mysql='docker-compose exec mysql mysql $1'
ralias d_node='docker-compose run --rm node $1'
alias d_yarn='docker-compose run --rm node yarn $1'
alias dc_start='docker-compose up -d'
alias dc_stop='docker-compose stop'
```

Project installation

```
cp .env.dist .env
dc_start
d_composer install -o
```
