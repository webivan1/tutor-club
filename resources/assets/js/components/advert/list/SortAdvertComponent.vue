<template>
    <div :class="{ 'loader': loader === true }">
        <div v-if="sort && sort.length > 0">
            <b-dropdown class="my-0" variant="link" no-caret>
                <template slot="button-content">
                    <span class="text-white">{{ activeOrder ? activeOrder.label : 'Choose' }}</span>
                </template>
                <div v-for="item in sort">
                    <b-dropdown-item @click="onSort(item)">
                        {{ item.label }}
                    </b-dropdown-item>
                </div>
            </b-dropdown>
        </div>
    </div>
</template>

<script>
    export default {
      props: ['sort', 'loader'],
      data() {
        return {
          activeOrder: null
        }
      },
      updated() {
        if (this.sort) {
          this.sort.map(item => {
            if (item.active === true) {
              this.activeOrder = item;
            }
          });
        }
      },
      methods: {
        onSort(item) {
          this.$emit('action-sort', item);
        }
      }
    }
</script>