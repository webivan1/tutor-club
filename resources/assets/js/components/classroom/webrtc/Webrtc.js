import SimplePeer from 'simple-peer'

export default class Webrtc {

  constructor(from, to, server) {
    this.from = from;
    this.to = to;
    this.peer = null;
    this.server = server;
  }

  init(initiator) {
    this.peer = new SimplePeer({
      initiator: initiator,
      stream: this.from.getStream(),
      //trickle: false,
    });

    this.peer.on('connect', _ => {
      // ...
    });

    this.peer.on('data', data => {
      this.from.attachEvent('data', JSON.parse(data.toString()));
    });

    this.peer.on('signal', signal => {
      this.to.send('signal', {
        uuid: this.from.getId(),
        params: this.from.getParams(),
        signal: signal
      });
    });

    this.peer.on('stream', stream => {
      this.to.addStream(stream);
    });

    this.peer.on('close', _ => {
      this.server.broadcast('close-peer', this.to.getId());
    });

    return this.peer;
  }
}