<template>
<transition name="slide">
   <avatar  v-if="$auth.check()" @click="hello()" :rounded="collapse=='true'"  v-bind:style="(collapse=='true')? 'margin-top:35px;margin-left:10px;':'margin-top:35px;margin-left:0px;'" :username="$auth.user().name" :src="$auth.user().avatar">{{$parent.collapse}}</avatar>
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
      console.log(this.collapse);
      
    }}
    
    ,methods:{
      photoMargin(){

          return {
  	  	marginLeft: this.collapse ? '30px' : '5px'  ,
	      marginTop: '35px'
    	};
          
      }
      ,hello(){
          console.log(this.$parent.collapse)
          console.log('hello')

      }
  },
  mounted(){
      this.$parent.menu[1].title = this.$auth.user().name;
      this.collapse = this.$parent.collapse;
      console.log(this.collapse);
      
      
      }
  }

</script>
  <style>
  .slide-enter-active .slide-leave-active {
  transition: transform .3s ease;
}

.slide-enter, .slide-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateX(10px);
 
}
    </style>
