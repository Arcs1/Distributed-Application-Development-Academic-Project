<script>
import { inject } from '@vue/runtime-core';
import { useUserStore } from '../stores/user';
export default {
    data: function () {
        return {
            toast: inject('toast'),
            userStore: useUserStore(),
            axios: inject('axios'),
            oldPassword: '',
            newPassword: '',
            confirmNewPassword: '',
        }
    },
    methods: {
        async updatePassword() {
            try {
                if (this.oldPassword == '' || this.newPassword == '' || this.confirmNewPassword == '') {
                    this.$toast.error("Please fill all the fields!")
                }
                else {
                    let passwords = new FormData
                    passwords.append('oldPassword', this.oldPassword)
                    passwords.append('newPassword', this.newPassword)
                    passwords.append('confirmNewPassword', this.confirmNewPassword)
                    const data = await this.axios.post('users/' + this.userStore.userId + '/updatePassword', passwords)
                    console.log(data)
                    if (data.data[0] == 600) {
                        this.$toast.error(data.data[1])
                    } else
                        if (data.data[0] == 601) {
                            this.$toast.error(data.data[1])
                        } else {
                            if (data.data[0] == 602) {
                                this.$toast.error(data.data[1])
                            } else {
                                if (data.data[0] == 200) {
                                    this.$toast.success(data.data[1])
                                    this.$router.push({ name: 'home' })

                                }
                            }
                        }
                }
            } catch (error) {
                this.$toast.error("Error changing password!")
            }


        }
    }
}
</script>

<template>
    <div class="col-md-6">
        <label class="form-label">Old Password</label>
        <input type="password" class="form-control" placeholder="" v-model="oldPassword">
    </div>
    <div class="col-md-6">
        <label class="form-label">New Password</label>
        <input type="password" class="form-control" placeholder="" v-model="newPassword">
    </div>
    <div class="col-md-6">
        <label class="form-label">Confirm New Password</label>
        <input type="password" class="form-control" placeholder="" v-model="confirmNewPassword">
    </div>
    <button type="button" class="btn btn-primary" @click="updatePassword">Update Password</button>
</template>

<style>
.content {
    padding-left: 25px;
    padding-top: 75px;
    opacity: 1;
}

input[type] {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    border: 2px solid black;

}

.form-label {
    font-weight: bold;
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
</style>
