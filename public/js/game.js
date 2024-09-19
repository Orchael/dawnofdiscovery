let playerState = {};

function updateGameState() {
    fetch('api.php?action=get_game_state')
        .then(response => response.json())
        .then(data => {
            playerState = data;
            updateDisplay();
        });
}

function updateDisplay() {
    document.getElementById('status-bar').innerHTML = `
        <span>Credits: ${playerState.credits}</span>
        <span>System: ${playerState.current_system}</span>
        <span>Ship: ${playerState.ship}</span>
    `;

    document.getElementById('system-info').innerHTML = `
        <h2>Current System: ${playerState.name}</h2>
        <p>Economy: ${playerState.economy_type}</p>
        <p>Security: ${playerState.security_level}</p>
        <p>Coordinates: (${playerState.x_coord}, ${playerState.y_coord}, ${playerState.z_coord})</p>
    `;

    document.getElementById('player-info').innerHTML = `
        <h2>Player Info</h2>
        <p>Ship: ${playerState.ship}</p>
        <p>Credits: ${playerState.credits}</p>
    `;

    document.getElementById('action-menu').innerHTML = `
        <button onclick="travel()">Travel</button>
        <button onclick="trade()">Trade</button>
        <button onclick="explore()">Explore</button>
    `;
}

function travel() {
    const destination = prompt("Enter destination system name:");
    if (destination) {
        fetch('api.php?action=travel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `destination=${encodeURIComponent(destination)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                alert(data.message);
                updateGameState();
            }
        });
    }
}

function trade() {
    fetch('api.php?action=trade')
        .then(response => response.json())
        .then(data => {
            alert(`${data.message}. Profit: ${data.profit} credits`);
            updateGameState();
        });
}

function explore() {
    fetch('api.php?action=explore')
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.credits_reward) {
                alert(`You earned ${data.credits_reward} credits!`);
            }
            updateGameState();
        });
}

// Poll for updates every 5 seconds
setInterval(updateGameState, 5000);

// Initial game state update
updateGameState();