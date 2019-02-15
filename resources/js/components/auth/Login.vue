<template>
  <mdb-container>
    <mdb-row>
      <mdb-col md="6">
        <h2 style="margin-top:3rem;" class="secondary-heading mb-3">Log in</h2>
        <p>{{ infoMessage }}</p>
      </mdb-col>

      <mdb-col md="6" class="align-self-end">
        <section class="preview" style="margin-top:3rem;">
          <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
          </div>

          <form autocomplete="off" method="post" novalidate @submit="checkForm">
            <div style="margin-top:3rem;">
              <mdb-input
                id="cardNumber"
                v-model="cardNumber"
                required
                type="text"
                label="Card number"
              />
              <span class="help-block" v-if="errors.cardNumber">{{errors.cardNumber}}</span>
              <span
                class="help-block"
                v-if="errors.response.cardNumber"
              >Your card number is required</span>
            </div>

            <div style="margin-top:3rem;">
              <mdb-input type="text" label="pin" id="pin" v-model="pin" required block/>
              <span class="invalid-feedback" v-if="errors.pin">Your pin is required</span>
              <span class="help-block" v-if="errors.response.pin">{{errors.response.pin}}</span>
            </div>
            <div class="custom-control custom-checkbox mb-3">
              <input
                type="checkbox"
                v-model="rememberMe"
                class="custom-control-input"
                id="customControlValidation1"
                default
                checked
              >
              <label class="custom-control-label" for="customControlValidation1">remember me</label>
            </div>
            <div class="text-center mt-5">
              <mdb-btn block id="loginbutton" color="primary" size="lg">
                <div v-if="!isLoading" id="registert">LOG IN</div>
                <object v-if="isLoading" id="loader" data="svg/oval.svg"></object>
              </mdb-btn>
            </div>
          </form>
          <div class="row" style="margin-top:20px;">
            <div class="col">
              <a @click="(event) => { provider(event, 'facebook') }" class="light-blue-text mx-2">
                <i class="fab fa-facebook-f"></i>
              </a>
            </div>
            <div @click="(event) => { provider(event, 'google') }" class="col">
              <a class="light-blue-text mx-2">
                <i class="fab fa-google"></i>
              </a>
            </div>
            <div class="col">
              <a class="light-blue-text mx-2">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
            <div @click="(event) => { provider(event, 'github') }" class="col">
              <a class="light-blue-text mx-2">
                <i class="fab fa-github"></i>
              </a>
            </div>
          </div>
        </section>
      </mdb-col>
    </mdb-row>
  </mdb-container>
</template>

<script>
/**
 * Login component
 * it get loaded by the baseComponent
 * handles user input and validate it.
 */
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

/**
 * Main logic
 */
export default {
  props: ["email", "confirmed", "user"], // props is passed from ../app.js  witch get parameters in the url
  data() {
    return {
      name: null,
      isLoading: false,
      rememberMe: true,
      error: false,
      infoMessage: "Please use your credit card information to login",
      errors: {
        cardNumber: "",
        pin: "",
        response: {}
      },
      success: {},
      cardNumber: "",
      pin: ""
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
    login() {
      // lunch the loader
      this.isLoading = !this.isLoading;
      var app = this;
      this.$auth.login({
        url: "/auth/cardlogin", //will get from the base url we defined on the app.js 'http://localhost:8000/api/' to the Larevel API Route
        data: {
          cardNumber: app.cardNumber, // set the post request
          pin: app.pin,
          rememberMe_me: app.rememberMe // this will extend the life of the token
        },
        success: function(res) {
          // handle json response and auth get JWT token as a Bearer
          (app.success = true), (app.message = res.data.message);
          this.rememberMe ? this.setCookie() : this.setCookie(false);
        },
        error: function(res) {
          console.log(res.response.data.errors);
        },
        rememberMeMe: app.rememberMe,
        redirect: "/dashboard", // if it reach here user has been granted access
        fetchUser: true
      });
      this.isLoading = !this.isLoading; // stop the loader
    },
    /**
     * setting or deleting the cookies if user check remember me
     */
    setCookie(setOrDelete = true) {
      setOrDelete
        ? (this.$cookie.set("cardNumber", this.cardNumber, 7),
          this.$cookie.set("pin", this.pin, 7))
        : (this.$cookie.delete("cardNumber"), this.$cookie.delete("pin"));
    },
    /**
     * getting the cookies on page load
     */

    getCookie() {
      this.cardNumber = this.$cookie.get("cardNumber");
      this.pin = this.$cookie.get("pin");
    },

    /**
     * card number validation
     * this a second layer of validation
     * Base validation layer leave on the back-end on app/Http/Controllers/AuthController on the login method
     * if parameters true returns cardNumber validation else return pin number validation
     * cardNumber : check if the first 9 characters are digits and the rest four are alphabetic
     * pin : check for four digits number .
     */

    isValid(pinOrCard) {
      return pinOrCard
        ? !isNaN(this.cardNumber.substring(0, 9)) &&
            isNaN(this.cardNumber.substring(9, 13)) &&
            this.cardNumber.length == 13
        : !isNaN(this.pin.substring(0, 4)) && this.pin.length == 4;
    },
    /**
     * heckForm will prevent submitting the form and validate the pin and the card number
     */

    checkForm(event) {
      let valid = 0;
      // frond-end validation on submit
      event.preventDefault(); // prevent form from submitting
      !this.isValid(true)
        ? (this.errors.cardNumber = "Your card number is invalid")
        : (this.errors.cardNumber = "");
      !this.isValid(false)
        ? (this.errors.pin = "Your pin is invalid")
        : !this.errors.cardNumber && !this.errors.pin
        ? (event.target.classList.add("was-validated"), this.login())
        : (this.errors.pin = ""); // if(cardNumber isValid){if(pin isValid){return with form submission}else{pin error}}else{cardNumber error}
      // (event.target.classList.add("was-validated"),this.login()) :
      // this.errors.pin = "Your pin is invalid" : this.errors.cardNumber = "Your card number is invalid"
      // if is valid then submit
    },
    message() {
      // customized welcome message that is displayed on login page
      const registered =
        typeof this.email !== "undefined"
          ? `Successfully registered, we send you credit card information to ${
              this.email
            } , please confirm your email and then login with your new pin and credit card.`
          : "";
      const welcome =
        typeof this.user !== "undefined"
          ? `Welcome back ${this.titleCase(
              this.user
            )} Please use your credit card information to login.`
          : "";
      // tenary operation they are like if else (if ? ) and else(:)
      // we check if there is a confirmation query

      return (this.infoMessage =
        typeof this.confirmed !== "undefined" // this way there will be no error if th variable is undefined
          ? this.confirmed == 0
            ? registered // 0 means didn't confirm fresh registered users will land here, the const message will get the infoMessage that we displayed a proper message to the user
            : welcome // this is user is coming from email confirmation so we say welcome back
          : this.infoMessage); // if nothing from the above condition is true then we dont change the public message
    },
    titleCase(str) {
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
      // e= event , pro =social provider like google, facebook, github .. they will be redirected to the laravel route
      return (window.location = `provider/login/${pro}`); //redirecting
    }
  },
  created() {
    // if the app is created we show a customized welcome message
    this.message();
    this.getCookie();
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
#loader {
  width: 20px;
  height: 20px;
}
</style>



