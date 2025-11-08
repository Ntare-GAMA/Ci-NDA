// API Client for CI-NDA Platform
const API_BASE_URL = 'http://localhost:5000/api/opportunities';

class APIClient {
  constructor() {
    this.token = localStorage.getItem('authToken');
  }

  // Helper method to get headers
  getHeaders(includeAuth = true) {
    const headers = {
      'Content-Type': 'application/json'
    };
    
    if (includeAuth && this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }
    
    return headers;
  }

  // Helper method to handle responses
  async handleResponse(response) {
    const data = await response.json();
    
    if (!response.ok) {
      throw new Error(data.message || 'Request failed');
    }
    
    return data;
  }

  // Store auth token
  setAuth(token) {
    this.token = token;
    localStorage.setItem('authToken', token);
  }

  // Clear auth
  clearAuth() {
    this.token = null;
    localStorage.removeItem('authToken');
    sessionStorage.clear();
  }

  // Check if user is authenticated
  isAuthenticated() {
    return !!this.token;
  }

  // ========== AUTH ENDPOINTS ==========
  
  async register(userData) {
    const response = await fetch(`${API_BASE_URL}/auth/register`, {
      method: 'POST',
      headers: this.getHeaders(false),
      body: JSON.stringify(userData)
    });
    
    const data = await this.handleResponse(response);
    this.setAuth(data.token);
    return data;
  }

  async login(credentials) {
    const response = await fetch(`${API_BASE_URL}/auth/login`, {
      method: 'POST',
      headers: this.getHeaders(false),
      body: JSON.stringify(credentials)
    });
    
    const data = await this.handleResponse(response);
    this.setAuth(data.token);
    
    // Store user info in sessionStorage
    sessionStorage.setItem('userLoggedIn', 'true');
    sessionStorage.setItem('userEmail', data.user.email);
    sessionStorage.setItem('userType', data.user.userType);
    sessionStorage.setItem('userName', data.user.name);
    
    return data;
  }

  async socialLogin(provider, userData) {
    const response = await fetch(`${API_BASE_URL}/auth/social-login`, {
      method: 'POST',
      headers: this.getHeaders(false),
      body: JSON.stringify(userData)
    });
    
    const data = await this.handleResponse(response);
    this.setAuth(data.token);
    
    // Store user info
    sessionStorage.setItem('userLoggedIn', 'true');
    sessionStorage.setItem('userEmail', data.user.email);
    sessionStorage.setItem('userType', data.user.userType);
    sessionStorage.setItem('userName', data.user.name);
    sessionStorage.setItem('socialLogin', provider);
    
    return data;
  }

  async logout() {
    try {
      await fetch(`${API_BASE_URL}/auth/logout`, {
        method: 'POST',
        headers: this.getHeaders()
      });
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      this.clearAuth();
    }
  }

  // ========== USER ENDPOINTS ==========
  
  async getProfile() {
    const response = await fetch(`${API_BASE_URL}/users/profile`, {
      headers: this.getHeaders()
    });
    
    return await this.handleResponse(response);
  }

  async updateProfile(profileData) {
    const response = await fetch(`${API_BASE_URL}/users/profile`, {
      method: 'PUT',
      headers: this.getHeaders(),
      body: JSON.stringify(profileData)
    });
    
    return await this.handleResponse(response);
  }

  // ========== COURSES ENDPOINTS ==========
  
  async getCourses(filters = {}) {
    const queryParams = new URLSearchParams(filters);
    const response = await fetch(`${API_BASE_URL}/courses?${queryParams}`, {
      headers: this.getHeaders(false)
    });
    
    return await this.handleResponse(response);
  }

  async getCourse(courseId) {
    const response = await fetch(`${API_BASE_URL}/courses/${courseId}`, {
      headers: this.getHeaders(false)
    });
    
    return await this.handleResponse(response);
  }

  async enrollInCourse(courseId) {
    const response = await fetch(`${API_BASE_URL}/courses/${courseId}/enroll`, {
      method: 'POST',
      headers: this.getHeaders()
    });
    
    return await this.handleResponse(response);
  }

  // ========== OPPORTUNITIES ENDPOINTS ==========
  
  async getOpportunities(filters = {}) {
    const queryParams = new URLSearchParams(filters);
    const response = await fetch(`${API_BASE_URL}/opportunities?${queryParams}`, {
      headers: this.getHeaders(false)
    });
    
    return await this.handleResponse(response);
  }

  async applyToOpportunity(opportunityId, coverLetter) {
    const response = await fetch(`${API_BASE_URL}/opportunities/${opportunityId}/apply`, {
      method: 'POST',
      headers: this.getHeaders(),
      body: JSON.stringify({ coverLetter })
    });
    
    return await this.handleResponse(response);
  }

  // ========== MENTORSHIP ENDPOINTS ==========
  
  async getMentors(filters = {}) {
    const queryParams = new URLSearchParams(filters);
    const response = await fetch(`${API_BASE_URL}/mentorship?${queryParams}`, {
      headers: this.getHeaders(false)
    });
    
    return await this.handleResponse(response);
  }

  async requestMentorship(mentorId, message) {
    const response = await fetch(`${API_BASE_URL}/mentorship/request`, {
      method: 'POST',
      headers: this.getHeaders(),
      body: JSON.stringify({ mentorId, message })
    });
    
    return await this.handleResponse(response);
  }

  // ========== PORTFOLIOS ENDPOINTS ==========
  
  async getPortfolios(filters = {}) {
    const queryParams = new URLSearchParams(filters);
    const response = await fetch(`${API_BASE_URL}/portfolios?${queryParams}`, {
      headers: this.getHeaders(false)
    });
    
    return await this.handleResponse(response);
  }

  async createPortfolio(portfolioData) {
    const response = await fetch(`${API_BASE_URL}/portfolios`, {
      method: 'POST',
      headers: this.getHeaders(),
      body: JSON.stringify(portfolioData)
    });
    
    return await this.handleResponse(response);
  }

  async likePortfolio(portfolioId) {
    const response = await fetch(`${API_BASE_URL}/portfolios/${portfolioId}/like`, {
      method: 'POST',
      headers: this.getHeaders()
    });
    
    return await this.handleResponse(response);
  }
}

// Create and export a single instance
const api = new APIClient();

// For browser usage
if (typeof window !== 'undefined') {
  window.api = api;
}

// For Node.js usage
if (typeof module !== 'undefined' && module.exports) {
  module.exports = api;
}