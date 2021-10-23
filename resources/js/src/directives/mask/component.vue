<template>
  <div
    ref="coninput"
    :style="styleLabel"
    :class="[`vs-input-${color}`,{
      'isFocus': isFocus,
      'input-icon-validate-success': success,
      'input-icon-validate-danger': danger,
      'input-icon-validate-warning': warning,
      'is-label-placeholder': labelPlaceholder
    }]"
    class="vs-component vs-con-input-label vs-input">
    <label
      v-if="labelPlaceholder ? false : label"
      class="vs-input--label"
      @click="focusInput">{{ label }}</label>
    <div class="vs-con-input">
      <input
        ref="vsinput"
        :style="style"
        :autofocus="autofocus"
        :class="[size,{
          'hasValue': value !== '',
          'hasIcon': icon,
          'icon-after-input': iconAfter
        }]"
        :placeholder="null"
        :value="display"
        type="text"
        class="vs-inputx vs-input--input"
        v-bind="$attrs"
        v-mask="config"
        @input="onInput"
      >
      <transition name="placeholderx">
        <span
          v-if="isValue && (labelPlaceholder || $attrs.placeholder)"
          ref="spanplaceholder"
          :style="styleLabel"
          :class="[
            labelPlaceholder && (size),
            size,
            {
              'vs-placeholder-label': labelPlaceholder,
          }]"
          class="input-span-placeholder vs-input--placeholder"
          @click="focusInput">
          {{ $attrs.placeholder || labelPlaceholder }}
        </span>
      </transition>

      <vs-icon
        v-if="icon"
        class="icon-inputx notranslate vs-input--icon"
        :class="{'icon-after': iconAfter, 'icon-no-border': iconNoBorder}"
        :iconPack="iconPack"
        :icon="icon"
        @click="focusInput">
      </vs-icon>
    </div>
  </div>
</template>

<script>
import mask from 'vue-the-mask/src/directive'
import tokens from 'vue-the-mask/src/tokens'
import masker from 'vue-the-mask/src/masker'
import _color from '@/utils/color'

export default {
  name: 'TheMask',
  inheritAttrs: false,
  props: {
    value: {
      default:'',
      type:[String, Number]
    },
    mask: {
      type: [String, Array],
      required: true
    },
    masked: { // by default emits the value unformatted, change to true to format with the mask
      type: Boolean,
      default: false // raw
    },
    tokens: {
      type: Object,
      default: () => tokens
    },
    labelPlaceholder:{
      default: null,
      type:[String, Number]
    },
    label:{
      default:null,
      type:[String, Number]
    },
    autofocus:{
      default:false,
      type:[Boolean, String]
    },
    icon:{
      default:null,
      type:String
    },
    iconAfter:{
      default:false,
      type:[Boolean, String]
    },
    iconNoBorder:{
      default:false,
      type:Boolean
    },
    iconPack:{
      default:'material-icons',
      type:String
    },
    color:{
      default:'primary',
      type:String
    },
    success:{
      default:false,
      type:Boolean
    },
    danger:{
      default:false,
      type:Boolean
    },
    warning:{
      default:false,
      type:Boolean
    },
    successText:{
      default: null,
      type:String
    },
    dangerText:{
      default: null,
      type:String
    },
    warningText:{
      default: null,
      type:String
    },
    descriptionText:{
      default: null,
      type:String
    },
    size:{
      default:'normal',
      type:String
    },
    valIconPack:{
      default:'material-icons',
      type:String
    },
    valIconSuccess:{
      default: null,
      type:String
    },
    valIconDanger:{
      default: null,
      type:String
    },
    valIconWarning:{
      default: null,
      type:String
    }
  },
  inject: {
    elForm: {
      default: ''
    },
    elFormItem: {
      default: ''
    }
  },
  directives: { mask },
  data () {
    return {
      lastValue: null, // avoid unecessary emit when has no change
      display: this.value,
      isFocus: false
    }
  },
  computed: {
    config () {
      return {
        mask: this.mask,
        tokens: this.tokens,
        masked: this.masked
      }
    },
    style () {
      return {
        border: `1px solid ${this.isFocus ? _color.getColor(this.color, 1) : 'rgba(0, 0, 0,.2)'}`
      }
    },
    styleLabel () {
      return {
        color: this.isFocus ? _color.getColor(this.color, 1) : null
      }
    },
    listeners () {
      return {
        ...this.$listeners,
        input: (evt) => {
          this.$emit('input', evt.target.value)
        },
        focus: (evt) => {
          this.$emit('focus', evt)
          this.changeFocus(true)
        },
        blur: (evt) => {
          this.$emit('blur', evt)
          this.changeFocus(false)
        }
      }
    },
    isValue () {
      return this.labelPlaceholder ? true : !this.value
    },
    getIcon () {
      return this.danger  ? this.valIconDanger
        : this.warning ? this.valIconWarning
          : this.success ? this.valIconSuccess
            : ''
    }
  },
  watch : {
    value (newValue) {
      if (newValue !== this.lastValue) {
        this.display = newValue
      }
    },
    masked () {
      this.refresh(this.display)
    }
  },
  methods: {
    onInput (e) {
      if (e.isTrusted) return // ignore native event
      this.refresh(e.target.value)
    },
    refresh (value) {
      this.display = value
      value = masker(value, this.mask, this.masked, this.tokens)
      if (value !== this.lastValue) {
        this.lastValue = value
        this.$emit('input', value)
      }
    },

    // animation
    changeFocus (booleanx) {
      this.isFocus = booleanx
    },
    focusInput () {
      this.$refs.vsinput.focus()
    }
  }
}
</script>
