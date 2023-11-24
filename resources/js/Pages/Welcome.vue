<template>
  <Head title="Welcome" />

  <div>
    <div class="container-fluid p-4">
      <div class="col-12">
        <div v-if="canLogin" class="d-flex justify-content-end">
          <div>
            <Link v-if="$page.props.user" :href="route('dashboard')" class="text-muted">
              Dashboard Link
            </Link>

            <template v-else>
              <Link :href="route('login')" class="text-muted">
                Log in
              </Link>

              <Link v-if="canRegister" :href="route('register')" class="ms-4 text-muted">
                Register
              </Link>
            </template>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid my-5 pt-5 px-5">
      <div class="row justify-content-center px-4">
        <div class="col-md-12 col-lg-9">
          <div class="google">Google</div>
          <div>
            <div class="search">
      				<form class="search-form" @submit.prevent="submit">
      					<input v-model="form.query" type="text" placeholder="Search for event, books etc">
      					<button type="submit">
      					  <i class="fa fa-search"></i>
      					</button>
      				</form>
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
    canLogin: Boolean,
    canRegister: Boolean,
  },
  data() {
    return {
      form: useForm({
        query: '',
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
  .google{
    font-size: 6em;
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
