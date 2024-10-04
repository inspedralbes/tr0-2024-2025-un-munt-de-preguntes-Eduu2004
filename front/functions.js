let currentQuestion = 0;
let numCorrectes = 0;  // Només per comptar el nombre de respostes correctes
let data;
let respostesUsuari = [];
let tempsContador = 30; 
let contador;

function iniciarJoc() {
  fetch(`http://localhost/public_html/Projecte0/getPreguntes.php`)
    .then(response => {
      return response.json();
    })
    .then(jsonData => {
      data = jsonData;
      mostrarPregunta();  
      iniciarTemporitzador();
    })
    .catch(error => {
      console.error(error);
      document.getElementById('preguntesContainer').innerHTML = `<p>${error.message}</p>`;
    });
}

function mostrarPregunta() {
  if (currentQuestion < data.length) {
    let preguntaActual = data[currentQuestion];
    let htmlString = `<h3>${preguntaActual.pregunta}</h3>`;
    

    if (preguntaActual.imatge) {
      htmlString += `<img src="img/${preguntaActual.imatge}" class="img" alt="Imatge de la pregunta ${currentQuestion}">`;
    }

    let opcions = [
      preguntaActual.resposta_correcta,
      preguntaActual.resposta_incorrecta1,
      preguntaActual.resposta_incorrecta2,
      preguntaActual.resposta_incorrecta3
    ];

    opcions = opcions.sort(() => Math.random() - 0.5);  

    let opcionsContainer = document.createElement('div'); 

    opcions.forEach((resposta) => {
      let botoResposta = document.createElement('button');
      botoResposta.innerText = resposta;

      botoResposta.addEventListener('click', () => seleccionarResposta(resposta, preguntaActual.resposta_correcta));
      opcionsContainer.appendChild(botoResposta);
    });

    document.getElementById('preguntesContainer').innerHTML = htmlString;
    document.getElementById('preguntesContainer').appendChild(opcionsContainer);
  } else {
    mostrarPuntuacioFinal();
  }
}

function seleccionarResposta(respostaSeleccionada, respostaCorrecta) {
  let resultat = document.createElement('p');
  
  respostesUsuari[currentQuestion] = respostaSeleccionada;

  if (respostaSeleccionada === respostaCorrecta) {
    resultat.innerHTML = "Correcte!";
    resultat.style.color = "green";
    numCorrectes++;  // Incrementa les respostes correctes
  } else {
    resultat.innerHTML = "Incorrecte!";
    resultat.style.color = "red";
  }

  document.getElementById('preguntesContainer').appendChild(resultat);

  // Mostra la següent pregunta després d'un retard de 1 segon
  setTimeout(() => {
    currentQuestion++;
    if (currentQuestion < data.length && tempsContador > 0) {
      mostrarPregunta();
    } else {
      mostrarPuntuacioFinal();
    }
  }, 500);
}

function iniciarTemporitzador() {
  contador = setInterval(() => {
    tempsContador--;
    document.getElementById('timer').innerText = `Temps restant: ${tempsContador} segons`;
    
    if (tempsContador <= 0) {
      clearInterval(contador);
      mostrarPuntuacioFinal();
    }
  }, 1000);
}

function mostrarPuntuacioFinal() {
  // Atura el temporitzador si encara està corrent
  clearInterval(contador);

  // Envia les respostes a la base de dades
  fetch('http://localhost/public_html/Projecte0/finalitza.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(respostesUsuari)
  })
  .then(response => response.json())
  .then(data => { 
    document.getElementById('preguntesContainer').innerHTML = `<h3>Has encertat ${numCorrectes}/${data.total} preguntes.</h3>`;
    const reiniciarBoto = document.getElementById('reiniciarBoto');
    reiniciarBoto.style.display = 'block';
    reiniciarBoto.addEventListener('click', reiniciarJoc);
  })
  .catch(error => console.error('Error en finalitzar el joc:', error));
}

function reiniciarJoc() {
  currentQuestion = 0;
  numCorrectes = 0;  
  respostesUsuari = [];
  tempsContador = 30; 
  const reiniciarBoto = document.getElementById('reiniciarBoto');
  reiniciarBoto.style.display = 'none';
  iniciarJoc(); 
}

function començarPartida() {
  document.getElementById('començarBoto').style.display = 'none';
  iniciarJoc();
}

document.getElementById('començarBoto').addEventListener('click', començarPartida);

