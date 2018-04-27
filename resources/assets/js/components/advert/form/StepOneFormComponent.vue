<template>
    <div>
        <render-category-component
            v-bind:category="category"
            v-bind:form="form"
        ></render-category-component>
    </div>
</template>

<script>
  import Vue from 'vue'
  import RenderCategory from './category/RootListCategoryComponent.vue';

  Vue.component('render-category-component', RenderCategory);

  export default {
    mounted() {
    },
    props: ['category', 'form'],
    data() {
      return {
        root: null
      }
    },
    methods: {

    }
  }
</script>