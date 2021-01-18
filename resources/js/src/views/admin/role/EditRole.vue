<!-- =========================================================================================
    File Name: EditRole.vue
    Description: Edit Role component
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <vs-prompt
    :title="$t('edit_role')"
    :accept-text="$t('save')"
    :cancel-text="$t('cancel')"
    button-cancel="border"
    @accept="saveRole"
    @cancel="clearFields"
    @close="clearFields"
    :is-valid="validateForm"
    :active.sync="activePrompt">
    <div>
      <form>
        <div class="vx-row">
          <div class="vx-col w-full">
            <vs-input v-validate="'required'" name="name" class="w-full mb-4 mt-5" v-model="role.name" :color="validateForm ? 'success' : 'danger'" />
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
      role: {
        id: '',
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
      return !this.errors.any() && this.role.name !== ''
    }
  },
  methods: {
    clearFields () {
      this.role.id = ''
      this.role.name = ''
    },
    saveRole () {
      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        if (this.$store.state.roleManagement.roles.find(role => role.name === this.role.name)) {
          this.$vs.notify({
            color: 'danger',
            text: this.$i18n.t('notify_fail_save_role_already_exists')
          })

          return
        }

        const roleID = this.role.id
        const roleData = this.role
        this.$store.dispatch('roleManagement/updateRole', {roleID, roleData})
          .then(() => this.clearFields())
          .catch(err => console.log(err))
      })
    }
  },
  created () {
    this.role = this.data
  }
}
</script>
