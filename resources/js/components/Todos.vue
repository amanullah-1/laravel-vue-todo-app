<template>
  <section class="content">
    <div class="container-fluid">
        <div class="row">

          <div class="col-12">
        
            <div class="card" >
              <div class="card-header">
                <h3 class="card-title">Todo List</h3>

                <div class="card-tools">
                  
                  <button type="button" class="btn btn-sm btn-primary" @click="newModal">
                      <i class="fa fa-plus-square"></i>
                      Add New
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Task</th>
                      <th>Is complete?</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                     <tr v-for="todo in todos" :key="todo.id" :class="todo.is_complete == 1 ? 'table-success' : ''">
                      <td>{{todo.id}}</td>
                      <td class="text-capitalize">{{todo.body}}</td>
                      <td>{{todo.is_complete == 1 ? 'Yes' : 'No'}}</td>
                      <td>
                        <a href="#" title="complete" @click="changeTodoComplete(todo.id)" v-if="todo.is_complete === 0">
                            <i class="fa fa-check-circle green"></i>&nbsp;&nbsp;/
                        </a>
                        <a  v-else>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </a>
                        
                        <a href="#" title="edit" @click="editModal(todo)">
                            &nbsp;&nbsp;<i class="fa fa-edit blue"></i>&nbsp;&nbsp;
                        </a>
                        /
                        <a href="#" title="delete" @click="deleteTodo(todo.id)">
                            &nbsp;&nbsp;<i class="fa fa-trash red"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                  <pagination :data="todos" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNew" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-show="!editmode">Create New Todo</h5>
                    <h5 class="modal-title" v-show="editmode">Update Todo Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form @submit.prevent="editmode ? updateTodo() : createTodo()">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Body</label>
                            <input v-model="form.body" type="text" name="body"
                                class="form-control" :class="{ 'is-invalid': form.errors.has('body') }">
                            <has-error :form="form" field="body"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
  </section>
</template>

<script>
    export default {
        data () {
            return {
                editmode: false,
                todos : {},
                form: new Form({
                    id : '',
                    body : '',
                    is_complete: '',
                    user_id: '',
                })
            }
        },
        methods: {

            getResults(page = 1) {

                  this.$Progress.start();
                  
                  axios.get('api/todos?page=' + page).then(({ data }) => (this.todos = data.todos));

                  this.$Progress.finish();
            },
            updateTodo(){
                this.$Progress.start();
                this.form.put('api/todos/'+this.form.id)
                .then((response) => {
                    // success
                    $('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();

                    this.loadTodos();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            changeTodoComplete(todo_id){
                this.$Progress.start();
                // console.log('Editing data');
                axios.patch('api/todos/'+todo_id+'/complete')
                .then((response) => {
                    // success
                    //$('#addNew').modal('hide');
                    Toast.fire({
                      icon: 'success',
                      title: response.data.message
                    });
                    this.$Progress.finish();

                    this.loadTodos();
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
            editModal(todo){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(todo);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteTodo(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {

                        // Send request to the server
                         if (result.value) {
                                this.form.delete('api/todos/'+id).then(()=>{
                                        Swal.fire(
                                        'Deleted!',
                                        'Your todo data has been deleted.',
                                        'success'
                                        );
                                    this.loadTodos();
                                }).catch((data)=> {
                                  Swal.fire("Failed!", data.message, "warning");
                              });
                         }
                    })
            },
          loadTodos(){
            this.$Progress.start();

            axios.get("api/todos").then(({ data }) => (this.todos = data.data.data));

            this.$Progress.finish();
          },
          
          createTodo(){

              this.form.post('api/todos')
              .then((response)=>{
                  $('#addNew').modal('hide');

                  Toast.fire({
                        icon: 'success',
                        title: response.data.message
                  });

                  this.$Progress.finish();
                  this.loadTodos();

              })
              .catch(()=>{

                  Toast.fire({
                      icon: 'error',
                      title: 'Some error occured! Please try again'
                  });
              })
          }

        },
        mounted() {
            console.log('Todos Component mounted.')
        },
        created() {

            this.$Progress.start();
            this.loadTodos();
            this.$Progress.finish();
        }
    }
</script>
