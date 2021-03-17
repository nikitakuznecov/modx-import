<?php require_once __DIR__ . '/core/bootstrap.php';?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
  <title>Import (v3.0.0)</title>

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/import/assets/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="/import/assets/css/main.css">
  <link rel="stylesheet" type="text/css" href="/import/assets/css/animate.min.css">

</head>
<body>
 
<div id="app">

  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
      <a class="navbar-brand" href="#">
        <h1>Import
          <small>(v3.0.0)</small>
        </h1>
      </a>
      <div class="btn-group">
        <div v-if="isReadyImport">
          <b-overlay :show="inProcess" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
            <b-button ref="button" :disabled="inProcess" variant="success">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
              <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
              <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
            </svg>Обновить каталог
            </b-button>
          </b-overlay>
        </div>
        <div v-else>
          <b-overlay :show="inProcess" rounded opacity="0.6" spinner-small spinner-variant="primary" class="d-inline-block">
            <b-button ref="button" :disabled="inProcess" variant="success" @click="getImport">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cloud-upload-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 0a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 4.095 0 5.555 0 7.318 0 9.366 1.708 11 3.781 11H7.5V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V11h4.188C14.502 11 16 9.57 16 7.773c0-1.636-1.242-2.969-2.834-3.194C12.923 1.999 10.69 0 8 0zm-.5 14.5V11h1v3.5a.5.5 0 0 1-1 0z"/>
              </svg>Загрузить данные
            </b-button>
          </b-overlay>
        </div>
      </div>
    </div>
  </nav>

<div class="container max-height">

  <div v-if="isReadyImport">
      <hr>
      <b-progress :max = "max" height="2rem" variant="success" striped show-progress :animated="true">
        <b-progress-bar :value="value">
          <span>Процесс: <strong>{{ value }} / {{ max }}</strong></span>
        </b-progress-bar>
      </b-progress>
      <hr>
  </div>
  <div v-else><hr></div>

  <div class="work-region">
      <b-tabs pills card vertical>
        <b-tab active>
          <template v-slot:title>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-graph-up" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm10 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V4.9l-3.613 4.417a.5.5 0 0 1-.74.037L7.06 6.767l-3.656 5.027a.5.5 0 0 1-.808-.588l4-5.5a.5.5 0 0 1 .758-.06l2.609 2.61L13.445 4H10.5a.5.5 0 0 1-.5-.5z"/>
            </svg> Общая статистика по позициям
          </template>
          <b-card-text>
            <h3>Общая информация</h3>
            <hr>
            <div class="row">
              <div class="item-statistics col-xs-12 col-md-6">
                <b-card bg-variant="secondary" text-variant="white" header="Новые категории" class="text-center">
                    <b-card-text>
                        <b-list-group>
                          <b-list-group-item class="d-flex justify-content-center align-items-center">
                              <h6>кол-во</h6>
                              <b-badge variant="primary" pill><h2><animate-number :number="newCategoryCount"></animate-number></h2></b-badge>
                            </b-list-group-item>
                          </b-list-group>
                    </b-card-text>
                </b-card>
              </div>
              <div class="item-statistics col-xs-12 col-md-6">
                <b-card bg-variant="secondary" text-variant="white" header="Категории требующие обновления" class="text-center">
                    <b-card-text>
                        <b-list-group>
                          <b-list-group-item class="d-flex justify-content-center align-items-center">
                              <h6>кол-во</h6>
                              <b-badge variant="primary" pill><h2><animate-number :number="updateCategoryCount"></animate-number></h2></b-badge>
                            </b-list-group-item>
                          </b-list-group>
                    </b-card-text>
                </b-card>
              </div>
              <div class="item-statistics col-xs-12 col-md-6">
                <b-card bg-variant="secondary" text-variant="white" header="Новые товары" class="text-center">
                    <b-card-text>
                        <b-list-group>
                          <b-list-group-item class="d-flex justify-content-center align-items-center">
                              <h6>кол-во</h6>
                              <b-badge variant="primary" pill><h2><animate-number :number="newProductCount"></animate-number></h2></b-badge>
                            </b-list-group-item>
                          </b-list-group>
                    </b-card-text>
                </b-card>
              </div>
              <div class="item-statistics col-xs-12 col-md-6">
                <b-card bg-variant="secondary" text-variant="white" header="Товары требующие обновления" class="text-center">
                    <b-card-text>
                        <b-list-group>
                          <b-list-group-item class="d-flex justify-content-center align-items-center">
                              <h6>кол-во</h6>
                              <b-badge variant="primary" pill><h2><animate-number :number="updateProductCount"></animate-number></h2></b-badge>
                            </b-list-group-item>
                          </b-list-group>
                    </b-card-text>
                </b-card>
              </div>
          </div>
          </b-card-text>
        </b-tab>
        <b-tab>
          <template v-slot:title>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg> Список категорий <b-badge variant="light"><animate-number :number="categoriesItemCount"></animate-number></b-badge>
          </template>
          <b-card-text>
          <div v-if="categoriesItemCount > 0">
               <h3>Список категорий</h3>
               <hr>
               <paginated-list :list-data="categories"/> 
            </div> 
            <div v-else>
             <b-alert show variant="success">Список категорий пуст..</b-alert>
           </div>
          </b-card-text>
        </b-tab>
        <b-tab>
          <template v-slot:title>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
              <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
            </svg> Список товаров <b-badge variant="light"><animate-number :number="productsItemCount"></animate-number></b-badge>
          </template>
          <b-card-text>
            <div v-if="productsItemCount > 0">
              <h3>Список товаров</h3>
              <hr>
              <paginated-list :list-data="products"/>  
            </div> 
            <div v-else>
             <b-alert show variant="success">Список продуктов пуст..</b-alert>
           </div>
          </b-card-text>
        </b-tab>
        <b-tab>
          <template v-slot:title>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
              <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg> Ошибки и предупреждения <b-badge variant="light"><animate-number :number="errorMessageItemCount"></animate-number></b-badge>
          </template>
          <b-card-text>
          <div v-if="errorMessageItemCount > 0">
              <h3>Список ошибок и предупреждений</h3>
              <hr>
              <div v-for="message in error_messages">
                <b-alert show variant="danger">
                  <h4 class="alert-heading">{{ message.code }}</h4>
                  <p>{{ message.description }}</p>
                </b-alert>
              </div>
           </div>
           <div v-else>
             <b-alert show variant="success">Список ошибок и предупреждений пуст..</b-alert>
           </div>
          </b-card-text>
        </b-tab>
      </b-tabs>
    </div>
  </div>
</div>
<script type="text/javascript" src="/import/assets/js/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="/import/assets/js/bootstrap.min.js"></script>
<script src="/import/assets/js/axios.min.js"></script>
<script type="text/javascript" src="/import/assets/js/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-vue/2.21.2/bootstrap-vue.min.js" integrity="sha512-Z0dNfC81uEXC2iTTXtE0rM18I3ATkwn1m8Lxe0onw/uPEEkCmVZd+H8GTeYGkAZv50yvoSR5N3hoy/Do2hNSkw==" crossorigin="anonymous"></script>
<script src="/import/assets/js/main.js"></script>

</body>
</html>

