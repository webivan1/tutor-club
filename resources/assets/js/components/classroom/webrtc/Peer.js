import Webrtc from './Webrtc'
import Event from 'events'

export default class Peer {

  constructor(uuid, params, server, from) {
    this.uuid = uuid;
    this.server = server;
    this.params = params;
    this.stream = null;
    this.connect = null;
    this.from = from || null;
    this.event = new Event();
    this.events = [];

    // listen events
    this.listenEvent();
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

  attachEvent(event, handler) {
    this.events.push([event, handler]);
  }

  sendMessage(event, message) {
    try {
      message = typeof message === 'object' ? message : JSON.parse(message);
    } catch (err) {
      message = { result: message };
    }

    Object.assign(message, { _eventName: event });

    this.event.emit('data', JSON.stringify(message));
  }

  listenEvent() {
    this.event.on('data', data => {
      data = JSON.parse(data);

      this.events.forEach(item => {
        let [eventName, handler] = item;
        if (eventName === data._eventName) {
          let cloneData = {...data};
          delete cloneData['_eventName'];
          handler(cloneData);
        }
      });
    });
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