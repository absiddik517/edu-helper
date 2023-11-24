<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Edit Event
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
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
              <Input type="text" v-model="form.event" field="event" label="Event" :form="form"/>
              <div class="form-group">
                <label for="detail">Addition Data</label>
                <Ckeditor v-model="form.detail" />
              </div>
              <div class="form-group">
                <label for="event_image">Event Image</label>
                <input @change="handelOnChnage" type="file" name="" id="">
                <img :src="previewImageUrl" alt="Preview" v-if="previewImageUrl" style="max-width: 100%; margin-top: 12px;">
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
  Select,
  Card,
  DeleteConfirm,
  Spinner,
  Dropdown,
  Pagination,
  Ckeditor,
  Filterth,
  Button,
  Content,
  useValidateForm,
} from "@/Components";
import toast from "@/Store/toast.js";
import _ from "lodash";
import { Inertia } from "@inertiajs/inertia";
import { reactive, ref } from "vue";
import { isRequired } from "intus/rules";
import {useForm} from "@inertiajs/inertia-vue3";
import {months} from "@/Store/static.js";

export default {
  name: "Create",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    DeleteConfirm,
    Ckeditor,
    Card,
    Input,
    Select,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    catagories: Object,
    event: Object,
    url: String,Object,
  },
  data() {
    return {
      form: useForm({
        year: this.event.year ?? null,
        month: this.event.month ?? null,
        day: this.event.day ?? null,
        catagory_id: this.event.catagory_id ?? null,
        event: this.event.event ?? null,
        detail: this.event.detail ?? null,
        file: null,
        url: this.url
      }),
      previewImageUrl: null,
      months,
    };
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
    
    handelOnChnage(e) {
      this.form.file = e.target.files[0]
      if (this.form.file) {
        const reader = new FileReader();

        reader.onload = (e) => {
          this.previewImageUrl = e.target.result;
        };

        reader.readAsDataURL(this.form.file);
      }
    }
  },
};
</script>
