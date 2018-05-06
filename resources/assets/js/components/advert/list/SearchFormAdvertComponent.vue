<template>
    <div :class="{ 'loader': loaderList === true, 'js-form': true }">
        <div v-if="loader">
            <div class="card-body">
                <div class="ld ld-ring ld-spin-fast text-center text-info"></div>
            </div>
        </div>
        <div v-else>
            <form @submit="submit" class="my-0 py-0">
                <div class="card-body">
                    <div class="form-group">
                        <div class="row mx-0">
                            <div class="col-2 px-0">
                                <select @change="changeCurrency" class="form-control" v-model="form.priceType">
                                    <option
                                        v-for="(value, key) in data.types"
                                        v-bind:value="key"
                                    >
                                        {{ value }}
                                    </option>
                                </select>
                            </div>
                            <div class="col px-0">
                                <input
                                    type="text"
                                    v-model="form.priceFrom"
                                    class="form-control"
                                    :placeholder="prices ? prices.min + ' - ' + prices.max : ''"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="select-gender" class="bmd-label-static">
                            {{ messages.SelectGender }}
                        </label>
                        <select v-model="form.gender" class="form-control" id="select-gender">
                            <option value=""></option>
                            <option v-for="(value, key) in data.genders" :value="key">
                                {{ value }}
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="select-gender" class="bmd-label-static">
                            {{ messages.SearchTutor }}
                        </label>
                        <input
                            type="text"
                            v-model="form.search"
                            class="form-control"
                        />
                    </div>

                    <div v-for="attr in data.attributes">
                        <div v-if="attr.type === 'select'" class="form-group">
                            <label class="bmd-label-static">
                                {{ attr.label }}
                            </label>
                            <select v-model="form.attributes[attr.id]" class="form-control">
                                <option value=""></option>
                                <option v-for="(value, key) in attr.variants" :value="key">
                                    {{ value }}
                                </option>
                            </select>
                        </div>
                        <div v-if="attr.type === 'checkbox'" class="checkbox">
                            <label>
                                <input
                                    type="checkbox"
                                    v-model="form.attributes[attr.id]"
                                    value="1"
                                /> <small>{{ attr.label }}</small>
                            </label>
                        </div>
                        <div v-if="attr.type === 'number' || attr.type === 'float'" class="form-group">
                            <label class="bmd-label-static">
                                {{ attr.label }}
                            </label>
                            <input
                                type="number"
                                class="form-control"
                                v-model="form.attributes[attr.id]"
                            />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-raised btn-warning btn-block">
                        {{ messages.Search }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
  export default {
    props: ['lang', 'messages', 'loaderList'],
    data() {
      return {
        loader: true,
        data: null,
        form: {
          priceType: 'usd',
          priceFrom: null,
          gender: null,
          search: null,
          attributes: {}
        },
        prices: null
      }
    },
    watch: {
      loader() {
        setTimeout(_=> {
          $('.js-form').bootstrapMaterialDesign();
        }, 100);
      }
    },
    methods: {
      formInit() {
        if (this.loader === false) {
          return false;
        }

        axios.post(window.location.pathname + '/form', {})
          .then(response => {
            this.data = response.data;
            this.setDefaultTypePrice();
            this.setPrices();
            this.setAttributes();
            this.loader = false;
          })
          .catch(err => {
            console.error(err);
          });
      },

      setDefaultTypePrice() {
        let defaultCurrency = 'eur';

        if (this.lang === 'ru') {
          defaultCurrency = 'rub'
        } else if (this.lang === 'en') {
          defaultCurrency = 'usd';
        }

        this.form.priceType = defaultCurrency;
      },

      setPrices() {
        this.prices = null;

        this.data.prices.map(item => {
          if (item.price_type === this.form.priceType) {
            this.prices = item;
          }
        });
      },

      setAttributes() {
        if (this.data.attributes.length > 0) {
          this.data.attributes.map(item => {
            this.form.attributes[item.id] = item.type === 'checkbox' ? false : null;
          });
        }
      },

      changeCurrency() {
        this.$nextTick(() => {
          this.setPrices();
          this.form.priceFrom = null;
        });
      },

      submit(event) {
        event.preventDefault();
        this.$emit('fetch-list', this.form);
      }
    },
    mounted() {
      this.formInit();
    }
  }
</script>