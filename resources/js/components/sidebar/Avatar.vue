<template>
<transition name="slide">
   <avatar  v-if="$auth.check()" :rounded="collapse=='true'"  v-bind:style="(collapse=='true')? 'margin-top:35px;margin-left:10px;':'margin-top:35px;margin-left:0px;'" :username="$auth.user().name" :src="$auth.user().avatar">{{$parent.collapse}}</avatar>
 </transition>
</template>

<script>
var Avatar = require('vue-avatar')
export default {
   
    components: {
    'avatar': Avatar.Avatar
  },data() { 
            return {
                collapse : false,

            }},watch: {
    '$route': function () {
      this.collapse = this.$route.query.collapse;
    }}
    
    ,methods:{
      photoMargin(){

          return {
  	  	marginLeft: this.collapse ? '30px' : '5px'  ,
	      marginTop: '35px'
    	};
          
      }
  },
  mounted(){
      this.$parent.menu[1].title = this.$auth.user().name;
      this.collapse = this.$parent.collapse;
      console.log(this.collapse);
      
      
      }
  }

</script>