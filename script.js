const playButton = document.getElementById('playButton');
const frontPage = document.getElementById('frontPage');
const gamePage = document.querySelector('.container');

playButton.addEventListener('click', () => {
    frontPage.style.display = 'none';
    gamePage.style.display = 'block';
    createBoard();
    startTimer();
});

const cardImages = [
    'assets/honda.png', 'assets/honda.png',
    'assets/yamaha.png', 'assets/yamaha.png',
    'assets/suzuki.png', 'assets/suzuki.png',
    'assets/kawasaki.png', 'assets/kawasaki.png',
    'assets/ducati.png', 'assets/ducati.png',
    'assets/bmw.png', 'assets/bmw.png',
    'assets/harley.png', 'assets/harley.png',
    'assets/vespa.png', 'assets/vespa.png'
];

let turn = 0;
let Flipped = [];
let Matched = [];

let timer;
let timeLeft = 90;

const gameBoard = document.getElementById('gameBoard');
const turnDisplay = document.getElementById('turn');
const timerDisplay = document.getElementById('timer');
const resetButton = document.getElementById('resetButton');
const modal = document.getElementById('congratsModal');
const closeModal = document.getElementById('closeModal');
const playAgainButton = document.getElementById('playAgainButton');

const flipSound = new Audio('assets/flip.mp3');
const matchSound = new Audio('assets/match.mp3');

function shuffle(array){
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
}

function createBoard(){
    const shuffledCards = shuffle(cardImages);
    gameBoard.innerHTML = '';
    modal.style.display = 'none';

    shuffledCards.forEach((image) => {
        const card = document.createElement('div');
        card.classList.add('card');
        card.dataset.image = image;

        const front = document.createElement('div');
        front.classList.add('front');
        const img = document.createElement('img');
        img.src = image;

        front.appendChild(img);
        card.appendChild(front);

        gameBoard.appendChild(card);
        card.addEventListener('click', flipCard);
    });
}

function flipCard(){
    if(Flipped.length < 2 && !this.classList.contains('flipped')){
        this.classList.add('flipped');
        flipSound.play();
        Flipped.push(this);

        if(Flipped.length === 2){
            checkForMatch();
        }
    }
}

function checkForMatch(){
    const [firstCard, secondCard] = Flipped;

    turn += 1;
    turnDisplay.innerText = `Turns : ${turn}`;

    if(firstCard.dataset.image === secondCard.dataset.image){
        Matched.push(firstCard, secondCard);
        matchSound.play();

        firstCard.classList.add('shake');
        secondCard.classList.add('shake');
        setTimeout(() => {
            firstCard.classList.remove('shake');
            secondCard.classList.remove('shake');
        }, 500);

        resetFlipped();

        if(Matched.length === cardImages.length){
            clearInterval(timer);
            setTimeout(() => {
                modal.style.display = 'flex';
                modal.querySelector('p').innerText = `Congratulations! You've matched all the cards in ${turn} turns!`;
                highestTurn(turn);
            }, 500);
        }
    }else{
        setTimeout(() => {
            firstCard.classList.remove('flipped');
            secondCard.classList.remove('flipped');
            resetFlipped();
        }, 1000);
    }
}

function startTimer(){
    timeLeft = 90;
    timerDisplay.innerText = `Time : ${timeLeft}s`;

    timer = setInterval(() => {
        timeLeft--;
        timerDisplay.innerText = `Time : ${timeLeft}s`;

        if(timeLeft <= 0){
            clearInterval(timer);
            alert("Time's up! Game will be reset.");
            resetGame();
        }
    }, 1000);
}

function resetFlipped(){
    Flipped = [];
}

function resetGame(){
    clearInterval(timer);
    turn = 0;
    Matched = [];
    turnDisplay.innerText = `Turns : ${turn}`;
    timerDisplay.innerText = `Time : 90s`;
    createBoard();
    startTimer();
}

resetButton.addEventListener('click', () => {
    resetGame();
});

closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

playAgainButton.addEventListener('click', () => {
    resetGame();
});

createBoard();

function highestTurn(turn) {
    fetch(`highest_turn.php?turns=${turn}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log(data.message);
    })
    .catch(error => {
        console.error('Error updating highest turn:', error);
    });
}

document.getElementById("deleteButton").addEventListener("click", function () {
    const confirmation = confirm("Are you sure you want to delete your account?");
    if (confirmation) {
        window.location.href = "delete_account.php";
    }
});
