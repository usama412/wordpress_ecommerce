<script>
export default {
  name: "vehica-updater",
  data() {
    return {
      currentPlugins: [],
      currentThemeStatus: '',
      showUpdateButton: true,
      showFinished: false,
    }
  },
  props: {
    queryUrl: String,
    plugins: {
      type: Array,
      default: () => {
        return []
      }
    },
    themeStatus: String,
  },
  computed: {
    updateInProgress() {
      if (this.currentThemeStatus === 'updating') {
        return true
      }

      return typeof this.plugins.find((plugin) => {
        return plugin.status === 'updating'
      }) !== 'undefined'
    }
  },
  methods: {
    onStart() {
      this.showUpdateButton = false

      if (this.currentThemeStatus === 'ok') {
        this.updatePlugins()
      } else {
        this.updateTheme()
      }
    },
    updatePlugins() {
      let plugin = this.currentPlugins.find((plugin) => {
        return plugin.status === 'need_update'
      });

      if (typeof plugin === 'undefined') {
        this.showFinished = true
        return
      }

      this.updatePlugin(plugin)
    },
    updatePlugin(plugin) {
      plugin.status = 'updating';

      jQuery.ajax({
        url: this.queryUrl + 'vehica_updater_plugin',
        type: 'post',
        data: {
          pluginKey: plugin.key
        }
      }).success(() => {
        plugin.status = 'ok';
        this.updatePlugins()
      }).error(() => {
        plugin.status = 'need_update';
        this.updatePlugins()
      })
    },
    updateTheme() {
      this.currentThemeStatus = 'updating';

      jQuery.ajax({
        url: this.queryUrl + 'vehica_updater_theme',
        type: 'post',
      }).success(() => {
        this.currentThemeStatus = 'ok';
        this.updatePlugins()
      }).error(() => {
        this.updateTheme()
      })
    }
  },
  render() {
    return this.$scopedSlots.default({
      onStart: this.onStart,
      plugins: this.currentPlugins,
      themeStatus: this.currentThemeStatus,
      updateInProgress: this.updateInProgress,
      showUpdateButton: this.showUpdateButton,
      showFinished: this.showFinished,
    })
  },
  created() {
    this.currentPlugins = this.plugins;
    this.currentThemeStatus = this.themeStatus;
  }
}
</script>
