<template>
  <transition name="slide">
    <avatar
      v-if="$auth.check()"
      :rounded="collapse=='false'"
      v-bind:style="(collapse=='false')? 'margin-top:35px;margin-left:10px;':'margin-top:35px;margin-left:0px;'"
      :username="$auth.user().name"
      :src="$auth.user().avatar"
    >{{$parent.collapse}}</avatar>
  </transition>
</template>

<script>
var Avatar = require("vue-avatar");
export default {
  components: {
    avatar: Avatar.Avatar
  },
  data() {
    return {
      collapse: false
    };
  },
  /**
   * watch the url on the route 
   * assign collapse on url change
   * main purpose:
   * let the avatar be rounded and add margin space if collapse is false
   * if there is no parameters in the url then get the parent state
   */
  watch: {
    $route: function() {
      this.collapse =  String(this.$parent.collapsed);
    }
  },

  methods: {
    // dynamic margin depends on the state of the sideBar
    photoMargin() {
      return {
        marginLeft: this.collapse ? "30px" : "5px",
        marginTop: "35px"
      };
    }
  },
  mounted() {
    this.$parent.menu[1].title = this.$auth.user().name;// add the name of the user under the avatar the parent is (baseComponent)
    this.collapse = this.$parent.collapsed; // initial value of collapse is taken from the parent component(baseComponent) 
    
    
  }
};
</script>