<template>
  <Head title="Welcome" />

  <div>
    <div class="container-fluid px-5">
      <div class="row justify-content-center">
        <div class="col-md-12 col-lg-9">
          <div class="google">Google</div>
          <div>
            <div class="search">
      				<form class="search-form" @submit.prevent="submit">
      					<input v-model="form.query" type="text" placeholder="Search for books, authors, categories and more..">
      					<button type="submit">
      					  <i class="fa fa-search"></i>
      					</button>
      				</form>
      			</div>
          </div>
          
          <div class="mt-3">
            <!-- result -->
            <div class="result" v-for="result in events">
              <div class="link">http://localhost.com/demo</div>
              <div class="title">{{ result.title }}</div>
              <div class="detail">{{ result.detail }}</div>
            </div>
            
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>

<script>
import { defineComponent } from "vue"
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

export default defineComponent({
  components: {
    Head,
    Link,
  },
  props: {
    query: String,
    events: Object,
  },
  data() {
    return {
      form: useForm({
        query: this.query ?? '',
      })
    }
  },
  methods: {
    submit() {
      this.form.clearErrors();
      this.form.post('', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
          if(res.props.toast.type == 'success') {
            this.form.reset();
          }
        },
      });
    },
  }
  
})
</script>

<style>
  .result{
    background: #fff;
    border-radius: 6px;
    box-shadow: rgba(0, 0, 0, 0.3) 0px 1px 4px;
    padding: 10px;
    margin-bottom: 10px;
  }
  
  .link{
    color: #666;
  }
  .title {
    font-weight: bold;
    color: rgb(12,136,255);
    font-size: 1.5em;
  }
  
  .google{
    font-size: 2em;
    color: #654;
    text-align: center;
  }
  
  
  
  /* ===========================
   ====== Search Box ====== 
   =========================== */

.search
{
	border: 2px solid #CF5C3F;
	overflow: auto;
	border-radius: 50px;
	-moz-border-radius: 50px;
	-webkit-border-radius: 50px;
}

.search input[type="text"]
{
	border: 0px;
	width: 86%;
	padding: 10px 10px;
}

.search input[type="text"]:focus
{
	outline: 0;
}

.search button[type="submit"]
{
	border: 0px;
	color: #CF5C3F;
	background: transparent;
	float: right;
	padding: 10px;
	border-radius-top-right: 5px;
	-moz-border-radius-top-right: 5px;
	-webkit-border-radius-top-right: 5px;
	border-radius-bottom-right: 5px;
	-moz-border-radius-bottom-right: 5px;
	-webkit-border-radius-bottom-right: 5px;
  cursor:pointer;
}

/* ===========================
   ====== Medua Query for Search Box ====== 
   =========================== */

@media only screen and (min-width : 150px) and (max-width : 780px)
{
	{}
	.search
	{
		width: 95%;
		margin: 0 auto;
	}

}
</style>
