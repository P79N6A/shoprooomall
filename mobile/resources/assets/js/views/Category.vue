<template>
  <div class="category">
    <h1>{{ msg }}</h1>

    <router-link :to="{path:'/goods', query:{id:1}}" tag="a">
      牛仔裤
    </router-link>
  </div>
</template>

<script>
  export default {
    name: 'category',
    data () {
      return {
        msg: 'Welcome to Category Page.'
      }
    }
  }
</script>
