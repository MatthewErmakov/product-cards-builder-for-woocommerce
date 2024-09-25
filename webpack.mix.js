const mix = require('laravel-mix');

// Set the environment
const isProduction = process.env.NODE_ENV === 'production';

// Define the entry points for admin and front
mix.js('src/admin/js/index.js', './public/assets/admin.min.js')
   .sass('src/admin/scss/style.scss', './admin.css')
   .js('src/front/js/index.js', './public/assets/front.min.js')
   .sass('src/front/scss/style.scss', './front.css');

// Versioning for cache busting in production
mix.version();

// Enable source maps in development
mix.sourceMaps();

// Set the public path for assets
mix.setPublicPath('public/assets');
