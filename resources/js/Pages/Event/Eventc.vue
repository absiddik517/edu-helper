<template>
  <div class="event">
    <div class="d-flex justify-content-between mb-2">
      <span class="text-bold text-muted"><i class="fa fa-clock"></i> {{ event.date }}</span>
      <span class="text-muted"><i class="fa fa-tag"></i> {{ event.catagory }}</span>
    </div>
    <div ref="card">
      <div class="title mb-2" v-html="event.event"></div>
      <div ref="detail" v-html="event.detail" v-show="event.detail" class="detail"></div>
      <img v-show="event.image" style="width:100%;" class="mb-2" :src="event.image" alt="Image">
    </div>
    <div class="d-flex justify-content-between">
      <div class="text-muted"><i class="fa fa-user"></i> {{ event.user_name }}</div>
      <div>
        <Link :href="route('calender.event.edit', event.id)+'?url='+encodeURIComponent(currentUrl)" class="btn btn-info btn-sm mr-2"><i class="fa fa-edit"></i></Link>
        <Button @click="deleteEvent(event.id)" size="sm" varient="danger" type="button"><i class="fa fa-trash"></i></Button>
      </div>
    </div>
  </div>
  <DeleteConfirm :deleteUrl="deleteUrl" item="event" :close-fn="closeModal"/>
</template>

<script>
import {
  DeleteConfirm,
  Button,
} from "@/Components";
  export default {
    components: {DeleteConfirm, Button},
    props: {
      event: Object,
      currentUrl: String,
    },
    data() {
      return {
        isTrancat: true,
        modal: { confirm: null },
        deleteUrl: null,
      }
    },
    mounted() {
      let confirm = document.querySelector("#confirmModel");
      this.modal.confirm = new bootstrap.Modal(confirm);
      this.$refs.card.addEventListener('click', this.toggleTruncat)
    },
    methods: {
      closeModal() {
        this.modal.confirm.hide();
      },
      toggleTruncat() {
        if(this.isTrancat == true) {
          this.$refs.detail.innerHTML = this.event.full_detail;
        }else{
          this.$refs.detail.innerHTML = this.event.detail;
        }
        this.isTrancat = !this.isTrancat
      },
      deleteEvent(id) {
        this.deleteUrl = this.route('calender.event.delete', id);
        this.modal.confirm.show();
        Inertia.on("finish", () => {
          this.deleteUrl = null;
          this.modal.confirm.hide();
        });
      },
    }
  }
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
    overflow-x: scroll;
  }
  
  .detail table {
    min-width: 100%;
  }
  .detail table tbody tr td, .detail table thead tr th{
    border: 1px solid #666;
    vertical-align: middle;
  }
  
</style>