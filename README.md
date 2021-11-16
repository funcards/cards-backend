# FunCards - Backend

### Install (dev)

```shell
composer install
docker-compose up -d
bin/console jwt:generate-keypair
bin/console migrations:migrate
./vendor/bin/rr get
./rr serve
```

[http://localhost:8080/swagger/ui](http://localhost:8080/swagger/ui)
