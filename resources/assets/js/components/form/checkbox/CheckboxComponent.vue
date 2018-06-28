<template>
    <div ref="mdcFormField" class="mdc-form-field">
        <div ref="mdcCheckbox" class="mdc-checkbox">
            <input
                :name="name"
                :checked="active"
                type="checkbox"
                class="mdc-checkbox__native-control"
                :id="'id-filed' + name"
                :disabled="disabled"
                :value="valueField || 1"
                @change="onChange"
            />
            <div class="mdc-checkbox__background">
                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                    <path
                        class="mdc-checkbox__checkmark-path"
                        fill="none"
                        d="M1.73,12.91 8.1,19.28 22.79,4.59"
                    />
                </svg>
                <div class="mdc-checkbox__mixedmark"></div>
            </div>
        </div>
        <label class="my-0" :for="'id-filed' + name">
            {{ label }}
        </label>
    </div>
</template>

<script>
  import { MDCFormField } from '@material/form-field';
  import { MDCCheckbox } from '@material/checkbox';

  export default {
    props: ['label', 'name', 'checked', 'value', 'disabled', 'valueField'],
    data() {
      return {
        active: false
      }
    },
    created() {
      if (parseInt(this.checked) > 0 || (typeof this.checked === 'string' && this.checked.toLocaleLowerCase() === 'on')) {
        this.active = true;
      }
    },
    mounted() {
      const checkbox = new MDCCheckbox(this.$refs.mdcCheckbox);
      const formField = new MDCFormField(this.$refs.mdcFormField);
      formField.input = checkbox;
    },
    methods: {
      onChange(event) {
        if (this.value) {
          this.$emit('input', event.target.value);
        }
      }
    }
  }
</script>