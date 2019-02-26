<template>
  <!-- with v-cloak we hide the ugly {{}} on page load and show loading message as refereed bottom in the style deceleration   -->
  <div style="margin-left:50px" v-cloak>
    <mdb-container fluid>
      <mdb-jumbotron>
        <form autocomplete="off" method="post" novalidate @submit="submitInput">
          <p class="lead">Withdraw page.</p>
          <hr class="my-2">
          <p>Here you can get money from your account by only typing an submitting a number .</p>
          <mdb-row class="align-items-center justify-content-start">
            <mdb-col style="margin:15px">
              <!-- Current balance card dynamically loaded   -->
              <mdb-card>
                <mdb-card-header :color="color">Withdraw</mdb-card-header>
                <mdb-card-body>
                  <mdb-card-title>{{message}}</mdb-card-title>
                  <div class="alert alert-danger" v-if="errors.body">
                    <p>{{ errors.header }}</p>
                    <p>- {{ errors.body }}</p>
                  </div>
                  <div style="margin-top:3rem;max-width:20rem">
                    <mdb-input
                      v-model="amount"
                      type="number"
                      label="How much ?"
                      @change="handleInput()"
                    />
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Reference textarea</label>
                    <textarea
                      v-model="reference"
                      class="form-control"
                      id="exampleFormControlTextarea1"
                      rows="2"
                    ></textarea>
                  </div>
                  <mdb-card-text></mdb-card-text>
                  <mdb-btn :class="buttonClass">get it from your account</mdb-btn>
                </mdb-card-body>
              </mdb-card>
            </mdb-col>
          </mdb-row>
        </form>
      </mdb-jumbotron>
    </mdb-container>

    <!-- MODAL visible if success  -->
    <mdb-modal
      v-if="success"
      @close="success = false"
      removeBackdrop
      side
      position="bottom-right"
      info
    >
      <mdb-modal-header>
        <p class="heading">Success!</p>
      </mdb-modal-header>
      <mdb-modal-body>
        <mdb-row>
          <mdb-col>
            <p>
              <strong>{{messages.header}}</strong>
            </p>
            <p>{{messages.body}}</p>
          </mdb-col>
        </mdb-row>
      </mdb-modal-body>
    </mdb-modal>
  </div>
</template>


<script>
import {
  mdbModal,
  mdbModalHeader,
  mdbModalBody,
  mdbModalFooter,
  mdbModalTitle,
  mdbBadge,
  mdbContainer,
  mdbRow,
  mdbCol,
  mdbBtn,
  mdbCard,
  mdbCardTitle,
  mdbCardText,
  mdbCardBody,
  mdbCardHeader,
  mdbJumbotron,
  mdbInput,
  mdbTextarea,
  mdbNumericInput
} from "mdbvue";

export default {
  name: "PanelPage",
  components: {
    mdbContainer,
    mdbRow,
    mdbCol,
    mdbBtn,
    mdbCard,
    mdbCardTitle,
    mdbCardText,
    mdbCardBody,
    mdbCardHeader,
    mdbJumbotron,
    mdbInput,
    mdbTextarea,
    mdbNumericInput,
    mdbModal,
    mdbModalHeader,
    mdbModalBody,
    mdbModalTitle,
    mdbBadge
  },
  data() {
    return {
      amount: "",
      reference: "",
      message: "Type the money on Dollars",
      color: "near-moon-gradient",
      buttonClass: "btn btn-primary btn-lg ripple-parent",
      success: false,
      messages: {
        header: "",
        body: ""
      },
      errors: {
        header: "",
        body: ""
      }
    };
  },
  methods: {
    /**
     * The following function changes the color and the headline depend on the user input
     */
    handleInput() {
      console.log(this.amount);
      this.amount > 10000
        ? ((this.color = "deep-orange lighten-1"),
          (this.message = "This is saving!!!"),
          (this.buttonClass = "btn btn-danger btn-lg ripple-parent"))
        : this.amount > 1000
        ? ((this.color = "ripe-malinka-gradient"),
          (this.message = "Saving is good!"),
          (this.buttonClass = "btn btn-success btn-lg ripple-parent"))
        : ((this.color = "morpheus-den-gradient"),
          (this.message = "Type the money on Dollars"),
          (this.buttonClass = "near-moon-gradient"));
    },
    /**
     * this method send a get request to api/auth/activity
     * this function is a little bit identical to the deposit
     * the request is catch on routes/api.php the handed to App/AccountActivity.php
     * were get proceeded by depositAndWithdraw public method.
     * we retrieve the refer url from the header where we see if it comes from deposit or withdraw
     * we send back a detailed message in a json response
     * the response trigger a modal that is displayed on the screen
     */
    submitInput(event) {
      event.preventDefault(); // block any default event
      let amount = this.amount,
        reference = this.reference; // get the data from the component
      this.amount > 0 // validate if the money field is not zero
        ? this.$http // valid -- send post request
            .post("/auth/activity", {
              // pass the data
              amount,
              reference
            })
            .then(response => {
              if (response.data.success) {
                this.messages.header = response.data.messages.header; // get the response header for modal header
                this.messages.body = response.data.messages.body; // response message for modal message
                this.success = true; // trigger modal with message
                this.amount = ""; // clear already submitted data
                this.reference = ""; // clear submitted reference
              } else {
                this.errors = response.data.errors;
                console.log(this.errors);
              }
            })
        : ""; // form not valid user didn't put any value
    }
  }
};
</script>

<style>
[v-cloak] > * {
  display: none;
}
[v-cloak]::before {
  content: "loading…";
}
.jumbotron {
  padding: 15px;
}
</style>



