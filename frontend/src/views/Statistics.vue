<template>
    <div class="wrapper">
        <div class="innerDiv">
            <h2>Número de orders por mês do ano {{ this.year }}</h2>
            <div class="btn-group">
                <button @click="setYear(2020)">2020</button>
                <button @click="setYear(2021)">2021</button>
                <button @click="setYear(2022)">2022</button>
            </div>
            <canvas v-show="this.year == 2022" id="myChart"></canvas>
            <canvas v-show="this.year == 2021" id="myChart1"></canvas>
            <canvas v-show="this.year == 2020" id="myChart2"></canvas>
        </div>
        <div class="innerDiv">
            <h2>Faturação de orders por mês do ano {{ this.yearPrice }}</h2>
            <div class="btn-group">
                <button @click="setYearPrice(2020)">2020</button>
                <button @click="setYearPrice(2021)">2021</button>
                <button @click="setYearPrice(2022)">2022</button>
            </div>
            <canvas v-show="this.yearPrice == 2022" id="myChart3"></canvas>
            <canvas v-show="this.yearPrice == 2021" id="myChart4"></canvas>
            <canvas v-show="this.yearPrice == 2020" id="myChart5"></canvas>
        </div>

    </div>
    <div class="indivStats">
        <div class="addStats" v-show="isLoaded">
            <h3>Best Customer</h3>
            <p>{{ this.bestCustomerArray[0] }} - {{ this.bestCustomerArray[1] }}€</p>
            <h3>Most Popular Items</h3>
            <p v-for="item in mostRequestedItemsArray" :key="item">{{ item[0].name }}</p>
            <h3>Total Sales</h3>
            <p>{{ this.formatPrice(this.totalSales) }}€</p>
            <h3>Total Delivered Orders</h3>
            <p>{{ this.totalOrders }}</p>
            <h3>Total Cancelled Orders</h3>
            <p>{{ this.totalCancelledOrders }}</p>
        </div>
        <div class="innerDiv">
            <h2>Clientes novos no Ano {{ this.yearUser }}</h2>
            <div class="btn-group">
                <button @click="setYearUser(2020)">2020</button>
                <button @click="setYearUser(2021)">2021</button>
                <button @click="setYearUser(2022)">2022</button>
            </div>
            <canvas v-show="this.yearUser == 2022" id="myChart6"></canvas>
            <canvas v-show="this.yearUser == 2021" id="myChart7"></canvas>
            <canvas v-show="this.yearUser == 2020" id="myChart8"></canvas>
        </div>
    </div>

</template>

<script >
import { inject } from '@vue/runtime-core';
import { Chart } from 'chart.js/auto';
export default {
    data() {
        return {
            axios: inject('axios'),
            year: 2022,
            yearPrice: 2022,
            yearUser: 2022,
            bestCustomerArray: [],
            mostRequestedItemsArray: [],
            totalSales: [],
            baseURL: inject("serverBaseUrl"),
            totalOrders: [],
            totalCancelledOrders: [],

            //orders mês
            myChart: null,
            myChart1: null,
            myChart2: null,

            //€ mês
            myChart3: null,
            myChart4: null,
            myChart5: null,


            //users mês
            myChart6: null,
            myChart7: null,
            myChart8: null,

            isLoaded: false

        }
    },
    methods: {
        formatPrice(value) {
            let val = (value / 1).toFixed(2).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        async loadTotalCancelledOrders() {
            let response = await this.axios.get('totalCancelledOrders')
            this.totalCancelledOrders = response.data[0]
        },
        async loadTotalSales() {
            let response = await this.axios.get('totalSales')
            this.totalSales = response.data[0]
        },
        async loadTotalOrders() {
            let response = await this.axios.get('totalOrders')
            this.totalOrders = response.data[0]
        },
        async mostRequestedItems() {
            let response = await this.axios.get('mostRequestedItems')
            this.mostRequestedItemsArray = response.data
            this.isLoaded = true;

        },
        async bestCustomer() {
            let response = await this.axios.get('bestCustomer')
            this.bestCustomerArray.push(response.data[0].name)
            this.bestCustomerArray.push(response.data[1])
        },
        setYearUser($yearUser) {
            this.yearUser = $yearUser
            this.usersCreatedMes()
        }
        ,
        setYearPrice($yearPrice) {
            this.yearPrice = $yearPrice
            this.ordersSalesMes()
        },
        setYear($year) {
            this.year = $year
            this.ordersMes()
        },
        async usersCreatedMes() {
            let dadosUsersPorMes = await this.axios.get('statistics/usersPerMonth/' + this.yearUser)

            if (this.yearUser == 2022) {
                const ctx = document.getElementById('myChart6');
                if (this.myChart6 == null) {
                    this.myChart6 = new Chart(ctx, {

                        type: 'polarArea',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# Customers',
                                data: dadosUsersPorMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart6.destroy()
                    this.myChart6 = new Chart(ctx, {

                        type: 'polarArea',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# Customers',
                                data: dadosUsersPorMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }


            } else if (this.yearUser == 2021) {
                const ctx = document.getElementById('myChart7');
                if (this.myChart7 == null) {
                    this.myChart7 = new Chart(ctx, {

                        type: 'polarArea',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# Customers',
                                data: dadosUsersPorMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart7.destroy()
                    this.myChart7 = new Chart(ctx, {

                        type: 'polarArea',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# Customers',
                                data: dadosUsersPorMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            } else {
                const ctx = document.getElementById('myChart8');
                if (this.myChart8 == null) {
                    this.myChart8 = new Chart(ctx, {

                        type: 'polarArea',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# Customers',
                                data: dadosUsersPorMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart8.destroy()
                    this.myChart8 = new Chart(ctx, {

                        type: 'polarArea',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# Customers',
                                data: dadosUsersPorMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
        }
        ,
        async ordersSalesMes() {

            let dadosSalesOrdersMes = await this.axios.get('statistics/salesPerMonth/' + this.yearPrice)

            if (this.yearPrice == 2022) {
                const ctx = document.getElementById('myChart3');
                if (this.myChart3 == null) {
                    this.myChart3 = new Chart(ctx, {

                        type: 'line',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Order Sales (€)',
                                data: dadosSalesOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart3.destroy()
                    this.myChart3 = new Chart(ctx, {

                        type: 'line',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Order Sales (€)',
                                data: dadosSalesOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }


            } else if (this.yearPrice == 2021) {
                const ctx = document.getElementById('myChart4');
                if (this.myChart4 == null) {
                    this.myChart4 = new Chart(ctx, {

                        type: 'line',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Order Sales (€)',
                                data: dadosSalesOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart4.destroy()
                    this.myChart4 = new Chart(ctx, {

                        type: 'line',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Order Sales (€)',
                                data: dadosSalesOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            } else {
                const ctx = document.getElementById('myChart5');
                if (this.myChart5 == null) {
                    this.myChart5 = new Chart(ctx, {

                        type: 'line',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Order Sales (€)',
                                data: dadosSalesOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart5.destroy()
                    this.myChart5 = new Chart(ctx, {

                        type: 'line',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: 'Order Sales (€)',
                                data: dadosSalesOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }


        }
        ,
        async ordersMes() {

            let dadosOrdersMes = await this.axios.get('statistics/ordersPerMonth/' + this.year)

            if (this.year == 2022) {
                const ctx = document.getElementById('myChart');
                if (this.myChart == null) {
                    this.myChart = new Chart(ctx, {

                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of orders',
                                data: dadosOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart.destroy()
                    this.myChart = new Chart(ctx, {

                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of orders',
                                data: dadosOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }


            } else if (this.year == 2021) {
                const ctx = document.getElementById('myChart1');
                if (this.myChart1 == null) {
                    this.myChart1 = new Chart(ctx, {

                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of orders',
                                data: dadosOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart1.destroy()
                    this.myChart1 = new Chart(ctx, {

                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of orders',
                                data: dadosOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            } else {
                const ctx = document.getElementById('myChart2');
                if (this.myChart2 == null) {
                    this.myChart2 = new Chart(ctx, {

                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of orders',
                                data: dadosOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                } else {
                    this.myChart2.destroy()
                    this.myChart2 = new Chart(ctx, {

                        type: 'bar',
                        data: {
                            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            datasets: [{
                                label: '# of orders',
                                data: dadosOrdersMes.data,
                                backgroundColor: [
                                    'rgba(255,99,142,0.2)',
                                    'rgba(54,162,235,0.2)',
                                    'rgba(255,206,86,0.2)',
                                    'rgba(75,192,192,0.2)',
                                    'rgba(153,102,255,0.2)',
                                    'rgba(255,159,64,0.2)',
                                ],
                                borderColor: [
                                    'rgba(255,99,142,1)',
                                    'rgba(54,162,235,1)',
                                    'rgba(255,206,86,1)',
                                    'rgba(75,192,192,1)',
                                    'rgba(153,102,255,1)',
                                    'rgba(255,159,64,1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }

        }


    },

    mounted() {
        this.ordersMes()
        this.ordersSalesMes()
        this.bestCustomer()
        this.mostRequestedItems()
        this.loadTotalSales()
        this.loadTotalOrders()
        this.loadTotalCancelledOrders()
        this.usersCreatedMes()
    }
}
</script>

<style scoped>
.indivStats {
    padding-left: 40px;
    display: flex;
}

.addStats {
    background-color: white;
    width: 400px;
    padding: 10px;

}

.wrapper {
    display: flex;
    padding: 20px;
}

.innerDiv {
    width: 50%;
    float: left;
    padding: 20px;
}

.btn-group button {
    background-color: #0d6efd;
    border: white;
    color: white;
    padding: 10px 24px;
    margin-bottom: 10px;
    cursor: pointer;
    float: left;
    border-radius: 2px;
}

.btn-group button:not(:last-child) {
    border-right: none;
}


.btn-group:after {
    content: "";
    clear: both;
    display: table;
}

.btn-group button:hover {
    background-color: rgb(59, 88, 253);
}



#myChart,
#myChart1,
#myChart2,
#myChart3,
#myChart4,
#myChart5,
#myChart6,
#myChart7,
#myChart8 {
    width: 500px;
    height: 100px;
    opacity: 100%;
    background-color: white;
}
</style>