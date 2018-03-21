var player = document.getElementById('audioPlayer');

// Fonction gérant le bouton play
function play(idPlayer, play){
    //var imgBoutonPlay = document.getElementById('iPlay');
    //var imgBoutonStop = document.getElementById('iStop');

    if(player.paused){
        player.play();
        play.src = "Melodya/web/bundles/amamamelodya/img/icons/pause1.png";
        console.log("PAUSE");
    } else {
        player.pause();
        play.src = "Melodya/web/bundles/amamamelodya/img/icons/play-button1.png";
        console.log("PLAY");
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

    progression.style.borderColor = 'black';
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
