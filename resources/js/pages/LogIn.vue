<template>
    <div class="main-container">
        <particles></particles>
        <div class="form-container">
            <div class="logo-container">
                <img src="../../assets/logo.svg" alt="logo">
            </div>
            <div class="form">
                <Form @submit="handleSubmit" :validation-schema="schema">
                    <div class="form-control">
                        <label for="email">Email</label>
                        <Field name="email" type="email" />
                        <ErrorMessage class="error-message" name="email" />
                    </div>
                    <div class="form-control">
                        <label for="password">Password</label>
                        <Field name="password" type="password" />
                        <ErrorMessage class="error-message" name="password" />
                    </div>
                    <button class="button primary button-full font-size-small">
                        Login
                    </button>
                </Form>
                <div class="register-container">
                    <p>Dont have an account <a href="">Register</a></p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Field, Form, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import config from '../utils.js'
import axios from 'axios'
import particles from "../layouts/Particle.vue"

export default {
    components: {
        Form,
        Field,
        ErrorMessage,
        particles
    },
    data() {
        const schema = yup.object({
            email: yup.string().required().email(),
            password: yup.string().required()
        });
        return {
            schema,
            token:''
        }
    },
    methods: {
        handleSubmit(values) {
            console.log(config.baseUrl);
            axios({
                method: "post",
                url: `${config.baseUrl}/api/login`,
                data: {
                   email:values.email,
                   password:values.password
                },
            }).then((res) => {
                localStorage.setItem('userDetails', JSON.stringify(res.data.user));
                localStorage.setItem('token', res.data.token);
                this.$router.push('/Home');
            });
        }
    },
    created(){
        this.token = config.token;
    }
}
</script>

<style lang="scss" scoped>
.main-container {
    box-sizing: border-box;
    width: 100%;
    height: 100vh;
    margin: 0;
    background: $pale-cream;
    padding: 40px 30px 30px 30px;

    .register-container{
        text-align: center;
        margin-top: 20px;
        box-sizing: border-box;
    }

    .logo-container{
        display: flex;
        justify-content: center;
        align-items: center;
        img{
           width: 80%;
        }
        margin-bottom: 10px;
        box-sizing: border-box;
    }

    .head-Text {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 4rem;
    }

    .form-container {
        padding: 30px;
        display: flex;
        flex-direction: column;
        margin: 0 auto;
        box-sizing: border-box;
        max-width: 500px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        border: solid 3px $light-red;
        border-radius: 10px;
        .form {
            box-sizing: border-box;
            .form-control {
                width: 95%;
                box-sizing: border-box;

                label {
                    color: $light-red;
                    margin-bottom: 10px;
                    display: block;
                }

                .error-message {
                    color: $red;
                    padding-bottom: 10px;
                    display: block;
                }

                input {
                    display: block;
                    width: 100%;
                    height: 40px;
                    background: $white;
                    border: none;
                    outline: 3px $light-red solid;
                    border-radius: 10px;
                    padding: 0 10px;
                    font-family: $font-family-sans-serif;
                    font-size: 1rem;
                    text-align: start;
                    margin-bottom: 10px;
                }


            }

            button {
                margin-top: 40px;
            }
        }

    }
}
</style>
