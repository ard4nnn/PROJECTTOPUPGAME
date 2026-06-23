// Calculator Panel Switcher & Logic
var currentLang = window.currentLang || 'id';

// Panel switcher logic
function switchCalcPanel(panelName) {
    document.querySelectorAll('.calc-menu-card').forEach(card => {
        card.classList.remove('active');
    });
    document.getElementById('menu-' + panelName).classList.add('active');

    document.querySelectorAll('.calc-panel').forEach(panel => {
        panel.classList.remove('active');
    });
    document.getElementById('panel-' + panelName).classList.add('active');
}

// Win Rate Calculator
function calculateWR() {
    const matches = parseInt(document.getElementById('wr-matches').value);
    const currentWr = parseFloat(document.getElementById('wr-current').value);
    const targetWr = parseFloat(document.getElementById('wr-target').value);

    const resultCard = document.getElementById('result-wr-card');
    const resultText = document.getElementById('result-wr-text');

    if (isNaN(matches) || isNaN(currentWr) || isNaN(targetWr) || matches <= 0) {
        alert(currentLang === 'id' ? 'Harap isi semua field dengan angka valid!' : 'Please fill all fields with valid numbers!');
        return;
    }

    if (currentWr >= 100 || targetWr >= 100 || currentWr <= 0 || targetWr <= 0) {
        alert(currentLang === 'id' ? 'Win rate harus berada di antara 0% dan 100%!' : 'Win rate must be between 0% and 100%!');
        return;
    }

    if (targetWr <= currentWr) {
        alert(currentLang === 'id' ? 'Target Win Rate harus lebih besar dari Win Rate saat ini!' : 'Target Win Rate must be greater than current Win Rate!');
        return;
    }

    const m = matches;
    const w = currentWr / 100;
    const t = targetWr / 100;

    const x = Math.ceil((m * (t - w)) / (1 - t));

    if (x === Infinity || isNaN(x)) {
        resultText.innerHTML = currentLang === 'id' ? 'Perhitungan tidak valid.' : 'Invalid calculation.';
    } else {
        let template = currentLang === 'id'
            ? 'Anda memerlukan sekitar <strong>{matches}</strong> kemenangan beruntun (tanpa kalah) untuk mencapai target win rate <strong>{target}%</strong>.'
            : 'You need around <strong>{matches}</strong> consecutive wins (without losing) to reach target win rate of <strong>{target}%</strong>.';

        resultText.innerHTML = template.replace('{matches}', x).replace('{target}', targetWr);
    }

    resultCard.style.display = 'block';
}

// Magic Wheel Calculator
function calculateMW() {
    const points = parseInt(document.getElementById('mw-points').value);
    const resultCard = document.getElementById('result-mw-card');
    const resultText = document.getElementById('result-mw-text');

    if (isNaN(points) || points < 0 || points >= 200) {
        alert(currentLang === 'id' ? 'Poin Magic Wheel harus berada di antara 0 dan 199!' : 'Magic Wheel points must be between 0 and 199!');
        return;
    }

    const remainingPoints = 200 - points;
    const drawsOfFive = Math.ceil(remainingPoints / 5);
    const diamondsNeeded = drawsOfFive * 270;
    const estCost = diamondsNeeded * 270;

    let template = currentLang === 'id'
        ? 'Sisa poin yang dibutuhkan: <strong>{points}</strong> poin.<br>Estimasi diamond yang dibutuhkan: sekitar <strong>{diamonds}</strong> Diamond (menggunakan opsi 5x draw seharga 270 diamond).<br>Estimasi biaya: <strong>Rp {cost}</strong> (kurs estimasi Rp 270/Diamond).'
        : 'Remaining points needed: <strong>{points}</strong> points.<br>Estimated diamonds needed: around <strong>{diamonds}</strong> Diamonds (using 5x draw option for 270 diamonds).<br>Estimated cost: <strong>IDR {cost}</strong> (estimated rate IDR 270/Diamond).';

    resultText.innerHTML = template
        .replace('{points}', remainingPoints)
        .replace('{diamonds}', diamondsNeeded.toLocaleString('id-ID'))
        .replace('{cost}', estCost.toLocaleString('id-ID'));

    resultCard.style.display = 'block';
}

// Zodiac Slider real-time calculation
function updateZodiacValue(val) {
    document.getElementById('zodiac-points-display').textContent = val;
    const remaining = 100 - parseInt(val);
    const maxDiamonds = remaining * 17;
    document.getElementById('zodiac-diamonds-display').textContent = maxDiamonds;
}
