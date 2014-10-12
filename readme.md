Venne Sandbox
=============



Installation
------------

```
composer install
vendor/bin/phing build
```

Now set up connection in `app/config/config.local.neon`

```
vendor/bin/phing update-db
php www/index.php venne:install
```
