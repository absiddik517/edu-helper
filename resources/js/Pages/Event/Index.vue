<template>
  <Content>
    <div class="row">
      <div class="col-12">
        <Card varient="gray">
          <template #title>
            Event
            <i v-show="loadingTable" class="fa fa-spinner fa-spin"></i>
          </template>
          <template #title_right>
            <button class="btn btn-link mr-2 text-white" @click="toggleAdvanceFilter"><span v-if="!isAdvanceFilterActive">Show</span> <span v-else>Hide</span> Filter</button>
            <Link
              :href="route('calender.event.create')"
              class="mr-2"
              type="button"
              varient="light"
            >
              Create
            </Link>
          </template>
          <div class="row gy-2 mb-2">
            <div class="col-6">
              <input
                v-model="filter.year"
                type="number"
                placeholder="Year"
                class="form-control"
              />
              <i
                v-show="filter.year"
                @click="filter.year = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
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
          <div class="row mb-2" v-show="isAdvanceFilterActive">
            <div class="col-6">
              <Select type="select" placeholder="Catagory" without-label v-model="filter.catagory_id" :options="catagories" field="catagory_id" label-text="Catagory" :form="filter" optional/>
            </div>
            <div class="col-6">
              <Select type="select" placeholder="Month" without-label v-model="filter.month" :options="months" field="month" label-text="Month" :form="filter" optional/>
            </div>
            <div class="col-4">
              <input
                v-model="filter.day"
                type="number"
                placeholder="Day"
                class="form-control"
              />
              <i
                v-show="filter.day"
                @click="filter.day = null"
                class="fa fa-times filter-close"
                style="right: 15px"
              ></i>
            </div>
            <div class="col-4">
              <Select type="select" placeholder="Order" without-label v-model="filter.order" :options="orders" field="order" label-text="Order" :form="filter" optional/>
            </div>
            <div class="col-4">
              <input class="form-control" type="number" v-model="filter.per_page" placeholder="Number of records per page"/>
            </div>
          </div>
          <div>
            <Eventc v-for="event in events.data" :event="event" :current-url="page_url"></Eventc>
          </div>
          
          <div v-if="events.data.length" class="p-2">
            <Pagination @traped="loadingTable = true" :items="events" :on-page-load="onPageLoad" />
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
import Eventc from './Eventc.vue'
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
    Eventc,
    Button,
    Dropdown,
    Filterth,
    Pagination,
  },
  props: {
    events: Object,
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
      isAdvanceFilterActive: false,
      page_url: '',
      orders: [
        {
          label: 'A-Z',
          value: 'ASC'
        },
        {
          label: 'Z-A',
          value: 'DESC',
        }
      ],
      months: months
    };
  },
  methods: {
    onPageLoad() {
      this.page_url = window.location.href;
    },
    getEvents(filter = {}) {
      this.loadingTable = true;
      Inertia.get(this.route("calender.event.index"), filter, {
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
    toggleAdvanceFilter(){
      this.isAdvanceFilterActive = !this.isAdvanceFilterActive
    },
    
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

        this.getEvents(query);
      }, 1000),
      deep: true,
    },
  },
};
</script>

<style>
  .event{
    border: 1px solid #bbb;
    border-radius: 4px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0px 0px 5px #666;
  }
  .event:last-child{
    margin-bottom: 0;
  }
  
  .title {
    font-weight: 600;
    font-size: 18px;
    color: Dark;
  }
  
  .detail {
    text-align: justify;
    color: #4e4e4ef9;
  }
  
</style>
