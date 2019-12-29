<template>
  <div class="main-content">
    <div class="card bg-light">
      <div class="card-body mx-auto" style="max-width: 400px;">
        <h4 class="card-title mt-3 text-center">Create Account</h4>
        <form @submit.prevent="submitForm" enctype="multipart/form-data">
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"> <i class="fa fa-user"></i> </span>
            </div>
            <input v-model="userData.name" class="form-control" placeholder="Full name" type="text">
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
            </div>
            <input v-model="userData.email" class="form-control" placeholder="Email address" type="email">
          </div>
          <div class="form-group input-group">
            <vue-tel-input @input="acceptPhoneNumber" v-model="userData.phone_number"></vue-tel-input>
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"> <i class="fa fa-building"></i> </span>
            </div>
            <input v-model="userData.address" class="form-control" placeholder="Address" type="text">
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"> <i class="fa fa-building"></i> </span>
            </div>
            <input v-model="userData.zip_code" class="form-control" placeholder="Zip Code" type="text">
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
            </div>
            <input v-model="userData.password" class="form-control" placeholder="Create password" type="password">
          </div>
          <div class="form-group input-group">
            <div class="input-group-prepend">
              <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
            </div>
            <input v-model="userData.password2" class="form-control" placeholder="Repeat password" type="password">
          </div>
          <div class="form-group input-group">
            <div class="custom-file">
              <input type="file" @change.prevent="onFileChange($event, 'photo')" class="custom-file-input text-center" id="photo">
              <label class="custom-file-label" for="photo">Upload profile photo</label>
            </div>
          </div>
          <div class="form-group input-group">
            <div class="custom-file">
              <input type="file" @change.prevent="onFileChange($event, 'file')" class="custom-file-input text-center" id="customFile">
              <label class="custom-file-label" for="customFile">Upload license file</label>
            </div>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block"> Create Account  </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
  import { required, minLength, maxLength, email, sameAs } from 'vuelidate/lib/validators';
  import axios from 'axios';

export default {
  name: 'WebForm',
  data() {
    return {
      userData: {
        name: '',
        email: '',
        phone_number: '',
        address: '',
        zip_code: '',
        photo: {},
        file: {},
        password: '',
        password2: ''
      },
    }
  },
  validations: {
    userData: {
      name: {
        required,
      },
      email: {
        required,
        email: email()
      },
      phone_number: {
        required,
        minLength: minLength(11),
        maxLength: maxLength(14)
      },
      zip_code: {
        minLength: minLength(5),
        maxLength: maxLength(5)
      },
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(16)
      },
      password2: {
        sameAsPassword: sameAs('password')
      }
    }
  },
  methods: {
    submitForm() {
      let vm = this;

      if(vm.$v.userData.name.$invalid) {
        // eslint-disable-next-line no-undef
        toastr.error('Please input username');
      } else if(vm.$v.userData.password.$invalid) {
        // eslint-disable-next-line no-undef
        toastr.error('Please input valid password from 8 to 16 characters');
      } else if(vm.$v.userData.password2.$invalid) {
        // eslint-disable-next-line no-undef
        toastr.error('Passwords are not matching');
      } else if(vm.$v.userData.phone_number.$invalid) {
        // eslint-disable-next-line no-undef
        toastr.error('Please enter a valid phone number');
      } else if(vm.$v.userData.zip_code.$invalid) {
        // eslint-disable-next-line no-undef
        toastr.error('Please input valid 5 digits zip code');
      } else if (vm.$v.userData.email.$invalid) {
        // eslint-disable-next-line no-undef
        toastr.error('Please enter valid email address');
      } else {
        axios.post('http://localhost:8000/api/users', vm.userData).then(res => {
          if(res.status === 200) {
            // eslint-disable-next-line no-undef
            toastr.success(res.msg);
          }
        }).catch(error => {
          // eslint-disable-next-line no-undef
          toastr.error(error.msg);
        });
      }
    },
    onFileChange(e, type) {
      let vm = this;
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length) {
        return;
      }
      vm.createFile(files[0], type);
    },
    acceptPhoneNumber() {
      let vm = this;
      var x = vm.userData.phone_number.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,5})/);
      vm.userData.phone_number = !x[2] ? x[1] : '(' + x[1] + ')' + x[2] + (x[3] ? '-' + x[3] : '');
    },
    createFile(file, type) {
      let vm = this;
      let reader = new FileReader();
      reader.onload = e => {
        if(type === 'photo') {
          vm.userData.photo = e.target.result;
        } else {
          vm.userData.file = e.target.result;
        }
      };
      reader.readAsDataURL(file);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
.custom-file-label::after {
  content: none !important;
}
.custom-file-label::before {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 3;
  display: block;
  height: calc(1.5em + .75rem);
  padding: .375rem .75rem;
  line-height: 1.5;
  color: #495057;
  content: "Browse";
  background-color: #e9ecef;
  border-radius: 0 .25rem .25rem 0;
}
.vue-tel-input {
  width: 100%;
}
</style>
