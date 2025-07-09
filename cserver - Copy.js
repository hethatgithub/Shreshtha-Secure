const express = require('express');
const mongoose = require('mongoose');
const bodyParser = require('body-parser');
const cors = require('cors');

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// MongoDB connection
mongoose.connect('mongodb://localhost:27017/contactform', {
  useNewUrlParser: true,
  useUnifiedTopology: true,
});

// Define schema and model
const messageSchema = new mongoose.Schema({
  email: String,
  message: String,
  submittedAt: { type: Date, default: Date.now },
});

const Message = mongoose.model('Message', messageSchema);

// POST route
app.post('/save', async (req, res) => {
  const { email, message } = req.body;

  if (!email || !message) {
    return res.status(400).send('Missing email or message');
  }

  try {
    const newMessage = new Message({ email, message });
    await newMessage.save();
    res.status(200).send('Message saved successfully!');
  } catch (err) {
    console.error('Error saving message:', err);
    res.status(500).send('Failed to save message');
  }
});

// Start server
app.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}`);
});
