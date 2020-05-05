# curriculum 
[![Build Status](https://travis-ci.org/joachimdieterich/laravel-curriculum-adminlte3.svg?branch=master)](https://travis-ci.org/joachimdieterich/laravel-curriculum-adminlte3)
[![StyleCI](https://github.styleci.io/repos/184726557/shield?branch=master)](https://github.styleci.io/repos/184726557)

## Important Information
Work in progress - recoding curriculum in laravel - only use for test purposes 

curriculum is a learning platform where teachers can create topic-based learning objectives. The resulting curricula can be linked with learning groups and be viewed by learning group members. This will give students, teachers (and parents) a good overview of the objectives to be achieved. Not yet reached objectives are shown in red - if a objective is achieved, it will be shown in green or orange (if achieved with help). So curriculum provides a good overview of the current learning status. More information at http://www.curriculumonline.de 

## Installation

### Prerequisites
- PHP 7.3 
Extensions 
- ghostscript 
- Imagemagick
- xml, dom, zip, curl, mbstring, bcmath
- git, composer 
- Node.js ghostscript


### Step 1.

Begin by cloning this repository to your machine, and installing all Composer dependencies.
Make sure that your system is up-to-date.
```bash
sudo apt-get update
```

```bash
git clone https://github.com/joachimdieterich/laravel-curriculum-adminlte3.git
cd laravel-curriculum-adminlte3 
```
For development
```bash
composer install --no-dev
```
For production
```bash
composer install 
composer dump-autoload

npm install
npm update
npm run production
```

### Step 2. 

Next, rename `.env.example` to `.env` 

```bash
cp .env.example .env
```

Create a new database and reference its name and username/password within the project's `.env` file. In the example below, we've named the database, "curriculum."
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=curriculum
DB_USERNAME=root
DB_PASSWORD=root
```

For production change
```
APP_ENV=production
APP_DEBUG=false
```

Information about configurating `.env` in documentation `localhost:[port]/documentation`

### Step 3.

Migrate

```
php artisan key:generate
php artisan migrate --seed
``` 

### Step 4.

Start server

```
php artisan serve
``` 

### Step 5.

Login:

username: admin@curriculumonline.de
password: password

### Step 6.
Generate oAuth2 clients
```
php artisan passport:install
```
The Password grant client can be used to access the API.

## Accessing API

### Getting access_token
```
POST https://localhost:[port]/oauth/token
header
Content-Type: 'application/json' 

form-data
client_id: [id]
client_secret: [secret]
grant_type: 'client_credentials'
```
Use access token to access API
```
GET 'http://localhost:8000/api/v1/users' 

header 
Content-Type: 'application/json' 
X-Requested-With: 'XMLHttpRequest' 
Authorization: 'Bearer [access_token]'
``` 

## OpenApi Documentation Endpoint
 
Generate OpenApi Documentation
```
php artisan l5-swagger:generate
```

localhost:[port]/api/documentation
Info: If you want to use another url edit `@OA\Server` in `/app/Http/Controllers/Api/V1/OpenApiDefinitions/Setup.php` 

## SSO with SAML2
Curriculum uses [aacotroneo/laravel-saml2](https://github.com/aacotroneo/laravel-saml2) to integrate a SP (service provider).

Set up the `.env` to get SSO working. Example:
```
SAML2_RLP_IDP_HOST=https://{idpUrl}
SAML2_RLP_IDP_ENTITYID=https://{idpUrl}/idp/SAML2/METADATA
SAML2_RLP_IDP_SSO_URL=https://{idpUrl}/idp/SAML2/REDIRECT/SSO
SAML2_RLP_IDP_SL_URL=https://{idpUrl}/idp/SAML2/REDIRECT/SLO
SAML2_RLP_IDP_x509=IDPcertificate

SAML2_RLP_SP_x509=SP certificate
SAML2_RLP_SP_PRIVATEKEY=privatekey

SAML2_RLP_IDP_CONTACT_NAME=name
SAML2_RLP_IDP_CONTACT_EMAIL=email

SAML2_RLP_IDP_ORG_NAME=org name
SAML2_RLP_IDP_ORG_URL=some url
```

Further Settings are found in `config\saml2\rlp_idp_settings.php` and `config\saml2_settings.php`

You also have to set up your IDP. The following routes will help you:
```
http://{curriculumUrl}/saml2/{idpName}/acs
http://{curriculumUrl}/saml2/{idpName}/login
http://{curriculumUrl}/saml2/{idpName}/logout
http://{curriculumUrl}/saml2/{idpName}/metadata
http://{curriculumUrl}/saml2/{idpName}/sls
```
More information at [aacotroneo/laravel-saml2](https://github.com/aacotroneo/laravel-saml2)


## Enabling guest login
By default the guest user is seeded with ID 8.
To enable login (over login page or route ```"/guest"```) add ```GUEST_USER=8``` to ```.env```


If the organization of the guest user has a navigator, he will be redirected to the first view of this navigator. 
If there is no navigator, he is redirected to the dashboard.



## Generating PDFs (Certificates,...)
Curriculum uses [barryvdh/laravel-snappy] (https://github.com/barryvdh/laravel-snappy)

Check that wkhtmltopdf binaries are present. (Further information on [barryvdh/laravel-snappy] (https://github.com/barryvdh/laravel-snappy))
Binaries for linux are included in the package, those for macs can be found under [profburial/wkhtmltopdf-binaries-osx] (https://github.com/profburial/wkhtmltopdf-binaries-osx)

Set up the `.env` to get it working. Example:
```
SNAPPY_PDF_BINARY="/absolute_path_to/vendor/bin/wkhtmltopdf-amd64-osx"
SNAPPY_IMAGE_BINARY="/absolute_path_to/vendor/bin/wkhtmltoimage-amd64-osx"
```

Now you can generate PDFs. Example: 
```
return SnappyPdf::loadFile('http://curriculumonline.de')->inline('cur.pdf');
```

## Testing

### Feature and Unit Tests
```
./vendor/bin/phpunit
```

### Browser Tests (Dusk)
Important! Start server in dusk environment.

```
php artisan config:clear
php artisan serve --env=dusk.testing
```

Run browser tests

```
php artisan dusk
```

### Documentation
Curriculum uses [saleem-hadad/larecipe](https://github.com/saleem-hadad/larecipe) to provide integrated project documentation. 
The documentation can be found at the following URL `localhost:[port]/documentation`

### Roles and Permissions
The initial installation has 8 Roles: 
- Administrator
- Creator
- Indexer
- Schooladmin
- Teacher
- Student
- Parent
- Guest

The [Permission-Map](permissionmap.md) gives a quick view over the permissions of those roles.