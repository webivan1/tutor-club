<template>
    <div>
        <a :href="'/offer/' + item.id" target="_blank">
            <div class="crop-image crop-image-200">
                <img class="card-img-top" :src="item.profile.image.file_path" :alt="item.user.name" />
            </div>
        </a>
        <div class="card-body">
            <h5 class="card-title">{{ item.title }} - {{ item.user.name }}</h5>
            <div class="card-text mb-2">
                <div class="info-list">
                    <div class="info-list-item text-crop mb-1" v-for="price in item.prices.slice(0, 7)">
                        <div class="float-right">
                            <b>{{ price.price_type }}</b> {{ price.price_from }} {{ price.minutes ? '/ ' + price.minutes : '' }}
                        </div>
                        <div class="float-left text-muted">
                            {{ price.category.name }}
                        </div>
                    </div>
                </div>
            </div>

            <a :href="'/offer/' + item.id" class="btn btn-info">{{ messages.ReadMore }}</a>

            <b-btn v-b-tooltip.hover :title="messages.ReadDescription" variant="info" @click="showModal">
                <i class="material-icons">description</i>
            </b-btn>

            <b-modal ref="modelMoreRef" hide-footer :title="messages.DescriptionOffer">
                <div v-html="item.description"></div>

                <div v-if="item.files && item.files.length > 0">
                    <b-carousel
                        id="carousel1"
                        style="text-shadow: 1px 1px 2px #333;"
                        controls
                        indicators
                        :interval="4000"
                    >
                        <!-- Slides with image only -->
                        <div v-for="img in item.files">
                            <b-carousel-slide
                                :img-src="img.file_path">
                            </b-carousel-slide>
                        </div>
                    </b-carousel>
                </div>
            </b-modal>

            <!--<pre>{{ item }}</pre>-->
        </div>
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