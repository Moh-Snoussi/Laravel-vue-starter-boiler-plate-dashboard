<template>
  <mdb-container>
    <mdb-row>
      <mdb-col md="6">
        <h2 style="margin-top:3rem;" class="secondary-heading mb-3">Log in</h2>
        <p>{{ infoMessage }}</p>
      </mdb-col>

      <mdb-col md="6" class="align-self-end">
        <section class="preview" style="margin-top:3rem;">
          <div class="alert alert-danger" v-if="errors.response.message">
            <p>There was an error:</p>
            <p>- {{ errors.response.message }}<p>
              <p v-if="errors.response.waitingMinutes">   - We only allow {{ errors.response.attempts }} attempts in {{ errors.response.waitingMinutes }} minutes .<p>
             <p v-if="errors.response.details">   - {{errors.response.details}}.<p>
            <p>Unable to complete log in.</p>
          </div>

          <form autocomplete="off" method="post" novalidate @submit="checkForm">
            <div style="margin-top:3rem;">
              <mdb-input
              style="margin-bottom: -0.5rem"
                id="cardNumber"
                v-model="cardNumber"
                required
                type="text"
                label="Card number"
              />
              <span
                class="notValide"
                v-if="errors.cardNumber"
              >Your card number is required</span>
            </div>

            <div style="margin-top:3rem;">
              <mdb-input style="margin-bottom: -0.5rem" type="text" label="pin" id="pin" v-model="pin" required block/>
              <span class="notValide" v-if="errors.pin">{{errors.pin}}</span>
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
              <label style="margin-top:3rem;" class="custom-control-label" for="customControlValidation1">remember me</label>
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
import { async } from 'q';

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
          this.isLoading = !this.isLoading;
        },
        error: function(res) {
          this.success = true;
          this.errors.response = res.response.data.errors;
          this.isLoading = !this.isLoading;
        },
        rememberMeMe: app.rememberMe,
        redirect: "/dashboard", // if it reach here user has been granted access
        fetchUser: true
      });
       // stop the loader
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
     * if parameters cardNumber returns cardNumber validation else return pin number validation
     * cardNumber : check if the first 9 characters are digits and the rest four are alphabetic
     * pin : check for four digits number .
     */

    isValid(cardOrPin) {
      return cardOrPin == 'cardNumber' // if true returns will execute the next line of code for cardNumber validation
        ?  !this.catchErrors(this.cardNumber) // cardOrPin is true,  check cardNumber if it is null to avoid Cannot read property of null errors
        ? false // cardNumber is null return false validation and no errors
        :   !isNaN(this.cardNumber.substring(0, 9)) // if cardNumber is not null and the first 9 characters are integers 
            && // and
            isNaN(this.cardNumber.substring(9, 13)) //  the last 4 characters are letters  
            && // and 
            this.cardNumber.length === 13 //  cardNumber length is 13 if code reach this line and find it true the cardNumber is valid
        :   //cardOrPin is false,  check pin validation
            !this.catchErrors(this.pin) // avoiding Cannot read property of null errors
            ? false // pin is null return false validation and no errors
        :   !isNaN(this.pin) // is pin a number
            && // and 
            this.pin.length == 4; //  pin length is 4 if code reach this line and find it true the pin is valid
    },catchErrors(parameter){
    try{
   parameter.length;
}catch(e){
   return false
}
return true},
    /**
     * heckForm will prevent submitting the form and validate the pin and the card number
     */

    checkForm(event) {
      let valid = 0;
      // frond-end validation on submit
      event.preventDefault(); // prevent form from submitting
      !this.isValid('cardNumber') // if cardNumber is not valid
        ? (this.errors.cardNumber = "Your card number is invalid") 
        : (this.errors.cardNumber = "");
      !this.isValid('pin') // if pin is not valid
        ? (this.errors.pin = "Your pin is invalid")
        : !this.errors.cardNumber
        ? this.login()
        : (this.errors.pin = ""); // if(cardNumber isValid){if(pin isValid){return with form submission}else{pin error}}else{cardNumber error}
  
    },
    welcomeMessage() {
      // customized welcome message that is displayed on login page
      const registered =
        this.catchErrors(this.email)
          ? `Successfully registered, we send you credit card information to ${
              this.email
            } , please confirm your email and then login with your new pin and credit card.`
          : "";
      const welcome =
        this.catchErrors(this.user) // avoiding Cannot read property of null errors
          ? `Welcome back ${this.titleCase( // user already confirmed hes email we will display his name
              this.user
            )} Please use your credit card information to login.`
          : "";
      // tenary operation they are like if else (if ? ) and else(:)
      // we check if there is a confirmation query

      return (this.infoMessage =
        this.catchErrors(this.confirmed) // this way there will be no error if the variable is undefined
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
    this.welcomeMessage();
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
.notValide{
    margin-bottom: 20px;
    color: red;
    font-size: 12px;
    padding-top: 0px;
 
    margin-bottom: 20px;
}
#loader {
  width: 20px;
  height: 20px;
}
</style>



