var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

io.on('connection', function (socket) {
  socket.on('user.online', function (user) {
    io.emit('is.online.' + user, user);
  });
});

http.listen(6002, function () {
  console.log('listening on *:6002');
});