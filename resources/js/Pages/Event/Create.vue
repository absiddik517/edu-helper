<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Create Event
          </template>
          <template #title_right>
            <Link
              :href="route('calender.event.index')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Go back
            </Link>
          </template>
          <div class="px-4 py-2">
            <form @submit.prevent="submit">
              <Select type="select" :error="form.errors.catagory_id" v-model="form.catagory_id" :options="catagories" field="catagory_id" label-text="Catagory" :form="form" optional/>
              <div class="row">
                <Input type="number" v-model="form.year" @change="" field="year" label="Year" :form="form" group-class="col-3"/>
                <Select type="select" v-model="form.month" :options="months" field="month" label-text="Month" :form="form" group-class="col-6" optional/>
                <Input type="number" v-model="form.day" @change="" field="day" label="Day" :form="form" group-class="col-3" optional/>
              </div>
              <Input type="text" v-model="form.event" autocomplete="events.event" field="event" label="Event" :form="form"/>
              <div class="form-group">
                <label for="detail">Addition Data</label>
                <Ckeditor v-model="form.detail"/>
                <!--
                <textarea v-model="form.detail" class="form-control" id="" cols="30" rows="5"></textarea>
                -->
              </div>
              <div class="form-group">
                <label for="event_image">Event Image</label>
                <input @change="handelOnChnage" type="file" name="" id="">
                <img :src="previewImageUrl" alt="Preview" v-if="previewImageUrl" style="max-width: 100%; margin-top: 12px;">
              </div>
              <div>
                <pre>{{form.detail}}</pre>
              </div>
              <div class="form-group text-right">
                <Button :is-loading="form.processing" hide-label><i class="fa fa-save"></i></Button>
              </div>
            </form>
          </div>
        </Card>
      </div>
    </div>
  </Content>
</template>

<script>
import {
  AdminLayout,
  Modal,
  Input,
  Ckeditor,
  Select,
  Card,
  DeleteConfirm,
  Spinner,
  Dropdown,
  Pagination,
  Filterth,
  Button,
  Content,
  useValidateForm,
} from "@/Components";
import toast from "@/Store/toast.js";
import {months} from "@/Store/static.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
  name: "Create",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    DeleteConfirm,
    Card,
    Ckeditor,
    Input,
    Select,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    catagories: Object,
  },
  data() {
    return {
      
      form: useForm({
        year: null,
        month: null,
        day: null,
        catagory_id: null,
        event: null,
        detail: null,
        image: null
      }),
      previewImageUrl: null,
      months: months
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      var url = route("calender.event.store");
      this.form.post(url, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (res) => {
          if(res.props.toast.type == 'success') {
            this.form.reset();
          }
        },
      });
    },
    
    handelOnChnage(e) {
      console.log(e.target.files[0])
      this.form.image = e.target.files[0]
      if (this.form.image) {
        const reader = new FileReader();

        reader.onload = (e) => {
          this.previewImageUrl = e.target.result;
        };

        reader.readAsDataURL(this.form.image);
      }
    }
  },
};
</script>
