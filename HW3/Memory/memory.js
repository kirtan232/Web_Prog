"use strict";

const query = new URLSearchParams(window.location.search);
const difficulty = parseInt(query.get("difficulty"));   // Time before cards flip.
const numPairs = parseInt(query.get("pairs"));   // Number of unique pairs selected by user.
 
const totalTime = numPairs === 8 ? 120 : numPairs === 10 ? 150 : 180;
let timeLeft = totalTime;
let firstCard = null;
let lockBoard = true;   // Prevent clicks while flipping.
let matchedPairs = 0;
let timer = null;
let gameEnded = false;

const board = document.getElementById("board");
const timerDisplay = document.getElementById("timer");
const message = document.getElementById("message");

// Image
const images = [
  "img/C.jpg",
  "img/Cat.jpg",
  "img/E.jpg",
  "img/H.jpg",
  "img/L.jpg",
  "img/M.jpg",
  "img/P.jpg",
  "img/S.jpg",
  "img/Z.jpg",
  "img/B.jpg",
  "img/F.jpg",
  "img/D.jpg"
];


// Shuffle helper using Fisher-Yates algorithm.
function shuffle(array) {
  for (let i = array.length - 1; i > 0; i--) {
    let j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}


// Main game initializer.
function startGame() {
  const gameImages = images.slice(0, numPairs);   // Select needed number of images.
  const cardValues = [...gameImages, ...gameImages];    // Duplicate to create pairs.
  shuffle(cardValues);   // Shuffle the cards.

  board.innerHTML = "";   // Clear previous cards.
  board.style.gridTemplateColumns = `repeat(${Math.ceil(Math.sqrt(numPairs * 2))}, 100px)`;   //grid

  // Create card DOM elements.
  cardValues.forEach((value, index) => {
    const card = document.createElement("div");
    card.classList.add("card");
    card.dataset.value = value;
    card.dataset.index = index;

    const front = document.createElement("img");
    front.src = value;
    front.classList.add("card-front");
    front.style.width = "100%";
    front.style.height = "100%";
    front.style.borderRadius = "8px";
    front.style.display = "block";   // Show image initially.

    const back = document.createElement("div");
    back.classList.add("card-back");
    back.textContent = index + 1;   // Number for reference.
    back.style.display = "none";   // Hide number initially.
    back.style.justifyContent = "center";
    back.style.alignItems = "center";
    back.style.height = "100%";

    card.appendChild(front);
    card.appendChild(back);
    board.appendChild(card);
  });

  setTimeout(hideCards, difficulty * 1000);   // Delay before hiding cards.
  setTimeout(() => (lockBoard = false), difficulty * 1000);
  startTimer();
}

function hideCards() {   // Hides card faces and shows their numbered backs.
  document.querySelectorAll(".card").forEach((card) => {
    const front = card.querySelector(".card-front");
    const back = card.querySelector(".card-back");
    front.style.display = "none";
    back.style.display = "flex";
    card.classList.remove("revealed");
  });
}

function startTimer() {   // Starts countdown timer and updates display.
  timer = setInterval(() => {
    timeLeft--;
    timerDisplay.textContent = `Time Left: ${timeLeft}s`;
    if (timeLeft <= 0) {
      clearInterval(timer);
      if (!gameEnded) showMessage("⏰ Time's up! Try again.");
    }
  }, 1000);
}

function showMessage(text) {   // Display a win or lose message.
  if (gameEnded) return;
  gameEnded = true;
  clearInterval(timer);
  message.classList.remove("hidden");
  message.innerHTML = `<p>${text}</p>`;
}

board.addEventListener("click", (e) => {   // Handle card click event.
  if (lockBoard || gameEnded) return;

  const clicked = e.target.closest(".card");
  if (!clicked || clicked.classList.contains("revealed")) return;

  const front = clicked.querySelector(".card-front");
  const back = clicked.querySelector(".card-back");

  front.style.display = "block";
  back.style.display = "none";
  clicked.classList.add("revealed");

  if (!firstCard) {
    firstCard = clicked;   // First card selected.
    return;
  }

  if (firstCard.dataset.index === clicked.dataset.index) return;   // Prevent double-click.

  lockBoard = true;   // Lock board while checking.

  const firstFront = firstCard.querySelector(".card-front");
  const firstBack = firstCard.querySelector(".card-back");

  if (firstCard.dataset.value === clicked.dataset.value) {
    matchedPairs++;
    firstCard = null;
    lockBoard = false;

    if (matchedPairs === numPairs) {  // Check win condition.
      setTimeout(() => {
        showMessage("🎉 You Win! 🎉");
      }, 300);
    }
  } else {
    setTimeout(() => {   // Not a match – flip both cards back.
      front.style.display = "none";
      back.style.display = "flex";
      clicked.classList.remove("revealed");

      firstFront.style.display = "none";
      firstBack.style.display = "flex";
      firstCard.classList.remove("revealed");

      firstCard = null;
      lockBoard = false;
    }, 800);
  }
});

startGame();
