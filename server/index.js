import express from 'express'
import cors from 'cors'
import mysql from 'mysql2'
import dotenv from 'dotenv'

dotenv.config()
const app = express()
app.use(cors())
app.use(express.json())

const db = mysql.createConnection({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASS,
  database: process.env.DB_NAME,
})

app.post('/api/score', (req, res) => {
  const { name, score } = req.body
  if (!name || score == null) return res.status(400).json({ error: 'Invalid' })
  db.query('INSERT INTO scores (name, score) VALUES (?, ?)', [name, score], (err) => {
    if (err) return res.status(500).json({ error: err.message })
    res.json({ success: true })
  })
})

app.listen(3000, () => {
  console.log('Server running on port 3000')
})
