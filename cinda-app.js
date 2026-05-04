/* ================================================================
   Ci-NDA — Master Application Layer
   Intercepts all API calls with rich mock data, manages auth state,
   provides shared UI (navbar, toasts, modals) across all 26 pages.
   ================================================================ */
(function () {
  'use strict';

  /* ──────────────────────────────────────────────
     MOCK DATA
  ────────────────────────────────────────────── */
  const MOCK_USERS = {
    'filmmaker@cinda.com': { password: 'filmmaker123', _id: 'u1', userType: 'filmmaker', name: 'Ntare GAMA Allan', email: 'filmmaker@cinda.com', bio: 'Cinematographer & entrepreneur based in Kigali, Rwanda. Founder of KCAMEL Productions and GAMA TT.', location: 'Kigali, Rwanda', website: 'https://kcamel.rw', skills: ['Cinematography','Directing','Lighting Design','Screenwriting','Visual Effects'], avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80' },
    'mentor@cinda.com':    { password: 'mentor123',    _id: 'u2', userType: 'mentor',    name: 'Sarah Mitchell',    email: 'mentor@cinda.com',    bio: 'Award-winning cinematographer with 15 years on feature films.', location: 'Los Angeles, CA', website: '', skills: ['Cinematography','Lighting','Color Grading'], avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&q=80' },
    'sponsor@cinda.com':   { password: 'sponsor123',   _id: 'u3', userType: 'sponsor',   name: 'David Chen',        email: 'sponsor@cinda.com',   bio: 'Film fund manager supporting emerging African filmmakers.', location: 'New York, NY', website: 'https://africafilmfund.org', skills: [], avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80' }
  };

  const MOCK_COURSES = [
    { _id:'c1', title:'Introduction to Cinematography', category:'Cinematography', level:'Beginner', duration:'12 weeks', description:'Master camera work, lighting, and visual storytelling through hands-on projects and real-world case studies from leading DPs.', instructor:{name:'Sarah Mitchell'}, rating:4.8, students:1240, image:'https://images.unsplash.com/photo-1574267432644-f74f3e909713?w=600&q=80', price:'Free', tags:['Camera','Lighting','Composition'] },
    { _id:'c2', title:'Film Editing Masterclass', category:'Post-Production', level:'Intermediate', duration:'8 weeks', description:'Professional editing techniques — from basic cuts to complex colour grading, audio mixing, and narrative pacing used in award-winning films.', instructor:{name:'James Chen'}, rating:4.9, students:876, image:'https://images.unsplash.com/photo-1536240478700-b869070f9279?w=600&q=80', price:'Free', tags:['Editing','Premiere','DaVinci'] },
    { _id:'c3', title:'Directing Actors', category:'Directing', level:'Advanced', duration:'10 weeks', description:'Bring out authentic, powerful performances using proven directing techniques, rehearsal strategies, and the language of mise-en-scène.', instructor:{name:'Maria Rodriguez'}, rating:4.7, students:543, image:'https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=600&q=80', price:'Free', tags:['Directing','Performance','Story'] },
    { _id:'c4', title:'Sound Design for Film', category:'Sound', level:'Intermediate', duration:'6 weeks', description:'Craft immersive audio from Foley artistry to spatial audio mixing that transforms visuals into a fully cinematic experience.', instructor:{name:'David Thompson'}, rating:4.6, students:432, image:'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?w=600&q=80', price:'Free', tags:['Audio','Foley','Mixing'] },
    { _id:'c5', title:'Screenwriting Essentials', category:'Writing', level:'Beginner', duration:'14 weeks', description:'Craft compelling narratives using industry-standard formatting and story structure that captivate from page one to fade out.', instructor:{name:'Elena Vasquez'}, rating:4.8, students:1102, image:'https://images.unsplash.com/photo-1455390582262-044cdead277a?w=600&q=80', price:'Free', tags:['Script','Narrative','Story'] },
    { _id:'c6', title:'Documentary Filmmaking', category:'Documentary', level:'Intermediate', duration:'8 weeks', description:'From ethical research and verité techniques to impact distribution — make documentaries that challenge, inform, and change minds.', instructor:{name:'Alex Park'}, rating:4.9, students:678, image:'https://images.unsplash.com/photo-1492619375914-88005aa9e8fb?w=600&q=80', price:'Free', tags:['Documentary','Research','Distribution'] },
    { _id:'c7', title:'Visual Effects & Compositing', category:'VFX', level:'Advanced', duration:'16 weeks', description:'Build professional VFX shots from scratch — green screen, motion tracking, particle systems, and multi-layer compositing.', instructor:{name:'Marcus Williams'}, rating:4.7, students:389, image:'https://images.unsplash.com/photo-1626544827763-d516dce335e2?w=600&q=80', price:'Free', tags:['VFX','Compositing','Motion'] },
    { _id:'c8', title:'Animation Production Pipeline', category:'Animation', level:'Intermediate', duration:'12 weeks', description:'Master the full animation pipeline — concept art, storyboarding, rigging, character animation, and final compositing for 2D and 3D projects.', instructor:{name:'Nadia Osei'}, rating:4.8, students:521, image:'https://images.unsplash.com/photo-1611162617474-5b21e879e113?w=600&q=80', price:'Free', tags:['Animation','2D','3D'] }
  ];

  const MOCK_MENTORS = [
    { _id:'m1', name:'Sarah Mitchell', title:'Award-winning Cinematographer', bio:'Former DP on 3 Sundance features. Teaches at USC Film School. Passionate about emerging voices in African cinema.', specialties:['Cinematography','Lighting Design','Color Grading'], experience:'15 years', available:5, rating:4.9, sessions:48, image:'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80', hourlyRate:0 },
    { _id:'m2', name:'James Chen', title:'Emmy-nominated Film Editor', bio:'Cut over 30 documentaries and feature films. Former editor at Netflix Documentary division. Mentor to 12 working editors.', specialties:['Film Editing','Documentary','Color Grading'], experience:'12 years', available:3, rating:4.8, sessions:36, image:'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80', hourlyRate:0 },
    { _id:'m3', name:'Maria Rodriguez', title:'Independent Film Director', bio:'Acclaimed director known for intimate character-driven stories. Her work has screened at Cannes, TIFF, and Sundance.', specialties:['Directing','Script Development','Actor Direction'], experience:'10 years', available:2, rating:4.9, sessions:24, image:'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80', hourlyRate:0 },
    { _id:'m4', name:'David Thompson', title:'Hollywood Sound Designer', bio:'Sound designer for major studio productions and streaming platforms including Netflix, Amazon, and Disney+.', specialties:['Sound Design','Mixing','Foley'], experience:'18 years', available:4, rating:4.7, sessions:52, image:'https://images.unsplash.com/photo-1566492031773-4f4e44671857?w=400&q=80', hourlyRate:0 },
    { _id:'m5', name:'Alex Park', title:'Documentary Producer', bio:'Producer of award-winning environmental and social justice documentaries. Former Sundance Creative Advisor.', specialties:['Documentary','Producing','Distribution'], experience:'14 years', available:6, rating:4.8, sessions:41, image:'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80', hourlyRate:0 },
    { _id:'m6', name:'Nadia Osei', title:'Animation Director & VFX Supervisor', bio:'Led animation teams at major studios. Directed award-winning short films that toured international festivals.', specialties:['Animation','VFX','2D/3D Pipeline'], experience:'11 years', available:3, rating:4.9, sessions:29, image:'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80', hourlyRate:0 },
    { _id:'m7', name:'Marcus Williams', title:'VFX Lead & Compositor', bio:'Visual effects lead on blockbuster productions. Pioneer in real-time VFX workflows for independent filmmakers.', specialties:['VFX','Compositing','Motion Graphics'], experience:'13 years', available:4, rating:4.6, sessions:33, image:'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80', hourlyRate:0 },
    { _id:'m8', name:'Elena Vasquez', title:'Screenwriter & Story Consultant', bio:'WGA award-nominated screenwriter whose scripts have been produced by A24, Focus Features, and Participant Media.', specialties:['Screenwriting','Story Development','Pitching'], experience:'9 years', available:5, rating:4.8, sessions:38, image:'https://images.unsplash.com/photo-1551836022-d5d88e9218df?w=400&q=80', hourlyRate:0 }
  ];

  const MOCK_OPPORTUNITIES = [
    { _id:'o1', title:'$50K Short Film Production Grant', type:'grant', company:'Stowe Story Labs', description:'Funding for narrative short films (~10 min). Includes production budget and professional mentorship package.', amount:'$50,000', location:'Worldwide', deadline:'2025-11-15', daysLeft:12, applyUrl:'https://stowestorylabs.org/short-film-production-grant', category:'grant' },
    { _id:'o2', title:'Environmental Documentary Series', type:'job', company:'GreenLens Productions', description:'Seeking cinematographer for 6-part environmental documentary series. Remote collaboration with occasional travel.', amount:'$4,500/month', location:'Remote', deadline:'2025-12-01', daysLeft:28, applyUrl:'#', category:'job' },
    { _id:'o3', title:'African International Film Festival', type:'competition', company:'Pan-African Cinema Alliance', description:'Submit your short film for the premier African cinema festival. Cash prizes and international distribution deals.', amount:'$25,000 prize', location:'Nairobi, Kenya', deadline:'2025-11-30', daysLeft:27, applyUrl:'#', category:'competition' },
    { _id:'o4', title:'Production Assistant — Feature Film', type:'job', company:'Signature Films (LA)', description:'Join a major independent studio production for hands-on set experience working alongside Oscar-nominated crew.', amount:'$600/week', location:'Los Angeles, CA', deadline:'2025-11-20', daysLeft:17, applyUrl:'#', category:'job' },
    { _id:'o5', title:'Sundance Screenwriting Lab', type:'festival', company:'Sundance Institute', description:'Intensive 5-day lab with industry professionals. Workshop your screenplay with working writers and directors.', amount:'Fully Funded', location:'Park City, Utah', deadline:'2025-12-15', daysLeft:42, applyUrl:'https://www.sundance.org/programs/screenwriters-lab', category:'festival' },
    { _id:'o6', title:'Animation Artist Residency', type:'grant', company:'Adobe Foundation', description:'3-month paid residency for animation artists exploring new storytelling formats and experimental techniques.', amount:'$12,000 stipend', location:'San Francisco, CA', deadline:'2025-11-25', daysLeft:22, applyUrl:'#', category:'grant' }
  ];

  const MOCK_PORTFOLIOS = [
    { _id:'p1', title:'Urban Dreams', creator:{name:'Ntare GAMA Allan', id:'u1'}, type:'Short Film', year:2024, views:'1.2K', likes:87, thumbnail:'https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=600&q=80', videoType:'image', description:'A cinematic exploration of Kigali\'s urban transformation — contrasting tradition with modernity through intimate human stories.', tags:['Short Film','Drama','Urban'] },
    { _id:'p2', title:'Spirit of the Blade — Animation Reel', creator:{name:'Kcamel Productions', id:'u1'}, type:'Animation', year:2024, views:'4.8K', likes:312, thumbnail:'https://images.unsplash.com/photo-1608889825205-eebdb9fc5806?w=600&q=80', videoType:'youtube', youtubeId:'VQGCKyvzIM4', description:'Studio animation reel showcasing 2D character animation, fight choreography, and cinematic lighting design.', tags:['Animation','Action','2D'] },
    { _id:'p3', title:'Kigali Stories', creator:{name:'Amara Diallo', id:'u4'}, type:'Documentary', year:2024, views:'2.1K', likes:143, thumbnail:'https://images.unsplash.com/photo-1534951009808-766178b47a4f?w=600&q=80', videoType:'image', description:'Five interconnected stories of resilience, creativity, and hope from the streets and rooftops of Kigali.', tags:['Documentary','Africa','Human Interest'] },
    { _id:'p4', title:'Jujutsu Chronicles — Fan Animation', creator:{name:'Digital Arts Collective', id:'u5'}, type:'Animation', year:2024, views:'8.9K', likes:621, thumbnail:'https://images.unsplash.com/photo-1614853316476-de00d14cb1fc?w=600&q=80', videoType:'youtube', youtubeId:'4A_X-Dvl0ws', description:'Hand-crafted 2D animation inspired by Japanese martial arts aesthetics. 6-month solo project exploring dynamic fight sequences.', tags:['Animation','2D','Action','Fan Art'] },
    { _id:'p5', title:'The Last Harvest', creator:{name:'Elena Vasquez', id:'u8'}, type:'Documentary', year:2023, views:'3.4K', likes:228, thumbnail:'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600&q=80', videoType:'image', description:'Award-winning short documentary following three generations of a Rwandan farming family facing climate change.', tags:['Documentary','Agriculture','Climate'] },
    { _id:'p6', title:'Demon Realm — Animated Short', creator:{name:'Neon Frame Studio', id:'u6'}, type:'Animation', year:2024, views:'6.2K', likes:445, thumbnail:'https://images.unsplash.com/photo-1635805737707-575885ab0820?w=600&q=80', videoType:'youtube', youtubeId:'MGRm4IzK1SQ', description:'A 4-minute animated short blending African mythology with Japanese animation techniques. Premiered at Nairobi Animation Festival 2024.', tags:['Animation','Mythology','Short Film'] },
    { _id:'p7', title:'Neon City', creator:{name:'Marcus Williams', id:'u7'}, type:'Short Film', year:2024, views:'1.8K', likes:109, thumbnail:'https://images.unsplash.com/photo-1542281286-9e0a16bb7366?w=600&q=80', videoType:'image', description:'A noir thriller set in a rain-soaked near-future Nairobi. Cinematography explores Dutch angles and practical neon lighting.', tags:['Short Film','Sci-Fi','Noir'] },
    { _id:'p8', title:'Brotherhood Reborn — Motion Reel', creator:{name:'Ntare GAMA Allan', id:'u1'}, type:'Animation', year:2024, views:'3.1K', likes:267, thumbnail:'https://images.unsplash.com/photo-1577648188599-291bb8b831c3?w=600&q=80', videoType:'youtube', youtubeId:'EM2oFVnZh3E', description:'Motion graphics and title sequence reel for the Brotherhood cinematic universe — blending live action with 2D animated overlays.', tags:['Motion Graphics','Title Design','Animation'] }
  ];

  const MOCK_MESSAGES = {
    m1: [
      { id:1, sender_id:'m1', message:"Hi! I saw your portfolio — your cinematography work is really impressive. Looking forward to our session!", created_at: new Date(Date.now()-86400000*2).toISOString() },
      { id:2, sender_id:'u1', message:"Thank you so much, Sarah! I've been following your work on the Sundance circuit for years. Really excited to get your feedback on my lighting approach.", created_at: new Date(Date.now()-86400000*2+3600000).toISOString() },
      { id:3, sender_id:'m1', message:"Of course! Let's dig into your color palette first — I think there are some opportunities to deepen the visual language. Can you share your reference images for the project?", created_at: new Date(Date.now()-86400000).toISOString() },
      { id:4, sender_id:'u1', message:"Absolutely! I'm going for a contrast between warm golden tones for memory sequences and cold blue-greens for the present day. Very inspired by Villeneuve's approach.", created_at: new Date(Date.now()-3600000).toISOString() }
    ],
    m2: [
      { id:1, sender_id:'m2', message:"Welcome to our mentorship! I'd love to see your current edit — even a rough cut helps me understand where you're heading.", created_at: new Date(Date.now()-86400000*3).toISOString() },
      { id:2, sender_id:'u1', message:"Thanks James! I've attached a 4-minute rough cut. The pacing in the third act feels off to me but I can't pinpoint why.", created_at: new Date(Date.now()-86400000*3+7200000).toISOString() }
    ]
  };

  /* ──────────────────────────────────────────────
     AUTH STATE
  ────────────────────────────────────────────── */
  var Auth = {
    getUser: function () {
      try { return JSON.parse(localStorage.getItem('cinda_user') || 'null'); } catch (e) { return null; }
    },
    setUser: function (user) {
      localStorage.setItem('cinda_user', JSON.stringify(user));
      localStorage.setItem('cinda_token', 'mock-jwt-' + user._id);
    },
    logout: function () {
      localStorage.removeItem('cinda_user');
      localStorage.removeItem('cinda_token');
      sessionStorage.clear();
      window.location.href = 'index.html';
    },
    isLoggedIn: function () { return !!this.getUser(); },
    requireAuth: function () {
      if (!this.isLoggedIn()) {
        window.location.href = 'authentication.html?next=' + encodeURIComponent(window.location.href);
        return false;
      }
      return true;
    }
  };

  /* ──────────────────────────────────────────────
     TOAST NOTIFICATIONS
  ────────────────────────────────────────────── */
  function toast(msg, type) {
    type = type || 'success';
    var el = document.createElement('div');
    el.style.cssText = 'position:fixed;bottom:30px;right:30px;z-index:99999;padding:14px 22px;border-radius:8px;font-size:14px;font-family:\'DM Sans\',sans-serif;font-weight:500;max-width:340px;box-shadow:0 8px 32px rgba(0,0,0,0.4);transform:translateY(20px);opacity:0;transition:all 0.35s cubic-bezier(.22,1,.36,1);line-height:1.5';
    if (type === 'success') { el.style.background = '#1a3a1f'; el.style.border = '1px solid #2d6a35'; el.style.color = '#46d369'; el.innerHTML = '✓ ' + msg; }
    else if (type === 'error') { el.style.background = '#3a0808'; el.style.border = '1px solid #e50914'; el.style.color = '#ff6b6b'; el.innerHTML = '✕ ' + msg; }
    else { el.style.background = '#1a1a2e'; el.style.border = '1px solid rgba(255,255,255,0.1)'; el.style.color = '#fff'; el.innerHTML = 'ℹ ' + msg; }
    document.body.appendChild(el);
    requestAnimationFrame(function() { el.style.transform = 'translateY(0)'; el.style.opacity = '1'; });
    setTimeout(function() { el.style.opacity = '0'; el.style.transform = 'translateY(20px)'; setTimeout(function() { el.remove(); }, 400); }, 3500);
  }
  window.CindaToast = toast;

  /* ──────────────────────────────────────────────
     MOCK API — fetch interceptor
  ────────────────────────────────────────────── */
  function makeResponse(data, status) {
    var body = JSON.stringify(data);
    return new Response(body, { status: status || 200, headers: { 'Content-Type': 'application/json' } });
  }

  var _fetch = window.fetch.bind(window);
  window.fetch = function (url, opts) {
    var s = (typeof url === 'string' ? url : (url && url.url) || String(url));
    opts = opts || {};
    var method = (opts.method || 'GET').toUpperCase();
    var body = {};
    try { if (opts.body) body = JSON.parse(opts.body); } catch (e) {}

    // Only intercept api/ calls
    if (!s.match(/api[\/\\]/)) return _fetch(url, opts);

    return new Promise(function (resolve) {
      setTimeout(function () {

        /* LOGIN */
        if (s.match(/auth\.(php|\/login)/) && method === 'POST') {
          var u = MOCK_USERS[body.email];
          if (u && u.password === body.password) {
            Auth.setUser(u);
            resolve(makeResponse({ success: true, token: 'mock-jwt-' + u._id, user: u }));
          } else {
            // Accept any credentials for demo
            var demo = Object.values(MOCK_USERS)[0];
            var demoUser = Object.assign({}, demo, { name: body.email ? body.email.split('@')[0].replace(/[^a-zA-Z ]/g,' ').replace(/\b\w/g,function(c){return c.toUpperCase()}).trim() : 'Filmmaker', email: body.email || demo.email });
            Auth.setUser(demoUser);
            resolve(makeResponse({ success: true, token: 'mock-jwt-demo', user: demoUser }));
          }
          return;
        }

        /* REGISTER */
        if (s.match(/register/i) && method === 'POST') {
          var newUser = { _id: 'u-' + Date.now(), userType: body.userType || 'filmmaker', name: body.name || 'New Filmmaker', email: body.email || '', bio: '', location: '', skills: [], avatar: null };
          Auth.setUser(newUser);
          resolve(makeResponse({ success: true, user: newUser }));
          return;
        }

        /* LOGOUT */
        if (s.match(/logout/i)) { resolve(makeResponse({ success: true })); return; }

        /* ME */
        if (s.match(/\/me\.php/) || s.match(/\/users\/me/)) {
          var u = Auth.getUser();
          resolve(u ? makeResponse(u) : makeResponse({ error: 'Unauthorized' }, 401));
          return;
        }

        /* COURSES */
        if (s.match(/courses/i) && method === 'GET') {
          resolve(makeResponse(MOCK_COURSES));
          return;
        }

        /* MENTORS */
        if (s.match(/mentors/i) && method === 'GET') {
          resolve(makeResponse(MOCK_MENTORS));
          return;
        }

        /* OPPORTUNITIES */
        if (s.match(/opportunities/i) && method === 'GET') {
          resolve(makeResponse(MOCK_OPPORTUNITIES));
          return;
        }

        /* PORTFOLIOS */
        if (s.match(/portfolios/i)) {
          if (method === 'GET') { resolve(makeResponse(MOCK_PORTFOLIOS)); return; }
          if (method === 'POST') {
            var p = Object.assign({ _id: 'p-' + Date.now(), views: '0', likes: 0 }, body);
            MOCK_PORTFOLIOS.unshift(p);
            resolve(makeResponse(p));
            return;
          }
        }

        /* ENROLL */
        if (s.match(/enroll/i) && method === 'POST') {
          resolve(makeResponse({ success: true, message: 'Enrolled successfully!' }));
          return;
        }

        /* MESSAGES GET */
        if (s.match(/messages/i) && method === 'GET') {
          var withId = s.match(/with=([^&]+)/);
          var msgs = withId ? (MOCK_MESSAGES[withId[1]] || []) : [];
          resolve(makeResponse(msgs));
          return;
        }

        /* MESSAGES POST */
        if (s.match(/messages/i) && method === 'POST') {
          var newMsg = { id: Date.now(), sender_id: body.sender_id, message: body.message, created_at: new Date().toISOString() };
          var key = body.receiver_id;
          if (!MOCK_MESSAGES[key]) MOCK_MESSAGES[key] = [];
          MOCK_MESSAGES[key].push(newMsg);
          resolve(makeResponse({ success: true, message: newMsg }));
          return;
        }

        /* MENTOR APPLICATIONS / MENTORSHIP REQUESTS / OPPORTUNITY APPLICATIONS / UPLOAD */
        if (s.match(/mentor-applications|mentorship-requests|opportunity-applications|upload|become-mentor/i)) {
          resolve(makeResponse({ success: true, message: 'Submitted successfully.' }));
          return;
        }

        /* SEED / DEBUG endpoints — silently succeed */
        if (s.match(/seed|debug|import|setup/i)) {
          resolve(makeResponse({ success: true }));
          return;
        }

        /* Catch-all success */
        resolve(makeResponse({ success: true }));

      }, 180); // realistic latency
    });
  };

  /* ──────────────────────────────────────────────
     NAVBAR — inject consistent nav into every page
  ────────────────────────────────────────────── */
  function updateNavbar() {
    var user = Auth.getUser();
    var navRight = document.querySelector('.nav-right');
    if (!navRight) return;

    if (user) {
      var initials = user.name ? user.name.split(' ').map(function(w){return w[0];}).join('').slice(0,2).toUpperCase() : 'U';
      navRight.innerHTML =
        '<a href="messages.html" style="color:rgba(255,255,255,.75);font-size:14px;text-decoration:none;position:relative">✉ Messages<span id="msgBadge" style="display:none;position:absolute;top:-6px;right:-10px;background:#e50914;color:#fff;font-size:10px;font-weight:700;border-radius:50%;width:16px;height:16px;line-height:16px;text-align:center">2</span></a>' +
        '<div style="position:relative" id="userMenuWrap">' +
        '<div onclick="document.getElementById(\'userDropdown\').classList.toggle(\'show\')" style="width:38px;height:38px;border-radius:6px;background:#e50914;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;cursor:pointer;user-select:none" title="' + user.name + '">' + initials + '</div>' +
        '<div id="userDropdown" style="display:none;position:absolute;top:50px;right:0;background:#1a1a22;border:1px solid rgba(229,9,20,.3);border-radius:8px;min-width:200px;box-shadow:0 8px 32px rgba(0,0,0,.6);z-index:9999">' +
        '<div style="padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.07);font-size:13px;color:#aaa">Signed in as<br><strong style="color:#fff;font-size:14px">' + user.name + '</strong></div>' +
        '<a href="profile.html" style="display:block;padding:12px 18px;font-size:13px;color:rgba(255,255,255,.8);text-decoration:none;border-bottom:1px solid rgba(255,255,255,.05);transition:background .2s" onmouseover="this.style.background=\'rgba(229,9,20,.1)\'" onmouseout="this.style.background=\'\'">👤 My Profile</a>' +
        '<a href="create-portfolio.html" style="display:block;padding:12px 18px;font-size:13px;color:rgba(255,255,255,.8);text-decoration:none;border-bottom:1px solid rgba(255,255,255,.05);transition:background .2s" onmouseover="this.style.background=\'rgba(229,9,20,.1)\'" onmouseout="this.style.background=\'\'">🎬 Upload Project</a>' +
        '<a href="settings.html" style="display:block;padding:12px 18px;font-size:13px;color:rgba(255,255,255,.8);text-decoration:none;border-bottom:1px solid rgba(255,255,255,.05);transition:background .2s" onmouseover="this.style.background=\'rgba(229,9,20,.1)\'" onmouseout="this.style.background=\'\'">⚙ Settings</a>' +
        '<div onclick="CindaAuth.logout()" style="padding:12px 18px;font-size:13px;color:#e50914;cursor:pointer;transition:background .2s" onmouseover="this.style.background=\'rgba(229,9,20,.1)\'" onmouseout="this.style.background=\'\'">🚪 Sign Out</div>' +
        '</div></div>';
      // Show message badge
      setTimeout(function(){ var b = document.getElementById('msgBadge'); if(b) b.style.display = 'block'; }, 1000);
    } else {
      navRight.innerHTML = '<a href="authentication.html" class="btn-signin">Sign In</a>';
    }

    // Close dropdown on outside click
    document.addEventListener('click', function(e){
      var wrap = document.getElementById('userMenuWrap');
      var dd = document.getElementById('userDropdown');
      if (dd && wrap && !wrap.contains(e.target)) dd.classList.remove('show');
    });
  }

  // Add show style dynamically
  var ddStyle = document.createElement('style');
  ddStyle.textContent = '#userDropdown.show{display:block!important}';
  document.head.appendChild(ddStyle);

  /* ──────────────────────────────────────────────
     PROTECTED PAGES
  ────────────────────────────────────────────── */
  var PROTECTED = ['profile.html','create-portfolio.html','messages.html','edit-profile.html','settings.html','upload-project.html','become-mentor.html'];
  var currentPage = window.location.pathname.split('/').pop() || 'index.html';
  if (PROTECTED.indexOf(currentPage) !== -1) Auth.requireAuth();

  /* ──────────────────────────────────────────────
     ADMIN AUTH
  ────────────────────────────────────────────── */
  if (currentPage === 'admin-dashboard.html') {
    var adminAuth = localStorage.getItem('cinda_admin');
    if (!adminAuth) { window.location.href = 'admin-signin.html'; }
  }

  /* ──────────────────────────────────────────────
     EXPOSE GLOBALS
  ────────────────────────────────────────────── */
  window.CindaAuth = Auth;
  window.CindaData = { courses: MOCK_COURSES, mentors: MOCK_MENTORS, opportunities: MOCK_OPPORTUNITIES, portfolios: MOCK_PORTFOLIOS };

  /* ──────────────────────────────────────────────
     PAGE-SPECIFIC INITIALISATION
  ────────────────────────────────────────────── */
  document.addEventListener('DOMContentLoaded', function () {
    updateNavbar();
    initPage(currentPage);
  });

  function initPage(page) {

    /* ── INDEX ── */
    if (page === 'index.html' || page === '' || page === '/') {
      if (Auth.isLoggedIn()) {
        var hero = document.querySelector('.hero h1');
        if (hero) hero.innerHTML = 'Welcome Back,<br>' + Auth.getUser().name.split(' ')[0];
      }
      return;
    }

    /* ── AUTHENTICATION ── */
    if (page === 'authentication.html') {
      if (Auth.isLoggedIn()) { window.location.href = 'profile.html'; return; }
      var form = document.getElementById('signinForm');
      if (form) {
        form.addEventListener('submit', function(e) {
          e.preventDefault();
          var email = (document.getElementById('email') || {}).value || '';
          var password = (document.getElementById('password') || {}).value || '';
          var btn = document.getElementById('signinBtn');
          var loading = document.getElementById('loading');
          if (btn) btn.disabled = true;
          if (loading) loading.style.display = 'block';
          fetch('api/auth.php', { method:'POST', headers:{'Content-Type':'application/json'}, body: JSON.stringify({email, password}) })
            .then(function(r){ return r.json(); })
            .then(function(data){
              if (data.token) {
                toast('Welcome back, ' + (data.user.name || 'Filmmaker') + '!');
                setTimeout(function(){ window.location.href = 'profile.html'; }, 800);
              }
            })
            .catch(function(){ if(btn) btn.disabled=false; if(loading) loading.style.display='none'; });
        }, { once: true });
      }
      return;
    }

    /* ── SIGNUP ── */
    if (page === 'signup.html') {
      if (Auth.isLoggedIn()) { window.location.href = 'profile.html'; return; }
      var sf = document.getElementById('signupForm');
      if (sf) {
        sf.addEventListener('submit', function(e){
          e.preventDefault();
          var name = (document.getElementById('name')||{}).value || '';
          var email = (document.getElementById('email')||{}).value || '';
          var password = (document.getElementById('password')||{}).value || '';
          var userType = (document.getElementById('userType')||{}).value || 'filmmaker';
          fetch('api/register.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({name,email,password,userType})})
            .then(function(r){return r.json();})
            .then(function(data){ if(data.success){ toast('Account created! Welcome to Ci-NDA!'); setTimeout(function(){ window.location.href='profile.html'; },800); }});
        }, { once: true });
      }
      return;
    }

    /* ── COURSES ── */
    if (page === 'courses.html') {
      fetch('api/courses.php')
        .then(function(r){return r.json();})
        .then(function(courses){ if(typeof renderCourses==='function') renderCourses(courses); })
        .catch(function(){});
      return;
    }

    /* ── MENTORSHIP ── */
    if (page === 'mentorship.html') {
      fetch('api/mentors.php')
        .then(function(r){return r.json();})
        .then(function(mentors){ if(typeof renderMentors==='function') renderMentors(mentors); })
        .catch(function(){});
      return;
    }

    /* ── PORTFOLIOS ── */
    if (page === 'portfolios.html') {
      fetch('api/portfolios.php')
        .then(function(r){return r.json();})
        .then(function(ps){ if(typeof renderPortfolios==='function') renderPortfolios(ps); })
        .catch(function(){});
      return;
    }

    /* ── PROFILE ── */
    if (page === 'profile.html') {
      var u = Auth.getUser();
      if (!u) return;
      var nameEl = document.querySelector('.profile-name');
      var roleEl = document.querySelector('.profile-role');
      var bioEl  = document.querySelector('.profile-bio');
      var avatarEl = document.querySelector('.profile-image');
      if (nameEl) nameEl.textContent = u.name;
      if (roleEl) roleEl.textContent = u.userType ? u.userType.charAt(0).toUpperCase()+u.userType.slice(1) : 'Filmmaker';
      if (bioEl) bioEl.textContent = u.bio || 'Filmmaker based in Kigali, Rwanda.';
      if (avatarEl && u.avatar) { avatarEl.style.backgroundImage = 'url('+u.avatar+')'; avatarEl.textContent = ''; }
      // Welcome banner
      var wb = document.querySelector('.welcome-banner h2');
      if (wb) wb.textContent = 'Welcome back, ' + u.name.split(' ')[0] + '!';
      return;
    }

    /* ── ADMIN SIGNIN ── */
    if (page === 'admin-signin.html') {
      var af = document.getElementById('adminLoginForm') || document.querySelector('form');
      if (af) {
        af.addEventListener('submit', function(e){
          e.preventDefault();
          localStorage.setItem('cinda_admin', 'true');
          toast('Admin access granted');
          setTimeout(function(){ window.location.href='admin-dashboard.html'; }, 700);
        }, { once: true });
      }
      return;
    }

    /* ── MESSAGES ── */
    if (page === 'messages.html') {
      var params = new URLSearchParams(window.location.search);
      var withId = params.get('with') || 'm1';
      var mentorName = decodeURIComponent(params.get('mentorName') || 'Sarah Mitchell');
      var nameEl = document.getElementById('mentorName');
      if (nameEl) nameEl.textContent = mentorName;
      // Load messages
      fetch('/api/messages.php?with=' + withId)
        .then(function(r){return r.json();})
        .then(function(msgs){
          var container = document.getElementById('messagesContainer');
          if (!container) return;
          if (!msgs.length) { container.innerHTML = '<div style="text-align:center;color:#666;padding:40px">No messages yet. Start the conversation!</div>'; return; }
          var currentUser = Auth.getUser() || {};
          container.innerHTML = msgs.map(function(m){
            var isSent = (m.sender_id === (currentUser._id || 'u1'));
            var time = new Date(m.created_at).toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'});
            return '<div class="message ' + (isSent?'sent':'received') + '"><div>' + escHtml(m.message) + '</div><div class="message-time">' + time + '</div></div>';
          }).join('');
          container.scrollTop = container.scrollHeight;
        })
        .catch(function(){});
      return;
    }
  }

  function escHtml(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

})();
