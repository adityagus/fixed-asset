declare module 'vue-height-collapsible/vue3' {
  import { DefineComponent } from 'vue'
  
  interface VueCollapsibleProps {
    isOpen: boolean
    unmount?: boolean
  }
  
  const VueCollapsible: DefineComponent<VueCollapsibleProps>
  export default VueCollapsible
}
