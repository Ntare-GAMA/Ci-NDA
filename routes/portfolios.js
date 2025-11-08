const express = require('express');
const router = express.Router();
const Portfolio = require('../models/Portfolio');
const auth = require('../middleware/auth');

// Get all portfolios
router.get('/', async (req, res) => {
  try {
    const { category, search } = req.query;
    let query = {};

    if (category && category !== 'All') {
      query.category = category;
    }

    if (search) {
      query.$or = [
        { title: { $regex: search, $options: 'i' } },
        { description: { $regex: search, $options: 'i' } },
        { tags: { $in: [new RegExp(search, 'i')] } }
      ];
    }

    const portfolios = await Portfolio.find(query)
      .populate('user', 'name avatar userType')
      .sort({ createdAt: -1 });

    res.json(portfolios);
  } catch (error) {
    console.error('Error fetching portfolios:', error);
    res.status(500).json({ message: 'Error fetching portfolios' });
  }
});

// Get single portfolio
router.get('/:id', async (req, res) => {
  try {
    const portfolio = await Portfolio.findById(req.params.id)
      .populate('user', 'name avatar bio userType')
      .populate('comments.user', 'name avatar');

    if (!portfolio) {
      return res.status(404).json({ message: 'Portfolio not found' });
    }

    // Increment view count
    portfolio.views += 1;
    await portfolio.save();

    res.json(portfolio);
  } catch (error) {
    console.error('Error fetching portfolio:', error);
    res.status(500).json({ message: 'Error fetching portfolio' });
  }
});

// Create portfolio
router.post('/', auth, async (req, res) => {
  try {
    const { title, description, thumbnail, videoUrl, tags, category } = req.body;

    const portfolio = new Portfolio({
      user: req.userId,
      title,
      description,
      thumbnail,
      videoUrl,
      tags,
      category
    });

    await portfolio.save();

    res.status(201).json({ message: 'Portfolio created successfully', portfolio });
  } catch (error) {
    console.error('Error creating portfolio:', error);
    res.status(500).json({ message: 'Error creating portfolio' });
  }
});

// Update portfolio
router.put('/:id', auth, async (req, res) => {
  try {
    const portfolio = await Portfolio.findById(req.params.id);

    if (!portfolio) {
      return res.status(404).json({ message: 'Portfolio not found' });
    }

    // Check ownership
    if (portfolio.user.toString() !== req.userId) {
      return res.status(403).json({ message: 'Not authorized to update this portfolio' });
    }

    const { title, description, thumbnail, videoUrl, tags, category } = req.body;

    portfolio.title = title || portfolio.title;
    portfolio.description = description || portfolio.description;
    portfolio.thumbnail = thumbnail || portfolio.thumbnail;
    portfolio.videoUrl = videoUrl || portfolio.videoUrl;
    portfolio.tags = tags || portfolio.tags;
    portfolio.category = category || portfolio.category;

    await portfolio.save();

    res.json({ message: 'Portfolio updated successfully', portfolio });
  } catch (error) {
    console.error('Error updating portfolio:', error);
    res.status(500).json({ message: 'Error updating portfolio' });
  }
});

// Delete portfolio
router.delete('/:id', auth, async (req, res) => {
  try {
    const portfolio = await Portfolio.findById(req.params.id);

    if (!portfolio) {
      return res.status(404).json({ message: 'Portfolio not found' });
    }

    // Check ownership
    if (portfolio.user.toString() !== req.userId) {
      return res.status(403).json({ message: 'Not authorized to delete this portfolio' });
    }

    await portfolio.deleteOne();

    res.json({ message: 'Portfolio deleted successfully' });
  } catch (error) {
    console.error('Error deleting portfolio:', error);
    res.status(500).json({ message: 'Error deleting portfolio' });
  }
});

// Like portfolio
router.post('/:id/like', auth, async (req, res) => {
  try {
    const portfolio = await Portfolio.findById(req.params.id);

    if (!portfolio) {
      return res.status(404).json({ message: 'Portfolio not found' });
    }

    const likeIndex = portfolio.likes.indexOf(req.userId);

    if (likeIndex > -1) {
      // Unlike
      portfolio.likes.splice(likeIndex, 1);
    } else {
      // Like
      portfolio.likes.push(req.userId);
    }

    await portfolio.save();

    res.json({ 
      message: likeIndex > -1 ? 'Portfolio unliked' : 'Portfolio liked',
      likes: portfolio.likes.length
    });
  } catch (error) {
    console.error('Error liking portfolio:', error);
    res.status(500).json({ message: 'Error liking portfolio' });
  }
});

// Add comment
router.post('/:id/comment', auth, async (req, res) => {
  try {
    const { content } = req.body;
    const portfolio = await Portfolio.findById(req.params.id);

    if (!portfolio) {
      return res.status(404).json({ message: 'Portfolio not found' });
    }

    portfolio.comments.push({
      user: req.userId,
      content
    });

    await portfolio.save();

    // Populate the new comment
    await portfolio.populate('comments.user', 'name avatar');

    res.json({ 
      message: 'Comment added successfully',
      comment: portfolio.comments[portfolio.comments.length - 1]
    });
  } catch (error) {
    console.error('Error adding comment:', error);
    res.status(500).json({ message: 'Error adding comment' });
  }
});

// Get user's portfolios
router.get('/user/:userId', async (req, res) => {
  try {
    const portfolios = await Portfolio.find({ user: req.params.userId })
      .populate('user', 'name avatar')
      .sort({ createdAt: -1 });

    res.json(portfolios);
  } catch (error) {
    console.error('Error fetching user portfolios:', error);
    res.status(500).json({ message: 'Error fetching user portfolios' });
  }
});

module.exports = router;
