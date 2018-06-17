import event from 'events'

class Peer {
  constructor(uuid, params, server) {
    this.uuid = uuid;
    this.server = server;
    this.params = params;
    this.stream = null;
    this.event = new event();
  }

  addStream(stream) {
    this.stream = stream;
  }

  getStream() {
    return this.stream;
  }

  getUuid() {
    return this.uuid;
  }

  getParams() {
    return this.params;
  }

  send(event, message) {
    this.server.broadcast(`${event}-${this.uuid}`, message);
  }

  on(event, handler) {
    this.server.subscribe(`${event}-${this.uuid}`).on('data', message => {
      handler(message);
    });
  }
}

export default class SignalP2P {

  /**
   * @param Signalhub signalServer - interface server Signalgub
   * @param Object options
   */
  constructor(signalServer, options) {
    this.server = signalServer;
    this.peers = {};
    this.options = {
      uuid: SignalP2P.randomId() + '-' + SignalP2P.randomId(),
      params: {}
    };

    Object.assign(this.options, options);

    this.init();
  }

  init() {
    this.createUser(this.options.uuid, this.options.params);

    this.onClose(peer => {
      if (this.peers[peer.getUuid()]) {
        delete this.peers[peer.getUuid()];
      }

      if (this.users[peer.getUuid()]) {
        delete this.users[peer.getUuid()];
      }
    });
  }

  getUser() {
    return this.peers[this.options.uuid];
  }

  isUser(uuid) {
    return this.getUser().getUuid() === uuid;
  }

  createUser(uuid, params) {
    if (!this.peers[uuid]) {
      this.peers[uuid] = new Peer(uuid, params, this.server);
    }

    return this.peers[uuid];
  }

  /** @return string */
  static randomId() {
    return Math.floor(Math.random() * 0xFFFFFF).toString(16);
  }

  sendUser(event) {
    this.server.broadcast(event, [this.options.uuid, this.options.params]);
  }

  onSignal(handler) {
    this.server.subscribe('all')
      .on('data', message => {
        let [ id, params ] = message;

        if (this.peers[id]) {
          return false;
        }

        let peer = this.createUser(id, params);

        if (id !== this.options.uuid) {
          handler(peer);
        }
      });

    this.sendUser('all');
  }

  onRealtime(handler) {
    this.server.subscribe('online')
      .on('data', message => {
        let [ id, params ] = message;

        handler(this.createUser(id, params));
      });

    setInterval(_ => this.sendUser('online'), 1000);
  }

  onClose(handler) {
    window.onbeforeunload = () => {
      handler(this.getUser());
    };
  }

  totalPeers() {
    return Object.keys(this.peers).length;
  }

  map(handler) {
    return Object.keys(this.peers).forEach(key => {
      handler(key, this.peers[key]);
    });
  }

  getArray() {
    let arr = [];

    this.map((key, peer) => {
      arr.push(peer);
    });

    return arr;
  }
}