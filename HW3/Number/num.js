"use strict";

let secretNumber;
let remainingGuesses;
const maxGuesses = 10;
let gameOver = false;

const guessInput = document.getElementById("guessInput");
const guessBtn = document.getElementById("guessBtn");
const feedback = document.getElementById("feedback");
const guessesLeft = document.getElementById("guessesLeft");

function startGame() {   // Function to start or restart the game.
  secretNumber = Math.floor(Math.random() * 100) + 1;
  remainingGuesses = maxGuesses;
  gameOver = false;
  feedback.textContent = "";
  guessesLeft.textContent = remainingGuesses;
  guessInput.disabled = false;
  guessInput.value = "";
  guessBtn.textContent = "Guess";
}

function handleGuess() {   // Handle player's guess input.
  const guess = parseInt(guessInput.value);  

  // If Validate input
  if (isNaN(guess) || guess < 1 || guess > 100) {
    feedback.textContent = "🚫 Please enter a number between 1 and 100.";
    return;
  }

  remainingGuesses--;   // Decrement guesses.
  guessesLeft.textContent = remainingGuesses;   // Update guesses left.

  if (guess === secretNumber) {
    feedback.textContent = `🎉 Correct! ${guess} is the number!`;
      endGame();   // End game if correct.
  } else if (remainingGuesses === 0) {
    feedback.textContent = `💥 You've lost! The number was ${secretNumber}.`;
     endGame();   // End game if out of guesses/
  } else {
    feedback.textContent = guess < secretNumber ? "📉 Too low! Try again." : "📈 Too high! Try again."; // hint
  }

  guessInput.value = "";   // Clear input field.
}

function endGame() {   // End the game and prompt to play again.
  gameOver = true;
  guessInput.disabled = true;
  guessBtn.textContent = "Play Again";
}

function handleButtonClick() {   // Handle button click: guess or restart.
  if (gameOver) {
    startGame();   // Restart game.
  } else {
    handleGuess(); // Check guess.
  }
}

guessBtn.addEventListener("click", handleButtonClick);   // Allow Enter key to submit guess.
guessInput.addEventListener("keydown", function (e) {
  if (e.key === "Enter" && !gameOver) handleGuess();
});

startGame();
