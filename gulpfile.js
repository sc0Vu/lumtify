const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir.config.notifications = false;

elixir(mix => {
	// compile js
    mix.webpack(
    	'./resources/assets/js/app.js',
    	'./public/js/app.js'
    );
});