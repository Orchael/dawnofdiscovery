$(document).ready(function() {
    let currentSystem = null;

    function updateSystemInfo(systemInfo) {
        $('#system-info').html(`
            <h2>${systemInfo.name}</h2>
            <p>Type: ${systemInfo.type}</p>
            <p>Economy: ${systemInfo.economyType}</p>
            <p>Security: ${systemInfo.securityLevel}</p>
        `);

        $('#planets').empty();
        systemInfo.planets.forEach(planet => {
            $('#planets').append(`
                <div class="planet">
                    <h3>${planet.name}</h3>
                    <p>Type: ${planet.type}</p>
                    <p>Size: ${planet.size}</p>
                    <p>Orbit: ${planet.orbit}</p>
                    <p>Habitable: ${planet.habitable ? 'Yes' : 'No'}</p>
                </div>
            `);
        });
    }

    function updateMarketPrices(prices) {
        $('#market').empty();
        prices.forEach(item => {
            $('#market').append(`
                <div class="market-item">
                    <span>${item.name}</span>
                    <span>${item.price} credits</span>
                    <button onclick="buy(${item.id})">Buy</button>
                    <button onclick="sell(${item.id})">Sell</button>
                </div>
            `);
        });
    }

    function scanSystem() {
        $.post('api.php', {action: 'scanSystem'}, function(response) {
            if (response.success) {
                currentSystem = response.systemInfo;
                updateSystemInfo(currentSystem);
            } else {
                alert(response.message);
            }
        });
    }

    function travel() {
        const targetSystemId = $('#target-system').val();
        $.post('api.php', {action: 'travel', targetSystemId: targetSystemId}, function(response) {
            if (response.success) {
                currentSystem = response.newSystem;
                updateSystemInfo(currentSystem);
                alert(response.message);
            } else {
                alert(response.message);
            }
        });
    }

    function getMarketPrices() {
        $.post('api.php', {action: 'getMarketPrices'}, function(response) {
            if (response.success) {
                updateMarketPrices(response.prices);
            } else {
                alert(response.message);
            }
        });
    }

    function buy(commodityId) {
        const quantity = prompt("How many units do you want to buy?");
        $.post('api.php', {action: 'trade', commodityId: commodityId, quantity: quantity, isBuying: true}, function(response) {
            alert(response.message);
            if (response.success) {
                getMarketPrices();
            }
        });
    }

    function sell(commodityId) {
        const quantity = prompt("How many units do you want to sell?");
        $.post('api.php', {action: 'trade', commodityId: commodityId, quantity: quantity, isBuying: false}, function(response) {
            alert(response.message);
            if (response.success) {
                getMarketPrices();
            }
        });
    }

    // Initial setup
    scanSystem();
    getMarketPrices();

    // Bind events
    $('#scan-system').click(scanSystem);
    $('#travel').click(travel);
    $('#refresh-market').click(getMarketPrices);
});
