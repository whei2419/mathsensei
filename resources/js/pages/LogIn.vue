<template>
  <div class="main-container">
        <div class="form-container">
            <h1 class="head-Text">Equation sensei 2</h1>
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
            </div>
        </div>
  </div>
</template>

<script>
import { Field, Form, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import baseUrl from '../utils.js'
import axios from 'axios'

export default {
components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        const schema = yup.object({
            email: yup.string().required().email(),
            password: yup.string().required()
        });
        return {
            schema,
        }
    },
    methods: {
        handleSubmit(values) {
            axios.post(`${baseUrl}/api/login`, values).then(res => {
                localStorage.setItem('userDetails',JSON.stringify(res.data.user));
                localStorage.setItem('token', res.data.token);
                this.$router.push('/Home');
            }).catch(err => {
                console.log(err);
            });
        }
    }
}
</script>

<style lang="scss" scoped>
.main-container {
    width: 100%;
    height: 100vh;
    margin: 0;
    background: $pale-cream;

    .head-Text {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 4rem;
    }

    .form-container {
        padding: 30px;
        display: flex;
        flex-direction: column;
        width: 400px;
        margin: 0 auto;

        .form {

            .form-control {
                width: 95%;

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
