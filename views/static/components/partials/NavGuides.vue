<template>
  <ul class = "nav nav-tabs">
    <li v-for="guide in guides" class = "nav-item">
        <a :class = "'nav-link' + active==guide?'active':''" aria-current = "page" href = "#">{{ guide }}</a>
    </li>
    </ul>
</template>
<script>
export default {

    props: {
        guides: {
            type: Array
        },
        active:{
            type: String
        }
    },
    methods: {
        handleClick(event) {
            this.$emit('button-click', this.id_element, event);
        }
    }
} 
</script>