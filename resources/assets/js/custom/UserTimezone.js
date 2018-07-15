import moment from 'moment-timezone'

export class UserTimezone {

  constructor() {
    if (!(localStorage.getItem('timezone') && localStorage.getItem('timezone') === UserTimezone.getTimezone())) {
      this.send();
    }
  }

  static getTimezone() {
    return moment.tz.guess();
  }

  send() {
    axios.post('/profile/timezone', { timezone: UserTimezone.getTimezone() })
      .then(response => {
        localStorage.setItem('timezone', UserTimezone.getTimezone());
      })
      .catch(err => {})
  }

}