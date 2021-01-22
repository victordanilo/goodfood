<template>
  <div class="flex">
    <div class="search-full-container w-full h-full absolute left-0 top-0" :class="{'flex': showFullSearch}" v-show="showFullSearch">
      <vx-auto-suggest
        ref="navbarSearch"
        :autoFocus="showFullSearch"
        :data="navbarSearchAndPinList"
        search_key="title"
        background-overlay
        class="w-full"
        inputClassses="w-full vs-input-no-border vs-input-no-shdow-focus"
        icon="SearchIcon"
        placeholder="Pesquisar..."
        @input="hnd_search_query_update"
        @selected="selected"
        @closeSearchbar="showFullSearch = false">

        <template v-slot:group="{ group_name }">
          <p class="font-semibold text-primary">{{ group_name | title }}</p>
        </template>

        <!-- Pages Suggestion -->
        <template v-slot:pages="{ suggestion }">
          <div class="flex items-end leading-none py-1">
            <feather-icon :icon="suggestion.icon" svgClasses="h-5 w-5" class="mr-4" />
            <span class="mt-1">{{ suggestion.title }}</span>
          </div>
        </template>
      </vx-auto-suggest>

      <div class="absolute right-0 h-full z-50">
        <feather-icon
          icon="XIcon"
          class="px-4 cursor-pointer h-full close-search-icon"
          @click="showFullSearch = false" />
      </div>
    </div>
    <feather-icon icon="SearchIcon" @click="showFullSearch = true" class="cursor-pointer navbar-fuzzy-search ml-4" />
  </div>
</template>

<script>
import VxAutoSuggest from '@/components/vx-auto-suggest/VxAutoSuggest.vue'

export default {
  components: {
    VxAutoSuggest
  },
  data () {
    return {
      navbarSearchAndPinList:  {
        produtos: {
          key: 'title',
          data: []
        },
        restaurantes: {
          key: 'title',
          data: []
        }
      },
      showFullSearch: false
    }
  },
  computed: {
    // below computed property 'get_ext_img' is not required if you are using dynamic image rendering instead of if-else for laravel issue
    get_ext_img () {
      return (ext) => {
        if (ext === 'doc')      return require('@assets/images/file-icons/doc.png')
        else if (ext === 'jpg') return require('@assets/images/file-icons/jpg.png')
        else if (ext === 'xls') return require('@assets/images/file-icons/xls.png')
        else if (ext === 'pdf') return require('@assets/images/file-icons/pdf.png')
        else return require('@assets/images/file-icons/doc.png')
      }
    }
  },
  methods: {
    selected (item) {
      if (item.pages) this.$router.push(item.pages.url).catch(() => {})
      this.showFullSearch = false
    },
    hnd_search_query_update (query) {
      // Show overlay if any character is entered
      this.$store.commit('TOGGLE_CONTENT_OVERLAY', !!query)
    }
  }
}

</script>
