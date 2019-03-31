<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">
                            Users List  
                        </h3>
                      

                           <div class="card-tools">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addNew">
                                <i class="fas fa-user-plus"></i> Add User
                                </button>  
                  
                </div>

                    </div>

                    <div class="card-body  table-responsive p-0">
                         <table class="table table-hover">
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Registered At</th>
                    <th>Modify</th>
                  </tr>
                  
                 
                  <tr v-for="user in users" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | mydate}}</td>
                    <td>
                <a href="#" class="btn-xs btn-edit btn btn-warning">
                    <i class="fas fa-edit">  </i>       </a> 
                    
                         <a href="#" @click="deleteUser(user.id)" class="btn-xs btn-edit btn btn-danger">
                    <i class="fas fa-trash">  </i>       </a>           </td>
                  </tr>
                </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add user</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
   <form @submit.prevent="createUser"> 
         <div class="modal-body">
    
    <div class="form-group">
    
      <input v-model="form.name" type="text" name="name" placeholder="Name"
        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
      <has-error :form="form" field="name"></has-error>
    </div>

    <div class="form-group">
     
      <input v-model="form.email" type="email" name="email" placeholder="Email Address"
        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>
    </div>

    <div class="form-group">
     
      <input v-model="form.password" type="password" name="password" placeholder="Password"
        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
      <has-error :form="form" field="password"></has-error>
    </div>
 <div class="form-group">
     <textarea name="bio" v-model="form.bio" id="bio"  rows="3" placeholder="Short bio for user (Optional)"
       class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
     <has-error :form="form" field="bio"></has-error>
 </div>

 <div class="form-group">
     <select name="type" id="type" v-model="form.type" 
     class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
     <option value="">Select User type</option>
     <option value="admin">Administrator</option>
         <option value="user">User</option>
            <option value="member">Member</option>
     </select><has-error :form="form" field="type"></has-error>
 </div>
 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Create</button>
      </div>

      </form>
    </div>
  </div>
</div>
    </div>

    
</template>

<script>
    export default {

        data(){
            return {
              users: {},
               form: new Form({
                   name: '',
                   email: '',
                   password: '',
                   type: '',
                   bio: '',
                   photo: ''
               })
            }
        },
        methods: {
          loadUsers(){
            axios.get("api/user").then(({ data }) => (this.users = data.data));
          },

            createUser(){
               this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                  Fire.$emit('AfterCreated');
                                  $('#addNew').modal('hide');
                                  Toast.fire({
                                      type: 'success',
                                      title: 'User Created successfully'
                                    });
                                    this.$Progress.finish();
                }).catch(() => {

                });
               
            },

            deleteUser(id){
              Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  //send message to the server
                  this.form.delete('api/user' +id).then(() => {
                      
                    Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success' 
                    )
  
                  })
                  .catch(()=>{
                    Swal('Something went wrong');
                  })
                 
})
            }
        },
        created() {
          this.loadUsers();
          Fire.$on('AfterCreated', () => {
            this.loadUsers();
          })
          //setInterval(()=>this.loadUsers(), 3000);
            
        }
    }
</script>
