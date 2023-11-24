<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray">
          <template #title>
            Confusion
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <Link
              :href="route('confuse.create')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Create
            </Link>
          </template>
          <div class="row gy-2 mb-2">
            <div class="col-6">
              <Select type="select" placeholder="Catagory" without-label v-model="filter.catagory_id" :options="catagories" field="catagory_id" label-text="Catagory" :form="filter" optional/>
            </div>
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
          </div>
          <div>
            <div class="conf_card" v-for="(confusion, index) in confusions.data">
              <div class="conf_header d-flex">
                <span class="flex-1" style="font-size:1.2em;" v-text="confusion.name"></span>
                <div>
                  <Link :href="route('confuse.edit', confusion.id)" class="btn_link">Edit</Link> . 
                  <button @click="deleteConfusion(confusion.id)" class="btn_link">Delete</button> 
                </div>
              </div>
              <div class="conf_body">
                <div v-for="confuse in confusion.confuses" class="d-flex align-items-center">
                  <div class="mr-1 py-1" style="width: 50%"><span v-html="confuse.name"></span> <span v-show="confuse.catagory">({{ confuse.catagory }})</span></div>
                  <div v-html="confuse.detail" class="ml-1 py-1 text-right" style="width: 50%"></div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-if="confusions.data.length" class="p-2">
            <Pagination @traped="loadingTable = true" :items="confusions" :on-page-load="onPageLoad" />
          </div>
        </Card>
      </div>
    </div>
    <DeleteConfirm :deleteUrl="deleteUrl" item="confusion"/>
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
import {months} from "@/Store/static.js";

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
    Select,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    confusions: Object,
    params: Object,
    catagories: Object,
  },
  data() {
    return {
      filter: reactive({
        search: this.params.search ?? '',
        year: this.params.year ?? '',
        month: this.params.month ?? '',
        order: this.params.order ?? '',
        day: this.params.day ?? '',
        per_page: this.params.per_page ?? 5,
        catagory_id: this.params.catagory_id ?? null,
      }),
      loadingTable: false,
      page_url: '',
      deleteUrl: null,
      modal: {},
    };
  },
  methods: {
    onPageLoad() {
      this.page_url = window.location.href;
    },
    getconfusions(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("confuse.index"), filter, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
        onSuccess: () => {
          this.loadingTable = false;
          this.page_url = window.location.href
        },
        onError: (error) => {
          this.loadingTable = false;
          let message = "";
          for (let key in error) {
            message += error[key] + " ";
          }
          toast.add({
            type: "error",
            message,
          });
        },
      });
    },
    deleteConfusion(id) {
      this.deleteUrl = this.route('confuse.delete', id);
      this.modal.confirm.show();
      Inertia.on("finish", () => {
        this.deleteUrl = null;
        this.modal.confirm.hide();
      });
    },
    
  },
  mounted() {
    let confirm = document.querySelector("#confirmModel");
    this.modal.confirm = new bootstrap.Modal(confirm);
  },
  watch: {
    filter: {
      handler: _.debounce(function (state, old) {
        let query = {};
        if (state.search) query.search = state.search;
        if (state.year) query.year = state.year;
        if (state.month) query.month = state.month;
        if (state.order) query.order = state.order;
        if (state.day) query.day = state.day;
        if (state.catagory_id) query.catagory_id = state.catagory_id;
        if (state.per_page) query.per_page = state.per_page;

        this.getconfusions(query);
      }, 1000),
      deep: true,
    },
  },
};
</script>

<style>
  .btn_link, .btn_link:hover, .btn_link:focus, .btn_link:active{
    border: none;
    outline: none;
    padding: 0;
    background: transparent;
    color: #007eca;
  }
  
  .conf_card {
    border: 1px solid #666;
    margin-bottom: 10px;
    border-radius: 6px;
    overflow: hidden;
  }
  .conf_header {
    background: #000;
    color: #fff;
    padding: 5px 10px;
  }
  .conf_body {
    padding: 8px;
  }
  
  .conf_body > div {
    border-top: 1px solid rgb(174,174,174);
  }
  .conf_body > div:first-child {
    border-top: 0px solid rgb(174,174,174);
  }
  
  
</style>
