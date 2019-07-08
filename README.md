# Installation

## Prepare database

```bash
echo 'CREATE USER readbean IDENTIFIED BY "redbean"' | mysql
echo 'CREATE DATABASE readbean' | mysql
echo 'GRANT ALL ON redbean.* to `redbean`@`%`' | mysql
echo 'FLUSH PRIVILEGES' | mysql
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
