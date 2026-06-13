import DefaultTheme from 'vitepress/theme'
import { h } from 'vue'
import StatsSection from './StatsSection.vue'

export default {
  extends: DefaultTheme,
  Layout() {
    return h(DefaultTheme.Layout, null, {
      'home-features-after': () => h(StatsSection),
    })
  },
}
