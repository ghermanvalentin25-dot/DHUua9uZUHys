require('dotenv').config();
const express = require('express');
const fetch = require('node-fetch');

const app = express();
app.use(express.json());


function requireApiKey(req, res, next) {
  const key = req.header('x-api-key');
  if (!key || key !== process.env.API_KEY) return res.status(401).json({ error: 'unauthorized' });
  next();
}


app.post('/api/forward', requireApiKey, async (req, res) => {
  try {
    const payload = req.body;
    const discordResp = await fetch(process.env.DISCORD_WEBHOOK_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });

    if (!discordResp.ok) {
      const text = await discordResp.text();
      return res.status(502).json({ error: 'discord_error', details: text });
    }

    res.json({ status: 'sent' });
  } catch (err) {
    res.status(500).json({ error: err.message });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
