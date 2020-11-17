module.exports = {
  css: {
    // Enable CSS source maps.
    sourceMap: process.env.NODE_ENV !== 'production'
  },
  devServer: {
    proxy: {
      '/api': {
        target: 'http://beta.haiwai.com',
        ws: true,
        changeOrigin: true
      },
      '/data': {
        target: 'https://cdn.wenxuecity.com',
        ws: true,
        changeOrigin: true
      },
    },
    
    // proxy: 'http://beta.haiwai.com'
  }
};
