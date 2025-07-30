window.onload = function () {
  const puzzleArea = document.getElementById("puzzlearea")   // The main area that holds all puzzle tiles.
  const tiles = []   // Array to store tile objects.
  let emptyX = 3, emptyY = 3   // Coordinates of the empty space.
  let timeLeft = 90, timerInterval   // Countdown timer values.
  let gameEnded = false, gameStarted = false   // Game state flags.

  // DOM elements.
  const popup = document.getElementById("popup")                    // Win/loss message popup.
  const popupMessage = document.getElementById("popup-message")     // Message content inside the popup.
  const popupPlayAgain = document.getElementById("popup-play-again") // Button to reset the game after game ends.
  const timerEl = document.getElementById("timer")                  // Timer display element.
  const shuffleBtn = document.getElementById("shufflebutton")       // Shuffle/start/reset game button.

  const bgMusic = document.getElementById("bg-music")   // Background music audio element.
  const bgToggle = document.getElementById("bg-toggle")   // Button to toggle background music.

  // Sound effects.
  const winSound = document.getElementById("win-sound")      // Sound to play when the user wins.
  const loseSound = document.getElementById("lose-sound")    // Sound to play when the user loses.
  const moveSound = document.getElementById("move-sound")    // Sound to play when a tile is moved.

  // Toggle background music on button click.
  bgToggle.addEventListener("click", function () {
    if (bgMusic.paused) {
      bgMusic.play()    // Start playing music.
      bgToggle.textContent = "Pause Music"  // Update button text.
    } else {
      bgMusic.pause()   // Pause music playback.
      bgToggle.textContent = "Play Music"   // Update button text.
    }
  })

  // Displays the popup message.
  function displayPopup(message) {
    popup.style.display = "flex"    // Show the popup.
    popupMessage.textContent = message  // Set the message text.
  }

  // Shows the popup and plays win/lose sound.
  window.showPopup = function (message) {
    if (/won/i.test(message)) {
      winSound.play()   // Play win sound.
    } else {
      loseSound.play()  // Play lose sound.
    }
    displayPopup(message)   // Show message in popup.
  }

  // Resets the game to the starting state.
  function resetGame() {
    clearInterval(timerInterval)    // Stop the timer.
    popup.style.display = "none"    // Hide popup if visible.

    // Reset tile positions to the solved state.
    tiles.forEach((tile, i) => {
      tile.x = i % 4
      tile.y = Math.floor(i / 4)
      tile.style.left = `${tile.x * 100}px`
      tile.style.top = `${tile.y * 100}px`
    })

    emptyX = 3  // Reset empty space.
    emptyY = 3
    updateMovableTiles()    // Update movable tiles' visual state.
    timeLeft = 90   // Reset timer.
    timerEl.textContent = `Time left: ${timeLeft}`
    gameEnded = false
    gameStarted = false
    shuffleBtn.textContent = `Shuffle & Start`   // Reset button label.
  }

  // Resets game when "Play Again" is clicked.
  popupPlayAgain.addEventListener("click", resetGame)

  // Shuffle or reset the game when shuffle button is clicked.
  shuffleBtn.addEventListener("click", () => {
    if (!gameStarted) {
      startGame()    // Start new game and shuffle.
      shuffleBtn.textContent = "Start Over!"   // Update button text.
      gameStarted = true
    } else {
      resetGame()    // Reset to initial solved state.
    }
  })

  // Starts the game and initializes the timer.
  function startGame() {
    // Shuffle the board using 300 random valid moves.
    for (let i = 0; i < 300; i++) {
      const movable = tiles.filter((t) => isMovable(t.x, t.y))
      const rand = movable[Math.floor(Math.random() * movable.length)]
      moveTile(rand, true)    // Silent move (no sound).
    }

    updateMovableTiles()    // Highlight movable tiles.
    popup.style.display = "none"    // Hide any existing popup.

    // Start the countdown timer.
    clearInterval(timerInterval)    // Clear previous timer.
    timeLeft = 90
    timerEl.textContent = `Time left: ${timeLeft}`
    gameEnded = false
    timerInterval = setInterval(() => {
      timeLeft--
      timerEl.textContent = `Time left: ${timeLeft}`
      if (timeLeft <= 0) {
        clearInterval(timerInterval)
        showPopup("You lost!") // Trigger loss.
        gameEnded = true
      }
    }, 1000)
  }

  // Create and add 15 puzzle tiles.
  for (let i = 0; i < 15; i++) {
    const tile = document.createElement("div")
    tile.className = "puzzlepiece"  // Add puzzle tile .
    tile.innerText = i + 1     // Display tile number.
    tile.x = i % 4             // Initial column.
    tile.y = Math.floor(i / 4) // Initial row.
    tile.style.left = `${tile.x * 100}px` // Set x position.
    tile.style.top = `${tile.y * 100}px`  // Set y position.
    tile.style.backgroundPosition = `-${tile.x * 100}px -${tile.y * 100}px`   // Align background.

    // Add click handler to move tile.
    tile.addEventListener("click", () => {
      moveTile(tile)    // Try to move the tile.
      updateMovableTiles()  // Refresh visual hints.
    })

    puzzleArea.appendChild(tile)   // Add tile to puzzle area.
    tiles.push(tile)   // Store tile in array.
  }

  // Check if a tile can move to the empty space.
  function isMovable(x, y) {
    return Math.abs(x - emptyX) + Math.abs(y - emptyY) === 1   // Must be adjacent.
  }

  // Moves the specified tile into the empty space.
  function moveTile(tile, silent = false) {
    if (gameEnded) return   // Prevent moves after game ends.
    if (!silent && !gameStarted) return     // Prevent moves before game starts.
    if (!isMovable(tile.x, tile.y)) return  // Tile not movable.

    const oldX = tile.x,
      oldY = tile.y
    tile.style.left = `${emptyX * 100}px`
    tile.style.top = `${emptyY * 100}px`
    tile.x = emptyX
    tile.y = emptyY
    emptyX = oldX
    emptyY = oldY

    if (!silent) {
      const soundClone = moveSound.cloneNode()  // Clone audio to allow overlaps.
      soundClone.play()     // Play move sound.
    }

    if (!silent && checkWin()) {
      clearInterval(timerInterval)  // Stop the timer.
      showPopup("You won!")         // Show win message.
      gameEnded = true
    }
  }

  // Checks if the puzzle is in a solved state.
  function checkWin() {
    return tiles.every((tile, i) => {
      const cx = i % 4,
        cy = Math.floor(i / 4)
      return tile.x === cx && tile.y === cy
    })
  }

  // Highlights tiles that can be moved.
  function updateMovableTiles() {
    tiles.forEach((tile) => {
      tile.classList.toggle("movablepiece", isMovable(tile.x, tile.y))
    })
  }

  // Initial visual update for movable tiles.
  updateMovableTiles()
}
