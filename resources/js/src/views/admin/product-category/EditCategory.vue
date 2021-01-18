<!-- =========================================================================================
    File Name: EditCategory.vue
    Description: Edit Category component
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
    <vs-prompt
      :title="$t('edit_category')"
      :accept-text="$t('save')"
      :cancel-text="$t('cancel')"
      button-cancel="border"
      @accept="saveCategory"
      @cancel="clearFields"
      @close="clearFields"
      :is-valid="validateForm"
      :active.sync="activePrompt">
      <div>
        <form>
          <div class="vx-row">
            <div class="vx-col w-full">
              <vs-input v-validate="'required'" name="name" class="w-full mb-4 mt-5" v-model="category.name" :color="validateForm ? 'success' : 'danger'" />
            </div>
          </div>
        </form>
      </div>
    </vs-prompt>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      required: true
    },
    data: {
      type: Object,
      required: true
    }
  },
  data () {
    return {
      category: {
        uuid: '',
        name: ''
      }
    }
  },
  computed: {
    activePrompt: {
      get () {
        return this.show
      },
      set (value) {
        this.$emit('hidePromptEdit', value)
      }
    },
    validateForm () {
      return !this.errors.any() && this.category.name !== ''
    }
  },
  methods: {
    clearFields () {
      this.category.uuid = ''
      this.category.name = ''
    },
    saveCategory () {
      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        if (this.$store.state.productManagement.categories.find(category => category.name === this.category.name)) {
          this.$vs.notify({
            color: 'danger',
            text: this.$i18n.t('notify_fail_save_category_already_exists')
          })

          return
        }

        const categoryUUID = this.category.uuid
        const categoryData = this.category
        this.$store.dispatch('productManagement/updateCategory', {categoryUUID, categoryData})
          .then(() => this.clearFields())
          .catch(err => console.log(err))
      })
    }
  },
  created () {
    this.category = this.data
  }
}
</script>
