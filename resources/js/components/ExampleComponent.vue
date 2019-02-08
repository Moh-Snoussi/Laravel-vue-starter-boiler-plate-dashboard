<template>
    <mdb-container>
      <mdb-row>
        <mdb-col md="6">
          <mdb-card>
            <mdb-card-body>
              <form>
                <p class="h4 text-center py-4">Sign up and get 300$</p>
                <div class="grey-text">
                  <mdb-input label="Your name"  group type="text" validate error="wrong" success="right"/>
                  <mdb-input label="Your email"  group type="email" validate error="wrong" success="right"/>
                  <mdb-input label="Confirm your email"  group type="text" validate error="wrong" success="right"/>
                  <mdb-input label="Your password" group type="password" validate/>
                </div>
                <div class="text-center py-4 mt-3">
                  <mdb-btn color="cyan" type="submit">Register</mdb-btn>
                </div>
              </form>
            </mdb-card-body>
          </mdb-card>
        </mdb-col>
      </mdb-row>
    </mdb-container>
</template>

<script>
import { mdbContainer, mdbRow, mdbCol, mdbInput, mdbTextarea, mdbBtn, mdbIcon, mdbCard, mdbCardBody, mdbModal, mdbModalHeader, mdbModalBody, mdbModalFooter } from 'mdbvue';

export default {
  name: 'FormsPage',
  components: {
    mdbContainer,
    mdbRow,
    mdbCol,
    mdbInput,
    mdbTextarea,
    mdbBtn,
    mdbIcon,
    mdbCard,
    mdbCardBody,
    mdbModal,
    mdbModalHeader,
    mdbModalBody,
    mdbModalFooter
  },
  data() {
    return {
      showModal: false
    };
  }
};
</script>

<style scoped>
</style>
