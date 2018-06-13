var fs = require('fs');
var app = require('express')();
var config = require('./config');

var port = config.port || 6002;
var secure = config.secure || false;

if (secure === true) {
  var options = {
    key: fs.readFileSync(config.secureKey),
    cert: fs.readFileSync(config.secureCert)
  };

  var server = require('https').createServer(options, app);
} else {
  var server = require('http').Server(app);
}

var io = require('socket.io')(server);

io.on('connection', function (socket) {

  // Запрос от юзера на проверку доступности собеседника
  socket.on('send.user', function (user) {
    // Отправляем запрос клиенту
    io.emit('is.online.' + user);
  });

  // Получаем ответ от юзера
  socket.on('user.online', function (user) {
    io.emit('user.online.' + user, user);
  });

});

server.listen(port, function () {
  console.log('listening on *:' + port);
});