<template>
  <div class="home">
    <h1>{{ msg }}</h1>

    <router-link :to="{path:'/catelog'}" tag="a">
      全部类别
    </router-link>
  </div>
</template>

<script>
  export default {
    name: 'home',
    data () {
      return {
        msg: 'Welcome to Home App'
      }
    }
  }
</script>
