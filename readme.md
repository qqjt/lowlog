A PHP blog built upon laravel framework & bootstrap 4. Check [low.bi](https://low.bi) for demo.
## Features
 - simple blog with fundamental functions, post, page, tag, comment, archive etc
 - mobile friendly (thanks to bootstrap)
 - markdown support(only) with synatax highlighting
 - automatic table of contents generation
 - fulltext search using elasticsearch and ik analytics
## Installation
### Requirements
Linux+nginx+MySQL+PHP(7.1+), install any required PHP extension if necessary.
### Install as a typical laravel project
```sh
git clone https://github.com/qqjt/lowlog.git
cd lowlog
php artisan install
cp .env.example .env
php artisan key:generate
```
Modify the .env file, migrate database:
`php artisan migrate`
and finally run a custom artisan command:
`php artisan blog:init`
### Configure nginx
sample nginx conf: [nginx-vhost.conf](/qqjt/lowlog/blob/master/nginx-vhost.conf)
### Install elasticsearch for search
Follow the instructions on [elastic](https://www.elastic.co/guide/en/elasticsearch/reference/current/install-elasticsearch.html) website, install jdk first.
install [ik-analysis](https://github.com/medcl/elasticsearch-analysis-ik) plugin if necessary.
## Additional packages
### PHP
|name|despcription|
|:---:|:---:|
|[barryvdh/laravel-translation-manager](https://github.com/barryvdh/laravel-translation-manager)|manage Laravel translation files|
|[caouecs/laravel-lang](https://github.com/caouecs/Laravel-lang)|Laravel framework translation files|
|[hieu-le/active](https://github.com/letrunghieu/active)|handle active link css class|
|[laravel/scout](https://github.com/laravel/scout)|fulltext search|
|[sunra/php-simple-html-dom-parser](https://github.com/sunra/php-simple-html-dom-parser)|parse html dom to generate toc|
|[tamayo/laravel-scout-elastic](https://github.com/ErickTamayo/laravel-scout-elastic)|elastic scout driver|
|[thomaswelton/laravel-gravatar](https://github.com/thomaswelton/laravel-gravatar)|gravatar support|
|[vinkla/hashids](https://github.com/vinkla/laravel-hashids)|generate hashid|
### Frontend
|name|despcription|
|:---:|:---:|
|[bootstrap-tagsinput](https://github.com/bootstrap-tagsinput/bootstrap-tagsinput)|form input for post tag|
|[font-awesome](https://github.com/FortAwesome/Font-Awesome)|icons|
|[pc-bootstrap4-datetimepicker](https://github.com/Eonasdan/bootstrap-datetimepicker/)|datetime picker|
|[simplemde](https://github.com/NextStepWebs/simplemde-markdown-editor)|markdown editor|
|[inline-attachment](https://github.com/Rovak/InlineAttachment)|for file uploading in simplemde|
|[sweetalert](https://github.com/t4t5/sweetalert)|alert dialog for bootstrap|
|[prism](http://prismjs.com)|code syntax highlighting|