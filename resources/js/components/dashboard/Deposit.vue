<template>
  <!-- with v-cloak we hide the ugly {{}} on page load and show loading message as refereed bottom in the style deceleration   -->
  <div style="margin-left:50px" v-cloak v-on:click="collapse">
    <mdb-container fluid>
      <mdb-jumbotron>
        <form autocomplete="off" method="post" novalidate @submit="submitInput">
          <p class="lead">Deposit page.</p>
          <hr class="my-2">
          <p>Here you can put money on your account by only typing an submitting a number .</p>
          <mdb-row class="align-items-center justify-content-start">
            <mdb-col style="margin:15px">
              <!-- Current balance card dynamically loaded   -->
              <mdb-card>
                <mdb-card-header :color="color">Deposit</mdb-card-header>
                <mdb-card-body>
                  <mdb-card-title>{{message}}</mdb-card-title>
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
                  <mdb-btn :class="buttonClass">get it on your account</mdb-btn>
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
    mdbModalFooter,
    mdbModalTitle,
    mdbBadge
  },
  data() {
    return {
      amount: "",
      reference: "",
      message: "Type the money on Dollars",
      color: "aqua-gradient",
      buttonClass: "btn btn-primary btn-lg ripple-parent",
      success: false,
      messages: {
        header: "",
        body: ""
      }
    };
  },
  methods: {
    /**
     * The following function returns color that depends on the user current balance
     * more then 100$ purple gradient
     * more then 0 yellow gradient
     * else red gradient
     *
     */
    handleInput() {
      console.log(this.amount);
      this.amount > 10000
        ? ((this.color = "deep-orange lighten-1"),
          (this.message = "Dont wast !!!"),
          (this.buttonClass = "btn btn-danger btn-lg ripple-parent"))
        : this.amount > 1000
        ? ((this.color = "ripe-malinka-gradient"),
          (this.message = "Make something good!"),
          (this.buttonClass = "btn btn-success btn-lg ripple-parent"))
        : ((this.color = "aqua-gradient"),
          (this.message = "Type the money on Dollars"),
          (this.buttonClass = "btn btn-primary btn-lg ripple-parent"));
    },
    /**
     * this method send a get request to api/auth/activity
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
              this.messages.header = response.data.messages.header; // get the response header for modal header
              this.messages.body = response.data.messages.body; // response message for modal message
              this.success = true; // trigger modal with message
              this.amount = ""; // clear already submitted data
              this.reference = ""; // clear submitted reference
            })
        : ""; // form not valid user didn't put any value
    }
    /**
     * the following function will fire on a click at the body component
     * will pass a collapse true
     * the parent component (baseComponent) should accordingly and collapse the side menu
     */,
    collapse() {
      this.$router.push(`?collapse=true`);
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



