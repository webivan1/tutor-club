import ExampleComponent from '../components/ExampleComponent.vue'

export default class Router {
  constructor() {
    this.pathname = window.location.pathname;
  }

  rules() {
    return [
      { url: /^\/$/, component: ExampleComponent }
    ];
  }

  getMatchRoute() {
    let route;

    this.rules().map(item => {
      if (this.pathname.match(item.url)) {
        route = item;
      }
    });

    return route;
  }
}