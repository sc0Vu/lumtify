# lumtify

[![Build Status](https://travis-ci.org/sc0Vu/lumtify.svg?branch=master)](https://travis-ci.org/sc0Vu/lumtify)
[![Coverage Status](https://coveralls.io/repos/github/sc0Vu/lumtify/badge.svg?branch=master)](https://coveralls.io/github/sc0Vu/lumtify?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/58d7719e6893fd004792c9e7/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/58d7719e6893fd004792c9e7)

[Lumen](https://github.com/laravel/lumen) + [Vuetify](https://github.com/vuetifyjs/vuetify) blog

lumtify is a blog cms build with lumen and vuetify


## Install

PHP

    composer install

    mv .env.example .env
    
    touch database/lumtify.sqlite

    php artisan migrate

    php artisan jwt:secret

Frontend
    
    npm install // yarn install

    gulp // compile assets
    
## Test

PHP

    mv .env.example .env && touch database/lumtify.sqlite && php artisan migrate --seed && php artisan jwt:secret

    vendor/bin/phpunit

Frontend

    to be continued

## Demo

[website](https://lumtify.ptrgl.com/)

* admin

    username: admin@lumtify.com

    password: ilovelumtify

* editor

    username: editor@lumtify.com

    password: ilovelumtify

## License

[MIT license](http://opensource.org/licenses/MIT)
