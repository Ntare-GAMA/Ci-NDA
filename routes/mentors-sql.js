const express = require('express');
const router = express.Router();
const { pool } = require('../db-node');

// GET /api/mentors  or /api/mentors?id=1
router.get('/', async (req, res) => {
  try {
    const id = req.query.id;
    if (id) {
      const [rows] = await pool.execute('SELECT * FROM mentors WHERE id = ?', [id]);
      if (!rows || rows.length === 0) return res.status(404).json({ message: 'Mentor not found' });
      // normalize field names to match frontend expectations
      const m = rows[0];
      return res.json(m);
    }

    const [rows] = await pool.execute('SELECT * FROM mentors ORDER BY id ASC LIMIT 100');
    return res.json(rows);
  } catch (err) {
    console.error('Error fetching mentors (MySQL):', err);
    res.status(500).json({ message: 'Error fetching mentors' });
  }
});

module.exports = router;
