
export class OnlineUsers {

  constructor() {
    this.time = this.now();
    this.userId = document.querySelector('body').getAttribute('self-user-id');

    this.event();
  }

  now() {
    return (new Date()).getTime();
  }

  event() {
    if (this.userId) {
      setInterval(this.change.bind(this), 1000);
    }
  }

  isTimeout() {
    return this.time <= this.now();
  }

  addTime(time) {
    this.time = this.now() + (time * 1000);
  }

  message(e) {
    let id = e.id;

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
    let selfTime = this.now();

    if (selfTime > time) {
      element.classList.contains('active') ? element.classList.remove('active') : null;
    }
  }

  toActive(element) {
    // + 15 minute
    element.setAttribute('user-active-date', this.now() + (15 * 60 * 1000));
    !element.classList.contains('active') ? element.classList.add('active') : null;
  }

  change() {
    io(':6002').emit(`user.online`, this.userId);

    // if (document.querySelector('body').getAttribute('self-user-id')) {
    //   if (this.isTimeout() === true) {
    //     this.addTime(10);
    //
    //     axios.get('/profile/online/user')
    //       .then(response => { this.userId = response.data.id })
    //       .catch(err => {
    //         this.userId = false;
    //         console.log(err);
    //       });
    //   }
    // }
  }
}