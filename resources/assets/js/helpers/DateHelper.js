export default class DateHelper {

  static getFormatTimezone(date) {
    let serverDate = new Date(date);
    return new Date(serverDate.getTime() + (-1 * serverDate.getTimezoneOffset() * 60000));
  }

}