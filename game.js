var canvas = document.getElementById("game");
var context = canvas.getContext("2d");
var aster=[];
var fire = [];
var timer=0;
var ship={ x: 250, y: 450 };
var ships = ["./files/ship.png", "./files/ship2.png", "./files/ship3.png"];
var score = 0;
var live = 3;
var vib = window.localStorage.getItem('vib')

let isPause = false;
const pause = document.getElementById("pause_btn");
var scoreboard = document.getElementById("score");
var liveboard = document.getElementById("live");
var restart = document.getElementById("restart_btn");
var sh1 = document.getElementById("sh1");
var sh2 = document.getElementById("sh2");
var sh3 = document.getElementById("sh3");

if(vib ==null){
    window.localStorage.setItem('vib', '0');
}

//Изображение фона
var fonimg = new Image();
fonimg.src = "./files/background.png";

//Изображение астеройда
var asterimg = new Image();
asterimg.src = "./files/aster.png";

//Изображение снаряда
var fireimg = new Image();
fireimg.src = "./files/fire.png";

//Изображение корабля
var shipimg = new Image();
shipimg.src = ships[vib];

//Управление мышью
canvas.addEventListener("mousemove", function (event) {
    ship.x = event.offsetX - 30;
    ship.y = event.offsetY - 30;
});

//Первый запуск
fonimg.onload = function(){
    animationId = requestAnimationFrame(game);
    document.getElementById("menuGameOver").hidden = true;
}

//Основная структура
function game(){

    render();
    animationId = requestAnimationFrame(game);
    update();
}

//Пауза
pause.addEventListener("click", () =>{
    isPause = !isPause;
    if(isPause){
        cancelAnimationFrame(animationId);
        
    }
    else{
        animationId = requestAnimationFrame(game);
    }
});

restart.addEventListener("click", () =>{
    location.reload();
});



//Обновление сцены
function update(){
    //Проверка жизней
    if(live==0){
        cancelAnimationFrame(animationId);
        document.getElementById("menuGameOver").hidden = false;
    }

    timer++;
    
    //Вывод данных
    scoreboard.textContent = ("Score: " + score);
    liveboard.textContent = ("Lives: " + live);

    //Генерация астеройдов
    if(timer%10==0){
        aster.push({
            x:Math.random()*1000,
            y:-50,
            dx:Math.random()*2-1,
            dy:Math.random()*2,
            del:0});
    }

    //Физика полета пули
    if (timer % 30 == 0) {
        fire.push({ x: ship.x + 15, y: ship.y + 30, dx: 0, dy: -5 });
    }

    //Удаление пули за сценой
    for (i in fire) {
        fire[i].x = fire[i].x + fire[i].dx;
        fire[i].y = fire[i].y + fire[i].dy;

        if (fire[i].y < 0) {
            fire.splice(i, 1)
        }
    }

    //Стлокновения и физика астеройдов
    for(i in aster){

        aster[i].x=aster[i].x+aster[i].dx;
        aster[i].y=aster[i].y+aster[i].dy;  

         //Границы
        if(aster[i].x>=990 || aster[i].x<0) aster[i].dx=-aster[i].dx;
        if(aster[i].y>=800) aster.splice(i,1);
        
        //Проверка на стокновение пули и астеройда
        for(j in fire){
            if(Math.abs(aster[i].x+25-fire[j].x-15)<=50 && Math.abs(aster[i].y-fire[j].y)<=25){
                aster[i].del=1;
                fire.splice(j,1);
                score++;
                break;
            }
            if(aster[i].del==1) aster.splice(i,1);
        }

        if(Math.abs(aster[i].x+25-ship.x-30)<=60 && Math.abs(aster[i].y-ship.y)<=30){
            aster.splice(i,1);
            live--;
            break;
        }
    }

   
}

//Отображение объектов
function render(){
    context.drawImage(fonimg, 0, 0,  1000, 800);
    context.drawImage(shipimg, ship.x, ship.y, 60, 60);
    for(i in aster){
        context.drawImage(asterimg, aster[i].x, aster[i].y, 50, 50);
    }
    for( i in fire){
        context.drawImage(fireimg, fire[i].x, fire[i].y, 30, 30);
    }
}


