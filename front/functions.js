let currentQuestion = 0;
let respostaCorrecta = 0;
let data;
let respostesUsuari = [];
let timeLeft = 30; 
let timerInterval; 

// Funció per començar el joc
function iniciarJoc() {
  // Obtenim les preguntes al carregar la pàgina des de getPreguntes.php
  fetch('http://localhost/public_html/Projecte0/getPreguntes.php')
    .then(response => response.json())
    .then(jsonData => {
      data = jsonData;
      mostrarPregunta();  
      iniciarTemporitzador(); // Iniciem el temporitzador quan comença la primera pregunta
    });
}

// Funció per mostrar una pregunta
function mostrarPregunta() {
  let preguntaActual = data[currentQuestion];
  let htmlString = `<h3>${preguntaActual.pregunta}</h3>`;
  htmlString += `<img src="img/${currentQuestion}.jpeg" class="img" alt="Imatge de la pregunta ${currentQuestion}">`;

  let opcions = [preguntaActual.resposta_correcta, ...preguntaActual.respostes_incorrectes];
  
  opcions = opcions.sort(() => Math.random() - 0.5);
  
  opcions.forEach((resposta) => {
    htmlString += `<button onclick="seleccionarResposta('${resposta}', '${preguntaActual.resposta_correcta}')">${resposta}</button><br>`;
  });

  document.getElementById('preguntesContainer').innerHTML = htmlString;
}

// Funció per seleccionar una resposta
function seleccionarResposta(respostaSeleccionada, respostaCorrecta) {
  let resultat = document.createElement('p');
  
  respostesUsuari[currentQuestion] = respostaSeleccionada;

  if (respostaSeleccionada === respostaCorrecta) {
    resultat.innerHTML = "Correcte!";
    resultat.style.color = "green";
    respostaCorrecta++; // Incrementem el comptador de respostes correctes
  } else {
    resultat.innerHTML = "Incorrecte!";
    resultat.style.color = "red";
  }

  document.getElementById('preguntesContainer').appendChild(resultat);

  // Mostrem la següent pregunta si no s'ha acabat el temps
  setTimeout(() => {
    currentQuestion++;
    if (timeLeft > 0) {
      mostrarPregunta();
    }
  }, 1000);
}

// Funció per iniciar el temporitzador
function iniciarTemporitzador() {
  timerInterval = setInterval(() => {
    timeLeft--;
    document.getElementById('timer').innerText = `Temps restant: ${timeLeft} segons`;

    // Si s'acaba el temps, finalitzem el joc
    if (timeLeft <= 0) {
      clearInterval(timerInterval); // Aturem el temporitzador
      mostrarPuntuacioFinal(); // Mostrem la puntuació final
    }
  }, 1000);
}

// Funció per mostrar la puntuació final
function mostrarPuntuacioFinal() {
  // Enviem les respostes de l'usuari a finalitza.php
  fetch('http://localhost/public_html/Projecte0/finalitza.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(respostesUsuari)
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('preguntesContainer').innerHTML = `<h3>Has encertat ${data.correctes}/${data.total} preguntes.</h3>`;
    document.getElementById('preguntesContainer').innerHTML += `<button onclick="reiniciarJoc()">Tornar a jugar</button>`;
  });
}

// Funció per reiniciar el joc
function reiniciarJoc() {
  currentQuestion = 0;
  respostaCorrecta = 0;
  respostesUsuari = [];
  timeLeft = 30; // Restablir el temps
  iniciarJoc(); // Tornar a començar el joc
}

// Iniciar el joc en carregar la pàgina
window.onload = iniciarJoc;
