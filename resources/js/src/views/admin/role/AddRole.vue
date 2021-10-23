<!-- =========================================================================================
    File Name: AddRole.vue
    Description: Add Role component
    ----------------------------------------------------------------------------------------
    Item Name: Vuexy - Vuejs, HTML & Laravel Admin Dashboard Template
      Author: Pixinvent
    Author URL: http://www.themeforest.net/user/pixinvent
========================================================================================== -->


<template>
  <div>
    <div class="btn-add-new p-3 mb-4 md:mr-4 rounded-lg cursor-pointer flex items-center justify-center text-lg font-medium text-base text-primary border border-solid border-primary" @click="activePrompt = true" >
      <feather-icon icon="PlusIcon" svgClasses="h-4 w-4" />
      <span class="ml-2 text-base text-primary">{{ $t('add') }}</span>
    </div>
    <vs-prompt
      :title="$t('add_role')"
      :accept-text="$t('save')"
      :cancel-text="$t('cancel')"
      button-cancel="border"
      @accept="addRole"
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
  </div>
</template>

<script>
export default {
  data () {
    return {
      activePrompt: false,
      role: {
        name: ''
      }
    }
  },
  computed: {
    validateForm () {
      return !this.errors.any() && this.role.name !== ''
    }
  },
  methods: {
    clearFields () {
      this.role.name = ''
    },
    addRole () {
      this.$validator.validateAll().then(statusValidator => {
        if (!statusValidator) return

        if (this.$store.state.roleManagement.roles.find(role => role.name === this.role.name)) {
          this.$vs.notify({
            color: 'danger',
            text: this.$i18n.t('notify_fail_save_role_already_exists')
          })

          return
        }

        this.$store.dispatch('roleManagement/addRole', this.role)
          .then(() => this.clearFields())
          .catch(err => console.log(err))
      })
    }
  }
}
</script>
