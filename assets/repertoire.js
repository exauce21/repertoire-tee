/* Répertoire TÉÉ v2.1 */
(function () {
  "use strict";

  const PROV_SVG = {
    Alberta: "AB",
    "Colombie-Britannique": "CB",
    Manitoba: "MB",
    "Nouveau-Brunswick": "NB",
    "Nouvelle-Écosse": "NE",
    "Terre-Neuve-et-Labrador": "TNL",
    "Île-du-Prince-Édouard": "IPE",
    Ontario: "ON",
    Québec: "QC",
    Saskatchewan: "SK",
    Yukon: "Yukon",
    "Territoires du Nord-Ouest": "TNO",
    Nunavut: "Nunavut",
  };

  const ICO = {
    school: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>`,
    grad: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>`,
    building: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22V12h6v10M9 7h1M14 7h1M9 11h1M14 11h1"/></svg>`,
    pin: `<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>`,
    phone: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.22h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 9.91a16 16 0 0 0 6.1 6.1l.91-.91a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>`,
    mail: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>`,
    globe: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>`,
    user: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`,
    book: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>`,
    star: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
    map: `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/><line x1="8" y1="2" x2="8" y2="18"/><line x1="16" y1="6" x2="16" y2="22"/></svg>`,
    chev: `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="m6 9 6 6 6-6"/></svg>`,
  };

  let allData = [],
    allVilles = [],
    activeTab = {};

  document.addEventListener("DOMContentLoaded", function () {
    if (!document.getElementById("rtee-app")) return;
    loadFiltres();
    loadData();
    bindFilters();
    bindMap();
    sendHeight();
  });

  function loadFiltres() {
    fetch(RTEE.apiUrl + "filtres", { headers: { "X-WP-Nonce": RTEE.nonce } })
      .then((r) => r.json())
      .then((d) => {
        const sel = document.getElementById("rtee-f-province");
        (d.provinces || []).forEach((p) => {
          const o = document.createElement("option");
          o.value = p;
          o.textContent = p;
          sel.appendChild(o);
        });
        allVilles = d.villes || [];
      });
  }

  function loadData(params) {
    const panel = document.getElementById("rtee-liste-panel");
    panel.innerHTML =
      '<div class="rtee-spinner-wrap"><div class="rtee-spinner"></div><span>Chargement…</span></div>';
    let url = RTEE.apiUrl + "etablissements";
    if (params && Object.keys(params).length)
      url += "?" + new URLSearchParams(params);
    fetch(url, { headers: { "X-WP-Nonce": RTEE.nonce } })
      .then((r) => r.json())
      .then((d) => {
        allData = d;
        renderList(d);
        updateMap(d);
        updateChip(d.length);
        setTimeout(sendHeight, 400);
      })
      .catch(() => {
        panel.innerHTML =
          '<div class="rtee-etat-vide"><p>Erreur de chargement.</p></div>';
      });
  }

  function bindFilters() {
    document.getElementById("rtee-btn-search").onclick = doSearch;
    document.getElementById("rtee-btn-reset").onclick = doReset;
    document.getElementById("rtee-f-province").onchange = function () {
      updateVilles(this.value);
      pill("rtee-pill-province", !!this.value);
    };
    ["type", "programme", "ville", "niveau"].forEach((k) => {
      const el = document.getElementById("rtee-f-" + k);
      if (el)
        el.onchange = function () {
          pill("rtee-pill-" + k, !!this.value);
        };
    });
  }

  function bindMap() {
    document.querySelectorAll("#rtee-canada-map .prov-group").forEach((el) => {
      el.addEventListener("click", function (ev) {
        ev.stopPropagation();
        const lbl = this.getAttribute("data-label");
        const sel = document.getElementById("rtee-f-province");
        sel.value = lbl;
        pill("rtee-pill-province", true);
        updateVilles(lbl);
        doSearch();
        highlightProv(this.id.replace("prov-", ""));
      });
      el.addEventListener("mouseenter", function () {
        showInfo(this.getAttribute("data-label"));
      });
      el.addEventListener("mouseleave", function () {
        const p = document.getElementById("rtee-f-province").value;
        p ? showInfo(p) : hideInfo();
      });
    });
    // Clic sur zone vide de la carte = réinitialiser
    document
      .getElementById("rtee-canada-map")
      .addEventListener("click", function () {
        doReset();
      });
  }

  function updateVilles(prov) {
    const sel = document.getElementById("rtee-f-ville");
    sel.innerHTML = '<option value="">Villes</option>';
    if (prov)
      allVilles
        .filter((v) => v.province === prov)
        .forEach((v) => {
          const o = document.createElement("option");
          o.value = v.ville;
          o.textContent = v.ville;
          sel.appendChild(o);
        });
  }

  function pill(id, on) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle("active", on);
  }

  function doSearch() {
    const p = {};
    const t = document.getElementById("rtee-f-type").value;
    const pg = document.getElementById("rtee-f-programme").value;
    const pr = document.getElementById("rtee-f-province").value;
    const v = document.getElementById("rtee-f-ville").value;
    const nv = document.getElementById("rtee-f-niveau").value;
    if (t) p.type = t;
    if (pg) p.programme = pg;
    if (pr) p.province = pr;
    if (v) p.ville = v;
    if (nv) p.niveau = nv;
    loadData(Object.keys(p).length ? p : null);
  }

  function doReset() {
    [
      "rtee-f-type",
      "rtee-f-programme",
      "rtee-f-province",
      "rtee-f-ville",
      "rtee-f-niveau",
    ].forEach((id) => {
      const el = document.getElementById(id);
      if (el) el.value = "";
    });
    [
      "rtee-pill-type",
      "rtee-pill-programme",
      "rtee-pill-province",
      "rtee-pill-ville",
      "rtee-pill-niveau",
    ].forEach((id) => pill(id, false));
    document
      .querySelectorAll("#rtee-canada-map .prov-group")
      .forEach((el) => el.classList.remove("selected"));
    hideInfo();
    document.getElementById("rtee-count-chip").style.display = "none";
    loadData();
  }

  function updateMap(items) {
    const has = new Set(items.map((e) => PROV_SVG[e.province]).filter(Boolean));
    document.querySelectorAll("#rtee-canada-map .prov-group").forEach((el) => {
      const code = el.id.replace("prov-", "");
      el.classList.toggle("dim", !has.has(code));
    });
  }

  function highlightProv(code) {
    document
      .querySelectorAll("#rtee-canada-map .prov-group")
      .forEach((el) => el.classList.remove("selected"));
    const el = document.getElementById("prov-" + code);
    if (el) el.classList.add("selected");
  }

  function showInfo(lbl) {
    const n = allData.filter((e) => e.province === lbl).length;
    document.getElementById("rtee-prov-nom").textContent = lbl;
    document.getElementById("rtee-prov-count").textContent =
      n + " école" + (n !== 1 ? "s" : "") + " dans le réseau TÉÉ";
    document
      .getElementById("rtee-prov-infobox")
      .classList.remove("rtee-hidden");
  }
  function hideInfo() {
    document.getElementById("rtee-prov-infobox").classList.add("rtee-hidden");
  }

  function updateChip(n) {
    const c = document.getElementById("rtee-count-chip");
    c.style.display = "inline-block";
    c.textContent = n + " école" + (n !== 1 ? "s" : "");
  }

  function renderList(items) {
    const panel = document.getElementById("rtee-liste-panel");
    if (!items || !items.length) {
      panel.innerHTML =
        '<div class="rtee-etat-vide">' +
        ICO.map +
        "<p>Aucun établissement trouvé.</p></div>";
      return;
    }
    const byP = {};
    items.forEach((e) => {
      (byP[e.province] = byP[e.province] || []).push(e);
    });
    let html = "";
    Object.entries(byP).forEach(([prov, ecoles], si) => {
      html += `<div class="rtee-section-prov" style="animation-delay:${si * 0.05}s">
      <div class="rtee-section-hdr"><h3>${X(prov)}</h3><span class="rtee-prov-badge">${ecoles.length}</span></div>
      <div>`;
      ecoles.forEach((e, i) => {
        html += card(e, i);
      });
      html += `</div></div>`;
    });
    panel.innerHTML = html;
    panel.querySelectorAll(".rtee-ecole-card").forEach((c) => {
      c.addEventListener("click", (ev) => {
        if (ev.target.closest("a")) return;
        toggleCard(c);
        setTimeout(sendHeight, 300);
      });
    });
    panel.querySelectorAll(".rtee-dtab").forEach((t) => {
      t.addEventListener("click", (ev) => {
        ev.stopPropagation();
        setTab(t.closest(".rtee-ecole-card").dataset.id, t.dataset.tab);
      });
    });
  }

  function card(e, i) {
    const tl = {
      elementaire: "Élémentaire",
      secondaire: "Secondaire",
      mixte: "Mixte 1-12",
    };
    const ti = {
      elementaire: ICO.school,
      secondaire: ICO.grad,
      mixte: ICO.building,
    };
    const pl = { francophone: "Francophone", immersion: "Immersion" };
    const ct = activeTab[e.id] || "tee";
    const site = e.site_web
      ? `<a href="${X(e.site_web)}" target="_blank" rel="noopener">${X(e.site_web.replace(/^https?:\/\//, "").replace(/\/$/, ""))}</a>`
      : "—";
    const nivs = parseNiv(e.niveaux);
    const nivHTML = nivs.length
      ? `<div class="rtee-niv-wrap">${nivs.map((n) => `<span class="rtee-niv-pill">${n}</span>`).join("")}</div>`
      : "<span>—</span>";
    const prog = e.type_programme || "francophone";
    return `
<div class="rtee-ecole-card" data-id="${e.id}" style="animation-delay:${i * 0.035}s">
  <div class="rtee-card-hdr">
    <div class="rtee-ecole-icon ${e.type_etablissement}">${ti[e.type_etablissement]}</div>
    <div class="rtee-ecole-main">
      <div class="rtee-ecole-nom">${X(e.nom)}</div>
      <div class="rtee-ecole-meta">
        <span class="rtee-ecole-loc">${ICO.pin} ${X(e.ville)}</span>
        <span class="rtee-type-tag ${e.type_etablissement}">${tl[e.type_etablissement]}</span>
        <span class="rtee-prog-tag ${prog}">${pl[prog]}</span>
        ${e.niveaux ? `<span class="rtee-niv-tag">${X(cleanNiveaux(e.niveaux))}</span>` : ""}
      </div>
    </div>
    <span class="rtee-arrow">${ICO.chev}</span>
  </div>
  <div class="rtee-details">
    <div class="rtee-dtabs">
      <div class="rtee-dtab ${ct === "tee" ? "active" : ""}" data-tab="tee">${ICO.user} TÉÉ</div>
      <div class="rtee-dtab ${ct === "niveaux" ? "active" : ""}" data-tab="niveaux">${ICO.book} Niveaux</div>
      <div class="rtee-dtab ${ct === "ecole" ? "active" : ""}" data-tab="ecole">${ICO.school} École</div>
    </div>
    <div class="rtee-dtab-content ${ct === "tee" ? "active" : ""}" data-content="tee">
      <div class="rtee-tee-box">
        <div class="rtee-tee-grid">
          <div class="rtee-info-row"><div class="rtee-info-lbl">Nom</div><div class="rtee-info-val">${ICO.user} ${e.coordonnateur_tee || "—"}</div></div>
          <div class="rtee-info-row"><div class="rtee-info-lbl">Courriel</div><div class="rtee-info-val">${ICO.mail} ${e.courriel_tee ? `<a href="mailto:${X(e.courriel_tee)}">${X(e.courriel_tee)}</a>` : "—"}</div></div>
          <div class="rtee-info-row"><div class="rtee-info-lbl">Téléphone</div><div class="rtee-info-val">${ICO.phone} ${e.telephone_tee ? `<a href="tel:${X(e.telephone_tee)}">${X(e.telephone_tee)}</a>` : "—"}</div></div>
        </div>
      </div>
    </div>
    <div class="rtee-dtab-content ${ct === "niveaux" ? "active" : ""}" data-content="niveaux">
      <div class="rtee-info-row" style="grid-column:1/-1">
        <div class="rtee-info-lbl">Niveaux — ${X(cleanNiveaux(e.niveaux)) || "—"}</div>${nivHTML}
      </div>
    </div>
    <div class="rtee-dtab-content ${ct === "ecole" ? "active" : ""}" data-content="ecole">
      <div class="rtee-info-row"><div class="rtee-info-lbl">Adresse</div><div class="rtee-info-val">${ICO.pin} ${e.adresse ? `<a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((e.adresse || "") + " " + (e.code_postal || "") + " " + (e.ville || ""))}" target="_blank" rel="noopener" style="color:var(--bleu);font-weight:600;text-decoration:none;">${X(e.adresse)} ${X(e.code_postal)}</a>` : "—"}</div></div>
      <div class="rtee-info-row"><div class="rtee-info-lbl">Téléphone</div><div class="rtee-info-val">${ICO.phone} ${e.telephone ? `<a href="tel:${X(e.telephone)}">${X(e.telephone)}</a>` : "—"}</div></div>
      <div class="rtee-info-row"><div class="rtee-info-lbl">Courriel</div><div class="rtee-info-val">${ICO.mail} ${e.courriel ? `<a href="mailto:${X(e.courriel)}">${X(e.courriel)}</a>` : "—"}</div></div>
      <div class="rtee-info-row"><div class="rtee-info-lbl">Site web</div><div class="rtee-info-val">${ICO.globe} ${site}</div></div>
      <div class="rtee-info-row" style="grid-column:1/-1"><div class="rtee-info-lbl">Conseil scolaire</div><div class="rtee-info-val">${ICO.building} ${X(e.conseil_scolaire) || "—"}</div></div>
    </div>
  </div>
</div>`;
  }

  function toggleCard(card) {
    const was = card.classList.contains("expanded");
    document
      .querySelectorAll(".rtee-ecole-card.expanded")
      .forEach((c) => c.classList.remove("expanded"));
    if (!was) card.classList.add("expanded");
  }

  function setTab(id, key) {
    activeTab[id] = key;
    const c = document.querySelector(`.rtee-ecole-card[data-id="${id}"]`);
    if (!c) return;
    c.querySelectorAll(".rtee-dtab").forEach((t) =>
      t.classList.toggle("active", t.dataset.tab === key),
    );
    c.querySelectorAll(".rtee-dtab-content").forEach((t) =>
      t.classList.toggle("active", t.dataset.content === key),
    );
  }

  function cleanNiveaux(s) {
    if (!s) return "";
    return s.replace(/,?\s*service de garde\s*/i, "").trim();
  }

  function parseNiv(s) {
    if (!s) return [];
    const clean = cleanNiveaux(s);
    const r = [];
    let debut = null,
      fin = null;
    const mRange = clean.match(/^M\s*-\s*(\d+)/i);
    const numRange = clean.match(/(\d+)\s*-\s*(\d+)/);
    const single = clean.match(/^(\d+)$/);
    if (mRange) {
      r.push("Maternelle");
      debut = 1;
      fin = parseInt(mRange[1], 10);
    } else if (numRange) {
      debut = parseInt(numRange[1], 10);
      fin = parseInt(numRange[2], 10);
    } else if (single) {
      debut = fin = parseInt(single[1], 10);
    } else if (/^M$/i.test(clean)) {
      r.push("Maternelle");
    }
    if (debut !== null && fin !== null)
      for (let i = debut; i <= fin; i++) r.push(i + "e année");
    return r;
  }

  function X(s) {
    if (!s) return "";
    return String(s)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;");
  }

  /* Auto-resize iframe */
  function sendHeight() {
    var h = document.body.scrollHeight;
    window.parent.postMessage({ rteeHeight: h }, "*");
  }
})();
