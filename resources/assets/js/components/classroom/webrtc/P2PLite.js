import Peer from './Peer'
import Event from 'events'

export class P2PLite {

  /**
   * @param {Signalhub} signalServer - interface server Signalhub
   * @param {Object} options
   */
  constructor(signalServer, stream, options) {
    this.server = signalServer;
    this.emit = new Event();
    this.peers = {};
    this.stream = stream;
    this.options = {
      uuid: P2PLite.randomId() + '-' + P2PLite.randomId(),
      params: {},
    };

    Object.assign(this.options, options);

    this.init();
  }

  init() {
    this.createUser(this.options.uuid, this.options.params);

    // add local stream
    this.getUser().addStream(this.stream);

    // answer simple-peer
    this.getUser().on('signal', message => {
      let user = this.peers[message.uuid];

      if (!user) {
        user = this.createUser(message.uuid, message.params);
      }

      if (!user.connect) {
        user.setFrom(this.getUser());
        user.call(false);
      }

      user.connect.peer.signal(message.signal);
    });

    this.onClose(uuid => {
      if (this.peers[uuid]) {
        delete this.peers[uuid];
      }
    });
  }

  /**
   * This user 
   * 
   * @return {Peer}
   */
  getUser() {
    return this.peers[this.options.uuid];
  }

  /**
   * @param {number} id 
   * @return {Peer}
   */
  getUserById(id) {
    return this.peers[id] || null;
  }

  /**
   * @param {any} uuid 
   * @return {boolean}
   */
  isUser(uuid) {
    return this.getUser().getId() === uuid;
  }

  /**
   * @param {any} uuid 
   * @param {Object} params
   * @return {Peer}
   */
  createUser(uuid, params) {
    if (!this.peers[uuid]) {
      this.peers[uuid] = new Peer(uuid, params, this.server, this.getUser());
    }

    return this.peers[uuid];
  }

  /** @return string */
  static randomId() {
    return Math.floor(Math.random() * 0xFFFFFF).toString(16);
  }

  /**
   * User send to signal server
   * 
   * @param {string|number} event
   */
  sendUser(event) {
    this.server.broadcast(event, [this.options.uuid, this.options.params]);
  }

  /**
   * Listen new peers
   * 
   * @param {Function} handler
   */
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

  /**
   * Close connection
   * 
   * @param {Function} handler
   */
  onClose(handler) {
    this.server.subscribe('close-peer').on('data', uuid => {
      handler(uuid);
    });
  }

  /**
   * Listen stream
   * 
   * @param {Function} handler
   */
  onStream(handler) {
    this.getUser().on('stream', uuid => {
      let peer = this.getUserById(uuid);
      handler(peer);
    });
  }

  /**
   * @return {number}
   */
  total() {
    return Object.keys(this.peers).length;
  }

  map(handler) {
    Object.keys(this.peers).forEach(key => {
      handler(key, this.peers[key]);
    });
  }
}