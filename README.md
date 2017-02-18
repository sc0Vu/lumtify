# lumtify

[![Build Status](https://travis-ci.org/sc0Vu/lumtify.svg?branch=master)](https://travis-ci.org/sc0Vu/lumtify)

[Lumen](https://github.com/laravel/lumen) + [Vuetify](https://github.com/vuetifyjs/vuetify) blog

lumtify is a blog cms build with lumen and vuetify


## Install

PHP

    composer install

Frontend
    
    npm install
    
## Test

PHP

    mv .env.example .env && touch storage/database/lumtify.sqlite && php artisan migrate --seed

    vendor/bin/phpunit

Frontend

    to be continued

## License

[MIT license](http://opensource.org/licenses/MIT)
