<template>
    <div :class="{ 'loader': loader === true }">
        <div class="card bg-grey-700 text-white" v-if="sort && sort.length > 0">
            <div class="list-group">
                <a v-for="item in sort" @click="onSort(item)" href="javascript:void(0)" :class="{ active: item.active }" class="text-white list-group-item">
                    {{ item.label }}
                    <i class="fas fa-sort-amount-up text-orange" v-if="item.order === 'asc'"></i>
                    <i class="fas fa-sort-amount-down text-orange" v-else-if="item.order === 'desc'"></i>
                </a>
            </div>
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