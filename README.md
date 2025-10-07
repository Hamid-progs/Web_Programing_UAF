<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>University Web Development Projects — Repository</title>
  <meta name="description" content="A central, searchable gallery for storing and previewing university web development projects." />
  <style>
    :root{
      --bg:#0f1724; --card:#0b1220; --muted:#9aa4b2; --accent:#58a6ff; --glass: rgba(255,255,255,0.03);
      --success:#18b57a; --danger:#ff6b6b; --radius:12px; --maxw:1100px;
    }
    *{box-sizing:border-box;font-family:Inter,ui-sans-serif,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial}
    body{margin:0; background:linear-gradient(180deg,#071025 0%, #071a2a 70%); color:#e6eef6; -webkit-font-smoothing:antialiased}
    .wrap{max-width:var(--maxw); margin:36px auto; padding:28px}
    header{display:flex;align-items:center;justify-content:space-between;gap:18px}
    h1{margin:0;font-size:20px}
    p.lead{margin:4px 0 0;color:var(--muted);font-size:13px}
    .controls{display:flex;gap:10px;align-items:center}
    input[type=text], select{background:var(--card);border:1px solid rgba(255,255,255,0.04);padding:10px 12px;border-radius:10px;color:inherit}
    button{background:var(--accent);border:none;color:#00172a;padding:10px 12px;border-radius:10px;font-weight:600;cursor:pointer}
    button.ghost{background:transparent;border:1px solid rgba(255,255,255,0.04);color:var(--muted)}
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:16px;margin-top:22px}
    .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01)); padding:14px;border-radius:var(--radius);box-shadow:0 6px 18px rgba(2,6,23,0.6);border:1px solid rgba(255,255,255,0.03)}
    .card h3{margin:0 0 6px;font-size:15px}
    .meta{font-size:12px;color:var(--muted)}
    .tags{margin-top:10px;display:flex;gap:8px;flex-wrap:wrap}
    .tag{font-size:11px;padding:6px 8px;border-radius:999px;background:var(--glass);color:var(--muted)}
    .card .actions{margin-top:12px;display:flex;gap:8px}
    .empty{padding:36px;border-radius:12px;background:linear-gradient(90deg, rgba(255,255,255,0.015), rgba(255,255,255,0.01));text-align:center;color:var(--muted)}
    footer{margin-top:20px;color:var(--muted);font-size:13px;text-align:center}
    /* Form drawer */
    .drawer{position:fixed;right:18px;bottom:18px}
    .fab{width:56px;height:56px;border-radius:50%;display:inline-grid;place-items:center;font-weight:700;background:linear-gradient(180deg,var(--accent),#2e8bff);box-shadow:0 10px 30px rgba(46,139,255,0.2);border:none;cursor:pointer}
    .modal{position:fixed;right:18px;bottom:90px;width:360px;max-width:calc(100% - 48px);background:var(--card);padding:14px;border-radius:12px;border:1px solid rgba(255,255,255,0.03);box-shadow:0 18px 40px rgba(2,6,23,0.6)}
    label{display:block;font-size:13px;color:var(--muted);margin-top:8px}
    textarea{min-height:90px;padding:10px;border-radius:8px;background:transparent;color:inherit;border:1px solid rgba(255,255,255,0.04)}
    .row{display:flex;gap:8px}
    .small{font-size:12px;color:var(--muted)}
    .search-highlight{background:linear-gradient(90deg, rgba(88,166,255,0.1), rgba(88,166,255,0.06));padding:6px;border-radius:8px}
    @media (max-width:640px){header{flex-direction:column;align-items:flex-start} .controls{width:100%;flex-wrap:wrap} .wrap{padding:16px}}
  </style>
</head>
<body>
  <div class="wrap" id="app">
    <header>
      <div>
        <h1>University Web Development Projects</h1>
        <p class="lead">A single place to store, preview, and share your course projects. Saved locally to this repo (or GitHub Pages) — start adding projects below.</p>
      </div>
      <div class="controls">
        <input id="q" type="text" placeholder="Search by title, description, tag..." />
        <select id="filter">
          <option value="all">All courses & years</option>
          <option value="web">Web</option>
          <option value="ml">Machine Learning</option>
          <option value="db">Database</option>
        </select>
        <button id="clear" class="ghost">Clear</button>
      </div>
    </header>

    <section id="results" style="margin-top:18px">
      <div id="grid" class="grid"></div>
      <div id="empty" class="empty" style="display:none">No projects yet — click the + button to add your first project.</div>
    </section>

    <footer>
      Tip: This page stores your projects in your browser (localStorage). To keep them in your Git repository, export the JSON from the Settings menu and commit it to the repo (instructions below).
    </footer>

    <!-- Floating action + modal -->
    <div class="drawer">
      <button id="fab" class="fab" title="Add project">+</button>
      <div id="modal" class="modal" style="display:none">
        <h3 style="margin:0">Add / Edit Project</h3>
        <label>Title <input id="title" type="text" placeholder="Project title"/></label>
        <label>Short description <textarea id="desc" placeholder="One or two sentences"></textarea></label>
        <label>Demo / Repo URL <input id="url" type="text" placeholder="https://github.com/you/project"/></label>
        <label>Tags (comma separated) <input id="tags" type="text" placeholder="web, html, css, final-project"/></label>
        <div style="display:flex;gap:8px;margin-top:12px;">
          <button id="save">Save</button>
          <button id="cancel" class="ghost">Cancel</button>
        </div>
        <div style="margin-top:8px;font-size:12px;color:var(--muted)">Saved projects are stored in your browser. Use <span class="small">Export / Import</span> (in the settings menu) to move them into a file for the repo.</div>
      </div>
    </div>
  </div>

  <script>
    /*
      index.html — University Web Development Projects gallery
      - Stores projects in localStorage under key 'uwp_projects'
      - Allows add / edit / delete, search and simple filtering
      - To persist to repo: Export JSON (copy-paste into a file like projects.json in repo)

      Feel free to ask me to:
      - convert this to a small React app
      - add GitHub API integration to fetch live README / demo links
      - create a printable README.md for your repo automatically
    */

    const STORAGE_KEY = 'uwp_projects_v1';

    const defaultProjects = [
      {id: cryptoRandomId(), title:'Portfolio Website', desc:'Personal portfolio built for CS-301 showing projects and resume.', url:'#', tags:['web','html','css']},
      {id: cryptoRandomId(), title:'Library Management (C#)', desc:'Windows Forms app to manage library books and users (DB project).', url:'#', tags:['db','csharp']},
      {id: cryptoRandomId(), title:'ML: Heart Disease Classifier', desc:'Jupyter + scikit-learn notebook predicting heart disease.', url:'#', tags:['ml','python','notebook']}
    ];

    function cryptoRandomId(){return 'p_' + Math.random().toString(36).slice(2,9)}

    function readProjects(){
      try{
        const raw = localStorage.getItem(STORAGE_KEY);
        if(!raw) return defaultProjects.slice();
        return JSON.parse(raw);
      }catch(e){console.error(e); return defaultProjects.slice();}
    }

    function saveProjects(list){ localStorage.setItem(STORAGE_KEY, JSON.stringify(list)); }

    // UI helpers
    const grid = document.getElementById('grid');
    const emptyBox = document.getElementById('empty');
    const q = document.getElementById('q');
    const filter = document.getElementById('filter');
    const modal = document.getElementById('modal');
    const fab = document.getElementById('fab');
    const titleI = document.getElementById('title');
    const descI = document.getElementById('desc');
    const urlI = document.getElementById('url');
    const tagsI = document.getElementById('tags');
    const saveBtn = document.getElementById('save');
    const cancelBtn = document.getElementById('cancel');
    const clearBtn = document.getElementById('clear');

    let projects = readProjects();
    let editingId = null;

    function render(list){
      grid.innerHTML = '';
      if(!list.length){ emptyBox.style.display='block'; return }
      emptyBox.style.display='none';
      for(const p of list){
        const el = document.createElement('div'); el.className='card';
        el.innerHTML = `
          <div class="search-highlight"><h3>${escapeHtml(p.title)}</h3><div class="meta">${escapeHtml(p.desc)}</div></div>
          <div class="tags">${(p.tags||[]).map(t=>`<span class="tag">${escapeHtml(t)}</span>`).join('')}</div>
          <div class="actions">
            <a class="ghost" href="${escapeAttr(p.url||'#')}" target="_blank">View</a>
            <button data-edit="${p.id}" class="ghost">Edit</button>
            <button data-delete="${p.id}" class="ghost">Delete</button>
          </div>
        `;
        grid.appendChild(el);
      }
    }

    function escapeHtml(s){ if(!s) return ''; return s.replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;'); }
    function escapeAttr(s){ if(!s) return '#'; return s.replaceAll('\"','%22'); }

    function filterAndRender(){
      const qv = q.value.trim().toLowerCase();
      const fv = filter.value;
      const filtered = projects.filter(p=>{
        if(fv!=='all' && !(p.tags||[]).includes(fv)) return false;
        if(!qv) return true;
        const hay = (p.title + ' ' + p.desc + ' ' + (p.tags||[]).join(' ')).toLowerCase();
        return hay.includes(qv);
      });
      render(filtered);
    }

    // events
    q.addEventListener('input', debounce(filterAndRender,200));
    filter.addEventListener('change', filterAndRender);
    clearBtn.addEventListener('click', ()=>{ q.value=''; filter.value='all'; filterAndRender(); });

    fab.addEventListener('click', ()=>{ openModal(); });
    cancelBtn.addEventListener('click', ()=>{ closeModal(); });
    saveBtn.addEventListener('click', ()=>{ saveFromModal(); });

    grid.addEventListener('click', e=>{
      const editId = e.target.getAttribute('data-edit');
      const delId = e.target.getAttribute('data-delete');
      if(editId) openModal(editId);
      if(delId) {
        if(confirm('Delete this project?')){
          projects = projects.filter(p=>p.id!==delId); saveProjects(projects); filterAndRender();
        }
      }
    });

    function openModal(id){
      editingId = id || null;
      if(id){
        const p = projects.find(x=>x.id===id);
        titleI.value = p.title; descI.value = p.desc; urlI.value = p.url || ''; tagsI.value = (p.tags||[]).join(', ');
      } else { titleI.value=''; descI.value=''; urlI.value=''; tagsI.value=''; }
      modal.style.display='block';
    }
    function closeModal(){ modal.style.display='none'; editingId = null; }

    function saveFromModal(){
      const t = titleI.value.trim(); if(!t){ alert('Please add a title'); return }
      const d = descI.value.trim(); const u = urlI.value.trim(); const tags = tagsI.value.split(',').map(x=>x.trim()).filter(Boolean);
      if(editingId){
        projects = projects.map(p=> p.id===editingId ? {...p, title:t, desc:d, url:u, tags:tags} : p);
      } else {
        projects.unshift({id:cryptoRandomId(), title:t, desc:d, url:u, tags:tags});
      }
      saveProjects(projects); closeModal(); filterAndRender();
    }

    // initial render
    filterAndRender();

    // small utilities
    function debounce(fn,ms=100){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), ms); }}

    // export/import helpers for repo persistence
    window.exportProjectsToJson = function(){ const data = JSON.stringify(projects, null, 2); prompt('Copy the JSON below and save as projects.json in your repo', data); }
    window.importProjectsFromJson = function(){ const raw = prompt('Paste projects.json content here to import');
      try{ const arr = JSON.parse(raw); if(Array.isArray(arr)){ projects = arr; saveProjects(projects); filterAndRender(); alert('Imported '+arr.length+' projects'); } else alert('Invalid JSON: expected an array'); }
      catch(e){ alert('Invalid JSON: ' + e.message); }
    }

    // keyboard shortcuts
    window.addEventListener('keydown', e=>{ if(e.key==='/' && document.activeElement.tagName!=='INPUT' && document.activeElement.tagName!=='TEXTAREA'){ e.preventDefault(); q.focus(); }});
  </script>

  <!--
    Repo tips (paste into your README.md):

    # University Web Development Projects
    This repository contains student web projects. Open `index.html` in the browser to view the local gallery. To persist projects to the repo:
    1. Click the Settings > Export (use browser console: exportProjectsToJson())
    2. Save the output into `projects.json` in the repo root.
    3. Commit the file and optionally add a GitHub Pages workflow to publish the `index.html`.

    Want me to:
     - generate a ready-to-commit `projects.json` from your project list,
     - create a GitHub Actions workflow to publish this page to GitHub Pages,
     - convert this into a React/Vite app with markdown-driven project cards?
  -->
</body>
</html>
