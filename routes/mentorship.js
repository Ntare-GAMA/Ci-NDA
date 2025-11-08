const express = require('express');
const router = express.Router();
const Mentorship = require('../models/Mentorship');
const User = require('../models/User');
const auth = require('../middleware/auth');

// Get all mentors
router.get('/', async (req, res) => {
  try {
    const { specialty } = req.query;
    let query = { userType: 'mentor' };

    const mentors = await User.find(query).select('-password');
    
    res.json(mentors);
  } catch (error) {
    console.error('Error fetching mentors:', error);
    res.status(500).json({ message: 'Error fetching mentors' });
  }
});

// Request mentorship
router.post('/request', auth, async (req, res) => {
  try {
    const { mentorId, message } = req.body;

    const mentor = await User.findById(mentorId);
    if (!mentor || mentor.userType !== 'mentor') {
      return res.status(404).json({ message: 'Mentor not found' });
    }

    // Check if mentorship already exists
    const existingMentorship = await Mentorship.findOne({
      mentor: mentorId,
      mentee: req.userId,
      status: { $in: ['pending', 'active'] }
    });

    if (existingMentorship) {
      return res.status(400).json({ message: 'Mentorship request already exists' });
    }

    const mentorship = new Mentorship({
      mentor: mentorId,
      mentee: req.userId,
      status: 'pending',
      messages: [{
        sender: req.userId,
        content: message || 'I would like to request mentorship'
      }]
    });

    await mentorship.save();

    res.json({ message: 'Mentorship request sent successfully', mentorship });
  } catch (error) {
    console.error('Mentorship request error:', error);
    res.status(500).json({ message: 'Error sending mentorship request' });
  }
});

// Get user's mentorships
router.get('/my-mentorships', auth, async (req, res) => {
  try {
    const mentorships = await Mentorship.find({
      $or: [
        { mentor: req.userId },
        { mentee: req.userId }
      ]
    })
    .populate('mentor', 'name email avatar')
    .populate('mentee', 'name email avatar')
    .sort({ createdAt: -1 });

    res.json(mentorships);
  } catch (error) {
    console.error('Error fetching mentorships:', error);
    res.status(500).json({ message: 'Error fetching mentorships' });
  }
});

// Update mentorship status
router.put('/:id/status', auth, async (req, res) => {
  try {
    const { status } = req.body;
    const mentorship = await Mentorship.findById(req.params.id);

    if (!mentorship) {
      return res.status(404).json({ message: 'Mentorship not found' });
    }

    // Only mentor can update status
    if (mentorship.mentor.toString() !== req.userId) {
      return res.status(403).json({ message: 'Not authorized' });
    }

    mentorship.status = status;
    await mentorship.save();

    res.json({ message: 'Mentorship status updated', mentorship });
  } catch (error) {
    console.error('Error updating mentorship:', error);
    res.status(500).json({ message: 'Error updating mentorship' });
  }
});

// Schedule session
router.post('/:id/schedule', auth, async (req, res) => {
  try {
    const { title, scheduledDate, duration, notes } = req.body;
    const mentorship = await Mentorship.findById(req.params.id);

    if (!mentorship) {
      return res.status(404).json({ message: 'Mentorship not found' });
    }

    // Check if user is part of this mentorship
    const isMentor = mentorship.mentor.toString() === req.userId;
    const isMentee = mentorship.mentee.toString() === req.userId;

    if (!isMentor && !isMentee) {
      return res.status(403).json({ message: 'Not authorized' });
    }

    mentorship.sessions.push({
      title,
      scheduledDate: new Date(scheduledDate),
      duration,
      notes
    });

    await mentorship.save();

    res.json({ message: 'Session scheduled successfully', mentorship });
  } catch (error) {
    console.error('Error scheduling session:', error);
    res.status(500).json({ message: 'Error scheduling session' });
  }
});

module.exports = router;