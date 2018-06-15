import Event from 'events'

const emit = new Event();

class Peer {
  constructor(uuid, params, server) {
    this.uuid = uuid;
    this.server = server;
    this.params = params;
  }

  send(message) {
    this.server.broadcast(`user-${this.uuid}`, message);
  }

  on() {
    return new Promise((resolve, reject) => {
      this.server.subscribe(`user-${this.uuid}`).on('data', message => {
        resolve(message, this);
      });
    });
  }
}

export default class WebrtcEasy {

  /**
   * @param Signalhub signalServer - interface server Signalgub
   * @param Object options
   */
  constructor(signalServer, options) {
    this.server = signalServer;
    this.peers = {};
    this.options = Object.assign({
      uuid: this.randomId(),
      stream: false,
      iceServers: [
        { url: 'stun:stun.l.google.com:19302' }
      ],
      params: {}
    }, options);

    this.connection = null;
  }

  /** @return string */
  static randomId() {
    return Math.floor(Math.random() * 0xFFFFFF).toString(16);
  }

  sendUser() {
    this.server.broadcast('all', [this.options.uuid, this.options.params]);
  }

  onConnect(handler) {
    this.server.subscribe('all')
      .on('data', message => {
        let { id, params } = message;

        if (this.peers[id]) {
          return false;
        }

        this.peers[id] = new Peer(id, params, this.server);

        this.initConnection();

        handler(this.peers[id]);
      });

    this.sendUser();
  }

  onClose() {
    // ...
  }

  totalPeers() {
    return Object.keys(this.peers).length;
  }

  map(handler) {
    return Object.keys(this.peers).forEach(key => {
      handler(key, this.peers[key]);
    });
  }

  onStream(handler) {
    if (this.connection) {
      this.connection.ontrack = event => {
        const stream = event.streams[0];
        handler(stream);
      };
    }
  }

  initConnection(isOfferer) {
    // https://github.com/ScaleDrone/webrtc/blob/master/script.js

    this.connection = new RTCPeerConnection({ iceServers: this.options.iceServers });

    this.connection.onicecandidate = event => {
      if (event.hasOwnProperty('candidate')) {
        this.sendSignalingMessage({ candidate: event.candidate });
      }
    };

    if (this.totalPeers() > 1) {
      this.connection.onnegotiationneeded = () => {
        this.connection.createOffer()
          .then(this.localDescCreated.bind(this))
          .catch(this.onError);
      }
    }

    if (this.options.stream) {
      this.options.stream.getTracks()
        .forEach(track => this.connection.addTrack(track, this.options.stream));
    }
  }

  listenData() {
    this.server.subscribe('data').on('data', message => {
      if (this.options.uuid === message.uuid) {
        return false;
      }

      if (message.hasOwnProperty('sdp')) {
        this.connection.setRemoteDescription(new RTCSessionDescription(message.sdp), () => {
          // When receiving an offer lets answer it
          if (this.connection.remoteDescription.type === 'offer') {
            this.connection.createAnswer()
              .then(this.localDescCreated.bind(this))
              .catch(this.onError);
          }
        }, onError);
      } else if (message.hasOwnProperty('candidate')) {
        this.connection.addIceCandidate(
          new RTCIceCandidate(message.candidate), this.onSuccess, this.onError
        );
      }
    });
  }

  sendMessage(message) {
    Object.assign(message, {
      uuid: this.options.uuid,
      params: this.options.params
    });

    this.server.broadcast('data', message);
  }

  localDescCreated(desc) {
    this.connection.setLocalDescription(
      desc,
      _ => this.sendMessage({sdp: this.connection.localDescription}),
      this.onError
    );
  }

  on(event, handler) {
    emit.on(event, handler);
  }

  static onError(err) {
    emit.emit('wrtc.error', err);
  }

  static onSuccess(success) {
    emit.emit('wrtc.success', success);
  }
}