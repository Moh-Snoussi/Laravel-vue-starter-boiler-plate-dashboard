<template>
  <mdb-container>
    <div class="mt-5" style="opacity:0.2">
      <!-- <h3 class="mx-3">Basic footer</h3> -->
      <mdb-footer color="blue" class="font-small pt-4 mt-4">
        <mdb-container class="text-left">
          <mdb-row>
            <mdb-col sm="6">
              <!-- <h5 class="title">Footer Content</h5>
              <p>Here you can use rows and columns here to organize your footer content.</p>-->
            </mdb-col>
            <mdb-col sm="6">
              <!-- <h5 class="title">Links</h5> -->
              <!-- <ul>
                <li class="list-unstyled"><a href="#!">Link 1</a></li>
                <li class="list-unstyled"><a href="#!">Link 2</a></li>
                <li class="list-unstyled"><a href="#!">Link 3</a></li>
                <li class="list-unstyled"><a href="#!">Link 4</a></li>
              </ul>-->
            </mdb-col>
          </mdb-row>
        </mdb-container>
        <div class="footer-copyright text-center py-3"></div>
      </mdb-footer>
    </div>
  </mdb-container>
</template>

<script>
import { mdbFooter, mdbContainer, mdbRow, mdbCol, mdbIcon } from "mdbvue";

export default {
  name: "FooterPage",
  components: {
    mdbFooter,
    mdbContainer,
    mdbRow,
    mdbCol,
    mdbIcon
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>
