# Installation

## Prepare database

```bash
echo 'CREATE USER redbean IDENTIFIED BY "redbean"' | mysql -u root
echo 'CREATE DATABASE redbean' | mysql -u root
echo 'GRANT ALL ON redbean.* to redbean' | mysql -u root
echo 'FLUSH PRIVILEGES' | mysql -u root
```

## Installation
```bash
git clone git@github.com:terenceybwong/orm.git
cd orm
composer install
```

## Run
```bash
php -e src/readbean.php
```
