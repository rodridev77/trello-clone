<template>
    <div class="container">
        <form class="card-task" @submit.prevent="addNewTask">
            <div class="card-content">
                <div class="text-title">
                    <input type="text" class="card-title" v-model.trim="newTask.title" placeholder="Enter a title for this card...">
                </div>
                <div class="card-details clear-fix">
                    <textarea class="card-textarea" v-model.trim="newTask.description" placeholder="Add a description for this card (optional)"></textarea>
                </div>
                <div v-show="errorMessage">
                    <span class="error-msg alert alert-danger">
                        {{ errorMessage }}
                    </span>
                </div>
            </div>
            <div class="card-btns">
                <input class="taskbtn-submit" type="submit" value="Add card">
                <a href="#" class="icon-close" @click="$emit('task-canceled')">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        props: {
            statusId: Number
        },
        data() {
            return {
                newTask: {
                    title: "",
                    description: "",
                    status_id: null
                },
                errorMessage: ""
            };
        },
        mounted() {
            this.newTask.status_id = this.statusId;
        },
        methods: {
            addNewTask() {
                if (!this.newTask.title) {
                    this.errorMessage = "The title field is required";
                    return;
                }

                axios.post("/tasks", this.newTask)
                .then(response => {
                    this.$emit("task-added", response.data);
                })
                .catch(error => {
                    this.handleErrors(error);
                });
            },
            handleErrors(error) {
                if (error.response && error.response.status === 422) {
                    const errorList = error.response.data.errors;
                    if (errorList.title) {
                        this.errorMessage = errorList.title[0];
                    } else if (errorList.description) {
                        this.errorMessage = errorList.description[0];
                    } else {
                        this.errorMessage = error.response.message;
                    }
                } else {
                    console.log(error.response);
                }
            }
        }
    };
</script>

<style scoped>
.card-task {
    width: 100%;
    border: 1px solid rgb(163, 148, 148)(158, 150, 150);
    padding-bottom: 8px;
}

.card-content {
    background-color: #fff;
    border-radius: 3px;
    box-shadow: 0 1px 0 rgba(9, 30, 66, .25);
    cursor: pointer;
    display: block;
    margin-bottom: 8px;
    max-width: 300px;
    min-height: 20px;
    position: relative;
    text-decoration: none;  
}

.card-details {
    overflow: hidden;
    padding: 6px 8px 2px;
}

.card-details > .clear-fix:after {
    clear: both;
    content: "";
    display: table;
}

.card-textarea {
    overflow: hidden;
    overflow-wrap: break-word;
    resize: none;
    height: 54px;
}

.card-textarea, .card-textarea:focus  {
    background: none;
    border: none;
    box-shadow: none;
    height: auto;
    margin-bottom: 4px;
    max-height: 162px;
    min-height: 54px;
    overflow-y: auto;
    padding: 0;
}

textarea, input {
    width: 100%;
    border: none;
    outline: none;
    box-sizing: border-box;
    border-radius: 3px;
    display: block;
    line-height: 20px;
    transition-property: background-color, border-color, box-shadow;
    transition-duration: 85ms;
    transition-timing-function: ease;
}

.card-btns {
    width: 100%;
    display: flex;
    justify-content: space-between;
}

.taskbtn-submit {
    background-color: #0079bf;
    box-shadow: none;
    border: none;
    color: #fff;
    margin-right: 10px;
}

.icon-close {
    color: #42526e;
    height: 32px;
    line-height: 32px;
    width: 32px;
    font-size: 24px;
}
</style>