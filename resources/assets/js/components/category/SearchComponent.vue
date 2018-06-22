<template>
    <!--div v-bind:class="{ dropdown: true, 'search-container': true, show: items.length }">
        <input
            class="form-control-search bg-white"
            type="text"
            v-model="search"
            v-bind:placeholder="content.placeholder"
            @keydown="change()"
        />

        <div v-if="(items.length && search.length >= 2) || errors" class="close-search-form">
            <a href="javascript:void(0)" class="text-danger" @click="closeDropdown()">
                <i class="material-icons">close</i>
            </a>
        </div>

        <div v-if="errors">
            <div class="dropdown-menu show">
                <div class="alert alert-danger mb-0">
                    {{ errors }}
                </div>
            </div>
        </div>

        <div v-if="items.length && search.length >= 2" class="dropdown-menu show">
            <a
                v-for="item in items"
                class="dropdown-item"
                :href="item.slug"
            >{{ item.name }}</a>
        </div>
    </div-->

    <div>
        <a href="javascript:void(0)" class="fs-24 text-blue-200" @click="formShow = !formShow">
            <i v-if="!formShow" class="fas fa-search"></i>
            <i class="fas fa-times" v-else></i>
        </a>

        <div v-if="formShow" class="form-control-search bg-grey-900">
            <div class="container position-relative">
                <form class="dropdown px-0 py-0 my-0 mx-0" :class="{ show: items.length }">
                    <input
                        class="form-control form-control-lg"
                        type="text"
                        v-model="search"
                        v-bind:placeholder="content.placeholder"
                        @keydown="change()"
                    />
                </form>

                <div v-if="items.length && search.length >= 2" class="dropdown-menu" :class="{ show: items.length }">
                    <a
                        v-for="item in items"
                        class="dropdown-item"
                        :href="item.slug"
                    >{{ item.name }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      props: ['messages'],
      mounted() {},
      data() {
        return {
          formShow: false,
          content: typeof this.messages === 'string'
            ? JSON.parse(this.messages)
            : {},
          loader: false,
          items: [],
          errors: null,
          search: ''
        };
      },
      methods: {
        change() {
          this.errors = null;

          this.$nextTick(() => {
            if (this.loader === true) {
              return false;
            }

            if (this.search.length >= 2) {
              this.loader = true;

              axios({
                method: 'post',
                url: `${this.content.search}?search=${this.search}`,
                data: {}
              })
                .then(response => {
                  this.loader = false;
                  this.items = response.data;
                })
                .catch(error => {
                  this.loader = false;
                  this.items = [];
                  this.errors = error.message;
                });
            }
          });
        },

        closeDropdown() {
          this.items = [];
          this.errors = null;
          this.search = '';
        }
      }
    }
</script>