<template>
    <div>
        <div v-if="item.avatar" class="card-body pb-0">
            <a :href="'/offer/' + item.id" target="_blank" class="card-avatar" v-bind:style="{
                backgroundImage: 'url(' + item.avatar + ')'
            }"></a>
        </div>

        <div class="card-body">
            <h5 class="card-title">
                <span class="d-block text-crop">{{ item.title }}</span>
                <span class="d-block text-crop">
                    @{{ item.user.name }} <online :user="item.user.id"></online>
                </span>
            </h5>
            <div class="card-text mb-2">
                <div class="row no-wrap col-inline mx-0 mb-1" v-for="price in item.prices.slice(0, 7)">
                    <div class="col-auto px-0 text-crop text-muted">
                        {{ price.category.name }}
                    </div>
                    <div class="col px-0 border-line-bottom"></div>
                    <div class="col-auto px-0">
                        {{ price.price_type }}
                        <b>{{ price.price_from }}</b>
                        <span
                            v-b-tooltip.hover
                            :title="messages.Min"
                            v-if="price.minutes"
                        > / {{ price.minutes }} <i class="far fa-clock"></i></span>
                    </div>
                </div>
            </div>

            <div class="mt-1">
                <a class="text-success" :href="'/offer/' + item.id">{{ messages.ReadMore }}</a>
            </div>
        </div>
        <div class="card-footer">
            <button
                type="button"
                @click="showModal"
                v-b-tooltip.hover
                :title="messages.ReadDescription"
                class="mdc-button mdc-button--outlined"
            >
                <i class="fas fa-info-circle"></i>
            </button>

            <add-dialog
                :user="item.user.id"
                :title="item.title"
                :data-json="{}"
            ></add-dialog>
        </div>

        <b-modal ref="modelMoreRef" hide-footer :title="messages.DescriptionOffer">
            <div v-html="item.description"></div>

            <div class="mt-2" v-if="item.files && item.files.length > 0">
                <b-carousel
                    id="carousel1"
                    controls
                    indicators
                    :interval="0"
                >
                    <div v-for="img in item.files">
                        <b-carousel-slide
                            :img-src="img.file_path">
                        </b-carousel-slide>
                    </div>
                </b-carousel>
            </div>
        </b-modal>
    </div>
</template>

<script>
  export default {
    props: ['item', 'messages'],
    methods: {
      showModal() {
        this.$refs.modelMoreRef.show();
      },
      hideModal() {
        this.$refs.modelMoreRef.hide();
      }
    }
  }
</script>