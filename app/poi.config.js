module.exports = (options, req) => ({
  filename: {
    js: '[name].js',
    css: 'style.css',
    fonts: 'assets/fonts/[name].[ext]',
    images: 'assets/images/[name].[ext]',
    chunk: '[id].chunk.js'
  },
  env: {
    API_URL: 'http://api.madp.localhost/'
  }
})