var sh1 = document.getElementById("sh1");
var sh2 = document.getElementById("sh2");
var sh3 = document.getElementById("sh3");


sh1.addEventListener("click", () =>{
    window.localStorage.setItem('vib', '0')
});
sh2.addEventListener("click", () =>{
    window.localStorage.setItem('vib', '1')
});
sh3.addEventListener("click", () =>{
    window.localStorage.setItem('vib', '2')
});