
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ガチャシステム</title>
</head>
<body>

<h1>ガチャ</h1>

<button onclick="drawGacha(1)">単発ガチャ</button>
<button onclick="drawGacha(10)">10連ガチャ</button>

<h2>ガチャ結果</h2>
<div id="results"></div>

<hr>

<h2>所持アイテム</h2>
<button onclick="loadInventory()">所持アイテム更新</button>
<div id="inventory"></div>

<script>
async function drawGacha(count) {

    const formData = new FormData();
    formData.append('count', count);

    const response = await fetch('gacha.php', {
        method: 'POST',
        body: formData
    });

    const data = await response.json();

    if (!response.ok) {
        alert(data.error || 'ガチャに失敗しました');
        return;
    }

    const results = document.getElementById('results');
    results.replaceChildren();

    data.results.forEach(item => {
        const div = document.createElement('div');

        div.textContent =
            item.rarity + ' : ' + item.item_name;

        results.appendChild(div);
    });

    loadInventory();
}

async function loadInventory() {

    const response = await fetch('inventory.php');
    const items = await response.json();

    const inventory = document.getElementById('inventory');
    inventory.replaceChildren();

    items.forEach(item => {
        const div = document.createElement('div');

        div.textContent =
            item.rarity + ' : ' +
            item.name + ' × ' +
            item.quantity;

        inventory.appendChild(div);
    });
}

loadInventory();
</script>

</body>
</html>
