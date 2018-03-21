/*window.addEventListener('load', (evenement)=>{
    buttonStart = document.getElementById('start_discoverya');
});*/

var buttonStart = document.getElementById('start_discoverya');
var divDiscoverya = document.getElementById('discoverya');
var buttonLike = document.getElementById('like');
var test = document.getElementById('test');
var player = document.getElementById('audioPlayer');
var boutonLike = document.getElementById('likeButton');

document.onreadystatechange = (ev) => {
    if (document.readyState === 'complete') {
        var urlActuel = document.URL;
        console.log(urlActuel);
        var numPage = urlActuel.substring(urlActuel.length, urlActuel.length - 1);
        if (numPage > 1) {
            buttonStart.style.visibility = "hidden";
            if (divDiscoverya.style.visibility === "hidden") {
                divDiscoverya.style.visibility = "visible";
                buttonStart.style.visibility = "hidden";
            }
            if(numPage == 1){
                divDiscoverya.style.visibility = "visible";
                buttonStart.style.visibility = "hidden";
            }
        }else{
            buttonStart.onclick = (evt)=>{
                divDiscoverya.style.visibility = "visible";
            }
        }
    }
}

function afficherBoutonProfil(){

}

boutonLike.addEventListener('click', (ev)=>{
    if(ev.target.src == "http://localhost/Melodya/web/bundles/amamamelodya/img/icons/like_empty.png"){
        ev.target.src = "http://localhost/Melodya/web/bundles/amamamelodya/img/icons/like.png";
        setTimeout(this.Eteindre, 2000)
    }else{
        ev.target.src = "http://localhost/Melodya/web/bundles/amamamelodya/img/icons/like_empty.png";
    }
});

// Fonction gérant le bouton play
function play(idPlayer, play){
    var imgBoutonPlay = document.getElementById('iPlay');

    if(player.paused){
        player.play();
        //imgBoutonPlay.src = "../img/icons/pause.png";
        console.log("AFFICHE PAUSE");
    } else {
        player.pause();
        //imgBoutonPlay.src = "../img/icons/play-button.png";
        console.log("AFFICHE PLAY");
    }
}

function stop(idPlayer){
    player.currentTime = 0;
    player.pause();
}

function update(player){
    var dureeTotale = player.duration; // durée totale
    var temps = player.currentTime; // Temps écoulé
    var fraction = temps / dureeTotale;
    var pourcentage = Math.ceil(fraction * 100);

    var progression = document.getElementById('progressBar');
    var tempsEcoule = document.getElementById('progressTime');
    var divProgression = document.getElementById('progressBarControl');

    divProgression.style.borderColor = 'black';
    progression.style.backgroundColor = '#B70000';
    progression.style.color = 'black';
    progression.style.width = pourcentage + '%';
    progression.textContent = pourcentage + '%';
    tempsEcoule.textContent = formatTime(temps);
}

function formatTime(time){
    var heures = Math.floor(time / 3600);
    var minutes = Math.floor((time % 3600) / 30);
    var secondes = Math.floor(time % 60);

    if(secondes < 10){
        secondes = "0" + secondes;
    }

    if(heures) {
        if(minutes < 10){
            minutes = "0" + minutes;
        }

        return heures + ":" + minutes + ":" + secondes;
    } else {
        return minutes + ":" + secondes;
    }
}

/*window.addEventListener('load', (ev)=>{
    var urlActuel = window.location.href;
    console.log(urlActuel);
    var numPage = urlActuel.substring(urlActuel.length,urlActuel.length - 1);
    console.log(numPage);

});*/


