<template>
  <div class="user-message">
    <h1>{{ msg }}</h1>
  </div>
</template>

<script>
  export default {
    name: 'user-message',
    data: function () {
      return {
        msg: 'Welcome to user-message App'
      }
    }
  }
</script>
