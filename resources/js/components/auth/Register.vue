<template>
  <mdb-container>
    <mdb-row>
      <mdb-col md="6">
        <h2 style="margin-top:3rem;" class="secondary-heading mb-3">Register a new account</h2>
        <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour,</p>
      </mdb-col>

      <mdb-col md="6" class="align-self-end">
        <section class="preview" style="margin-top:3rem;">
          <div class="alert alert-danger" v-if="error && !success">
            <p>There was an error, unable to complete registration.</p>
          </div>
          <div class="alert alert-success" v-if="success">
            <p>{{ message }}</p>
          </div>
          <form autocomplete="off" v-if="!success" method="post" novalidate @submit="checkForm">
            <div style="margin-top:3rem;">
              <mdb-input id="name" block v-model="name" required type="text" label="Name"/>
              <span class="help-block" v-if="errors.name">{{ errors.name }}</span>
            </div>
            <div style="margin-top:3rem;">
              <mdb-input
                block
                id="email"
                v-model="email"
                required
                type="email"
                label="Your e-mail"
              />
              <span class="help-block" v-if="errors.email">{{ errors.email }}</span>
            </div>
            <div class="text-center mt-5">
              <mdb-btn id="registerbutton" color="primary" block>
                <div v-if="!isLoading" id="register">REGISTER</div>
                <object v-if="isLoading" id="loader" data="svg/tail-spin.svg"></object>
              </mdb-btn>
            </div>
          </form>
          <div v-cloak class="text-center mt-5">
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
          </div>
        </section>
      </mdb-col>
    </mdb-row>
  </mdb-container>
</template>
<script>
/**
 * Register component
 * validate user input and send it as a post request
 */
import {
  mdbCol,
  mdbInput,
  mdbTextarea,
  mdbContainer,
  mdbNumericInput,
  mdbTooltip,
  mdbRow,
  mdbIcon,
  mdbBtn,
  mdbBtnGroup,
  mdbBtnToolbar
} from "mdbvue";

/**
 * Main logic
 */
export default {
  data() {
    return {
      name: "",
      email: "",
      error: false,
      message: {},
      isLoading: false,
      errors: {
        email: "",
        response: {}
      },
      success: false
    };
  },
  components: {
    mdbCol,
    mdbInput,
    mdbTextarea,
    mdbContainer,
    mdbNumericInput,
    mdbRow,
    mdbIcon,
    mdbBtn,
    mdbBtnGroup,
    mdbBtnToolbar
  },
  methods: {
    register() {
      this.isLoading = true; // show the loader svg
      var app = this;
      this.$auth.register({
        url: "/auth/signup",
        data: {
          name: app.name,
          email: app.email
        },
        success: function(res) {
          (app.success = true), (app.message = res.data.message);
        },
        error: function(res) {
          app.error = true;
          app.errors = res.response.data.errors;
          app.isLoading = false;
        },
        redirect: null
      });
    },
    validateEmail(email) {
      // validating email with RegExp it returns the email i lower case if it passes the test else returns false
      return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(
        String(email).toLowerCase()
      );
    },
    provider: (e, pro) => {
      // e= event , pro =social provider like google, facebook, github .. they will be redirected to the laravel route
      return (window.location = `provider/login/${pro}`); // we send to Laravel root depending on the social provider
    },
    checkForm(event) {
      event.preventDefault();
      this.validateEmail(this.email)
        ? (event.target.classList.add("was-validated"), this.register())
        : (this.errors.email = "please enter a valid email");
    }
  }
};
</script>

[v-cloak] > * { display:none } // v-cloak is only available on page load, it  is useful as sometime at page load we see the {{}} 
[v-cloak]::before { content: "loading…" }
// scoped style will be not used by other component
<style scoped>
section.preview {
  border: 1px solid #e0e0e0;
  padding: 15px;
}

#register {
  display: "";
}
#loader {
  width: 20px;
  height: 20px;
}
</style>
