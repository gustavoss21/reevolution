import { createApp } from 'vue'
import type { App } from 'vue'
import ExampleComponent from './components/ExampleComponent.vue'

const app: App = createApp(ExampleComponent)
app.mount('#app')
