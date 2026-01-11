<template>
  <HomeComponent />
</template>

<script>
import HomeComponent from './components/HomeComponent.vue'
import 'bootstrap/dist/js/bootstrap.bundle';        // carrega JS (bundle com popper)
import 'bootstrap/dist/css/bootstrap.min.css'; 
import '../css/style.css';

export default {
  components: { HomeComponent }
}
</script>