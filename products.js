const DATA = [
  {
      "name"  : "Nothing Phone(2a)",
      "manufacturer" : "Nothing",
      "color" : "black",
      "type" : "smartphone",
      "image" : "https://www.cifrus.ru/photos/big/nothing/nothing-phone-2a-128gb-8gb-dual-5g-black-global-1.jpg",
      "price" : 40000,
      "productUrl" : "nothingProductPage.html"
  }, 
  {
      "name" : "Xiaomi Deerma VC25 Wireless Vacuum Cleaner",
      "manufacturer" : "Xiaomi",
      "color" : "white",
      "type" : "vacuum cleaner",
      "image" : "https://avatars.mds.yandex.net/get-mpic/5255164/img_id786360530445303356.jpeg/600x800",
      "price" : 5995,
      "productUrl" : "vacuumProductPage.html"
  },
  {
      "name" : "Dark Project KD83A",
      "manufacturer" : "Dark Project",
      "color" : "mixed",
      "type" : "keyboard",
      "image" : "https://wylsa.com/wp-content/uploads/2023/02/dark-project-kd83a-9.jpg",
      "price" : 9999,
      "productUrl" : "keyboardProductPage.html"
  },
  {
    "name" : "Microsoft Xbox Series S Series S 1TB",
    "manufacturer" : "Microsoft",
    "color" : "black",
    "type" : "game console",
    "image" : "https://avatars.mds.yandex.net/get-mpic/12523390/2a0000018d8d0b0ee3edb826b5ab959768c5/600x800",
    "price" : 39900,
    "productUrl" : "consoleProductPage.html"
  },
  {
    "name" : "Poco M5",
    "manufacturer" : "Poco",
    "color" : "green",
    "type" : "smartphone",
    "image" : "./files/poco-poco-x3-pro.gif",
    "price" : 11000,
    "productUrl" : "pocoProductPage.html"
  },
  {
    "name" : "Battery unboxing",
    "manufacturer" : "GerNig",
    "color" : "undefined",
    "type" : "service",
    "image" : "./files/battery-explosion.gif",
    "price" : 1500,
    "productUrl" : "batteryUnboxingProductPage.html"
  }
];



const filters = document.querySelector('#filters');
filters.addEventListener('input', filterGoods);
function filterGoods() {
    const 
    colors = [...filters.querySelectorAll('#color input:checked')].map(n => n.value),
    manufacturers = [...filters.querySelectorAll('#manufacturer input:checked')].map(n => n.value),
    type = [...filters.querySelectorAll('#type input:checked')].map(n => n.value),
    priceMin = document.querySelector('#price-min').value,
    priceMax = document.querySelector('#price-max').value;  
    outputGoods(DATA.filter(n => (
      (!type.length || type.includes(n.type)) &&
      (!colors.length || colors.includes(n.color)) &&   
      (!manufacturers.length || manufacturers.includes(n.manufacturer)) &&
      (!priceMin || priceMin <= n.price) &&
      (!priceMax || priceMax >= n.price)
    )));
}  
function outputGoods(goods) {
  var productPageUrl = "productPage.html";
    document.getElementById('content').innerHTML = goods.map(n => `
    <div class="single-goods">
    <a href="${n.productUrl}">
        <h2>${n.name}</h3>
        <img src="${n.image}">
        <h2>Цвет: ${n.color}</h1>
        <p>Цена: ${n.price}</p>
    </a>
    <button class="add-to-cart" data-art="${n.name}">Купить</button>
    </div>
    `).join('');
}
outputGoods(DATA);


