<template>
  <!-- with v-cloak we hide the ugly {{}} on page load and show loading message as refereed bottom in the style deceleration   -->
  <div style="margin-left:50px" v-cloak>
    <mdb-container fluid>
      <mdb-jumbotron>
        <form autocomplete="off" method="post" novalidate @submit="checkReceiversName">
          <p class="lead">Transfer page.</p>
          <hr class="my-2">
          <p>Here you can transfer money all you need is the receiver card number and sufficient balance on your account .</p>
          <mdb-row class="align-items-center justify-content-start">
            <mdb-col style="margin:15px">
              <!-- Current balance card dynamically loaded   -->
              <mdb-card>
                <mdb-card-header :color="color">Transfer</mdb-card-header>
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
                  <div style="margin-top:3rem;">
                    <mdb-input
                      v-model="receiverCardNumber"
                      type="text"
                      label="Card number of the reciever"
                      @change="handleInput()"
                    />
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlTextarea1">Example textarea</label>
                    <textarea
                      v-model="reference"
                      class="form-control"
                      id="exampleFormControlTextarea1"
                      rows="2"
                    ></textarea>
                  </div>

                  <mdb-card-text></mdb-card-text>
                  <mdb-btn :class="buttonClass">send it from your account</mdb-btn>
                </mdb-card-body>
              </mdb-card>
            </mdb-col>
          </mdb-row>
        </form>
      </mdb-jumbotron>
    </mdb-container>

    <mdb-modal
      v-if="checkedUser"
      @close="checkedUser = false"
      removeBackdrop
      fullHeight
      position="top"
      success
    >
      <mdb-modal-body>
        <mdb-row center class="align-items-center">
          <h2>
            <mdb-badge>Confirm</mdb-badge>
          </h2>
          <p class="pt-3 mx-4">
            {{confirm.header}}
            <strong>{{confirm.body}}</strong>.
          </p>
          <mdb-btn
            color="success"
            @click="submitInput"
            icon="fa-eye"
            iconClass="ml-1"
            iconColor="white"
            iconRight
          >I confirm</mdb-btn>
          <mdb-btn outline="success" @click="checkedUser = false">Cancel</mdb-btn>
        </mdb-row>
      </mdb-modal-body>
    </mdb-modal>

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
      receiverCardNumber: "",
      errors: {
        body: "",
        header: ""
      },
      checkedUser: false,
      confirm: { message: "" },
      message: "Type the money on Dollars",
      color: "ripe-malinka-gradient",
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
      this.amount > 1000000
        ? ((this.color = "deep-orange lighten-1"),
          (this.message = "Lucky receiver!!!"),
          (this.buttonClass = "btn btn-danger btn-lg ripple-parent"))
        : this.amount > 1000
        ? ((this.color = "ripe-malinka-gradient"),
          (this.message = "giving is good!"),
          (this.buttonClass = "btn btn-success btn-lg ripple-parent"))
        : ((this.color = "ripe-malinka-gradient"),
          (this.message = "Type the money on Dollars"),
          (this.buttonClass = "btn btn-primary btn-lg ripple-parent"));
    },
    checkReceiversName() {
      event.preventDefault();
      const receiver = this.receiverCardNumber,
        amount = this.amount;
      !this.isValid() && this.receiverCardNumber
        ? ((this.errors.header = "Invalid input"),
          (this.errors.body =
            "The card number you entered is wrong please try again"))
        : !(amount > 0)
        ? ((this.errors.header = "Invalid input"),
          (this.errors.body = "The minimum amount need to be sent."))
        : this.$http // valid -- send post request
            .post("/auth/cardchecker", {
              // pass the data
              receiver,
              amount
            })
            .then(response => {
              console.log(response);
              response.data.success
                ? ((this.checkedUser = true),
                  (this.confirm.header = response.data.messages.header),
                  (this.confirm.body = response.data.messages.body),
                  (this.errors.header = ""),
                  (this.errors.body = ""))
                : ((this.errors.header = response.data.errors.header),
                  (this.errors.body = response.data.errors.body));
            })
            .catch(e => {
              console.log(e);
            });
      // form not valid user didn't

      return false;
    },
    /**
     * card number validation
     * this a second layer of validation
     * Base validation layer leave on the back-end on app/Http/Controllers/AuthController on the login method
     * if parameters receiverCardNumber returns receiverCardNumber validation else return pin number validation
     * receiverCardNumber : check if the first 9 characters are digits and the rest four are alphabetic
     * pin : check for four digits number .
     */

    isValid() {
      return this.catchErrors(this.receiverCardNumber) // check receiverCardNumber if it is null to avoid Cannot read property of null errors
        ? // receiverCardNumber is null return false validation and no errors
          !isNaN(this.receiverCardNumber.substring(0, 9)) && // if receiverCardNumber is not null and the first 9 characters are integers // and
          isNaN(this.receiverCardNumber.substring(9, 13)) && //  the last 4 characters are letters // and
            this.receiverCardNumber.length === 13 //  receiverCardNumber length is 13 if code reach this line and find it true the cardNumber is valid
        : //cardOrPin is false,  check pin validation
          false; //  validation failed
    },
    /**
     * function to avoid null and undefined errors
     */
    catchErrors(parameter) {
      try {
        parameter.length;
      } catch (e) {
        return false;
      }
      return true;
    },
    /**
     * this method send a get request to api/auth/transaction
     * the request is catch on routes/api.php the handed to App/AccountActivity.php
     * were get proceeded by transaction public method.
     * we send back a detailed message in a json response
     * the response trigger a modal that show the receiver name and ask the user for confirmation on the screen
     * this function triggered after user confirmation
     * expected valid data
     */
    submitInput(event) {
      let amount = this.amount,
        reference = this.reference,
        secondPartyCardNumber = this.receiverCardNumber;
      this.checkedUser = false; // hiding confirmation modal
      // get the data from the component
      this.$http // valid -- send post request
        .post("/auth/transaction", {
          // pass the data
          amount,
          reference,
          secondPartyCardNumber
        })
        .then(response => {
          // promise
          response.data.success
            ? ((this.messages.header = response.data.messages.header), // get the response header for modal header
              (this.messages.body = response.data.messages.body), // response message for modal message
              (this.success = true), // trigger modal with message
              (this.amount = ""), // clear already submitted data
              (this.reference = ""))
            : ((this.errors.header = response.data.errors.header),
              (this.errors.body = response.data.errors.body)); // clear submitted reference
        });
      // form not valid user didn't put any value
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



