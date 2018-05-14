
export class OnlineUsers {

  constructor() {
    this.time = (new Date()).getTime();
    this.port = 8888;
    this.host = window.location.hostname;

    this.initServer();

    this.event();
  }

  initServer() {
    this.connect = new WebSocket(`ws://${this.host}:${this.port}/online`);

    this.connect.onopen = e => {
      console.log('/online [OK]');
    };

    this.connect.onmessage = this.message.bind(this);
  }

  event() {
    window.onmousemove = this.change.bind(this);
  }

  isTimeout() {
    return this.time <= (new Date()).getTime();
  }

  addTime(time) {
    this.time = (new Date()).getTime() + time;
  }

  message(e) {
    let { id } = JSON.parse(e.data);

    [].forEach.call(document.querySelectorAll('[user-id]'), element => {
      if (!element.hasAttribute('user-active-date')) {
        return false;
      }

      this.toOlder(element);

      if (parseInt(element.getAttribute('user-id')) === parseInt(id)) {
        this.toActive(element);
      }
    });
  }

  toOlder(element) {
    let time = (element.getAttribute('user-active-date'));
    let selfTime = (new Date()).getTime();

    if (selfTime > time) {
      element.classList.contains('active') ? element.classList.remove('active') : null;
    }
  }

  toActive(element) {
    // + 15 minute
    element.setAttribute('user-active-date', (new Date()).getTime() + (15 * 60 * 1000));
    !element.classList.contains('active') ? element.classList.add('active') : null;
  }

  change(e) {
    // close connection
    if (this.connect.readyState === 3) {
      this.initServer();
    }

    if (this.isTimeout() === true) {
      this.addTime(5 * 1000);

      axios.get('/profile/online/user')
        .then(response => {
          this.connect.send(JSON.stringify(response.data));
        })
        .catch(err => {
          console.log(err);
        });
    }
  }

}