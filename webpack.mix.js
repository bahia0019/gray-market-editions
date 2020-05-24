let mix = require('laravel-mix')

mix.setPublicPath('./')
mix.js('resources/js/app.js', 'dist/js/app.js')

mix.sass('resources/scss/app.scss', 'dist/css/app.css').options({
  postCss: [
    require('autoprefixer')({
      grid: false,
    }),
  ],
})

mix.version()
