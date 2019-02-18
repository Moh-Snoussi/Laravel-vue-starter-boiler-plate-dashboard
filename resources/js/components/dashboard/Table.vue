<template>
  <div>
    <dir style="margin-left:30px; z-index:-1000;">
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
    </dir>
  </div>
</template>

<script>
import { VueGoodTable } from "vue-good-table";

/**
 * Table component
 * pagination, sorting and searching on server side
 */

export default {
  components: {
    VueGoodTable
  },
  data() {
    return {
      columns: [
        {
          label: "Date",
          field: "created_at",
          type: "date",
          dateInputFormat: "YYYY-MM-DD HH:mm:ss",
          dateOutputFormat: "MMM Do YY HH:mm"
        },
        {
          label: "Operation",
          field: "operation"
        },
        {
          label: "Balance",
          field: "currentBalance"
        },
        {
          label: "Reference",
          field: "reference"
        },

        {
          label: "to/from",
          field: "secondPartyName"
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
     */
    rowStyleClass(row) {
      return row.operation; // to refer and style the corresponding row
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
      console.log(params[0].type);

      this.updateParams({
        sort: {
          type: params[0].type,
          field: params[0].field
        }
      });
      this.loadItems();
    },

    /**
     * getting all component data and submitting it
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
          console.log(response.data.data);
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

.transactionFrom {
  background-color: #a5d6a7;
  color: black;
}

.transactionFrom:hover {
  background-color: #4caf50;
  color: white;
}

.transactionTo {
  background-color: #f48fb1;
  color: black;
}

.transactionTo:hover {
  background-color: #e91e63;
}
</style>
