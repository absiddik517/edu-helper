<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray" body-class="p-0">
          <template #title>
            Catagory
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link
              :href="route('calender.catagory.create')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Create
            </Link>
          </template>
          <div class="row p-2 gy-2">
            <div class="col-6">
              <input
                v-model="filter.search"
                type="text"
                placeholder="Search..."
                class="form-control"
              />
              <i
                v-show="filter.search"
                @click="filter.search = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
            </div>
            <div class="col-6 d-flex justify-content-end align-items-center">
              Show
              <select
                v-model="filter.per_page"
                class="ml-2 select_per_page"
                id="per_page"
              >
                <option disabled value="null">🔻</option>
                <option v-for="index in 100" :value="index * 5">
                  {{ index * 5 }}
                </option>
                <option value="all">All</option>
              </select>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-hover y-middle">
            <div v-show="loadingTable" class="overlays">
              <span>Loading... <i class="fa fa-spin fa-spinner"></i></span>
            </div>
            <thead>
              <tr>
                <th>#</th>
                <th>Type</th>
                <th>Name</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(catagory, index) in catagories">
                <td>{{ index + 1 }}</td>
                <td>{{ catagory.type }}</td>
                <td>{{ catagory.name }}</td>
                <td class="text-right">
                  <Link class="mr-2 btn btn-info btn-sm" :href="route('calender.catagory.edit', catagory.id)"><i class="fa fa-edit"></i></Link>
                  <Button class="btn-sm" @click="deleteCatagory(catagory.id)" varient="danger"><i class="fa fa-trash"></i></Button>
                </td>
              </tr>
            </tbody>
          </table>
          </div>
        </Card>
      </div>
    </div>
  </Content>
  
  <DeleteConfirm :deleteUrl="deleteUrl" item="catagory" />
</template>

<script>
import {
  AdminLayout,
  Modal,
  Input,
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

export default {
  name: "Permission",
  layout: AdminLayout,
  components: {
    Spinner,
    Modal,
    Content,
    DeleteConfirm,
    Card,
    Input,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    catagories: Object,
    params: Object,
  },
  data() {
    return {
      filter: reactive({
        search: null,
        per_page: 5,
      }),
      modal: {
        confirm: null
      },
      loadingTable: false,
      deleteUrl: null,
    };
  },
  mounted() {
    let confirm = document.querySelector("#confirmModel");
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  methods: {
    deleteCatagory(id) {
      this.deleteUrl = this.route('calender.catagory.delete', id);
      this.modal.confirm.show();
      Inertia.on("finish", () => {
        this.deleteUrl = null;
        this.modal.confirm.hide();
      });
    },
  },
};
</script>
