<template>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header text-white" style="background: url('./img/photo1.png') center center;">
                <h3 class="widget-user-username">Elizabeth Pierce</h3>
                <h5 class="widget-user-desc">Web Designer</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
              </div>
              <div class="card-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>

            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active show" href="#activity" data-toggle="tab">Activity</a></li>
                  
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active show" id="activity">
              
                            Display User Activity
                  </div>
                 

                  <div class="tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-12">
                          <input type="text" v-model="form.name" class="form-control"  :class="{ 'is-invalid': form.errors.has('name') }" id="inputName" placeholder="Name">
                           <has-error :form="form" field="name"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                        <div class="col-sm-12">
                          <input type="email" v-model="form.email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }" id="inputEmail" placeholder="Email">
                             <has-error :form="form" field="email"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputName2" class="col-sm-6 control-label">Password</label>

                        <div class="col-sm-12">
                          <input type="password" v-model="form.password" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }" id="password" placeholder="Password">
                           <has-error :form="form" field="password"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-6 control-label">Bio</label>

                        <div class="col-sm-12">
                          <textarea v-model="form.bio" class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }" id="inputExperience" placeholder="Experience"></textarea>
                             <has-error :form="form" field="bio"></has-error>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputSkills" class="col-sm-6 control-label">Passport Photograph</label>

                        <div class="col-sm-12">
                          <input type="file" name="photo" @change="updateProfile" class="form-input"  id="photo" >
                        </div>
                      </div>

     <div class="form-group">

             <label for="inputSkills" class="col-sm-6 control-label">User type</label>

                      <div class="col-12">                  
                                    <select name="type" id="type" v-model="form.type" 
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                    <option value="">Select User type</option>
                                    <option value="admin">Administrator</option>
                                        <option value="user">User</option>
                                            <option value="member">Member</option>
                                    </select><has-error :form="form" field="type"></has-error>
                                </div>
                      </div>
                   
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" @click.prevent="updateInfo" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            </div>
        </div>
    </div>
</template>

<style>
.widget-user-header{
    height: 300px;
}
</style>


<script>
    export default {
        data() {
            return {
              form: new Form({
                   id: '',
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
      getProfilePhoto(){
        let photo = (this.form.photo.length > 200 ) ? this.form.photo :"img/profile/"+ this.form.photo ;
         
           return photo;
      },
          updateInfo() {
            this.$Progress.start();
            this.form.put('api/profile/')
            .then(() => {

              this.$Progress.finish();
            })
            .catch(() => {
              
              this.$Progress.fail();
            });
          },
          updateProfile(e){
            let file = e.target.files[0];
            console.log(file)
              let reader = new FileReader();

              if(file['size'] < 2111775){
                 reader.onloadend = (file) => {
                  //console.log('RESULT', reader.result)
                  this.form.photo = reader.result;
                }
                reader.readAsDataURL(file);
              } else {
                Swal.fire({
                  type: 'error',
                  title: 'Oops..',
                  text: 'The Picture is too big',
                });
              }
               
                        }
        },

        created() {
            axios.get("api/profile").then(({ data }) => (this.form.fill(data)) );
        }
    }
</script>
