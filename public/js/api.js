// Lightweight API client for the PHP backend (relative `api/` endpoints)
class APIClient {
  constructor() {
    this.token = localStorage.getItem('cinda_token') || null;
  }
  setAuth(token){ this.token = token; localStorage.setItem('cinda_token', token); }
  clearAuth(){ this.token = null; localStorage.removeItem('cinda_token'); sessionStorage.clear(); }
  getHeaders(includeAuth=true){
    const h = {'Content-Type':'application/json'};
    if(includeAuth && this.token) h['Authorization']=`Bearer ${this.token}`;
    return h;
  }
  async login(email,password){
    const res = await fetch('api/auth.php',{method:'POST',headers:this.getHeaders(false),body:JSON.stringify({email,password})});
    if(!res.ok) throw new Error('Login failed');
    const data = await res.json(); if(data.token) this.setAuth(data.token); return data;
  }
  async logout(){ try{ await fetch('api/logout.php',{method:'POST',headers:this.getHeaders()}); }catch(e){} this.clearAuth(); }
  async getMentors(){ const r=await fetch('api/mentors.php',{headers:this.getHeaders(false)}); if(!r.ok) throw new Error('Failed'); return r.json(); }
  async getCourses(){ const r=await fetch('api/courses.php',{headers:this.getHeaders(false)}); if(!r.ok) throw new Error('Failed'); return r.json(); }
  async getOpportunities(){ const r=await fetch('api/opportunities.php',{headers:this.getHeaders(false)}); if(!r.ok) throw new Error('Failed'); return r.json(); }
  async getPortfolios(){ const r=await fetch('api/portfolios.php',{headers:this.getHeaders(false)}); if(!r.ok) throw new Error('Failed'); return r.json(); }
  async createPortfolio(payload){ const r=await fetch('api/portfolios.php',{method:'POST',headers:this.getHeaders(),body:JSON.stringify(payload)}); if(!r.ok) throw new Error('Failed'); return r.json(); }
  async enroll(courseId){ const r=await fetch('api/enroll.php',{method:'POST',headers:this.getHeaders(),body:JSON.stringify({courseId})}); if(!r.ok) throw new Error('Enroll failed'); return r.json(); }
}
const api = new APIClient(); if(typeof window!=='undefined') window.api = api; if(typeof module!=='undefined'&&module.exports) module.exports = api;