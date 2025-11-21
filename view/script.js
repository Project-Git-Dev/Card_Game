// ========== RESET LOCALSTORAGE BIJ NIEUWE LOGIN ==========
const previousUser = localStorage.getItem("currentUser");

if (previousUser !== currentUser) {
    localStorage.clear();

    if (currentUser !== "gast") {
        localStorage.setItem("playerScore", userStats.score || "0");
        localStorage.setItem("playerLevel", userStats.level || "1");
        localStorage.setItem("extraCards", JSON.stringify([]));
    } else {
        localStorage.setItem("playerScore", "0");
        localStorage.setItem("playerLevel", "1");
        localStorage.setItem("extraCards", JSON.stringify([]));
    }

    localStorage.setItem("currentUser", currentUser);
}

// ========== SAVE PROGRESS TO SERVER ==========
function saveProgressToServer() {
    const level = parseInt(localStorage.getItem("playerLevel")) || 1;
    const score = parseInt(localStorage.getItem("playerScore")) || 0;

    fetch("save_progress.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ level, score }),
    })
        .then(response => response.text())
        .then(data => console.log("Progress opgeslagen:", data))
        .catch(error => console.error("Fout bij opslaan progress:", error));
}

// ========== GAME STATE ==========
let mana = 3;
let aiMana = 3;
let playerHealth = 20;
let aiHealth = 18;
let shieldActive = false;
let temporaryHealth = 0;
let fireImmunityRounds = 0;
let aiStunnedRounds = 0;

let score = parseInt(localStorage.getItem("playerScore")) || 0;
let playerLevel = parseInt(localStorage.getItem("playerLevel")) || 1;
let playerHand = [];
let aiHand = [];

const levelRequirements = {
    1: 100,
    2: 250,
    3: 400
};

const savedExtraCards = JSON.parse(localStorage.getItem("extraCards")) || [];

// ========== STATISTICS UPDATE ==========
function updateStats({ win = false, cardsPlayed = 0, gamesPlayed = false } = {}) {
    fetch('update_stats.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            win,
            cardsPlayed,
            gamesPlayed
        })
    })
        .then(response => response.json())
        .then(data => console.log("Statistieken bijgewerkt:", data))
        .catch(error => console.error("Fout bij bijwerken stats:", error));
}

// ========== CARD CLASSES ==========
class Card {
    constructor(name, type, cost, rarity, image) {
        this.name = name;
        this.type = type;
        this.cost = cost;
        this.rarity = rarity;
        this.image = image;
    }
}

class CreatureCard extends Card {
    constructor(name, cost, attack, rarity, image) {
        super(name, "wezen", cost, rarity, image);
        this.attack = attack;
    }
}

class SpellCard extends Card {
    constructor(name, cost, damage, rarity, image) {
        super(name, "spreuk", cost, rarity, image);
        this.damage = damage;
    }
}

class ArtifactCard extends Card {
    constructor(name, cost, effect, rarity, image) {
        super(name, "artefact", cost, rarity, image);
        this.effect = effect;
    }
}

// ========== CARD LIBRARY ==========
const cards = [
    new CreatureCard("Drakenridder", 5, 7, "Legendary", "6.jpg"),
    new SpellCard("Vuurbal", 3, 4, "Rare", "2.jpg"),
    new ArtifactCard("Green Elixer", 1, "Verdubbelt de mana per beurt.", "Common", "3.jpg"),
    new CreatureCard("Hog Rider", 3, 4, "Epic", "1.jpg"),
    new ArtifactCard("Shield", 2, "Geeft 5 tijdelijke gezondheid en biedt immuniteit.", "Common", "4.jpg"),
    new ArtifactCard("Chicken Wing", 1, "Geeft +2 schade aan Hog Rider als deze wordt gespeeld.", "Common", "7.jpg"),
    new ArtifactCard("Anti Fire Potion", 2, "Geeft immuniteit tegen Vuurbal en Drakenridder voor 2 rondes.", "Rare", "8.jpg"),
    new CreatureCard("Knight", 2, 3, "Common", "9.jpg"),
    new SpellCard("Bliksemschicht", 4, 2, "Epic", "11.jpg"),
];

savedExtraCards.forEach(extraCard => {
    cards.push(Object.assign(new CreatureCard(), extraCard));
});

// ========== GAME FUNCTIONS ==========
function levelUp() {
    const requiredPoints = levelRequirements[playerLevel];
    if (score >= requiredPoints) {
        playerLevel++;
        updateStats({ level: playerLevel });
        localStorage.setItem("playerLevel", playerLevel);
        alert(`Gefeliciteerd! Je bent nu niveau ${playerLevel}!`);

        if (playerLevel === 2) {
            score += 100;
            localStorage.setItem("playerScore", score);
            alert("Je hebt 100 extra punten gekregen!");

            const newCard = new CreatureCard("Gouden Draak", 10, 17, "Legendary", "10.jpg");
            cards.push(newCard);
            savedExtraCards.push(newCard);
            localStorage.setItem("extraCards", JSON.stringify(savedExtraCards));
            alert("Je hebt een nieuwe kaart ontvangen: Gouden Draak!");
        }

        saveProgressToServer();
        updateLevelDisplay();
    }
}

function updateLevelDisplay() {
    document.getElementById("level-count").innerText = playerLevel;
}

function updateManaDisplay() {
    document.getElementById("mana-count").innerText = mana;
}

function updateHealthDisplay() {
    document.getElementById("player-health-count").innerText = playerHealth + temporaryHealth;
    document.getElementById("ai-health-count").innerText = aiHealth;

    document.getElementById("player-health").style.width = ((playerHealth + temporaryHealth) / 20) * 100 + "%";
    document.getElementById("ai-health").style.width = (aiHealth / 20) * 100 + "%";
}

function updateScoreDisplay() {
    document.getElementById("score-count").innerText = score;
}

function drawCards() {
    playerHand = [...cards];
    displayHand();
}

function drawAiCards() {
    aiHand = [...cards];
}

function resetGame() {
    mana = 3;
    aiMana = 3;
    playerHealth = 20;
    aiHealth = 20;
    shieldActive = false;
    temporaryHealth = 0;
    fireImmunityRounds = 0;
    aiStunnedRounds = 0;

    drawCards();
    drawAiCards();
    updateManaDisplay();
    updateHealthDisplay();
    updateScoreDisplay();
}

function hardResetGame() {
    if (confirm("Weet je zeker dat je alles wilt resetten?")) {
        localStorage.clear();
        score = 0;
        playerLevel = 1;
        updateStats({ gamesPlayed: true });
        resetGame();
        updateLevelDisplay();
        saveProgressToServer();
        alert("Spel volledig gereset!");
    }
}

function displayHand() {
    const handDiv = document.getElementById("player-hand");
    handDiv.innerHTML = "";

    playerHand.forEach((card, index) => {
        const cardDiv = document.createElement("div");
        cardDiv.className = `card ${card.rarity.toLowerCase()}`;

        const cardInner = document.createElement("div");
        cardInner.className = "card-inner";
        cardInner.onclick = () => playCard(index);

        const front = document.createElement("div");
        front.className = "card-front";
        front.innerHTML = `
            <img src="${card.image}" alt="${card.name}" style="width:100%; height:auto;">
            <div style="padding: 5px; text-align: center;">
                <p><strong>${card.name}</strong></p>
                <p>Kosten: ${card.cost}</p>
                <p>${card.rarity}</p>
            </div>
        `;

        const back = document.createElement("div");
        back.className = "card-back";
        back.innerHTML = `<p>${getCardDescription(card)}</p>`;

        cardInner.appendChild(front);
        cardInner.appendChild(back);
        cardDiv.appendChild(cardInner);
        handDiv.appendChild(cardDiv);
    });
}

function getCardDescription(card) {
    if (card instanceof CreatureCard) {
        return `Valt aan voor ${card.attack} schade.`;
    } else if (card instanceof SpellCard) {
        if (card.name === "Bliksemschicht") return `Doet ${card.damage} schade en verlamt AI voor 1 beurt.`;
        return `Spreuk: doet ${card.damage} schade.`;
    } else if (card instanceof ArtifactCard) {
        switch (card.name) {
            case "Green Elixer": return "Geeft +2 mana bij gebruik.";
            case "Shield": return "Geeft 5 tijdelijke HP + immuniteit.";
            case "Chicken Wing": return "Geeft +2 aanval aan Hog Rider.";
            case "Anti Fire Potion": return "2 beurten immuun voor vuur.";
            default: return card.effect;
        }
    }
    return "Geen beschrijving beschikbaar.";
}

function playCard(index) {
    const card = playerHand[index];

    if (card.cost > mana) {
        alert("Niet genoeg mana!");
        return;
    }

    mana -= card.cost;

    if (card instanceof ArtifactCard) {
        handleArtifact(card);
    } else if (card instanceof CreatureCard) {
        aiHealth -= card.attack;
        score += card.attack;
        alert(`${card.name} doet ${card.attack} schade!`);
    } else if (card instanceof SpellCard) {
        aiHealth -= card.damage;
        score += card.damage;
        alert(`${card.name} doet ${card.damage} schade!`);
        if (card.name === "Bliksemschicht") {
            aiStunnedRounds = 1;
            alert("De AI is verlamd en slaat zijn beurt over!");
        }
    }

    if (aiHealth <= 0) {
        score += 50;
        alert("De AI is verslagen! +50 punten.");
        levelUp();
        updateStats({ win: true, gamesPlayed: true });
        saveProgressToServer();
        resetGame();
        return;
    }

    playerHand.splice(index, 1);
    localStorage.setItem("playerScore", score);
    updateStats({ score: score });
    updateStats({ cardsPlayed: 1 });
    saveProgressToServer();

    updateManaDisplay();
    updateHealthDisplay();
    updateScoreDisplay();
    displayHand();
}

function handleArtifact(card) {
    switch (card.name) {
        case "Green Elixer":
            mana += 2;
            alert("Green Elixer gebruikt! Je krijgt 2 mana.");
            break;
        case "Shield":
            shieldActive = true;
            temporaryHealth += 5;
            alert("Shield geactiveerd! +5 tijdelijke gezondheid.");
            break;
        case "Chicken Wing":
            const hogRider = playerHand.find(c => c.name === "Hog Rider");
            if (hogRider) {
                hogRider.attack += 2;
                alert("Hog Rider krijgt +2 aanval!");
            } else {
                alert("Geen Hog Rider in hand.");
            }
            break;
        case "Anti Fire Potion":
            fireImmunityRounds = 2;
            alert("Immuniteit tegen vuur voor 2 rondes!");
            break;
        default:
            alert(`${card.name} geactiveerd.`);
    }
}

function applyDamageToPlayer(damage) {
    if (shieldActive) {
        alert("Shield absorbeert de schade!");
        shieldActive = false;
    } else if (fireImmunityRounds > 0) {
        alert("Immuniteit actief – geen schade ontvangen.");
        fireImmunityRounds--;
    } else {
        playerHealth -= damage;
        score -= damage;
    }

    updateHealthDisplay();
    updateScoreDisplay();
}

function aiTurn() {
    if (aiStunnedRounds > 0) {
        alert("AI is verlamd en slaat beurt over.");
        aiStunnedRounds--;
        return;
    }

    const playable = aiHand.filter(card => aiMana >= card.cost);
    if (playable.length > 0) {
        const card = playable[Math.floor(Math.random() * playable.length)];
        aiMana -= card.cost;
        alert(`AI speelt ${card.name}!`);

        if (card instanceof CreatureCard) {
            applyDamageToPlayer(card.attack);
        } else if (card instanceof SpellCard) {
            applyDamageToPlayer(card.damage);
        }

        aiHand.splice(aiHand.indexOf(card), 1);
    } else {
        alert("AI heeft geen speelbare kaarten.");
    }

    if (playerHealth <= 0) {
        alert("Je bent verslagen!");
        resetGame();
    }
}

function enhanceRandomCreature() {
    const creatures = playerHand.filter(c => c instanceof CreatureCard);
    if (creatures.length > 0) {
        const random = creatures[Math.floor(Math.random() * creatures.length)];
        random.attack += 1;
        alert(`${random.name} wordt sterker! +1 aanval.`);
    }
}

// ========== EVENT HANDLERS ==========
document.getElementById("end-turn").addEventListener("click", () => {
    shieldActive = false;
    temporaryHealth = 0;
    mana += 2;
    aiMana += 2;
    drawCards();
    aiTurn();
    enhanceRandomCreature();
    updateManaDisplay();
    updateHealthDisplay();
    saveProgressToServer();
});

document.getElementById("reset-game").addEventListener("click", hardResetGame);

// ========== INIT ==========
updateManaDisplay();
updateScoreDisplay();
updateLevelDisplay();
updateHealthDisplay();
drawCards();
drawAiCards();

// ========== NAV MENU TOGGLE ==========
document.addEventListener("DOMContentLoaded", () => {
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('nav-menu');

    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('visible');
        });
    }
});
