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
                <div v-if="!isloading" id="register">REGISTER</div>
                <object v-if="isloading" id="loader" data="svg/tail-spin.svg"></object>
              </mdb-btn>
            </div>
          </form>
          <div v-cloak class="text-center mt-5">
            <mdb-btn-toolbar>
              <mdb-btn-group class="flex-fill" style="margin-top:10px;">
                <mdb-btn
            
                  @click="(event) => { provider(event, 'facebook') }"
                  color="mdb-color lighten-2"
                >
                  <object class="social" data="svg/facebook-f.svg"></object>
                </mdb-btn>

                <mdb-btn 
                
                  
                  @click="(event) => { provider(event, 'google') }"
                  color="indigo lighten-2"
                >
                  <object  class="social" data="svg/google.svg"></object>
                </mdb-btn>
                <mdb-btn
               
                
                  @click="(event) => { provider(event, 'github') }"
                  color="cyan lighten-2"
                >
                  <object class="social" style="width:30px;" data="svg/github.svg"></object>
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
export default {
  data() {
    return {
      name: "",
      email: "",
      error: false,
      message: {},
      isloading: false,
      errors: {},
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
      this.isloading = true;
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
          app.isloading = false;
        },
        redirect: null
      });
    },
    provider: (e, pro) => {
      return (window.location = `provider/login/${pro}`);
    },
    checkForm(event) {
      event.preventDefault();
      event.target.classList.add("was-validated");
      this.register();
    }
  }
};
</script>

[v-cloak] > * { display:none }
[v-cloak]::before { content: "loading…" }

<style scoped>
section.preview {
  border: 1px solid #e0e0e0;
  padding: 15px;
}
.social {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 25px;
  height: 25px;
  bottom: 5px;
  opacity: 1;
}

#register {
  display: "";
}
#loader {
  width: 20px;
  height: 20px;
}
</style>
