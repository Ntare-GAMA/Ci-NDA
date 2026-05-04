/* ================================================================
   Ci-NDA — API Compatibility Shim
   Makes legacy api.* calls work via cinda-app.js mock fetch
   ================================================================ */
window.api = {
  getCourses:      function(f){ return fetch('api/courses.php').then(function(r){return r.json();}); },
  getMentors:      function(f){ return fetch('api/mentors.php').then(function(r){return r.json();}); },
  getOpportunities:function(f){ return fetch('api/opportunities.php').then(function(r){return r.json();}); },
  getPortfolios:   function(f){ return fetch('api/portfolios.php').then(function(r){return r.json();}); },
  enrollInCourse:  function(id){ return fetch('api/enroll.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({course_id:id})}).then(function(r){return r.json();}); },
  applyOpportunity:function(d){ return fetch('api/opportunity-applications.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(function(r){return r.json();}); },
  requestMentorship:function(d){ return fetch('api/mentorship-requests.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(function(r){return r.json();}); },
  uploadPortfolio: function(d){ return fetch('api/portfolios.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(function(r){return r.json();}); },
  getMessages:     function(withId){ return fetch('/api/messages.php?with='+withId).then(function(r){return r.json();}); },
  sendMessage:     function(d){ return fetch('/api/messages.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(function(r){return r.json();}); },
  login:           function(d){ return fetch('api/auth.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(function(r){return r.json();}); },
  register:        function(d){ return fetch('api/register.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(d)}).then(function(r){return r.json();}); },
  getCurrentUser:  function(){ return fetch('api/me.php').then(function(r){return r.json();}); }
};
