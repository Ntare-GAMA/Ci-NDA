const API_BASE_URL = 'http://localhost:5000/api';

class CindaAPI {
  constructor() {
    this.token = localStorage.getItem('authToken');
  }

  getHeaders() {
    const headers = { 'Content-Type': 'application/json' };
    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`;
    }
    return headers;
  }

  async request(endpoint, options = {}) {
    try {
      const response = await fetch(`${API_BASE_URL}${endpoint}`, {
        ...options,
        headers: this.getHeaders(),
        credentials: 'include'
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Request failed');
      }

      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  // Auth Methods
  async register(userData) {
    const data = await this.request('/auth/register', {
      method: 'POST',
      body: JSON.stringify(userData)
    });
    this.saveAuth(data);
    return data;
  }

  async login(credentials) {
    const data = await this.request('/auth/login', {
      method: 'POST',
      body: JSON.stringify(credentials)
    });
    this.saveAuth(data);
    return data;
  }

  async socialLogin(provider, userData) {
    const data = await this.request('/auth/social-login', {
      method: 'POST',
      body: JSON.stringify({ ...userData, provider })
    });
    this.saveAuth(data);
    return data;
  }

  async logout() {
    await this.request('/auth/logout', { method: 'POST' });
    this.clearAuth();
  }

  saveAuth(data) {
    this.token = data.token;
    localStorage.setItem('authToken', data.token);
    localStorage.setItem('user', JSON.stringify(data.user));
    sessionStorage.setItem('userLoggedIn', 'true');
    sessionStorage.setItem('userType', data.user.userType);
    sessionStorage.setItem('userEmail', data.user.email);
    sessionStorage.setItem('userName', data.user.name);
  }

  clearAuth() {
    this.token = null;
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
    sessionStorage.clear();
  }

  // Course Methods
  async getCourses(filters = {}) {
    const params = new URLSearchParams(filters);
    return await this.request(`/courses?${params}`);
  }

  async getCourse(id) {
    return await this.request(`/courses/${id}`);
  }

  async enrollInCourse(courseId) {
    return await this.request(`/courses/${courseId}/enroll`, {
      method: 'POST'
    });
  }

  // Opportunity Methods
  async getOpportunities(filters = {}) {
    const params = new URLSearchParams(filters);
    return await this.request(`/opportunities?${params}`);
  }

  async applyToOpportunity(opportunityId, coverLetter) {
    return await this.request(`/opportunities/${opportunityId}/apply`, {
      method: 'POST',
      body: JSON.stringify({ coverLetter })
    });
  }

  // User Methods
  async getProfile() {
    return await this.request('/users/profile');
  }

  async updateProfile(profileData) {
    return await this.request('/users/profile', {
      method: 'PUT',
      body: JSON.stringify(profileData)
    });
  }
}

// Global API instance
const api = new CindaAPI();

// Check authentication on page load
window.addEventListener('DOMContentLoaded', () => {
  const protectedPages = ['profile.html', 'signin.html'];
  const currentPage = window.location.pathname.split('/').pop();
  
  if (protectedPages.includes(currentPage)) {
    checkAuthentication();
  }
});

function checkAuthentication() {
  const token = localStorage.getItem('authToken');
  const currentPage = window.location.pathname.split('/').pop();
  
  if (!token && currentPage === 'profile.html') {
    alert('Please sign in to access your profile');
    window.location.href = 'authentication.html';
  }
}
