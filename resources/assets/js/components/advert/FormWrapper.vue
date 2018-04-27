<template>
    <div>
        <div v-for="form in forms">
            <div :is="form" v-bind:category="category"></div>
        </div>

        <button type="button" @click.prevent="onClick()">Click me</button>
    </div>
</template>

<script>
  import Vue from 'vue'

  Vue.component('form-price', {
    props: ['category'],
    template: `
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Выберите категорию</label>
                    <select name="prices[category_id][]" class="form-control">
                        <option v-for="item in category" v-bind:value="item.id">
                            {{ item.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Валюта</label>
                    <input type="float" class="form-control" name="prices[price_type][]" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Цена</label>
                    <input type="float" class="form-control" name="prices[price_from][]" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Длительность 1 занятия</label>
                    <input type="float" class="form-control" name="prices[minutes][]" />
                </div>
            </div>
        </div>
    `
  });

  export default {
    mounted() {
      this.forms.push('form-price');
    },
    props: ['category-json', 'request'],
    data() {
      return {
        category: typeof this.categoryJson === 'string'
          ? JSON.parse(this.categoryJson)
          : [],
        forms: [],
      };
    },
    methods: {
      onClick() {
        this.forms.push('form-price');
      }
    }
  }
</script>