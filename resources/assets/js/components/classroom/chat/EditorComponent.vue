<template>
    <textarea class="form-control" :name="name"></textarea>
</template>

<script>
  export default {
    replace: true,
    inherit: false,
    props: {
      model: {
        required: true,
        twoWay: true
      },
      language: {
        type: String,
        required: false,
        default: "en-US"
      },
      height: {
        type: Number,
        required: false,
        default: 160
      },
      minHeight: {
        type: Number,
        required: false,
        default: 160
      },
      maxHeight: {
        type: Number,
        required: false,
        default: 800
      },
      name: {
        type: String,
        required: false,
        default: ""
      },
      toolbar: {
        type: Array,
        required: false,
        default() {
          return [
            ["font", ["bold", "italic", "underline", "clear"]],
            ["fontsize", ["fontsize"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["color", ["color"]],
            ["insert", ["link", "hr"]]
          ];
        }
      }
    },
    created: function () {
      this.isChanging = false;
      this.control = null;
    },
    mounted: function () {
      //  initialize the summernote
      if (this.minHeight > this.height) {
        this.minHeight = this.height;
      }
      if (this.maxHeight < this.height) {
        this.maxHeight = this.height;
      }
      var me = this;
      this.control = $(this.$el);
      this.control.summernote({
        lang: this.language,
        height: this.height,
        minHeight: this.minHeight,
        maxHeight: this.maxHeight,
        toolbar: this.toolbar,
        callbacks: {
          onInit: function () {
            me.control.summernote("code", me.model);
          },
          onChange: function () {
            if (!me.isChanging) {
              me.isChanging = true;
              var code = me.control.summernote("code");
              me.model = (code === null || code.length === 0 ? null : code);
              me.$nextTick(function () {
                me.isChanging = false;
              });
            }
            me.$parent.text = code
          }
        }
      })
    },
    watch: {
      model(val) {
        if (!this.isChanging) {
          this.isChanging = true;
          let code = (val === null ? '' : val);
          this.control.summernote('code', code);
          this.isChanging = false;
        }
      }
    },
  }
</script>