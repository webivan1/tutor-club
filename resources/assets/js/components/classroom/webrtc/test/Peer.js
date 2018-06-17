import Webrtc from './Webrtc'

export default class Peer {

  constructor(uuid, params, server, from) {
    this.uuid = uuid;
    this.server = server;
    this.params = params;
    this.stream = null;
    this.connect = null;
    this.from = from || null;
  }

  addStream(stream) {
    this.stream = stream;

    if (this.from) {
      this.from.send('stream', this.getId());
    }
  }

  getStream() {
    return this.stream;
  }

  getId() {
    return this.uuid;
  }

  getParams() {
    return this.params;
  }

  send(event, message) {
    this.server.broadcast(`${event}.${this.uuid}`, message);
  }

  on(event, handler) {
    this.server.subscribe(`${event}.${this.uuid}`).on('data', message => {
      handler(message);
    });
  }

  setFrom(from) {
    this.from = from;
  }

  call(initiator) {
    try {
      if (this.from === null) {
        throw new Error('You can not call!');
      }

      this.connect = new Webrtc(this.from, this, this.server);
      this.connect.init(initiator);
    } catch (err) {
      console.error(err);
    }
  }
}