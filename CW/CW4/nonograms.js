
(function() {
  "use strict";

  let currentType = "";
  let down = false;


  window.onload = function() {
    setUpTiles(); 
    $("clear").onclick = clearPuzzle;
  };


  window.onmouseup = function() {
    currentType = "";
    down = false;
  };

  document.getElementById("color-picker").addEventListener("input", function() {
  document.documentElement.style.setProperty("--custom-fill", this.value);
  });

  function changeBoxMark() {   // This function handles changing the box when clicked.
    down = true;
    if (this.classList.contains("filled")) {
        this.classList.remove("filled");
        this.classList.remove("crossed-out");
        currentType = "crossed-out";
        this.classList.add("crossed-out");
        this.style.backgroundColor = ""; // reset
    } else if (this.classList.contains("crossed-out")) {
        this.classList.remove("crossed-out");
        this.style.backgroundColor = ""; // reset
        currentType = "";
    } else {
        this.classList.add("filled");
        this.classList.remove("crossed-out");
        const selectedColor = document.getElementById("color-picker").value;
        this.style.backgroundColor = selectedColor;
        currentType = "filled";
    }
  }


  function clearPuzzle() {   // Clears the puzzle by removing all marked box. 
    if (confirm("Are you sure you want to clear the puzzle?")) {
      let boxes = $$(".box");
      this.style.backgroundColor = "";
      for (let i = 0; i < boxes.length; i++) {
        boxes[i].classList.remove("filled", "crossed-out");
      }
    }
  }


  function dragBoxStatus() {     // This function allows click-and-drag for the tiles.
    if (down) {
        const selectedColor = document.getElementById("color-picker").value;
        if (currentType === "") {
            this.classList.remove("crossed-out", "filled");
            this.style.backgroundColor = "";
        } else if (currentType === "filled") {
            this.classList.add("filled");
            this.classList.remove("crossed-out");
            this.style.backgroundColor = selectedColor;
        } else {
            this.classList.add("crossed-out");
            this.classList.remove("filled");
            this.style.backgroundColor = "";
        }
    }
  }

  function setUpTiles() {   // Sets up all tiles with mouse events.
    let tiles = $$(".box");
    for (let i = 0; i < tiles.length; i++) {
      let div = tiles[i];
      div.onmousedown = changeBoxMark;
      div.onmouseover = dragBoxStatus;
      div.onclick = function() {
        down = false;
        currentType = "";
      };
    }
  }

  /**
   * Returns the element that has the ID attribute with the specified value.
   * @param {string} id - element ID
   * @return {object} DOM object associated with id.
   */
  function $(id) {
    return document.getElementById(id);
  }

  /**
   * Returns the array of elements that match the given CSS selector.
   * @param {string} query - CSS query selector
   * @return {object[]} array of DOM objects matching the query.
   */
  function $$(query) {
    return document.querySelectorAll(query);
  }
})();