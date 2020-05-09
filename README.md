## Demonstrate RedBean problem under non-partial bean mode

RedBean ORM suffers from a data update problem if _partial bean mode_ 
is not turned on (this mode is not turned on by default). It is because
it does not maintain a change list on a data object resulting in later
update overriding the changes made in previous update.

Simply speaking, when a record is fetched in method A, made some changes
without saving them to database and then calls method B.

Method B also fetch the same record, made some other changes and save 
them to database.

When method B finishes and method A resumes it's execution to save the
changes it made to database.

The result is the changes made in A overrides that of B.

It has a serious problem because method A should not be assumed to have
knowledge on how B works.

Doctrine ORM would not suffer from that problem because it maintains a
change list in its unit of work.

This problem was discovered when working on a company project using 
RedBean.

### Prepare database

```bash
echo 'CREATE USER redbean IDENTIFIED BY "redbean"' | mysql -u root
echo 'CREATE DATABASE redbean' | mysql -u root
echo 'GRANT ALL ON redbean.* to redbean' | mysql -u root
echo 'FLUSH PRIVILEGES' | mysql -u root
```

### Installation
```bash
git clone git@github.com:terenceybwong/orm.git
cd orm
composer install
```

### Run update by RedBean
```bash
php -e src/readbean.php
```

### Run update by Doctrine
_(We have not yet installed Symfony console command to allow Doctrine to
create database tables for us, so you need to create the tables yourself
manually in the mean time.)_
```
php -e src/doctrine/src/Shop.php
```
