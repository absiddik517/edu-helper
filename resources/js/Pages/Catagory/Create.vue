<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Create Catagory
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link
              :href="route('calender.catagory.index')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Go back
            </Link>
          </template>
          <div class="px-4 py-2">
            <form @submit.prevent="submit">
              <Select type="select" :error="form.errors.type" v-model="form.type" :options="types" field="type" label-text="Catagory Type" :form="form"/>
              <Input type="text" v-model="form.name" @change="" field="name" label="Catagory Name" :form="form"/>
              
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
import {types} from "@/Store/static.js";

export default {
  name: "Create",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    DeleteConfirm,
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
  },
  data() {
    return {
      form: useForm({
        name: null,
        type: null,
      }),
      types,
        
      
    };
  },
  methods: {
    
    submit() {
      this.form.clearErrors();
      var url = route("calender.catagory.store");
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
    
    
  },
};
</script>
