import moment from 'moment-timezone'

export class UserTimezone {

  constructor() {
    this.send();
  }

  static getTimezone() {
    return moment.tz.guess();
  }

  send() {
    axios.post('/profile/timezone', { timezone: UserTimezone.getTimezone() })
      .then(response => {})
      .catch(err => {})
  }

}