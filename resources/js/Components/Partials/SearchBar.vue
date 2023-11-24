<template>
  <div class="navbar-search-block">
      <form class="form-inline" @submit.prevent="submit">
          <div class="input-group input-group-sm">
              <input ref="input" v-model="form.search" class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                      <i class="fas fa-search"></i>
                  </button>
                  <button ref="btnClose" class="btn btn-navbar" type="button" data-widget="navbar-search">
                      <i class="fas fa-times"></i>
                  </button>
              </div>
          </div>
      </form>
      <div class="result_container">
        <div class="result">
          <div><pre>{{result}}</pre></div>
        </div>
        <div v-for="result in results" class="result">
          <div class="head text-muted">{{ result.catagory }}</div>
          <div class="title">{{ result.title }}</div>
          <div class="description">
          
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
export default {
  data(){
    return {
      form: useForm({
        search: null
      }),
      results: null,
      error: null,
    }
  },
  mounted(){
    this.$refs.btnClose.addEventListener('click', this.handleCloseClick)
  },
  beforeUnmount() {
    this.$refs.btnClose.removeEventListener('click', this.handleCloseClick)
  },
  methods: {
    async submit(){
      if(!this.form.search) return;
      await axios.get(this.route('search', {search: this.form.search}))
      .then(response => {
        this.results = response.data
      })
      .catch(err => {
        this.error = err
      });
    },
    handleCloseClick() {
      this.result = null
      this.form.search = null
    },
    
    submits() {
      var url = route("search");
      this.form.get(url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
          this.results = res;
          if(res.props.toast.type == 'success') {
            this.form.reset();
          }
        },
      });
    },
  }
}
</script>

<style scoped>
  .result_container {
    background: rgba(255,255, 255, 0.5);
    position:absolute; 
    top: 100%; 
    left:0; 
    width: 100%;
    padding: 12px;
  }
  
  .result {
    background: rgba(255,255,255, 1);
    color: #000;
    -webkit-box-shadow: 0px 0px 19px -8px rgba(66, 68, 90, 1);
    -moz-box-shadow: 0px 0px 19px -8px rgba(66, 68, 90, 1);
    box-shadow: 0px 0px 19px -8px rgba(66, 68, 90, 1);
    border-radius: 6px;
    border: 1px solid #fff;
    padding: 6px;
    margin-bottom: 20px;
  }
  .result:last-child {
    margin-bottom: 0px;
  }
  
  .title {
    color: #0082f0;
    font-size: 1.2em;
    font-weight: bold;
    padding-top: 5px;
    padding-bottom: 5px;
  }
  
  .description {
    color: #404040;
  }
  
  
</style>