/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./jquery/main');

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueTimeago from 'vue-timeago'
import datePicker from 'vue-bootstrap-datetimepicker'

import { OnlineUsers } from './custom/OnlineUsers'
import { UserTimezone } from './custom/UserTimezone'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

jQuery.extend(true, jQuery.fn.datetimepicker.defaults, {
  icons: {
    time: 'far fa-clock',
    date: 'far fa-calendar',
    up: 'fas fa-arrow-up',
    down: 'fas fa-arrow-down',
    previous: 'fas fa-chevron-left',
    next: 'fas fa-chevron-right',
    today: 'fas fa-calendar-check',
    clear: 'far fa-trash-alt',
    close: 'far fa-times-circle'
  }
});

Object.assign(window, {
  onlineUser: new OnlineUsers(),
  timezone: new UserTimezone()
});

try {
  Vue.use(BootstrapVue);
  Vue.use(VueTimeago, {
    name: 'Timeago', // Component name, `Timeago` by default
    locale: document.querySelector('html').getAttribute('lang'), // Default locale
    locales: {
      'ru': require('date-fns/locale/ru'),
      'en': require('date-fns/locale/en'),
    }
  });
  Vue.use(datePicker);

  Vue.component('advert-form-component', require('./components/advert/FormWrapper.vue'));
  Vue.component('search-category-component', require('./components/category/SearchComponent.vue'));
  Vue.component('advert-list-component', require('./components/advert/list/ListWrapperComponent.vue'));
  Vue.component('chat', require('./components/chat/ChatMainComponent.vue'));
  Vue.component('add-dialog', require('./components/chat/AddDialogComponent.vue'));
  Vue.component('online', require('./components/online/OnlineUserComponent.vue'));
  Vue.component('classroom', require('./components/classroom/ClassroomComponent.vue'));
  Vue.component('checkbox', require('./components/form/checkbox/CheckboxComponent.vue'));
  Vue.component('classroom-register', require('./components/classroom/ClassroomRegisterComponent.vue'));
  Vue.component('real-timer', require('./components/realtimer/RealTimerComponent.vue'));

  [].forEach.call(document.querySelectorAll('.app-vue'), element => {
    new Vue({
      el: element
    });
  });

} catch (err) {
  console.warn(err);
}