# otrouha
📚 Thesis Library Management Web-Application for Universities

## Development

### Install

Just run

```sh
composer install -o
```

### Configuring hosts

For adding host (in Ubuntu, for example) just add

```192.168.33.38 otrouha.dev```
```192.168.33.38 cdn.otrouha.dev```

into your ```/etc/hosts file```

Then you should restart your server.

And on windows just and that line to ```C:\Windows\System32\drivers\etc\hosts```

### Configuring apache2

Edit the line in 
```/etc/apache2/apache2.conf```

```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>
```

To look like the following

```
<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
```

Edit
```
/etc/apache2/sites-enabled/000-default.conf
```

Set DocumentRoot

```
DocumentRoot /var/www/html/public
```

### Create the database

Create the database ```otrouha``` for your environment using ```utf8mb4``` encoding.
For test environment create ```otrouha_test``` database with details in ```app\config\config-test.ini```

### Allow remote access to MySQL

For that you need to edit the file ```/etc/mysql/my.cnf``` and comment the line

```
# bind-address          = 127.0.0.1
```

The connect to mysql console using ```mysql -u root -p``` and run the following command

```
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'change_it_by_root_password' WITH GRANT OPTION;
```

### Secure MySQL for production

Just run the command ```mysql_secure_installation```

### Fix code style

Before every commit general code style needs to checked

```sh
./tools/opinion --fix
```

### Configure Xdebug With Vagrant & PhpStorm

Edit file `/etc/php/5.6/mods-available/xdebug.ini` by adding the lines:

```
xdebug.remote_enable = On
xdebug.remote_connect_back = On
```

Then restart apache `sudo service apache2 restart`

In PhpStorm got to `File->Settings->Languages & Frameworks->PHP` and add a new
PHP Remote/Vagrant interpreter.

Then `File->Settings->Languages & Frameworks->PHP->Servers` add a new server.

And finally in menu `Run->Edit Configurations-`, create a new `PHP Web Application`
and assign the server you just created.

### Running unit tests

Unit tests can be ran in three ways

1. Using command line
  * Without coverage : `./tools/opinion --test`
  * With code coverage : `../tools/opinion --test -c`
2. Using the web interface: Open run `./tools/opinion --enabletests` then
navigate the address http://otrouha.dev/?exam or
http://otrouha.dev/?exam=withCoverage to run with code coverage
3. Using command line
  * Show help, test suites that can be ran : `php public/index.php "/?exam&help"`
  * Without coverage : `php public/index.php "/?exam&cli&test=all"`
  * With code coverage : `php public/index.php "/?exam=withCoverage&cli&test=all"`
  * You can specify group of tests via test param : `php public/index.php "/?exam&cli&test=<group_name>"`
  * To get all group names : `php public/index.php "/?exam&cli"`

The result of the tests can be viewed at http://otrouha.dev/exam/result/

### Debugging sent emails
Sent email works in two different ways:
1. In **production** all emails are sent using SMTP.
2. In **non production** all emails are saved to files inside
```./tmp/mail/``` directory. Please use "MailView.zip" application
available in the downloads section to read them.


### Create a new migration

Writing migrations guide can be found at this link
http://docs.phinx.org/en/latest/migrations.html

```sh
vendor/bin/phinx create SignificantMigrationName
```

### Running migrations

After creating a migration it can be ran using the command where "-e"
contains the environment name

```sh
vendor/bin/phinx migrate -e development
```

For rolling back a migration, use this carefully on development and
never use it in production, instead for production create new migrations
that fixes previous ones.

```sh
vendor/bin/phinx rollback -e development
```

### Working with CDN

Allowed sizes for CDN images are in ```cdn.ini```
