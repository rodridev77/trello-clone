<template>
    <div class="board-main" style="display:flex;">
        <div class="board-content" v-for="status in statuses" :key="status.slug">
            <div class="list-wrapper">
                <div class="list-content">
                    <div class="list-header">
                        <h2>{{ status.title }}</h2>
                    </div>

                    <draggable class="flex-1 overflow-hidden"
                        v-model="status.tasks"
                        v-bind="taskDragOptions"
                        @end="handleTaskMoved"
                    >
                        <transition-group class="" tag="div">
                           
                            <div class="list-task card-task" v-for="task in status.tasks" :key="task.id">

                                <edit-task-form 
                                    v-if="editTaskForStatus === task.id"
                                    :task-updated="task"
                                    v-on:task-updated="handleTaskUpdated"
                                    v-on:edit-task-canceled="closeEditTaskForm"
                                />

                                <div class="card-content" v-else>
                                    <span class="iconedit-card" @click="openEditTaskForm(task.id)">
                                        <i class="fas fa-pencil-alt"></i>
                                    </span>
                                    <div class="card-details .clear-fix:after">
                                        <span class="">
                                        {{ task.title }}
                                        </span>
                                        <p class="" v-if="task.description">
                                            {{ task.description }}
                                        </p>
                                    </div>
                                </div>

                            </div>

                        </transition-group>
                    </draggable>

                    <new-task-form 
                    v-if="newTaskForStatus === status.id"
                    :status-id="status.id"
                    v-on:task-added="handleTaskAdded"
                    v-on:task-canceled="closeTaskForm"
                     />
                    
                    <div class="open-card" v-else>
                        <a href="#" class="opnecard-btn" @click="openTaskForm(status.id)">
                            <span class="icon-add">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span class="addcard-text">Add a card</span>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
import draggable from "vuedraggable";
import NewTaskForm from "./NewTaskForm.vue";
import EditTaskForm from "./EditTaskForm.vue";

export default {
    components: { draggable, NewTaskForm, EditTaskForm },
    props: {
        initialData: Array
    },
    data() {
        return {
            statuses: [],
            newTaskForStatus: 0,
            editTaskForStatus: 0,
            task: {
                title: "",
                description: "",
            }
        };
    },
    mounted() {
       
        this.statuses = JSON.parse(JSON.stringify(this.initialData));
         //console.log(this.statuses);
    },
    computed: {
        taskDragOptions() {
            return {
                Animation: 200,
                group: "task-list",
                dragClass: "status-drag"
            };
        }
    },
    methods: {
        openTaskForm(statusId) {
            this.newTaskForStatus = statusId;
        },
        closeTaskForm() {
            this.newTaskForStatus = 0;
        },
        handleTaskAdded(newTask) {
            const statusIndex = this.statuses.findIndex(
                status => status.id === newTask.status_id
            );

            this.statuses[statusIndex].tasks.push(newTask);
            this.closeTaskForm();
        },
        handleTaskMoved() {
            axios.put("/tasks/sync", {columns: this.statuses})
            .catch(error => {console.log(error.response)});
        },
        handleTaskUpdated(updatedTask) {
            //Vue.set(updatedTask, 'updatedTask.title', 'task-updated');
           //this.task.title = updatedTask.title;
            this.closeEditTaskForm();
        },
        openEditTaskForm(taskId) {
            this.editTaskForStatus = taskId;
        },
        closeEditTaskForm() {
            this.editTaskForStatus = 0;
        },
    }
};
</script>

<style scoped>
.board-main {
    font-size: 14px;
    font-weight: 400;
}

.list-wrapper {
    width: 272px; 
    margin: 0 4px; 
    height: 100%; 
    box-sizing: border-box; 
    display: inline-block; 
    vertical-align: top; 
    white-space: nowrap;
}

.list-content {
    background-color: #f3f3f3;
    border-radius: 3px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    max-height: 100%;
    position: relative;
    white-space: normal;
}

.list-header {
    float: 0 0 auto;
    padding: 10px 8px;
    position: relative;
    min-height: 20px;
}
/* dfdfdf */
.card-task {
    width: 100%;
    padding: 0 4px;
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

.iconedit-card {
    color: #42566e;
    height: 20px;
    font-size: 10px;
    line-height: 10px;
    width: 20px;
    background-color: #f4f5f7;
    background-clip: padding-box;
    background-origin: initial;
    border-radius: 3px;
    opacity: .8;
    padding: 4px;
    position: absolute;
    right: 2px;
    top: 2px;
    /* visibility: hidden; */
    z-index: 40;
    border: 1px solid red;
}

/* .iconedit-card > i {
    color: #42566e;
    height: 20px;
    font-size: 16px;
    line-height: 20px;
    width: 20px;
} */

.card-details {
    overflow: hidden;
    padding: 6px 8px 2px;
}

.card-details > .clear-fix:after {
    clear: both;
    content: "";
    display: table;
} /* dfdfdf */

/* .list-content {
    background-color: #ebecf0;
    border-radius: 3px;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    max-height: 100%;
    position: relative;
    white-space: normal;
}

.list-header {
    font-size: 14px;
    color: #172b4d;
    font-weight: 900;
    flex: 0 0 auto;
    padding: 10px 8px;
    position: relative;
    min-height: 20px;
}

.list-task {
    mb-3 p-3 h-24 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer
    flex: 1 1 auto;
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: auto;
    overflow-y: auto;
    overflow-x: hidden;
    margin: 0 4px;
    padding: 0 4px;
} */

.open-card {
    height: 38px;
}

.opnecard-btn {
    border-radius: 3px;
    color: #5e6c84;
    display: block;
    margin: 2px 0 8px 8px;
    padding: 4px 8px;
    text-decoration: none;
}

.opnecard-btn:hover {
    background-color: #d4d4d4;
}

.icon-add {
    color: #6b778c;
    margin-right: 2px;
}

.status-drag {
    transition: transform .5s;
    transition-property: all;
}
</style>