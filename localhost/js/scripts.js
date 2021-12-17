'use strict';

//Обработчик событий для фильтров товаров
$(document).ready(function() {
    getProducts();
    $('order-item__btn').on('click', changeOrdStatus);
    $(document).on('change', '.shop__sorting', sortProducts);
    $(document).on('click', '.paginator__item', showNextProductPage);
    $('a.filter__list-item').on('click', makeProducts);
    $('.filter_form').on('submit', makeProducts);
    //$(".main-menu__item[href^='/?filter=']").on('click', makeProducts);
    $('.page-authorization > .custom-form').on('submit', sendAjaxToAuth);

});

const toggleHidden = (...fields) => {

  fields.forEach((field) => {

    if (field.hidden === true) {

      field.hidden = false;

    } else {

      field.hidden = true;

    }
  });
};

const labelHidden = (form) => {

  form.addEventListener('focusout', (evt) => {

    const field = evt.target;
    const label = field.nextElementSibling;

    if (field.tagName === 'INPUT' && field.value && label) {

      label.hidden = true;

    } else if (label) {

      label.hidden = false;

    }
  });
};

const toggleDelivery = (elem) => {

  const delivery = elem.querySelector('.js-radio');
  const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
  const deliveryNo = elem.querySelector('.shop-page__delivery--no');
  const fields = deliveryYes.querySelectorAll('.custom-form__input');

  delivery.addEventListener('change', (evt) => {

    if (evt.target.id === 'dev-no') {

      fields.forEach(inp => {
        if (inp.required === true) {
          inp.required = false;
        }
      });


      toggleHidden(deliveryYes, deliveryNo);

      deliveryNo.classList.add('fade');
      setTimeout(() => {
        deliveryNo.classList.remove('fade');
      }, 1000);

    } else {

      fields.forEach(inp => {
        if (inp.required === false) {
          inp.required = true;
        }
      });

      toggleHidden(deliveryYes, deliveryNo);

      deliveryYes.classList.add('fade');
      setTimeout(() => {
        deliveryYes.classList.remove('fade');
      }, 1000);
    }
  });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

  filterWrapper.addEventListener('click', evt => {

    const filterList = filterWrapper.querySelectorAll('.filter__list-item');

    filterList.forEach(filter => {

      if (filter.classList.contains('active')) {

        filter.classList.remove('active');

      }

    });

    const filter = evt.target;

    filter.classList.add('active');

  });

}

// Каталог товаров - обработчик catalogue.php
const shopList = document.querySelector('.shop__wrapper'); //shop__list
if (shopList) {

  shopList.addEventListener('click', (evt) => {

    const prod = evt.path || (evt.composedPath && evt.composedPath());;

    if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {

      const shopOrder = document.querySelector('.shop-page__order');
      const popupEnd = document.querySelector('.shop-page__popup-end');

      toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

      window.scroll(0, 0);

      shopOrder.classList.add('fade');
      setTimeout(() => shopOrder.classList.remove('fade'), 1000);

      const form = shopOrder.querySelector('.custom-form');
      labelHidden(form);

      toggleDelivery(shopOrder);

      const buttonOrder = shopOrder.querySelector('.button');

      buttonOrder.addEventListener('click', (evt) => {

        form.noValidate = true;

        const inputs = Array.from(shopOrder.querySelectorAll('[required]'));

        inputs.forEach(inp => {

          if (!!inp.value) {

            if (inp.classList.contains('custom-form__input--error')) {
              inp.classList.remove('custom-form__input--error');
            }

          } else {

            inp.classList.add('custom-form__input--error');

          }
        });

        if (inputs.every(inp => !!inp.value)) {

          evt.preventDefault();

          sendAjaxToOrder(form, prod[0], popupEnd, shopOrder);

        } else {
          window.scroll(0, 0);
          evt.preventDefault();
        }
      });
    }
  });
}

//Форма заказа обработчик orders.php
const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

  pageOrderList.addEventListener('click', evt => {


    if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
      var path = evt.path || (evt.composedPath && evt.composedPath());
      Array.from(path).forEach(element => {

        if (element.classList && element.classList.contains('page-order__item')) {

          element.classList.toggle('order-item--active');

        }

      });

      evt.target.classList.toggle('order-item__toggle--active');

    }

    if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {

      const status = evt.target.previousElementSibling;
      const id =  status.dataset.id;

      if (status.classList && status.classList.contains('order-item__info--no')) {
        status.textContent = 'Выполнено';
        changeOrdStatus(id, '1');
      } else {
        status.textContent = 'Не выполнено';
        changeOrdStatus(id, '0');
      }

      status.classList.toggle('order-item__info--no');
      status.classList.toggle('order-item__info--yes');

    }

  });

}

const checkList = (list, btn) => {

  if (list.children.length === 1) {

    btn.hidden = false;

  } else {
    btn.hidden = true;
  }

};

//Админ панель обработчик события добавления товара add.php
const addList = document.querySelector('.add-list');
if (addList) {

  const form = document.querySelector('.custom-form');
  labelHidden(form);

  const addButton = addList.querySelector('.add-list__item--add');
  const addInput = addList.querySelector('#product-photo');

  checkList(addList, addButton);

  addInput.addEventListener('change', evt => {

    const template = document.createElement('LI');
    const img = document.createElement('IMG');

    template.className = 'add-list__item add-list__item--active';
    template.addEventListener('click', evt => {
      addList.removeChild(evt.target);
      addInput.value = '';
      checkList(addList, addButton);
    });
    const file = evt.target.files[0];
    const reader = new FileReader();

    reader.onload = (evt) => {
      img.src = evt.target.result;
      template.appendChild(img);
      addList.appendChild(template);
      checkList(addList, addButton);
    };

    reader.readAsDataURL(file);

  });

  const button = document.querySelector('.button');
  const popupEnd = document.querySelector('.page-add__popup-end');

  button.addEventListener('click', (evt) => {

    evt.preventDefault();

    sendProductInfo(form);

  })

}

//Админ панель обработчик удаления продуктов productsList.php
const productsList = document.querySelector('.page-products__list');
if (productsList) {

  productsList.addEventListener('click', evt => {

    const target = evt.target;

    if (target.classList && target.classList.contains('product-item__delete')) {

      let productId =  target.dataset.id;
      deactiveProduct(productId);
      productsList.removeChild(target.parentElement);
    }

    if (target.classList && target.classList.contains('product-item__edit')) {
      let productId = target.nextElementSibling.dataset.id;
      getProudctInfo(productId);
    }

  });

}

// jquery range maxmin
if (document.querySelector('.shop-page')) {

  $('.range__line').slider({
    min: 350,
    max: 32000,
    values: [350, 32000],
    range: true,
    stop: function(event, ui) {

      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');

    },
    slide: function(event, ui) {

      $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
      $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');

    }
  });

}

/**
*Функция первоначальной загрузки каталога и страниц новинка и sale
*/
function getProducts()
{
  if (window.location.pathname == '/') {
    let data = [
      {
        name: 'execFunc',
        value: 'makeProducts'
      },
      {
        name: 'category_id',
        value: '1'
      }
    ];
    let filter = window.location.search.match(/\?filter=(\w+)/);
    if (filter !== null) {
      $('#' + filter[1]).attr("checked", true);
      data.push(
        {
          name: filter[1],
          value: '1'
        }
      );
    }
    $.get(
      '/helpers/helperContent.php',
      data,
      function( data ) {
        let elem = $.parseHTML(data);
        let products = $(elem).children('.shop__item.product');
        showCurrentProductPage(products, 1);
        $(elem).appendTo($( "div.shop__wrapper" ));
      }
    );
  }
}

/**
*Функция объединяет данные фильтров для получения блока продуктов от сервера
* @param event объект события
*/
function makeProducts(event)
{
    event.preventDefault();
    var data = [
      {
        name : 'execFunc',
        value: 'makeProducts'
      }
    ];

    var newFilter = false;
    var saleFilter = false;

    if ($(this).attr('href')) {
      $('#new').attr("checked", false);
      $('#sale').attr("checked", false);
      $('.range__line').slider( "values", [350, 32000]);
      $('.min-price').text('350 руб.');
      $('.max-price').text('32000 руб.');
      let href = $(this).attr('href');
      if(href.includes('/?filter=category_id')) {

        let category = parseInt(href.slice('/?filter=category_id'.length - href.length));
        data.push(
          {
            name: 'category_id',
            value: category
          }
        );
      }
    } else {
      newFilter =  $('#new').prop("checked");
      saleFilter =  $('#sale').prop("checked");
      var priceMinFilt = parseInt($('.min-price').text());
      var priceMaxFilt = parseInt($('.max-price').text());
      data.push(
        {
          name : 'max_price',
          value: priceMaxFilt
        },
        {
          name : 'min_price',
          value: priceMinFilt
        }
      );
    }
    if (newFilter) data.push({
      name : 'new',
      value : '1'
    });
    if (saleFilter) data.push({
      name : 'sale',
      value : '1'
    });
    let url = $(this).attr('action') ? $(this).attr('action') : '/helpers/helperContent.php';

    $.get( url, data, function( data ) {
        $( "div.shop__wrapper" ).html( data );
    });
}

/**
*Функция сортировки продуктов по наименованию и цене по возрастанию и убыванию
*@param event объект события
*/
function sortProducts(event)
{
    event.preventDefault();
    let category = $("select[name='category']").children("option:selected").val();
    let order = $("select[name='order']").children("option:selected").val();
    if (category != 'Сортировка' && order != 'Порядок') {
        let productsSelector = '.shop__item.product';
        let container = $('section.shop__list');
        let sortFunc = category == 'price' ? 'sortPrices' : 'sortNames';

        let products = window[sortFunc](productsSelector, order);
        let pageNum = parseInt($(".paginator__item:not([href='#'])").text());
        showCurrentProductPage($(products), pageNum);
        $(products).appendTo(container);
    }
}


/**
*Функция сортировки продуктов по наименованию и цене по возрастанию и убыванию
*@param event объект события
*/
function showNextProductPage(event)
{
  event.preventDefault();
  let pageNum = $(this).text();
  let products = $('.shop__item.product').hide();
  showCurrentProductPage(products, pageNum);
  $(".paginator__item:not([href='#'])").attr('href', '#');
  $(this).removeAttr('href');
}

/**
*Функция сортировки по строкам элементов jquery
*@param selector элементов для использования jquery
*@param order принимает значение 'asc' и 'desc'
*return массив селекторов элементов
*/
function sortNames(selector, order)
{
    let ord = order == 'asc' ? 1 : -1;
    let elements = $.makeArray($(selector));
    elements.sort(function(a, b){
        var nameA = $(a).children('.product__name').text().toLowerCase();
        var nameB = $(b).children('.product__name').text().toLowerCase();
        if (nameA < nameB) {
            return -1 * ord;
        }
        if (nameA > nameB) {
            return 1 * ord;
        }
        return 0;
    })
    return elements;
}

/**
*Функция сортировки по числовым значениям элементов jquery
*@param selector элементов для использования jquery
*@param order принимает значение 'asc' и 'desc'
*return массив селекторов элементов
*/
function sortPrices(selector, order)
{
    let ord = order == 'asc' ? 1 : -1;
    let elements = $.makeArray($(selector));
    elements.sort(function(a, b){
        var an = parseFloat($(a).children('.product__price').text());
        var bn = parseFloat($(b).children('.product__price').text());
        return (an - bn) * ord;
    })
    return elements;
}

/**
*Функция отправки ajax запроса для авторизации
*@param event объект события
*/
function sendAjaxToAuth(event)
{
  event.preventDefault();
  let data = $(this).serializeArray();
  $.ajax({
            url     : $(this).attr('action'),
            type    : "POST",
            dataType: "json",
            data    : {
                         execFunc: 'auth',
                         email   : data[0].value,
                         password: data[1].value,
                      },
            success : function(respond, status, jqXHR)
                      {
                        if (respond.success) {
                        	if (respond.success == 'admin' || respond.success == 'operator') {
                        		window.location.href = window.location.origin + "/admin/?orders=yes";
                        	} else {
                        		window.location.href = window.location.origin;
                        	}
                          //window.location.href = window.location.origin;
                        }

                        if (respond.error) {
                          $("button.button[type='submit']").text(respond.error);
                        }
                      },
            error  : function( jqXHR, status, errorThrown )
                     {
                         console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
                     }
  });
}

/**
*Функция отправки ajax запроса для добавления нового заказа
*@param form - объект DOM формы заказа
*@param product - объект DOM заказываемого продукта
*/
function sendAjaxToOrder(form, product, popupEnd, shopOrder)
{
    event.preventDefault();
    let data = [
        {
            name  : 'execFunc',
            value : 'newOrder'
        },
        {
            name  : 'prod_id',
            value : $(product).attr('data-product_id')
        },
        {
          name : 'order_cost',
          value : parseFloat($(product).children('.product__price').text())
        }
    ];
    data = data.concat($(form).serializeArray());
    $.ajax({
            url     : "/helpers/helperContent.php",
            type    : "POST",
            dataType: "json",
            data    : data,
            success : function(respond, status, jqXHR)
                    {
                      if (respond.error) {
                        $("h2.error-header").text('');
                        let errorMsg = 'ОШИБКА: ';
                        for (let error of respond.error) {
                          errorMsg += 'некорректные данные в поле: ' + error + '; ';
                        }
                        $("p.result-of-query").text(errorMsg);
                      }
                      if (respond.success) {
                        toggleHidden(shopOrder, popupEnd);
                        popupEnd.classList.add('fade');
                        setTimeout(() => popupEnd.classList.remove('fade'), 1000);

                        window.scroll(0, 0);

                        const buttonEnd = popupEnd.querySelector('.button');

                        buttonEnd.addEventListener('click', () => {


                          popupEnd.classList.add('fade-reverse');

                          setTimeout(() => {

                            popupEnd.classList.remove('fade-reverse');

                            toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

                          }, 1000);

                        });
                      }
                    },
            error   : function(respond, status, jqXHR)
                      {
                        console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
                      }
    });

}

/**
*Функция отправки ajax post запроса для изменения
*статуса заказа в административной панели
*@param id - id заказа в базе данных
*@param status - значение статуса заказа
*/
function changeOrdStatus(id, status)
{
  let data = [
    {
      name: 'execFunc',
      value: 'changeOrdStatus'
    },
    {
      name: 'id',
      value: id,
    },
    {
      name: 'status',
      value: status,
    }
  ];
  $.post( "/helpers/helperContent.php", data);
}

/**
*Функция отправки ajax post запроса для удаления продукта
*из панели администратора(в базе данных продукт не удаляется)
*@param id - id продукта в базе данных
*/
function deactiveProduct(id)
{
    let data = [
    {
      name: 'execFunc',
      value: 'deactiveProduct'
    },
    {
      name: 'id',
      value: id,
    }
  ];
  $.post( "/helpers/helperContent.php", data);
}

/**
*Функция отправки ajax post запроса для
*получения информации о продукте из базы данных
*@param productId - id продукта в базе данных
*/
function getProudctInfo(productId)
{
    let data = [
    {
      name: 'execFunc',
      value: 'getProudctInfo'
    },
    {
      name: 'id',
      value: productId,
    }
  ];
  $.post( "/helpers/helperContent.php", data);
}

/**
*Функция отправки ajax запроса для
*изменения информации о продукте из базы данных
*@param form - объект DOM формы изменения продукта
*/
function sendProductInfo(form)
{
  var formData = new FormData(form);
  var execFunc;

  if(form.dataset.id == 'new_product') {
    execFunc = 'addProduct';
  } else {
    execFunc = 'changeProduct';
    formData.append('product_id', form.dataset.id);
  }
  formData.append('execFunc', execFunc);
  
  $.ajax({
          url     : "/helpers/helperContent.php",
          type    : "POST",
          contentType: false,
          processData: false,
          dataType: 'json',
          cache:false,
          data    : formData,
          success : function(respond, status, jqXHR)
                    {
                      if (respond.error) {
                        $("h2.error-header").text('');
                        let errorMsg = 'ОШИБКА: ';
                        for (const error of respond.error) {
                          errorMsg += 'некорректные данные в поле: ' + error + '; ';
                        }
                        $("p.result-of-query").text(errorMsg);
                      }
                      if (respond.success) {
                            form.hidden = true;
                            let popupEnd = document.querySelector('.shop-page__popup-end');
                            let errors = document.querySelector('.errors-box');
                            errors.hidden = true;
                            popupEnd.hidden = false;
                      }
                    },
          error   : function( jqXHR, status, errorThrown )
                     {
                         console.log( 'ОШИБКА AJAX запроса: ' + status, jqXHR );
                     }
  });
}

function showCurrentProductPage(products, pageNum)
{
  products.each(function( index ) {
    if (Math.trunc(index / 9) + 1 == pageNum) {
      $(this).css( "display", "block" );
    } else {
      $(this).css( "display", "none" );
    }
  });
}
