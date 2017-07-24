# Test Phalcon API
Test api service built on phalcon php framework v3. This application creates an API and exposes the three following endpoints:

* `GET /api/v1/files` List
* `GET /api/v1/files/1` Info
* `POST /api/v1/files` Create

There are additional routes defined also:

* `GET /public/docs/html` Documentation
* `GET /download/filename.xlsx` Download

## Get Started

### Requirements

* [PHP][1] >= 5.6
* [Web Server][2] with php enabled
* Latest stable [Phalcon Framework release][3] extension enabled
* [MySQL][4] >= 5.6
* [Codeception][5] >= 2.2
* [PHPExcel][6] >= 1.8
* [NodeJS + NPM][7] >= 6.11

### Installation

#### 1. Clone the git repo

First you need to clone this repository:

```sh
git clone https://github.com/latheesan-k/test-phalcon-api.git
```

Install composer in a common location or in your project:

```sh
curl -s http://getcomposer.org/installer | php
```

Then install composer dependencies:

```sh
php composer.phar install --prefer-dist
```

#### 2. Setup Database

Create the database in your mysql server:

```sh
echo 'CREATE DATABASE test-phalcon-api CHARSET=utf8 COLLATE=utf8_unicode_ci' | mysql -u root -p
```

Then create the required table(s) and import dummy data:

```
mysql -u root -p test-phalcon-api < schemas/upload_files.sql
```

#### 3. Create & configure application settings

First copy the sample settings file

```sh
cp app/config/settings.ini.sample settings.ini
```

Then update the `app/config/settings.ini` with your server details.

#### 4. Example vHost configuration for Apache2

Here is how you can quickly setup a vhost entry for your apache2 to serve this project.

```
<VirtualHost *:80>
    ServerName test-phalcon-api.local
    DocumentRoot "C:/xampp/htdocs/test-phalcon-api.local
</VirtualHost>
```

* Don't forget to enable `mod_rewrite` in your apache2 server

#### 5. Test your api

> API documentation can be read by visiting [http://test-phalcon-api.local/public/docs/html/][8]

> GET http://test-phalcon-api.local/api/v1/files - Returns a list of uploaded files

> GET http://test-phalcon-api.local/api/v1/files/1 - View single uploaded file info

> POST http://test-phalcon-api.local/api/v1/files - Upload a new file

> GET http://test-phalcon-api.local/download/filename.xlsx - Download an uploaded file.

See api [docs][8] for more detail.

### How to run the tests

#### 1. Create & configure application settings

First copy the sample settings file

```sh
cp tests/_config/settings.ini.sample settings.ini
```

Then update the `tests/_config/settings.ini` with your test server details.

#### 2. Build and Run codeception tests

Windows:

```
run_tests.bat
```

Linux:

```
sh run_tests.sh
```

### How to build docs

_Requites NodeJS and NPM installed._

1. Install `npm install apidoc -g`
2. Run `sh build.sh` (Linux) or run the `build.bat` (Windows) from `public/docs` dir

Built api documentation files can be viewed from your application url, for example: 

```
http://test-phalcon-api.local/public/docs/html/
```

[1]: http://php.net/
[2]: https://httpd.apache.org/
[3]: https://github.com/phalcon/cphalcon/releases/
[4]: https://www.mysql.com/
[5]: http://codeception.com/
[6]: https://github.com/PHPOffice/PHPExcel/
[7]: https://nodejs.org/en/
[8]: http://test-phalcon-api.local/public/docs/html/