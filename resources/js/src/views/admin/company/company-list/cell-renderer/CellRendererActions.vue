<template>
    <div :style="{'direction': $vs.rtl ? 'rtl' : 'ltr'}">
      <feather-icon icon="Edit3Icon" svgClasses="h-5 w-5 mr-4 hover:text-primary cursor-pointer" @click="editRecord" />
      <feather-icon icon="Trash2Icon" svgClasses="h-5 w-5 hover:text-danger cursor-pointer" @click="confirmDeleteRecord" />
    </div>
</template>

<script>
export default {
  name: 'CellRendererActions',
  methods: {
    editRecord () {
      this.$router.push({name: 'admin-company-edit', params: {companyUUID: this.params.data.uuid} }).catch(() => {})
    },
    confirmDeleteRecord () {
      this.$vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: this.$i18n.t('delete_confirm.title'),
        text: this.$i18n.t('delete_confirm.text', {username: this.params.data.name}),
        accept: this.deleteRecord,
        acceptText: 'Delete'
      })
    },
    deleteRecord () {
      this.$store.dispatch('companyManagement/removeCompany', this.params.data.uuid)
        .then((response) => {
          this.$vs.notify({
            color: 'success',
            text: response.data.message
          })
        })
        .catch(err => {
          this.$vs.notify({
            color: 'danger',
            text: err.response.data.message
          })
        })
    }
  }
}
</script>
