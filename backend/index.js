require('dotenv').config()
const express = require('express')
const cors = require('cors')
const mysql = require('mysql2')

const app = express()
app.use(cors())
app.use(express.json())

const db = mysql.createConnection({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASS,
  database: process.env.DB_NAME,
})

app.post('/submit-score', (req, res) => {
  const { score } = req.body
  db.query('INSERT INTO scores (score) VALUES (?)', [score], (err, result) => {
    if (err) return res.status(500).send(err)
    res.send({ success: true })
  })
})

app.listen(3000, () => console.log('API running on port 3000'))
