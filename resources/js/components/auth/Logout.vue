<template>
  <h1>Good bye</h1>
</template>

<script>
export default {
  mounted() {
    this.$auth.logout({
      makeRequest: true,
      params: {}, // data: {} in axios
      success: function() {},
      error: function() {},
      url: "api/auth/logout", // logout from controller and revoke token from datbase
      redirect: "/"
    });
  }
};
</script>
