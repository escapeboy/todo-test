<template>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Lists


                </div>
                <div class="panel-body">
                    <form action="" method="post" role="form" v-on:submit.prevent="createList">
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="new_list_title"
                                   v-model="new_list_title" value="" placeholder="Create new list">
                        </div>
                    </form>
                    <ul class="list-group" v-if="lists.length">
                        <li class="list-group-item"
                            v-for="list in lists"
                            v-bind:class="{'active': selected_list && list.id===selected_list.id}"
                            @click="select_list(list)"
                        >{{list.title}}



                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-danger btn-xs" @click="deleteList(list)"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                            <p class="list-group-item-text"><span v-if="list.owner"><i
                                    class="fa fa-user"></i> {{list.owner.name}}</span> <i class="fa fa-calendar"></i>
                                {{moment(list.created_at).format('d.M.Y')}}</p>
                        </li>
                    </ul>
                    <button type="button" class="btn btn-primary btn-xs pull-left" v-if="lists_prev_page"
                            @click="get_lists(lists_prev_page)">Prev page

                    </button>
                    <button type="button" class="btn btn-primary btn-xs pull-right" v-if="lists_next_page"
                            @click="get_lists(lists_next_page)">Next page

                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Tasks


                </div>
                <div class="panel-body">
                    <form action="" method="post" role="form" v-on:submit.prevent="createTask"
                          v-if="selected_list!==null">
                        <div class="form-group">
                            <input type="text" class="form-control" name="title" id="new_task_title"
                                   v-model="new_task_title" value="" placeholder="Create new task">
                        </div>
                    </form>
                    <ul class="list-group" v-if="tasks.length">
                        <li class="list-group-item"
                            v-for="task in tasks"
                            v-bind:style="{'text-decoration': task.finished_on ? 'line-through' : 'none', 'opacity': task.finished_on ? '0.5' : '1'}"
                        >
                            <div class="checkbox pull-left">
                                <label>
                                    <input type="checkbox" value="1" name="" v-model="task.finished_on"
                                           @click="toggleTask(task)">
                                </label>
                            </div>
                            {{task.title}}

                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-danger btn-xs" @click="deleteTask(task)"><i
                                        class="fa fa-trash"></i></button>
                            </div>
                            <p class="list-group-item-text"><span v-if="task.owner"><i
                                    class="fa fa-user"></i> {{task.owner.name}}</span> <i class="fa fa-calendar"></i>
                                {{moment(task.created_at).format('d.M.Y')}}</p>
                            <p class="list-group-item-text" v-if="task.description">{{task.description}}</p>
                        </li>

                    </ul>
                    <button type="button" class="btn btn-primary btn-xs pull-left" v-if="tasks_prev_page"
                            @click="get_tasks(tasks_prev_page)">Prev page

                    </button>
                    <button type="button" class="btn btn-primary btn-xs pull-right" v-if="tasks_next_page"
                            @click="get_tasks(tasks_next_page)">Next page

                    </button>

                    <div class="alert alert-info" v-if="selected_list!==null && !tasks.length">
                        <p>No tasks found for this list</p>
                    </div>
                    <div class="alert alert-info" v-if="selected_list===null">
                        <p>Please select a list</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    var moment = require('moment');
    moment.locale('bg');
    export default {
        data() {
            return {
                lists: [],
                tasks: [],
                selected_list: null,
                selected_task: null,
                lists_prev_page: null,
                lists_next_page: null,
                tasks_prev_page: null,
                tasks_next_page: null,
                new_list_title: null,
                new_task_title: null,
            }
        },
        created() {
            this.get_lists();
        },
        filters: {
            calendar: function (date) {
                return moment(date).calendar();
            }
        },
        methods: {
            moment: function () {
                return moment();
            },
            get_lists(url = '/lists') {
                axios.get(url)
                    .then(({data}) => {
                        this.lists = data.items.data ? data.items.data : [];
                        this.lists_prev_page = data.items.next_page_url;
                        this.lists_next_page = data.items.prev_page_url;
                    })
            },
            select_list(list) {
                this.selected_list = list;
                this.get_tasks();
            },
            get_tasks(url = undefined){
                if (url === undefined) {
                    url = '/lists/' + this.selected_list.id + '/tasks';
                }
                axios.get(url)
                    .then(({data}) => {
                        this.tasks = data.items.data ? data.items.data : [];
                        this.tasks_prev_page = data.items.next_page_url;
                        this.tasks_next_page = data.items.prev_page_url;
                    })
            },
            createList(){
                axios.post('/lists', {
                    'title': this.new_list_title
                }).then(({data}) => {
                    this.new_list_title = null;
                    this.get_lists();
                });
            },
            createTask(){
                axios.post('/lists/' + this.selected_list.id + '/tasks', {
                    'title': this.new_task_title
                }).then(({data}) => {
                    this.new_task_title = null;
                    this.get_tasks();
                });
            },

            deleteList(list)
            {
                if (confirm('Are you sure?')) {
                    axios.get('/lists/' + list.id + '/delete')
                        .then(({data}) => {
                            this.lists.splice(this.lists.indexOf(list), 1);
                            this.selected_list = null;
                            this.tasks = [];
                        });
                }
            },

            deleteTask(task)
            {
                if (confirm('Are you sure?')) {
                    axios.get('/tasks/' + task.id + '/delete')
                        .then(({data}) => {
                            this.tasks.splice(this.tasks.indexOf(task), 1);
                        });
                }
            },

            toggleTask(task)
            {
                let status = task.finished_on ? 'in_progress' : 'finished';
                axios.get('/tasks/' + task.id + '/mark/' + status)
                    .then(({data}) => {
                        if (status === 'finished') {
                            task.finished_on = moment();
                        } else {
                            task.finished_on = null;
                        }
                    });
            }

        }
    }
</script>