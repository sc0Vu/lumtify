# lumtify

[![Build Status](https://travis-ci.org/sc0Vu/lumtify.svg?branch=master)](https://travis-ci.org/sc0Vu/lumtify)

[Lumen](https://github.com/laravel/lumen) + [Vuetify](https://github.com/vuetifyjs/vuetify) blog

lumtify is a blog cms build with lumen and vuetify


## Install

PHP

    composer install
    
    touch database/lumtify.sqlite

    php artisan migrate

Frontend
    
    npm install // yarn install

    gulp
    
## Test

PHP

    mv .env.example .env && touch database/lumtify.sqlite && php artisan migrate --seed && php artisan jwt:secret

    vendor/bin/phpunit

Frontend

    to be continued

## License

[MIT license](http://opensource.org/licenses/MIT)
