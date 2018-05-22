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

import { OnlineUsers } from './custom/OnlineUsers'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Object.assign(window, {
  onlineUser: new OnlineUsers()
});

try {
  if (!document.querySelector('#app')) {
    throw new Error('Undefined #app');
  }

  Vue.use(BootstrapVue);
  Vue.use(VueTimeago, {
    name: 'Timeago', // Component name, `Timeago` by default
    locale: document.querySelector('html').getAttribute('lang'), // Default locale
    locales: {
      'ru': require('date-fns/locale/ru'),
      'en': require('date-fns/locale/en'),
    }
  });

  Vue.component('example-component', require('./components/ExampleComponent.vue'));
  Vue.component('advert-form-component', require('./components/advert/FormWrapper.vue'));
  Vue.component('search-category-component', require('./components/category/SearchComponent.vue'));
  Vue.component('advert-list-component', require('./components/advert/list/ListWrapperComponent.vue'));
  Vue.component('chat', require('./components/chat/ChatMainComponent.vue'));
  Vue.component('add-dialog', require('./components/chat/AddDialogComponent.vue'));
  Vue.component('online', require('./components/online/OnlineUserComponent'));

  const app = new Vue({
    el: '#app'
  });

} catch (err) {
  console.warn(err);
}