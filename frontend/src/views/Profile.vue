<script>
import { inject } from '@vue/runtime-core';
import { useUserStore } from '../stores/user';
export default {
    data: function () {
        return {
            toast: inject('toast'),
            isEditing: false,
            userStore: useUserStore(),
            baseURL: inject("serverBaseUrl"),
            previewImage: null,
            image: null,
            axios: inject('axios'),
            orders: [],
            counterDelivered: 0,
            counterCancelled: 0,
            mostExpensive: null,
            totalPrices: [],
            favoriteItems: []
        }
    },
    methods: {
        async loadFavoriteItem() {
            try {
                let response = await this.axios.get('order_items/customers/' + this.userStore.customer.id + '{id}/mostFrequent')
                this.favoriteItems = response.data
            } catch (error) {

            }

        },
        editProfile() {
            this.isEditing = true;
            this.$toast.success('Edit profile Enabled')
        },
        async updateProfile() {
            let userInfo = new FormData
            let customerInfo = new FormData
            userInfo.append('name', this.userStore.user.name)
            userInfo.append('email', this.userStore.user.email)
            userInfo.append('photo', this.image)
            userInfo.append('type', this.userStore.user.type)

            if (this.userStore.user.type == 'C') {
                customerInfo.append('phone', this.userStore.customer.phone)
                customerInfo.append('nif', this.userStore.customer.nif)
                customerInfo.append('default_payment_type', this.userStore.customer.default_payment_type)
                customerInfo.append('default_payment_reference', this.userStore.customer.default_payment_reference)
            }

            var config = {
                headers: {
                    "Content-type":
                        "multipart/form-data; charset=utf-8; boundary=" +
                        Math.random()
                            .toString()
                            .substr(2),
                    processData: true,
                    Accept: "application/json"
                }
            };

            try {
                const dataUser = await this.axios.post('users/' + this.userStore.user.id + '/update', userInfo, config)

                if (this.userStore.user.type == 'C') {
                    const dataCustomer = await this.axios.post('customers/' + this.userStore.user.id + '/update', customerInfo)
                }
                this.isEditing = false;
                this.userStore.loadUser()
                this.$toast.success('Profile has been edited successfuly')
                this.$toast.error('Edit profile Disabled')


            } catch (error) {
                this.$toast.error('Error editing profile')
                this.$toast.error('Edit profile Disabled')
            }
        },
        uploadImage(e) {
            this.image = e.target.files[0];
            let reader = new FileReader();
            reader.readAsDataURL(this.image)
            reader.onload = e => {
                this.previewImage = e.target.result;
            }
        },
        async removePhoto() {
            try {
                const data = await this.axios.delete('users/' + this.userStore.userId + '/photo')
                this.userStore.loadUser()
                this.$toast.success('Photo deleted Successfuly')
            } catch (error) {
                this.$toast.error('There was an error deleting the photo')
            }
        },
        async loadCustomerOrders() {
            try {
                let response = await this.axios.get('orders/customers/' + this.userStore.customer.id)
                this.orders = response.data

                this.orders.forEach(order => {
                    this.totalPrices.push(order.total_price)
                    if (order.status == 'D') {
                        this.counterDelivered++
                    } else if (order.status == 'C') {
                        this.counterCancelled++
                    }
                });
                this.mostExpensive = Math.max(...this.totalPrices)

            } catch (error) {

            }
        }
    },
    mounted() {
        this.loadCustomerOrders()
        this.loadFavoriteItem()
    }
}
</script>


<template>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Page title -->
                    <div class="my-5">
                        <h3>{{ userStore.user.name }}'s Profile</h3>
                        <hr>
                    </div>
                    <!-- Form START -->
                    <form class="file-upload">
                        <div class="row mb-5 gx-5">

                            <!-- Upload profile -->
                            <div class="col-xxl-4">
                                <div class="bg-secondary-soft px-4 py-5 rounded">
                                    <div class="row g-3" id="profileItem">
                                        <div class="text-center">
                                            <!-- Image upload -->
                                            <div class="square position-relative display-2 mb-3">
                                                <i v-if="previewImage"
                                                    class="fas fa-fw fa-user position-absolute top-50 start-50 translate-middle text-secondary"><img
                                                        id="photo" :src="previewImage"></i>
                                                <i v-else
                                                    class="fas fa-fw fa-user position-absolute top-50 start-50 translate-middle text-secondary"><img
                                                        id="photo" :src="userStore.userPhotoUrl"></i>
                                            </div>
                                            <!-- Button -->

                                            <input type="file" id="customFile" name="file" hidden=""
                                                @change="uploadImage">
                                            <label v-show="this.isEditing == true"
                                                class="btn btn-success-soft btn-block" for="customFile">Upload</label>
                                            <button v-show="this.isEditing == true" type="button"
                                                class="btn btn-danger-soft" @click="removePhoto">Remove</button>
                                            <!-- Content -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- User detail -->
                            <div class="col-xxl-8 mb-5 mb-xxl-0">
                                <div class="bg-secondary-soft px-4 py-5 rounded">
                                    <div class="row g-3" id="profileItem">
                                        <h4 class="mb-4 mt-0">User Details</h4>
                                        <!-- Name -->
                                        <div class="col-md-6">
                                            <label class="form-label">Name</label>

                                            <input type="text" class="form-control" placeholder=""
                                                v-model="userStore.user.name" :readonly="this.isEditing == false">
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <label class="form-label">Email</label>

                                            <input type="email" class="form-control" placeholder=""
                                                v-model="userStore.user.email" :readonly="this.isEditing == false">
                                        </div>
                                        <div v-if="userStore.user.type == 'C'">
                                            <!-- Mobile number -->
                                            <div class="col-md-6">
                                                <label class="form-label">Mobile number</label>

                                                <input type="text" class="form-control" placeholder=""
                                                    v-model="userStore.customer.phone"
                                                    :readonly="this.isEditing == false">
                                            </div>
                                            <!-- NIF -->
                                            <h4 class="my-4">Payment Information</h4>
                                            <div class="col-md-6">
                                                <label for="inputEmail4" class="form-label">NIF</label>

                                                <input type="text" class="form-control" v-model="userStore.customer.nif"
                                                    :readonly="this.isEditing == false">
                                            </div>
                                            <!-- PAYMENT TYPE -->
                                            <div class="col-md-6">
                                                <label class="form-label">Default Payment Type</label>
                                                <select v-model="userStore.customer.default_payment_type"
                                                    :disabled="this.isEditing == false">
                                                    <option value=null>Select a payment Type</option>
                                                    <option value="VISA">VISA</option>
                                                    <option value="PAYPAL">PAYPAL</option>
                                                    <option value="MBWAY">MBWAY</option>
                                                </select>

                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Default Payment Reference</label>

                                                <input type="text" class="form-control" placeholder=""
                                                    v-model="userStore.customer.default_payment_reference"
                                                    :readonly="this.isEditing == false">

                                            </div>

                                        </div>
                                    </div> <!-- Row END -->
                                </div>
                            </div>
                            <div class="col-xxl-6" v-if="userStore.user.type == 'C'">
                                <div class="bg-secondary-soft px-4 py-5 rounded">
                                    <div class="row g-3" id="profileItem">
                                        <h4 class="my-4">Points Information</h4>
                                        <div class="col-md-6">
                                            <label for="exampleInputPassword1" class="form-label">Points</label>
                                            <br>
                                            <input type="text" class="form-control" id="exampleInputPassword1" readonly
                                                :value="userStore.customer.points">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6" v-if="userStore.user.type == 'C'">
                                <div class="bg-secondary-soft px-4 py-5 rounded">
                                    <h4 class="my-4">Purchase History</h4>
                                    <div class="tableWrapper" id="profileItem">

                                        <div class="tableScroll" v-show="orders.length != 0">
                                            <table>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Status</th>
                                                    <th>Total Price</th>
                                                    <th>Points Gained</th>
                                                    <th>Points Used To Pay</th>
                                                    <th>Payment Type</th>
                                                    <th>Payment Reference</th>
                                                    <th>Date</th>
                                                </tr>
                                                <tr v-for="order in orders" :key="order">
                                                    <td>{{ order.id }}</td>
                                                    <td>{{ order.status }}</td>
                                                    <td>{{ order.total_price }}€</td>
                                                    <td>{{ order.points_gained }}</td>
                                                    <td>{{ order.points_used_to_pay }}</td>
                                                    <td>{{ order.payment_type }}</td>
                                                    <td>{{ order.payment_reference }}</td>
                                                    <td>{{ order.date }}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                    <h4 class="my-4">Orders Information</h4>
                                    <div>
                                        <label>Total Orders Delivered: <label class="lblDelivered">{{ counterDelivered
                                        }}</label></label>
                                        <br>
                                        <label>Total Orders Cancelled: <label class="lblCancelled">{{ counterCancelled
                                        }}</label></label>
                                        <br>
                                        <label>Most Expensive Purchase: <label class="lblNormal"
                                                v-show="this.mostExpensive != '-Infinity'">{{ mostExpensive
                                                }}€</label></label>
                                        <br>
                                        <br>
                                        <div class="favoriteItems">
                                            <label>Favorite Items: <p v-for="favorite in favoriteItems" :key="favorite"
                                                    class="lblNormal">{{ favorite[0].name }}</p></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- Row END -->

                        <!-- button -->
                        <button v-show="this.isEditing == false" type="button" class="btn btn-success"
                            @click="editProfile">Edit profile</button>
                        <div v-show="this.isEditing == true" class="gap-3 d-md-flex justify-content-md-end text-center">
                            <!-- <button type="button" class="btn btn-danger">Delete profile</button> -->
                            <button type="button" class="btn btn-primary" @click="updateProfile">Update profile</button>
                        </div>
                    </form> <!-- Form END -->
                </div>
            </div>
        </div>
    </div>
</template>



<style scoped>
.favoriteItems {
    border: 2px solid black;
}

.lblNormal {
    font-weight: bold;
}

.lblCancelled {
    font-weight: bold;
    color: red
}

.lblDelivered {
    font-weight: bold;
    color: green
}

.tableWrapper {
    position: relative;
}

.tableScroll {
    height: 300px;
    overflow: auto;
    margin-top: 20px;
}

.tableWrapper table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
    overflow: auto;
}

.tableWrapper td,
th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

.tableWrapper tr:nth-child(even) {
    background-color: #dddddd;
}

.content {
    background-color: lightslategrey;
    width: 1450px;
    border-radius: 2%;
    opacity: 95%;



}



option:first-child {
    display: none;
}



input[type],
select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    border: 2px solid black;

}

.form-label {
    font-weight: bold;
}

#photo {
    width: 252px;
    height: 252px;
}

.btn {
    display: inline-block;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-color: transparent;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
    color: white;
    background-color: #0d6efd;
    border-color: #0d6efd;
    float: right;
    margin-bottom: 10px;
}

.btn-success {
    color: white;
    background-color: green;
    border-color: green;
    float: right;
    margin-bottom: 10px;
}

.btn-danger {
    color: white;
    background-color: red;
    border-color: red;
}

#profileItem {
    display: inline-block !important;
}

div.row {
    display: flex;
}


.container {
    width: 100%;
    height: 100%;
}


.rounded {
    border-radius: 5px !important;
}

.py-5 {
    padding-top: 3rem !important;
    padding-bottom: 3rem !important;
}

.px-4 {
    padding-right: 1.5rem !important;
    padding-left: 1.5rem !important;
}

.file-upload .square {
    height: 250px;
    width: 250px;
    margin: auto;
    vertical-align: middle;
    border: 1px solid #e5dfe4;
    background-color: #fff;
    border-radius: 5px;
}

.text-secondary {
    --bs-text-opacity: 1;

}

.btn-success-soft {
    color: #28a745;
    background-color: rgba(40, 167, 69, 0.1);
}

.btn-danger-soft {
    color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
}

.form-control {
    width: 100%;
    padding: 0.5rem 1rem;
    font-size: 0.9375rem;
    font-weight: 400;
    line-height: 1.6;
    color: #29292e;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #e5dfe4;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 5px;
    -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
}
</style>