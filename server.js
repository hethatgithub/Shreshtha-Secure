const express = require("express");
const mongoose = require("mongoose");
const bodyParser = require("body-parser");
const cors = require("cors");

const app = express();
const PORT = 3000;

// Middleware
app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// MongoDB Connection
mongoose.connect("mongodb://localhost:27017", {
  useNewUrlParser: true,
  useUnifiedTopology: true,
});
const db = mongoose.connection;
db.on("error", console.error.bind(console, "MongoDB connection error:"));
db.once("open", () => console.log("MongoDB connected"));

// Mongoose Schema
const leadSchema = new mongoose.Schema({
  fullname: String,
  countrycode: String,
  mobile: String,
  email: String,
  city: String,
  service: String,
  networth: String,
  referral: String,
  submittedAt: { type: Date, default: Date.now },
});

const Lead = mongoose.model("Lead", leadSchema);

// POST route
app.post("/submit", async (req, res) => {
  try {
    const lead = new Lead(req.body);
    await lead.save();
    res.status(200).json({ message: "Lead saved successfully!" });
  } catch (err) {
    console.error("Error saving lead:", err);
    res.status(500).json({ message: "Error saving lead" });
  }
});

// Start Server
app.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}`);
});
