var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);

io.on('connection', function (socket) {
  console.log('a user connected');

  socket.on('test', function (msg) {
    console.log('message: ' + msg);
  });
});

http.listen(6002, function () {
  console.log('listening on *:6002');
});


// var request = require('request');
// var Socket  = require('socket.io')(6002, {
//   origins: ['local.my-tutor.club:*', 'dev.my-tutor.club:*', 'my-tutor.club:*']
// });
// var Redis = require('ioredis');
// var client = new Redis();
//
// // Socket.use(function (socket, next) {
// //   request.get({
// //     url: socket.request.headers.origin + '/check-user',
// //     json: true,
// //     headers: {
// //       cookie: socket.request.headers.cookie
// //     }
// //   }, function (error, response, json) {
// //     return json.auth ? next() : next(new Error('Auth error!'));
// //   });
// // });
//
// client.psubscribe('*', function (error, count) {
//   //...
// });
//
// client.on('pmessage', function (pattern, channel, message) {
//   message = JSON.parse(message);
//   Socket.emit(message.event, channel, message.data);
// });