<template>
  <h1>Good bye</h1>
</template>

<script>
export default {
  mounted() {
    this.$auth.logout({
      method: 'get',
       url: "auth/logout", // logout from controller and revoke token from datbase
      makeRequest: true,
      redirect: "/"
    });
   
  }
};
</script>
