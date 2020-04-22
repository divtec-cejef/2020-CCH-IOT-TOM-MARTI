<template>
    <div>
        <button @click.prevent="deleteValue" type="submit" class="delete">Delete</button>
        <p>or</p>
        <form @submit.prevent="updateValue">
            <legend>Update</legend>
            <label for="temp">Température</label>
            <input type="number" v-model="temp" id="temp">
            <label for="hum">Humidité</label>
            <input type="number" v-model="hum" id="hum">
            <button type="submit" class="update">Update</button>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    export default {
        name: "Edit",
        data () {
            return {
                id: this.$route.params.id,
                temp: this.$route.params.temp,
                hum: this.$route.params.hum
            }
        },
        methods: {
            deleteValue () {
                if(confirm("Êtes-vous sur de vouloir supprimer ces valeurs ?")) {
                    axios.delete('https://temp.martitom.ch/api/measures/' + this.id).then(response =>  {
                        console.log(response.data);
                    });
                    this.$router.push('/');
                }
            },
            updateValue () {
                if(confirm("Voulez vous vraiment mettre à jour ces valeurs ?")) {
                    axios.put('https://temp.martitom.ch/api/measures/' + this.id, "temperature=" + this.temp + "&humidity=" + this.hum).then(response =>  {
                        console.log(response.data);
                    });
                    this.$router.push('/');
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    div {
        max-width: 300px;
        margin:auto;
        button {
            width: 100%;
            font-family: 'Concert One', sans-serif;
            font-size: 20px;
            padding: 10px;
            max-width: 200px;
            border-radius: 5px;
            border: none;
            background-color: #42b983;
        }
        button:hover {
            background-color: rgba(66, 185, 131, 0.67);
        }
        button.delete {
            background-color: red;
            margin-top: 30px;
        }
        button.delete:hover {
            background-color: rgba(255, 0, 0, 0.67);
        }
        form {
            max-width: 200px;
            margin: auto;
            label {
                padding-top: 20px;
                display: block;
                font-family: Roboto, sans-serif;
                color: #42b983;
                text-align: left;
                font-size: 16px;
            }
            input {
                background-color: transparent;
                border: none;
                margin-bottom: 20px;
                font-family: Roboto, sans-serif;
                font-size: 20px;
            }
            legend {
                font-family: Roboto, sans-serif;
            }
        }
        p {
            font-family: Roboto, sans-serif;
        }
    }
</style>