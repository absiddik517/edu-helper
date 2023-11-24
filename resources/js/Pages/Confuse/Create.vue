<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Create Confusion
          </template>
          <template #title_right>
            <Link
              :href="route('confuse.index')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Go back
            </Link>
          </template>
          <div class="px-4 py-2">
            <form @submit.prevent="submit">
              <Select type="select" :error="form.errors.catagory_id" v-model="form.catagory_id" :options="catagories" field="catagory_id" label-text="Catagory" :form="form"/>
              <Input type="text" @input="populateOtherName"  v-model="form.name" field="name" label="Name" :form="form"/>
              <h3>Confusions data</h3>
              <div>
                <div class="row confuse" v-for="(conf, index) in form.conf">
                  <div class="col-6">
                    <Input type="text" v-model="conf.name" field="name" label="Name" :form="form"/>
                  </div>
                  <div class="col-6">
                    <Input type="text" autocomplete="confuses.catagory" v-model="conf.catagory" field="catagory" label="Catagory" :form="form" optional/>
                  </div>
                  <div class="col-12">
                    <Input type="text" autocomplete="confuses.detail" v-model="conf.detail" field="detail" label="Detail" :form="form"/>
                  </div>
                  <div class="col-12 mt-2">
                    <Button classes="btn-block" varient="danger" type="button" @click="deleteField(index)"><i class="fa fa-trash"></i></Button>
                  </div>
                </div>
              </div>
              <div class="form-group d-flex justify-content-between">
                <Button type="button" varient="success" @click="addFileds"><i class="fa fa-plus"></i></Button>
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
        catagory_id: null,
        name: null,
        conf: [
          {
            name: null,
            catagory: null,
            detail: null,
          },
          {
            name: null,
            catagory: null,
            detail: null,
          }
        ]
      }),
    };
  },
  methods: {
    submit() {
      this.form.clearErrors();
      var url = route("confuse.store");
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
    addFileds() {
      this.form.conf.push({
        name: this.form.name,
        catagory: '',
        detail: '',
      })
    },
    deleteField(index){
       this.form.conf.splice(index, 1);
    },
    populateOtherName(){
      for(let i = 0; i < this.form.conf.length; i++){
        this.form.conf[i].name = this.form.name.replace('***', '');
      }
    }
  },
};
</script>

<style>
  .confuse {
    border: 1px solid black;
    margin-bottom: 10px;
    padding-top: 6px;
    padding-bottom: 6px;
  }
  .v-align-bottom{
    vertical-align: bottom;
  }
</style>
