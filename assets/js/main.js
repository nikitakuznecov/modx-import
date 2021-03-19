
var APP_LOG_LIFECYCLE_EVENTS = true;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

//axios.defaults.timeout = 36000000;

Vue.component('animate-number', {
  
  template: `<span :v-bind="onchange_number">{{ val }}</span>`,
  props: {
    number:{
        type:Number,
        required:true
    }
  },
  data(){
    return {
      val: 0
    }
  },
  computed: {
    onchange_number() {
      if(this.number !== 0){
        const interval = setInterval(() => {
          if (++this.val === this.number) {
            clearInterval(interval);
          }
        }, 3000 / this.number);
      }
    }
  }
});

Vue.component('paginated-list',{
  data(){
    return {
      pageNumber: 0
    }
  },
  props:{
    listData:{
      type:Array,
      required:true
    },
    size:{
      type:Number,
      required:false,
      default: 20
    }
  },
  methods:{
      nextPage(){
         this.pageNumber++;
      },
      prevPage(){
        this.pageNumber--;
      }
  },
  computed:{
    pageCount(){
      let l = this.listData.length,
          s = this.size;
      return Math.ceil(l/s);
    },
    paginatedData(){
      const start = this.pageNumber * this.size,
            end = start + this.size;
      return this.listData
               .slice(start, end);
    }
  },
  template: `
  <div>   
    <b-list-group>
      <div v-for="(p, index) in paginatedData" :key="index">
        <b-list-group-item variant="success">
        <div class="d-flex align-items-center justify-content-between">
               <span class="title">Название - {{ p.pagetitle }}   <span v-if="p.article"> Артикул - {{ p.article }} </span></span> 
               <span class="state badge warning" v-if="p.state == true"> Обновление </span>
               <span class="state badge success" v-else> Новый </span>
        </div>
        </b-list-group-item>
      </div>
    </b-list-group>
    <div class="d-flex justify-content-center">
      <button class="btn btn-warning" :disabled="pageNumber === 0" @click="prevPage">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
      </svg>
      </button>
      <button class="btn btn-warning" :disabled="pageNumber >= pageCount -1" @click="nextPage">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
      </svg>
      </button>
    </div>
  </div>
  `
});

var app = new Vue({
  el: '#app',
	data: {
	  sitename: "Import (v3.0.0)",
		readyImport: false,
    process: false,
	  products:[],
		categories:[],
    value: 0,
    max:0,
    error_messages:[],
    caption:'',
    dataType:'categoriesUpdate'
	},
	filters: {},
	methods:{ 
    getImport: function(){
      this.process = true;
      axios.get("getImport") 
      .then(response => {
        console.log(response.data);
        if(response.data.error){
          this.process = false;
          this.error_messages.push({description: response.data.response, code: 'Ошибка'});
        }else{
          this.products = response.data.products;
          this.categories = response.data.categories;
          this.readyImport = true;
          this.process = false;
        }
      }).catch(error => {
        this.process = false;
        this.error_messages.push({description: 'В результате выполнения программы произошел сбой', code: error});
      });
    },
    runImport: function(){

      this.process = true;

      axios.get(this.dataType) //Направляем запрос на сервер (по умолчанию обновление категорий)
      .then(response => {

        //Проверим есть ли ответ от сервера
        if(response.data){

          this.value = response.data.uploaded;
          this.max = response.data.amount;
          this.caption = response.data.caption;
          this.dataType = response.data.dataType; // Тут получаем следующее действие а именно название роутера
          
          if(response.data.dataType !== 'done'){//Если не конец выгрузки то продолжаем

              this.runImport();  //Запускаемся еще раз с новым значением роутера 

          }else{//Конец выгрузки завершаем процесс

              this.process = false;
              this.products = [];
              this.categories = [];
          }
          console.log(response.data);

        }else{ //Если ответа нет то завершаем процесс и возвращаем ошибку

          this.process = false;
          this.error_messages.push({description: 'Сервер отдал непригодные данные для выгрузки..', code: 'Ошибка'});

        }

      }).catch(error => {//В случае если вдруг что-то пошло не так, завершаем процесс и отдаем ошибку

        this.process = false;
        this.error_messages.push({description: 'В результате выполнения программы произошел сбой', code: error});

      });

    }
	},
	computed: {
    inProcess(){
      return this.process || false;
    },
	  errorMessageItemCount: function() {
	    return this.error_messages.length || 0;
	  },
    isReadyImport: function() {
	    return this.readyImport;
	  },
    productsItemCount: function() {
	    return this.products.length;
	  },
    categoriesItemCount: function() {
	    return this.categories.length || 0;
	  },
    newProductCount(){
       let result = [];
       this.products.forEach(element => {
          if(!element.state){result.push(element);}
       });
       return result.length;
    },
    updateProductCount(){
       let result = [];
       this.products.forEach(element => {
          if(element.state){result.push(element);}
       });
       return result.length;
    },
    newCategoryCount(){
       let result = [];
       this.categories.forEach(element => {
          if(!element.state){result.push(element);}
       });
       return result.length;
    },
    updateCategoryCount(){
       let result = [];
       this.categories.forEach(element => {
          if(element.state){result.push(element);}
       });
       return result.length;
    }
	},
	beforeCreate: function() {},
  created: function() {},
  beforeMount: function() {},
  mounted:  function() {},
  beforeUpdate:  function() {},
  updated:  function() {},
  beforeDestroy:  function() {},
  destroyed:  function() {}
})
