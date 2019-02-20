<template>
  <!-- with v-cloak we hide the ugly {{}} on page load and show loading message as refereed bottom in the style deceleration   -->
  <div style="margin-left:50px" v-cloak>
    <mdb-container fluid>
      <mdb-jumbotron>
        <p class="lead">Welcome to dashboard.</p>
        <hr class="my-2">
        <p>Here you can have a global view on your account.</p>
        <mdb-row class="align-items-center justify-content-start">
          <mdb-col style="margin:15px">
            <!-- Current balance card dynamically loaded   -->
            <mdb-card class="text-center md-auto">
              <mdb-card-header :color="accountCondition()">Balance</mdb-card-header>
              <mdb-card-body>
                <mdb-card-title>{{currentBalance || '0'}} $</mdb-card-title>
                <mdb-card-text>Current balance in your account.</mdb-card-text>
              </mdb-card-body>
              <mdb-card-footer :color="accountCondition()">{{currentBalance || 'no records'}}</mdb-card-footer>
            </mdb-card>
          </mdb-col>
          <mdb-col style="margin:15px">
            <!-- Activity card dynamically loaded   -->
            <mdb-card class="text-center">
              <mdb-card-header color="aqua-gradient">Latest activity</mdb-card-header>
              <mdb-card-body>
                <mdb-card-title>{{lastActivity.operation || 'Nothing to show' }}</mdb-card-title>
                <mdb-card-text>{{lastActivity.amount}} $</mdb-card-text>
              </mdb-card-body>
              <mdb-card-footer
                color="aqua-gradient"
              >{{ lastActivity.date | moment("from") || 'no records'}}</mdb-card-footer>
            </mdb-card>
          </mdb-col>
        </mdb-row>
        <mdb-row class="align-items-center justify-content-start">
          <mdb-col style="margin:15px">
            <!-- Account balance history dynamically loaded -->
            <mdb-card class="card-body">
              <mdb-card-title>Account balance</mdb-card-title>
              <mdb-card-body>
                <IEcharts
                  v-if="lastActivity.amount"
                  style="min-width:200px;min-height:200px;"
                  :option="bar"
                  :loading="loading"
                />

                <mdb-card-text>{{ lastActivity.amount ? 'Latest activity on your records.': 'no records'}}</mdb-card-text>
              </mdb-card-body>
            </mdb-card>
          </mdb-col>
          <mdb-col style="margin:15px">
            <!-- Activities comparison  dynamically loaded -->
            <mdb-card class="card-body">
              <mdb-card-title>Activities comparison</mdb-card-title>
              <mdb-card-body>
                <IEcharts
                  v-if="lastActivity.amount"
                  style="min-width:200px;min-height:200px;"
                  :option="option"
                  :loading="loading"
                />

                <mdb-card-text>{{ lastActivity.amount ? 'Latest activity on your records.': 'no records'}}</mdb-card-text>
              </mdb-card-body>
            </mdb-card>
          </mdb-col>
        </mdb-row>
      </mdb-jumbotron>
    </mdb-container>
  </div>
</template>


<script>
import IEcharts from "vue-echarts-v3/src/lite.js";
import "echarts/lib/component/tooltip";
import "echarts/lib/chart/bar";
import "echarts/lib/chart/pie";
import {
  mdbContainer,
  mdbRow,
  mdbCol,
  mdbBtn,
  mdbCard,
  mdbCardTitle,
  mdbCardText,
  mdbCardFooter,
  mdbCardBody,
  mdbCardHeader,
  mdbListGroup,
  mdbListGroupItem,
  mdbNavItem,
  mdbCardGroup,
  mdbJumbotron,
  mdbIcon,
  mdbFooter,
  mdbTab,
  mdbTabItem,
  mdbTabContent,
  mdbTabPane
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
    mdbCardFooter,
    mdbCardBody,
    mdbCardHeader,
    mdbListGroup,
    mdbListGroupItem,
    mdbNavItem,
    mdbCardGroup,
    mdbJumbotron,
    mdbIcon,
    mdbFooter,
    mdbTab,
    mdbTabItem,
    mdbTabContent,
    mdbTabPane,
    IEcharts
  },
  data() {
    return {
      currentBalance: "",
      lastActivity: {
        date: "",
        amount: "",
        option: ""
      },
      loading: false,
      bar: {
        title: {
          text: "ECharts Hello World"
        },
        tooltip: {
          trigger: "item",
          showContent: true
        },
        xAxis: {
          data: []
        },
        yAxis: {},
        series: [
          {
            name: "Sales",
            type: "bar",
            data: []
          }
        ]
      },
      active: 0,
      active2: 0,

      option: {
        title: {
          text: "Customized Pie",
          left: "center",
          top: 20,
          textStyle: {
            color: "#ccc"
          }
        },

        tooltip: {
          trigger: "item",
          showContent: true
        },

        visualMap: {
          show: false,
          min: 200,
          max: 600,
          inRange: {
            colorLightness: [0, 1]
          }
        },
        series: {
          name: "Account activities",
          type: "pie",
          radius: "50%",
          data: [
            { value: 0, name: "Deposit" },
            { value: 0, name: "Withdraw" },
            { value: 0, name: "Tran-to" },
            { value: 0, name: "Tran-from" }
          ],
          roseType: "angle",
          label: {
            normal: {
              textStyle: {
                color: "#000000"
              }
            }
          },
          labelLine: {
            normal: {
              lineStyle: {
                color: "#000000"
              }
            }
          },
          itemStyle: {
            normal: {
              color: "#c23531",
              shadowBlur: 100,
              shadowColor: "rgba(0, 0, 0, 0.5)"
            }
          }
        }
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
    accountCondition() {
      return parseInt(this.currentBalance) > 100
        ? "purple-gradient"
        : parseInt(this.currentBalance) > 0
        ? "peach-gradient"
        : "red";
    },
    /**
     * only one method that do all the work of the dashboard, send a get request to api/auth/dashboard
     * the request is catch on routes/api.php the handed to App/AccountActivity.php
     * were get proceeded by dashboard public method.
     * we retrieve the user from the request authorization header and we send an json response back
     * the response is proceeded and set the data of this component accordingly.
     */
    loadItems() {
      // variable delations
      var totalDeposit = 0,
        totalWithdraw = 0,
        totalTransactionFrom = 0,
        totalTransactionTo = 0;
      // get request sending
      this.$http
        .get("/auth/dashboard")
        .then(response => {
          // receiving a promise
          response.data.map(data => {
            // map throw the response

            // get the operation name and count the total sum of every operation for the pie chart
            switch (data.operation) {
              case "deposit":
                totalDeposit += parseInt(data.amount); // add the sum to the function variable totalDeposit
                break;

              case "withdraw":
                totalWithdraw += parseInt(data.amount); // add the sum to the function variable withdraw
                break;

              case "TransactionFrom":
                totalTransactionFrom += parseInt(data.amount); // add the sum to the function variable transactionFrom
                break;

              case "TransactionTo":
                totalTransactionTo += parseInt(data.amount); // add the sum to the function variable transactionTo
                break;

              default:
                break;
            }

            //  adding to the data array of the component for the bar chart date and balance
            //  formating the date to be a valid javascript object
            let t = data.created_at.split(/[- :]/);
            let date = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
            this.bar.xAxis.data.push(this.formatDate(date));

            this.bar.series[0].data.push(data.currentBalance);
          });

          // Set the cards information
          // format the date object in order to display ** minutes ago
          let t = response.data[0].created_at.split(/[- :]/);
          this.lastActivity.date = new Date(t[0],t[1]-1,t[2],t[3],t[4],t[5]);
          this.currentBalance = response.data[0].currentBalance;
          this.lastActivity.amount = response.data[0].amount;
          this.lastActivity.operation = response.data[0].operation;

          this.option.series.data[0].value = parseInt(totalDeposit);
          this.option.series.data[1].value = parseInt(totalWithdraw);
          this.option.series.data[2].value = parseInt(totalTransactionTo);
          this.option.series.data[3].value = parseInt(totalTransactionFrom);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    /** 
     * formating the date for the bar chart
    */
    formatDate(date) {
      var monthNames = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
      ];

      var day = date.getDate();
      var monthIndex = date.getMonth();

      return day + " " + monthNames[monthIndex];
    }
    /**
     * on page load all the function
     * Yes i know with web socket we will see the changes on the go
     */
  },
  mounted() {
    this.loadItems();
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



