
window.onload = function () {
    const puzzleArea = document.getElementById("puzzlearea");
    const tiles = [];
    let emptyX = 3, emptyY = 3;

    for (let i = 0; i < 15; i++) {
        const tile = document.createElement("div");
        tile.classList.add("puzzlepiece");
        tile.innerText = i + 1;
        let x = i % 4;
        let y = Math.floor(i / 4);
        tile.style.left = (x * 100) + "px";
        tile.style.top = (y * 100) + "px";
        tile.style.backgroundPosition = (-x * 100) + "px " + (-y * 100) + "px";
        tile.x = x;
        tile.y = y;
        puzzleArea.appendChild(tile);
        tiles.push(tile);
    }

    function isMovable(x, y) {
        return (Math.abs(x - emptyX) + Math.abs(y - emptyY)) === 1;
    }

    function moveTile(tile) {
        if (isMovable(tile.x, tile.y)) {
            let tempX = tile.x;
            let tempY = tile.y;
            tile.style.left = (emptyX * 100) + "px";
            tile.style.top = (emptyY * 100) + "px";
            tile.x = emptyX;
            tile.y = emptyY;
            emptyX = tempX;
            emptyY = tempY;
        }
    }

    function updateMovableTiles() {
        tiles.forEach(tile => {
            if (isMovable(tile.x, tile.y)) {
                tile.classList.add("movablepiece");
            } else {
                tile.classList.remove("movablepiece");
            }
        });
    }

    tiles.forEach(tile => {
        tile.addEventListener("click", () => {
            moveTile(tile);
            updateMovableTiles();
        });
    });

    document.getElementById("shufflebutton").onclick = function () {
        for (let i = 0; i < 300; i++) {
            let neighbors = tiles.filter(tile => isMovable(tile.x, tile.y));
            if (neighbors.length > 0) {
                let rand = neighbors[Math.floor(Math.random() * neighbors.length)];
                moveTile(rand);
            }
        }
        updateMovableTiles();
    };

    updateMovableTiles();
};
