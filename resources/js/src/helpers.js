export default {
  diff_JSON: (obj1, obj2) => {
    const res = {}
    for (const i in obj2) {
      if (!obj1.hasOwnProperty(i) || obj2[i] !== obj1[i]) {
        res[i] = obj2[i]
      }
    }

    return res
  },
  assets: (path) => {
    const base_path = window.baseUrl || ''
    return `${base_path  }/${  path}`
  }
}
