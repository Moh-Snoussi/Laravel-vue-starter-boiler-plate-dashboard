


<template>
  <!-- with v-cloak we hide the ugly {{}} on page load and show loading message as refereed bottom in the style deceleration   -->
  <div style="margin-left:50px" v-cloak>
    <mdb-container fluid>
      <mdb-jumbotron>
        <p class="lead">Search page.</p>
        <hr class="my-2">
        <p>Here you can search, paginate, if want to sort then click on the header of the column you want to sort.</p>

        <vue-good-table
          style=" z-index:1;"
          mode="remote"
          @on-page-change="onPageChange"
          @on-sort-change="onSortChange"
          @on-per-page-change="onPerPageChange"
          :row-style-class="rowStyleClass"
          @on-search="searchField"
          :sort-options="{
    enabled: true,
  }"
          :totalRows="totalRecords"
          :search-options="{
    enabled: true,
    placeholder: 'Search this table',
  }"
          :pagination-options="{
    enabled: true,
    perPage: 7,
    position: 'bottom',
    mode:'pages',
    dropdownAllowAll: true,
    nextLabel: 'next',
    prevLabel: 'prev',
    rowsPerPageLabel: 'Rows per page',
    ofLabel: 'of',
    pageLabel: 'page',
    allLabel: 'All',
  }"
          :rows="rows"
          :columns="columns"
        />
      </mdb-jumbotron>
    </mdb-container>
  </div>
</template>
      
    

<script>
import { VueGoodTable } from "vue-good-table";

/**
 * Table component
 * pagination, sorting and searching on server side
 */

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
  components: {
    VueGoodTable,
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
      columns: [
        {
          label: "Date",
          field: "created_at",
          type: "date",
          dateInputFormat: "YYYY-MM-DD HH:mm:ss",
          dateOutputFormat: "MMM Do HH:mm"
        },
        {
          label: "Operation",
          field: "operation"
        },
        {
          label: "Amount",
          field: "amount"
        },
        {
          label: "to/from",
          field: "secondPartyName"
        },
        {
          label: "Reference",
          field: "reference"
        },
        {
          label: "Card Number",
          field: "secondPartyCardNumber"
        }
        //...
      ],
      rows: [],
      totalRecords: 0,
      serverParams: {
        search: { searchTerm: "" },

        sort: {
          field: "",
          type: ""
        },
        page: 1,
        perPage: 10
      }
    };
  },
  methods: {
    /**
     * rowStyleClass to style rows depend on the operation
     * accepts the processed row
     * return class name same as the operation name
     * this add a class name to the corresponding row
     */
    rowStyleClass(row) {
      return row.operation; //
    },
    /**
     * update filter parameters
     */
    updateParams(newProps) {
      this.serverParams = Object.assign({}, this.serverParams, newProps);
    },
    /**
     * Submit the typed value typed on the input to the component data.
     */
    searchField(value) {
      this.serverParams.search = value;
      this.loadItems();
    },

    /**
     * handling pagination
     */
    onPageChange(params) {
      this.updateParams({ page: params.currentPage });
      this.loadItems();
    },
    /**
     * handling how many items to be displayed per page
     */

    onPerPageChange(params) {
      this.updateParams({ perPage: params.currentPerPage });
      this.loadItems();
    },

    /**
     * handling sorting items assign the value to the component data
     */

    onSortChange(params) {
      this.updateParams({
        sort: {
          type: params[0].type,
          field: params[0].field
        }
      });
      this.loadItems();
    },

    /**
     *
     * getting all the data from the server
     * the class associated with this work is in app/Http/Controller/AccountActivity.php index method
     * getting all component data and submitting it
     *
     */
    // load items
    loadItems() {
      var search = this.serverParams.search;
      var sort = this.serverParams.sort;
      var page = this.serverParams.page;
      var perPage = this.serverParams.perPage;
      this.$http
        .get("/auth/history", {
          params: {
            search,
            sort,
            page,
            perPage
          }
        })
        .then(response => {
          this.totalRecords = response.data.total;
          this.rows = response.data.data;
        })
        .catch(function(error) {
          console.log(error);
        });
    }
    /**
     * on page load request data from the server
     */
  },
  mounted() {
    this.loadItems();
  }
};
</script>

<style>
table.vgt-table td {
  color: unset;
}
.deposit {
  background-color: #81d4fa;
  color: black;
}
.deposit:hover {
  background-color: #03a9f4;
  color: white;
}
.withdraw {
  background-color: #ef9a9a;
  color: black;
}

.withdraw:hover {
  background-color: #f44336;
}

.TransactionFrom {
  background-color: #a5d6a7;
  color: black;
}

.TransactionFrom:hover {
  background-color: #4caf50;
  color: white;
}

.TransactionTo {
  background-color: #f48fb1;
  color: black;
}

.TransactionTo:hover {
  background-color: #e91e63;
}
</style>
