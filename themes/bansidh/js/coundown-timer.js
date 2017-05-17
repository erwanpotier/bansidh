
(function ($, Drupal, drupalSettings) {
var deadline = new Date(Date.parse(event_date));
//document.getElementById("timeto").innerHTML = deadline;
initializeClock('countdown', deadline);
};

function getTimeRemaining(endtime) {
  var t = Date.parse(endtime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));
  return {
    'total': t,
    'days': days,
    'hours': hours,
    'minutes': minutes,
    'seconds': seconds
  };
}

function initializeClock(id, endtime) {
  var clock = document.getElementById(id);
  var daysSpan = clock.querySelector('.days');
  var hoursSpan = clock.querySelector('.hours');
  var minutesSpan = clock.querySelector('.minutes');
  var secondsSpan = clock.querySelector('.seconds');

  function updateClock() {
    var t = getTimeRemaining(endtime);

    daysSpan.innerHTML = t.days;
    hoursSpan.innerHTML = ('0' + t.hours).slice(-2);
    minutesSpan.innerHTML = ('0' + t.minutes).slice(-2);
    secondsSpan.innerHTML = ('0' + t.seconds).slice(-2);

    if (t.total <= 0) {
      clearInterval(timeinterval);
      //var newBox = document.createElement('span');
      //newBox.innerHTML = "En cours..."
      //document.getElementById('clock').appendChild(newBox);


// crée un nouveau nœud d'élément <span> vide
// sans aucun ID, attribut ou contenu
var newBox = document.createElement("span");

// lui donne un attribut id appelé 'nouveauSpan'
newBox.setAttribute("id", "startevent");

// crée un peu de contenu pour cet élément.
var newBox_content = document.createTextNode("En cours...");

// ajoute ce contenu au nouvel élément
newBox.appendChild(newBox_content);

// Obtient une référence de l'élément devant lequel on veut insérer notre nouveau span
var suivant = document.getElementById("countdown");

// Obtient une référence du nœud parent
var parentDiv = suivant.parentNode;

// insère le nouvel élément dans le DOM avant sp2
parentDiv.replaceChild(newBox, suivant);



    }
  }

  updateClock();
  var timeinterval = setInterval(updateClock, 1000);
}
