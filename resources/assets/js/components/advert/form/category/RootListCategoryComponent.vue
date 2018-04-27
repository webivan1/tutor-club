<template>
    <div>
        <div v-if="!rootCategory">
            <div class="list-group" v-if="category && category.length > 0">
                <div v-for="(item, index) in category">
                    <a
                        href="javascript:void(0)"
                        class="list-group-item"
                        @click.prevent="rootCategory = item"
                    >
                        {{ item.name }}
                    </a>
                </div>
            </div>
        </div>
        <div v-else-if="rootCategory && rootCategory.children">
            <child-category v-bind:category="rootCategory.children"></child-category>
        </div>
        <div v-else>
            Error...
        </div>
    </div>
</template>

<script>
  import Vue from 'vue'
  import ChildCategoryComponent from './ChildCategoryComponent.vue';

  Vue.component('child-category', ChildCategoryComponent);

  export default {
    props: ['category'],
    data() {
      return {
        rootCategory: null
      }
    }
  };
</script>