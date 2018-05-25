var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var axios = require('axios');

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

http.listen(6002, function () {
  console.log('listening on *:6002');
});