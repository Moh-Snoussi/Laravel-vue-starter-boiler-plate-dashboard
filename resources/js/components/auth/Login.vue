<template>
  <mdb-container>
    <mdb-row>
      <mdb-col md="6">
        <h2 style="margin-top:3rem;" class="secondary-heading mb-3">Log in</h2>
        <p>{{ infomessage }}</p>
      </mdb-col>

      <mdb-col md="6" class="align-self-end">
        <section class="preview" style="margin-top:3rem;">
          <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
          </div>

          <form autocomplete="off" method="post" novalidate @submit="checkForm">
            <div style="margin-top:3rem;">
              <mdb-input id="CARD_NUMBER" v-model="CARD" required type="text" label="Card number"/>
              <span class="help-block" v-if="errors.CARD_NUMBER">{{ JSON.stringify(errors.CARD_NUMBER) }}</span>
            </div>

            <div style="margin-top:3rem;">
              <mdb-input type="text" label="Pin" id="PIN" v-model="PIN" required block/>
              <span class="help-block" v-if="errors.PIN">{{ errors.PIN }}</span>
            </div>
                 <div class="custom-control custom-checkbox mb-3">
          <input type="checkbox" v-model="remember" class="custom-control-input" id="customControlValidation1" default checked>
          <label class="custom-control-label" for="customControlValidation1">Remember me</label>
    
        </div>
            <div class="text-center mt-5">
              <mdb-btn block id="loginbutton" color="primary" size="lg">
                <div v-if="!istloading" id="registert">LOG IN</div>
                <object v-if="istloading" id="loadert" data="svg/oval.svg"></object>
              </mdb-btn>
            </div>
          </form>
          <div class="text-center md-5">
            <mdb-btn-toolbar>
              <mdb-btn-group class="flex-fill" style="margin-top:10px;">
                <mdb-btn
                  @click="(event) => { provider(event, 'facebook') }"
                  color="mdb-color lighten-2"
                >
                  <object class="rounded mx-auto d-block" style="height:30px" data="svg/facebook-f.svg"></object>
                </mdb-btn>
                <mdb-btn
                  @click="(event) => { provider(event, 'google') }"
                  color="indigo lighten-2"
                >
                  <object class="rounded mx-auto d-block" style="width:30px" data="svg/google.svg"></object>
                </mdb-btn>
                <mdb-btn
                  @click="(event) => { provider(event, 'github') }"
                  color="cyan lighten-2"
                >
                  <object  class="rounded mx-auto d-block" style="width:30px" data="svg/github.svg"></object>
                </mdb-btn>
              </mdb-btn-group>
            </mdb-btn-toolbar>
          </div>
        </section>
      </mdb-col>
    </mdb-row>
  </mdb-container>
</template>

<script>
//this component leave in the app.js grand parrent directory
import {
  mdbBtnGroup,
  mdbBtnToolbar,
  mdbIcon,
  mdbCol,
  mdbInput,
  mdbTextarea,
  mdbContainer,
  mdbNumericInput,
  mdbRow,
  mdbBtn
} from "mdbvue";
export default {
  props: ["email", "confirmed", "user"],
  data() {
    return {
      name: null,
      remember: true,
      error: false,
      infomessage: "Please use your credit card information to login",
      istloading: false,
      errors: {},
      success: {},
      CARD: "",
      PIN: ""
    };
  },
  components: {
    mdbBtnGroup,
    mdbBtnToolbar,
    mdbIcon,
    mdbCol,
    mdbInput,
    mdbTextarea,
    mdbContainer,
    mdbNumericInput,
    mdbRow,
    mdbIcon,
    mdbBtn
  },
  methods: {
    login: function() {
      this.istloading = true;// lunch the loader
      var app = this;
       
      this.$auth.login({
        url: "/auth/cardlogin", // Larevel API Route
        data: {
          CARD_NUMBER: app.CARD, // Card number and pin authentication
          PIN: app.PIN,
          remember_me: app.remember // this will extend the life of the token
        },
        success: function(res) {
          // handle json response and auth get JWT token as a Bearer
          (app.success = true), (app.message = res.data.message);
        },
        error: function(res) {
          app.isloading = false; // stop loading
          app.errors = res.response.data.errors;
          
          
        },
        rememberMe: app.remember,
        redirect: "/dashboard",
        fetchUser: true
      });
      app.istloading = false;
    },
    checkForm(event) {
      // frondend validation on submit
      event.preventDefault(); // stop form from sending
      event.target.classList.add("was-validated");
      this.login(); // if is valid then submit
    },
    message: function() {
      // costumized welcome message that is displayed on login page
      const registerd =
        typeof this.email !== "undefined"
          ? `Successfully registred, we send you credit card information to ${
              this.email
            } , please confirm your email and then login with your new pin and creadit card.`
          : "";
      const welcome =
        typeof this.user !== "undefined"
          ? `Welcome back ${this.titleCase(
              this.user
            )} Please use your credit card information to login.`
          : "";
      // tenary operation they are like if else (if ? ) and else(:)
      // we check if there is a confirmation query
      this.infomessage =
        typeof this.confirmed !== "undefined" // this way there will be no error if th variable is undefined
          ? this.confirmed == 0
            ? registerd // 0 means didnt confirm fresh registred users will land here, the const message will get the infomessage that we displayed a proper message to the user
            : welcome // this is user is coming from email confirmation so we say welcome back
          : this.infomessage; // if nothing from the above condition is true then we dpnt want to change the public message
    },
    titleCase: function(str) {
      // to turn every first letter of every word capitalized used to display the users name
      var splitStr = str.toLowerCase().split(" ");
      for (var i = 0; i < splitStr.length; i++) {
        splitStr[i] =
          splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
      }
      // Directly return the joined string
      return splitStr.join(" ");
    },
    provider: function(e, pro) {
      // e= event , pro =social provider like google, facebooke, github .. they will be redirected to the laravel route

      return (window.location = `provider/login/${pro}`); //redirecting
    },
    checkForm(event) {
      event.preventDefault();
      event.target.classList.add("was-validated");
      this.login();
  },},
  created() {
    // if the app is created we show a costumized welcome message
    this.message();
  }
};
</script>


<style scoped>
section.preview {
  border: 1px solid #e0e0e0;
  padding: 15px;
}
.social {
  display: block;
  width: 25px;
  height: 25px;
  bottom: 5px;
  opacity: 1;
}

#registert {
  display: "";
}
#loadert {
  width: 20px;
  height: 20px;
}
</style>



