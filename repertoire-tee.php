<?php

/**
 * Plugin Name: Répertoire des Établissements TÉÉ
 * Description: Répertoire interactif des établissements scolaires avec carte du Canada cliquable.
 * Version: 2.0
 * Author: RNTÉÉ
 */
if (!defined('ABSPATH')) exit;

/* ── PROTECTION DES DONNÉES ──
 * La désinstallation du plugin NE supprime PAS la table wp_tee_etablissements.
 * Les données sont préservées même si le plugin est désactivé/réinstallé.
 * Pour effacer manuellement : DELETE TABLE wp_tee_etablissements via phpMyAdmin.
 */
/* ── ACTIVATION ── */
register_activation_hook(__FILE__, 'rtee_create_table');
// Désinstallation sans effacement des données
register_uninstall_hook(__FILE__, 'rtee_uninstall_safe');
function rtee_uninstall_safe()
{ /* Les données sont préservées intentionnellement. */
}
function rtee_create_table()
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
        nom VARCHAR(255) NOT NULL,
        type_etablissement ENUM('elementaire','secondaire','mixte') NOT NULL DEFAULT 'elementaire',
        type_programme ENUM('francophone','immersion') NOT NULL DEFAULT 'francophone',
        niveaux VARCHAR(50) DEFAULT '',
        province VARCHAR(100) NOT NULL,
        ville VARCHAR(100) NOT NULL,
        adresse VARCHAR(255) DEFAULT '',
        code_postal VARCHAR(10) DEFAULT '',
        telephone VARCHAR(30) DEFAULT '',
        courriel VARCHAR(150) DEFAULT '',
        site_web VARCHAR(255) DEFAULT '',
        coordonnateur_tee VARCHAR(150) DEFAULT '',
        courriel_tee VARCHAR(150) DEFAULT '',
        telephone_tee VARCHAR(30) DEFAULT '',
        conseil_scolaire VARCHAR(200) DEFAULT '',
        actif TINYINT(1) NOT NULL DEFAULT 1,
        date_ajout DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) " . $wpdb->get_charset_collate() . ";";
  require_once ABSPATH . 'wp-admin/includes/upgrade.php';
  dbDelta($sql);
  rtee_insert_sample_data();
}

function rtee_insert_sample_data()
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  if ($wpdb->get_var("SELECT COUNT(*) FROM $table") > 0) return;
  $rows = [
    ['École Michaëlle Jean', 'elementaire', 'M-6', 'Alberta', 'Edmonton', '123 rue Lacombe', 'T6H 3P9', '780-555-0101', 'mjean@example.ca', 'https://mjean.example.ca', 'Marie Dupont', 'marie.dupont@frap.ca', '780-555-0102', 'Conseil scolaire Centre-Nord', 'francophone'],
    ['École franco-albertaine', 'secondaire', '7-12', 'Alberta', 'Calgary', '456 av. Sainte-Anne', 'T2P 1K8', '403-555-0201', 'efa@example.ca', 'https://efa.example.ca', 'Jean Martin', 'jean.martin@cscn.ca', '403-555-0202', 'Conseil scolaire Centre-Nord', 'francophone'],
    ['École Camille-J.-Lerouge', 'elementaire', 'M-6', 'Alberta', 'Camrose', '789 boul. Francophone', 'T4V 2J6', '780-555-0301', 'cjl@example.ca', '', 'Lucie Bernard', 'lucie.b@frap.ca', '780-555-0302', 'Conseil scolaire Centre-Nord', 'francophone'],
    ["École d'immersion Saint-Jean", 'elementaire', 'M-6', 'Alberta', 'Edmonton', '200 av. Grandin', 'T6K 1P2', '780-555-0303', 'imm-stj@example.ca', '', 'François Côté', 'f.cote@frap.ca', '780-555-0304', 'Edmonton Public Schools', 'immersion'],
    ['École Sainte-Anne', 'mixte', 'M-12', 'Colombie-Britannique', 'Vancouver', '10 rue de la Montagne', 'V6B 1A1', '604-555-0401', 'ste-anne@example.ca', 'https://ste-anne.example.ca', 'Paul Tremblay', 'paul.t@csfv.ca', '604-555-0402', 'Conseil scolaire francophone', 'francophone'],
    ['École francophone de Victoria', 'elementaire', 'M-5', 'Colombie-Britannique', 'Victoria', '22 av. Royale', 'V8W 1T4', '250-555-0501', 'efv@example.ca', '', 'Anne Côté', 'anne.c@csfv.ca', '250-555-0502', 'Conseil scolaire francophone', 'francophone'],
    ["École d'immersion Brentwood", 'secondaire', '7-12', 'Colombie-Britannique', 'Burnaby', '45 boul. Willingdon', 'V5C 5G4', '604-555-0403', 'imm-bw@example.ca', '', 'Luc Trottier', 'l.trottier@csfv.ca', '604-555-0404', 'Burnaby School District', 'immersion'],
    ['École Terre des Jeunes', 'secondaire', '7-12', 'Ontario', 'Ottawa', '55 ch. Rideau', 'K1N 5X7', '613-555-0601', 'tdj@example.ca', 'https://tdj.example.ca', 'Sophie Labelle', 'sophie.l@cepeo.on.ca', '613-555-0602', 'CEPEO', 'francophone'],
    ['École Saint-François', 'elementaire', 'M-8', 'Ontario', 'Toronto', '88 rue Bloor Ouest', 'M5S 1M8', '416-555-0701', 'stf@example.ca', '', 'Michel Roy', 'michel.r@cscno.ca', '416-555-0702', 'Conseil scolaire catholique', 'francophone'],
    ["École d'immersion du Lac", 'elementaire', 'M-8', 'Ontario', 'Kingston', '300 rue Princess', 'K7L 1B2', '613-555-0603', 'imm-lac@example.ca', '', 'Joëlle Prévost', 'j.prevost@cepeo.on.ca', '613-555-0604', 'Limestone District School Board', 'immersion'],
    ['École franco-manitobaine', 'mixte', 'M-12', 'Manitoba', 'Winnipeg', '33 boul. Provencher', 'R2H 0G2', '204-555-0801', 'efm@example.ca', 'https://efm.example.ca', 'Claire Fontaine', 'claire.f@dsfm.ca', '204-555-0802', 'DSFM', 'francophone'],
    ['École Boréale', 'secondaire', '9-12', 'Saskatchewan', 'Saskatoon', '99 av. Prairie', 'S7K 0G2', '306-555-0901', 'boreale@example.ca', '', 'Guy Picard', 'guy.p@cscf.sk.ca', '306-555-0902', 'Conseil des écoles fransaskoises', 'francophone'],
    ['École des Pionniers', 'elementaire', 'M-6', 'Nouveau-Brunswick', 'Moncton', '12 ch. des Acadiens', 'E1C 1E3', '506-555-1001', 'pionniers@example.ca', 'https://pionniers.example.ca', 'Hélène Boudreau', 'helene.b@dsf.ca', '506-555-1002', 'District scolaire francophone', 'francophone'],
    ['École Île-sans-Souci', 'mixte', 'M-12', 'Île-du-Prince-Édouard', 'Charlottetown', '7 rue des Acadiens', 'C1A 4P3', '902-555-1101', 'iss@example.ca', '', 'Roger Gallant', 'roger.g@cfepei.ca', '902-555-1102', 'Commission scolaire de langue française', 'francophone'],
    ['École Émilie-Tremblay', 'elementaire', 'M-6', 'Yukon', 'Whitehorse', '45 rue Hawkins', 'Y1A 1X7', '867-555-1201', 'etremblay@example.ca', '', 'Isabelle Morin', 'isabelle.m@csfy.ca', '867-555-1202', 'Conseil scolaire francophone du Yukon', 'francophone'],
    ['École des Explorateurs', 'elementaire', 'M-6', 'Nouvelle-Écosse', 'Halifax', '15 av. Hollis', 'B3J 1V9', '902-555-1301', 'explorateurs@example.ca', 'https://explorateurs.example.ca', 'Nathalie Arsenault', 'nath.a@csap.ca', '902-555-1302', 'Conseil scolaire acadien provincial', 'francophone'],
    ["École Bonaventure", 'mixte', 'M-12', 'Terre-Neuve-et-Labrador', "St. John's", '28 rue Water', 'A1C 1A1', '709-555-1401', 'bonaventure@example.ca', '', 'François Leblanc', 'f.leblanc@csfp.ca', '709-555-1402', 'Conseil scolaire francophone provincial', 'francophone'],
    ['École Aglait', 'elementaire', 'M-6', 'Nunavut', 'Iqaluit', '1 rue Mivvik', 'X0A 0H0', '867-555-1501', 'aglait@example.ca', '', 'Karianne Okalik', 'k.okalik@csftno.ca', '867-555-1502', 'Commission scolaire francophone du Nunavut', 'francophone'],
    ['École du Bout-du-Monde', 'mixte', 'M-12', 'Nunavut', 'Rankin Inlet', '5 rue Kivalliq', 'X0C 0G0', '867-555-1503', 'boutdumonde@example.ca', '', 'Thomas Ittinuar', 't.ittinuar@csftno.ca', '867-555-1504', 'Commission scolaire francophone du Nunavut', 'francophone'],
    ['École Allain St-Cyr', 'elementaire', 'M-6', 'Territoires du Nord-Ouest', 'Yellowknife', '4702 50e rue', 'X1A 2N3', '867-555-1601', 'allain@example.ca', 'https://allain.example.ca', 'Marguerite Beaulieu', 'm.beaulieu@csftno.ca', '867-555-1602', 'Conseil scolaire francophone territorial', 'francophone'],
    ['École Borealis', 'secondaire', '7-12', 'Territoires du Nord-Ouest', 'Inuvik', '100 Mackenzie Rd', 'X0E 0T0', '867-555-1603', 'borealis@example.ca', '', 'Jean-Paul Bouchard', 'jp.bouchard@csftno.ca', '867-555-1604', 'Conseil scolaire francophone territorial', 'francophone'],
  ];
  foreach ($rows as $r) {
    $wpdb->insert($table, [
      'nom' => $r[0],
      'type_etablissement' => $r[1],
      'niveaux' => $r[2],
      'province' => $r[3],
      'ville' => $r[4],
      'adresse' => $r[5],
      'code_postal' => $r[6],
      'telephone' => $r[7],
      'courriel' => $r[8],
      'site_web' => $r[9],
      'coordonnateur_tee' => $r[10],
      'courriel_tee' => $r[11],
      'telephone_tee' => $r[12],
      'conseil_scolaire' => $r[13],
      'type_programme' => isset($r[14]) ? $r[14] : 'francophone',
    ]);
  }
}

/* ── REST API ── */
add_action('rest_api_init', function () {
  register_rest_route('rtee/v1', '/etablissements', ['methods' => 'GET', 'callback' => 'rtee_api_get', 'permission_callback' => '__return_true']);
  register_rest_route('rtee/v1', '/filtres', ['methods' => 'GET', 'callback' => 'rtee_api_filtres', 'permission_callback' => '__return_true']);
});

function rtee_api_get(WP_REST_Request $req)
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  $where = ['actif=1'];
  $vals = [];
  if ($t = $req->get_param('type')) {
    $where[] = 'type_etablissement=%s';
    $vals[] = $t;
  }
  if ($p2 = $req->get_param('programme')) {
    $where[] = 'type_programme=%s';
    $vals[] = $p2;
  }
  if ($p = $req->get_param('province')) {
    $where[] = 'province=%s';
    $vals[] = $p;
  }
  $filter_niveau = null;
  $nv = $req->get_param('niveau');
  if ($nv !== null && $nv !== '') {
    // Récupère tous les établissements actifs avec niveaux non vides et filtre en PHP
    // car la logique de plage (M-6, 7-12, etc.) ne peut pas se faire avec LIKE
    // On récupère d'abord sans filtre niveau, puis on filtre côté serveur
    // (0 = Maternelle ; on ne peut pas utiliser un simple if($nv) ou !empty() ici
    // car PHP considère la chaîne "0" et l'entier 0 comme "vides")
    $filter_niveau = intval($nv);
  }
  if ($v = $req->get_param('ville')) {
    $where[] = 'ville=%s';
    $vals[] = $v;
  }
  $sql = 'SELECT * FROM ' . $table . ' WHERE ' . implode(' AND ', $where) . ' ORDER BY province,nom';
  if ($vals) $sql = call_user_func_array(array($wpdb, 'prepare'), array_merge(array($sql), $vals));
  $results = $wpdb->get_results($sql);

  // Filtrage par niveau cote serveur (logique de plage)
  if ($filter_niveau !== null) {
    $n = $filter_niveau;
    $results = array_values(array_filter($results, function ($e) use ($n) {
      $niveaux = trim($e->niveaux);
      if (empty($niveaux)) return false;
      $debut = 999;
      $fin = -1;
      // Maternelle = niveau 0
      if (preg_match('/^M/i', $niveaux)) {
        $debut = 0;
      }
      // Plage avec chiffres ex: 7-12, M-6
      if (preg_match('/^M\s*[-]\s*(\d+)/i', $niveaux, $m)) {
        $debut = 0;
        $fin = intval($m[1]);
      } elseif (preg_match('/(\d+)\s*[-]\s*(\d+)/', $niveaux, $m)) {
        if ($debut === 999) $debut = intval($m[1]);
        $fin = intval($m[2]);
      } elseif (preg_match('/^(\d+)$/', trim($niveaux), $m)) {
        $debut = $fin = intval($m[1]);
      }
      if ($fin === -1 && $debut !== 999) $fin = $debut;
      return ($debut <= $n && $n <= $fin);
    }));
  }

  return rest_ensure_response($results);
}

function rtee_api_filtres()
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  return rest_ensure_response([
    'provinces' => $wpdb->get_col("SELECT DISTINCT province FROM $table WHERE actif=1 ORDER BY province"),
    'villes'    => $wpdb->get_results("SELECT DISTINCT province, ville FROM $table WHERE actif=1 ORDER BY province, ville"),
  ]);
}

/* ── ADMIN ── */
add_action('admin_menu', function () {
  add_menu_page('Répertoire TÉÉ', 'Répertoire TÉÉ', 'manage_options', 'rtee-admin', 'rtee_admin_page', 'dashicons-location', 30);
  add_submenu_page('rtee-admin', 'Ajouter un établissement', '+ Ajouter', 'manage_options', 'rtee-add', 'rtee_add_page');
  add_submenu_page('rtee-admin', 'Importer CSV/Excel', '+ Importer', 'manage_options', 'rtee-import', 'rtee_import_page');
});

function rtee_admin_page()
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  if (isset($_POST['rtee_delete']) && check_admin_referer('rtee_delete')) {
    $wpdb->update($table, ['actif' => 0], ['id' => intval($_POST['rtee_id'])]);
    echo '<div class="notice notice-success"><p>Établissement archivé.</p></div>';
  }
  $rows = $wpdb->get_results("SELECT * FROM $table WHERE actif=1 ORDER BY province,nom");
  echo '<div class="wrap"><h1 class="wp-heading-inline">Répertoire TÉÉ</h1> ';
  echo '<a href="' . admin_url('admin.php?page=rtee-add') . '" class="page-title-action">+ Ajouter</a>';
  echo '<table class="wp-list-table widefat fixed striped"><thead><tr><th>Nom</th><th>Type</th><th>Province</th><th>Ville</th><th>TÉÉ</th><th>Actions</th></tr></thead><tbody>';
  foreach ($rows as $r) {
    $e = admin_url('admin.php?page=rtee-add&edit=' . $r->id);
    echo '<tr><td><strong>' . esc_html($r->nom) . '</strong></td><td>' . esc_html($r->type_etablissement) . '</td>';
    echo '<td>' . esc_html($r->province) . '</td><td>' . esc_html($r->ville) . '</td><td>' . esc_html($r->coordonnateur_tee) . '</td>';
    echo '<td><a href="' . $e . '">Modifier</a> &nbsp;|&nbsp; <form style="display:inline" method="post">';
    wp_nonce_field('rtee_delete');
    echo '<input type="hidden" name="rtee_id" value="' . $r->id . '"><button name="rtee_delete" value="1" onclick="return confirm(\'Archiver ?\')">Archiver</button></form></td></tr>';
  }
  echo '</tbody></table></div>';
}

function rtee_add_page()
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  $eid = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
  $row = $eid ? $wpdb->get_row("SELECT * FROM $table WHERE id=$eid") : null;
  if (isset($_POST['rtee_save']) && check_admin_referer('rtee_save')) {
    $data = array_map('sanitize_text_field', [
      'nom' => $_POST['nom'],
      'type_etablissement' => $_POST['type_etablissement'],
      'niveaux' => $_POST['niveaux'],
      'province' => $_POST['province'],
      'ville' => $_POST['ville'],
      'adresse' => $_POST['adresse'],
      'code_postal' => $_POST['code_postal'],
      'telephone' => $_POST['telephone'],
      'coordonnateur_tee' => $_POST['coordonnateur_tee'],
      'telephone_tee' => $_POST['telephone_tee'],
      'conseil_scolaire' => $_POST['conseil_scolaire'],
    ]);
    $data['courriel']     = sanitize_email($_POST['courriel']);
    $data['courriel_tee'] = sanitize_email($_POST['courriel_tee']);
    $data['site_web']     = esc_url_raw($_POST['site_web']);
    if ($eid) {
      $wpdb->update($table, $data, ['id' => $eid]);
      echo '<div class="notice notice-success"><p>Mis à jour.</p></div>';
    } else {
      $wpdb->insert($table, $data);
      echo '<div class="notice notice-success"><p>Ajouté.</p></div>';
    }
    $row = $eid ? $wpdb->get_row("SELECT * FROM $table WHERE id=$eid") : null;
  }
  $v = function ($f) use ($row) {
    return $row ? esc_attr($row->$f) : '';
  };
  $provinces = [
    'Alberta',
    'Colombie-Britannique',
    'Manitoba',
    'Nouveau-Brunswick',
    'Nouvelle-Écosse',
    'Terre-Neuve-et-Labrador',
    'Île-du-Prince-Édouard',
    'Ontario',
    'Saskatchewan',
    'Yukon',
    'Territoires du Nord-Ouest',
    'Nunavut'
  ];
  echo '<div class="wrap"><h1>' . ($eid ? 'Modifier' : 'Ajouter') . ' un établissement</h1><form method="post"><table class="form-table">';
  wp_nonce_field('rtee_save');
  $fields = [
    ['Nom', 'nom', 'text', '', true],
    ['Province', 'province', 'prov', '', true],
    ['Ville', 'ville', 'text', '', true],
    ['Type', 'type_etablissement', 'type', '', true],
    ['Niveaux', 'niveaux', 'text', 'ex: 1-6, 7-12'],
    ['Adresse', 'adresse', 'text', ''],
    ['Code postal', 'code_postal', 'text', ''],
    ['Tél. école', 'telephone', 'text', ''],
    ['Courriel école', 'courriel', 'email', ''],
    ['Site web', 'site_web', 'url', ''],
    ['Coord. TÉÉ', 'coordonnateur_tee', 'text', ''],
    ['Courriel TÉÉ', 'courriel_tee', 'email', ''],
    ['Tél. TÉÉ', 'telephone_tee', 'text', ''],
    ['Conseil scolaire', 'conseil_scolaire', 'text', ''],
  ];
  foreach ($fields as $f) {
    [$lbl, $nm, $tp, $ph] = $f;
    $req = (count($f) > 4 && $f[4]) ? 'required' : '';
    echo "<tr><th><label for='$nm'>$lbl</label></th><td>";
    if ($tp === 'prov') {
      echo "<select name='$nm' id='$nm' $req><option value=''>-- Choisir --</option>";
      foreach ($provinces as $p) {
        echo '<option value="' . esc_attr($p) . '" ' . selected($v($nm), $p, false) . '>' . esc_html($p) . '</option>';
      }
      echo '</select>';
    } elseif ($tp === 'type') {
      echo "<select name='$nm' id='$nm'>";
      foreach (['elementaire' => 'Élémentaire', 'secondaire' => 'Secondaire', 'mixte' => 'Mixte 1-12'] as $k => $l) {
        echo '<option value="' . $k . '" ' . selected($v($nm), $k, false) . '>' . $l . '</option>';
      }
      echo '</select>';
    } else {
      echo "<input type='$tp' name='$nm' id='$nm' value='" . $v($nm) . "' class='regular-text' placeholder='$ph' $req>";
    }
    echo '</td></tr>';
  }
  echo '</table><p class="submit"><input type="submit" name="rtee_save" value="Enregistrer" class="button-primary"></p></form></div>';
}


/* ── IMPORT CSV/EXCEL ── */
function rtee_import_page()
{
  global $wpdb;
  $table = 'GQa_tee_etablissements';
  $msg = '';
  if (isset($_POST['rtee_import']) && check_admin_referer('rtee_import')) {
    if (!empty($_FILES['rtee_csv']['tmp_name'])) {
      $file = $_FILES['rtee_csv']['tmp_name'];
      $ext  = strtolower(pathinfo($_FILES['rtee_csv']['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, ['csv', 'txt'])) {
        $msg = '<div class="notice notice-error"><p>Format non supporté. Exportez votre Excel en <strong>.csv</strong> (UTF-8) depuis Fichier → Enregistrer sous.</p></div>';
      } else {
        $handle = fopen($file, 'r');
        $header = null;
        $count = 0;
        $errors = [];
        $expected = [
          'nom',
          'type_etablissement',
          'type_programme',
          'niveaux',
          'province',
          'ville',
          'adresse',
          'code_postal',
          'telephone',
          'courriel',
          'site_web',
          'coordonnateur_tee',
          'courriel_tee',
          'telephone_tee',
          'conseil_scolaire'
        ];
        while (($row = fgetcsv($handle, 0, ',')) !== false) {
          if (!$header) {
            // Nettoyer BOM UTF-8
            $row[0] = preg_replace('/\xEF\xBB\xBF/', '', $row[0]);
            $header = array_map('strtolower', array_map('trim', $row));
            continue;
          }
          if (count($row) < 2) continue;
          $data = [];
          foreach ($expected as $col) {
            $idx = array_search($col, $header);
            $data[$col] = ($idx !== false && isset($row[$idx])) ? sanitize_text_field(trim($row[$idx])) : '';
          }
          if (empty($data['nom']) || empty($data['province'])) {
            $errors[] = "Ligne ignorée (nom/province manquant) : " . esc_html($row[0]);
            continue;
          }
          // Valeurs par défaut
          if (!in_array($data['type_etablissement'], ['elementaire', 'secondaire', 'mixte'])) $data['type_etablissement'] = 'elementaire';
          if (!in_array($data['type_programme'], ['francophone', 'immersion'])) $data['type_programme'] = 'francophone';
          $data['courriel']     = sanitize_email($data['courriel']);
          $data['courriel_tee'] = sanitize_email($data['courriel_tee']);
          $data['site_web']     = esc_url_raw($data['site_web']);
          $data['actif']        = 1;
          $wpdb->insert($table, $data);
          $count++;
        }
        fclose($handle);
        $msg = '<div class="notice notice-success"><p>' . $count . ' établissement(s) importé(s) avec succès.</p></div>';
        if ($errors) $msg .= '<div class="notice notice-warning"><p>' . implode('<br>', $errors) . '</p></div>';
      }
    }
  }
?>
  <div class="wrap">
    <h1>+ Importer des établissements</h1>
    <?php echo $msg; ?>
    <div style="background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:24px;max-width:640px;margin-top:16px;">
      <h2 style="margin-top:0">Format requis : fichier CSV (UTF-8)</h2>
      <p>Exportez votre fichier Excel en <strong>CSV UTF-8</strong> depuis :<br>
        <em>Fichier → Enregistrer sous → CSV UTF-8 (délimité par des virgules)</em>
      </p>
      <p style="margin-top:12px"><strong>Colonnes attendues</strong> (ordre libre, noms exacts) :</p>
      <code style="display:block;background:#f6f7f7;padding:10px;border-radius:4px;font-size:.82rem;line-height:1.8;">
        nom, type_etablissement, type_programme, niveaux, province, ville,<br>
        adresse, code_postal, telephone, courriel, site_web,<br>
        coordonnateur_tee, courriel_tee, telephone_tee, conseil_scolaire
      </code>
      <p style="margin-top:8px;font-size:.82rem;color:#555;">
        <strong>type_etablissement</strong> : elementaire | secondaire | mixte<br>
        <strong>type_programme</strong> : francophone | immersion<br>
        <strong>province</strong> : nom complet (ex: Alberta, Colombie-Britannique…)
      </p>
      <hr style="margin:20px 0">
      <form method="post" enctype="multipart/form-data">
        <?php wp_nonce_field('rtee_import'); ?>
        <table class="form-table">
          <tr>
            <th><label for="rtee_csv">Fichier CSV</label></th>
            <td><input type="file" name="rtee_csv" id="rtee_csv" accept=".csv,.txt" required></td>
          </tr>
        </table>
        <p class="submit">
          <input type="submit" name="rtee_import" value="Importer" class="button-primary">
        </p>
      </form>
      <hr style="margin:20px 0">
      <h3>Telecharger un modèle vide</h3>
      <a href="data:text/csv;charset=utf-8,nom%2Ctype_etablissement%2Ctype_programme%2Cniveaux%2Cprovince%2Cville%2Cadresse%2Ccode_postal%2Ctelephone%2Ccourriel%2Csite_web%2Ccoordonnateur_tee%2Ccourriel_tee%2Ctelephone_tee%2Cconseil_scolaire%0AÉcole%20exemple%2Celementaire%2Cfrancophone%2CM-6%2CAlberta%2CEdmonton%2C123%20rue%20Exemple%2CT5A%201B2%2C780-555-0000%2Cinfo%40exemple.ca%2Chttps%3A%2F%2Fexemple.ca%2CMarie%20Dupont%2Cmarie%40exemple.ca%2C780-555-0001%2CConseil%20scolaire%20exemple"
        download="modele-import-tee.csv" class="button button-secondary">
        Telecharger le modèle CSV
      </a>
    </div>
  </div>
<?php
}

/* ── ASSETS + SHORTCODE ── */
/* Assets chargés dans la page iframe — pas dans le thème principal */

/* Page iframe pour le répertoire */
/* Le rendu iframe passe par wp_ajax (aucun thème chargé) */

/* Endpoint AJAX public pour l'iframe (aucun thème chargé) */
add_action('wp_ajax_rtee_iframe', 'rtee_render_iframe_page');
add_action('wp_ajax_nopriv_rtee_iframe', 'rtee_render_iframe_page');

function rtee_render_iframe_page()
{
  $api_url = rest_url('rtee/v1/');
  $nonce   = wp_create_nonce('wp_rest');
  $css_url = plugin_dir_url(__FILE__) . 'assets/repertoire.css';
  $js_url  = plugin_dir_url(__FILE__) . 'assets/repertoire.js';
?>
  <!DOCTYPE html>
  <html lang="fr">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répertoire TÉÉ</title>
    <link rel="stylesheet" href="<?php echo esc_url($css_url); ?>?v=<?php echo time(); ?>">
  </head>

  <body>
    <?php rtee_shortcode_html(); ?>
    <script>
      var RTEE = {
        apiUrl: "<?php echo esc_js($api_url); ?>",
        nonce: "<?php echo esc_js($nonce); ?>"
      };
    </script>
    <script src="<?php echo esc_url($js_url); ?>?v=<?php echo time(); ?>"></script>
  </body>

  </html>
  <?php
  exit;
        }

        add_shortcode('repertoire_tee', 'rtee_shortcode');
        function rtee_shortcode()
        {
          $iframe_url = add_query_arg(['action' => 'rtee_iframe'], admin_url('admin-ajax.php'));
          $height = '780px';
          return '<iframe src="' . esc_url($iframe_url) . '" style="width:100%;height:' . $height . ';border:none;display:block;" frameborder="0" scrolling="no" id="rtee-iframe" allowfullscreen></iframe>
          <script>
          window.addEventListener("message", function(e) {
            if (e.data && e.data.rteeHeight) {
              document.getElementById("rtee-iframe").style.height = e.data.rteeHeight + "px";
            }
          });
          </script>';
        }

        function rtee_shortcode_html()
        { 
  ?>
  <div id="rtee-app">
    <div class="rtee-layout">
    <div class="rtee-filtres-bar">
      <div class="rtee-fpill" id="rtee-pill-type">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
          <polyline points="9 22 9 12 15 12 15 22" />
        </svg>
        <select id="rtee-f-type">
          <option value="">Types</option>
          <option value="elementaire">Élémentaire</option>
          <option value="secondaire">Secondaire</option>
          <option value="mixte">Mixte 1-12</option>
        </select>
      </div>
      <div class="rtee-fpill" id="rtee-pill-programme">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
        </svg>
        <select id="rtee-f-programme">
          <option value="">Programmes</option>
          <option value="francophone">École francophone</option>
          <option value="immersion">Immersion française</option>
        </select>
      </div>
      <div class="rtee-fpill" id="rtee-pill-province">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
          <circle cx="12" cy="10" r="3" />
        </svg>
        <select id="rtee-f-province">
          <option value="">Provinces</option>
        </select>
      </div>
      <div class="rtee-fpill" id="rtee-pill-ville">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <rect x="2" y="7" width="20" height="14" rx="2" />
          <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
        </svg>
        <select id="rtee-f-ville">
          <option value="">Villes</option>
        </select>
      </div>
      <div class="rtee-fpill" id="rtee-pill-niveau">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
        </svg>
        <select id="rtee-f-niveau">
          <option value="">Niveaux</option>
          <option value="0">Maternelle</option>
          <option value="1">1re année</option>
          <option value="2">2e année</option>
          <option value="3">3e année</option>
          <option value="4">4e année</option>
          <option value="5">5e année</option>
          <option value="6">6e année</option>
          <option value="7">7e année</option>
          <option value="8">8e année</option>
          <option value="9">9e année</option>
          <option value="10">10e année</option>
          <option value="11">11e année</option>
          <option value="12">12e année</option>
        </select>
      </div>
      <div class="rtee-fsep"></div>
      <button class="rtee-btn-search" id="rtee-btn-search">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
          <circle cx="11" cy="11" r="8" />
          <path d="m21 21-4.35-4.35" />
        </svg>
        Rechercher
      </button>
      <button class="rtee-btn-reset" id="rtee-btn-reset">Effacer</button>
      <span class="rtee-count-chip" id="rtee-count-chip" style="display:none"></span>
    </div>

      <div class="rtee-carte-panel">
        <div class="rtee-carte-inner">
          <div class="rtee-carte-title">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
              <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6" />
              <line x1="8" y1="2" x2="8" y2="18" />
              <line x1="16" y1="6" x2="16" y2="22" />
            </svg>
            Cliquez sur une province pour filtrer
          </div>
          <svg id="rtee-canada-map" viewBox="0 0 1280 1083" xmlns="http://www.w3.org/2000/svg">
            <defs>
              <style>
                .cls-1,
                .cls-2 {
                  font-family: Helvetica, Arial, sans-serif;
                  font-size: 30px;
                  font-weight: 700;
                  fill: #111533;
                }

                .cls-1 {
                  font-size: 26px;
                }

                .cls-3 {
                  letter-spacing: -.01em;
                }

                .cls-4 {
                  fill: inherit;
                }
              </style>
            </defs>
            <g class="prov-group" id="prov-QC" data-label="Québec">
              <path id="QC" d="M1119.94,652.77c-28.26,18.82-56.44,36.11-86.14,51.91-1.38.73-2.46,1.36-3.76.75-2.62-1.21.08-3.63-3.1-5.94-1.01-.73-3.7-.69-4,.46,1.82,3.71,2.69,7.07,3.04,10.86.16,1.8,3.02,3.36,1.99,5.63-.54,1.2-.78,4.14-2.97,4.21-1.39.04-3.06-2.47-4.81-1.47-3.92,2.24-9.59,3.28-12.14-1.81-3.98.82-13.78,4.38-17.13,2.23-2.33-1.5-.89-3.83-1.93-5.57-2.44-4.04-3.35-8.1-6.09-11.78-.72,3.39,1.5,7.26-1.61,9.26-5.52.72-13.1-1.04-13.24-8.32-.04-2.02,2.72-1.73,3.47-2.8.89-1.26-.4-5.44-1.86-5.85-4.45.91-10.52-2.42-13.38-5.86-.94-1.13-3.69-.51-4.09-2.41-.21-.98-.81-2.31-.42-3.79.8-3.01-5.5-5.65-.01-11.7.93-1.03,2.41-.39,2.6-2.11.5-4.39-12.03-6.16-6.18-10.21,2.62-1.82,5.88,4.19,11.49,3.03-3.27-3.69-6.11-7.5-7.81-12.1,9.13-1.94,6.49,5.42,17.88,3.25,1.05.16,2.78,3.2,4.31,1.93.62-1.92,4.47-8.3,7.51-7.77,1.88.33,3.66.05,5.54,2.14,1.5-4.21,5.42-3.2,9.04-2.2.69.14,1.79-.33.96-.85-1.36-1.83-2.64-4.47-2.25-6.64.25-1.36,2.7-1.83,1.72-3.4-.85-1.38-1.81-2.39-3.25-3.4-1.06-.75-.37-2.24-.85-2.79-.47-.54-2.65-.66-2.9-1.27-1.52-3.67-2.63-7.5-6.36-9.53.46-8.44-.99,5.55-9.99-9.97,4.86-5.46-4.24-1.77-3.31-8.79.67-5.07-1.47-8.59-3.26-12.35-.98-2.05-.88-4.55-2.35-6.53-1.57-2.13-4.22-.79-6.31-2.12-2.69-1.71-4.44-4.53-8.23-5.32.14-3.36.55-6.11,1.58-9.45-10.14-14.45-13.59-1.09-15.97-7.26-.91-2.37-.19-3.57,1.91-4.77-2.15-10.68-9.38-3.79-9.76-8.76-.32-4.14-1.54-8.7-5.98-9.05,1.09,3.35-.19,6.17-1.33,9.38,10.39,7.15-2.54,14.61,8.73,14.5-3.11,1.38-4.51,3.58-4.08,6.31,2.56,0,2.41,1.54.2,1.54-1.7.85-.38,2.11.24,2.95.67.92,2.28.25,2.55,1.77-3.01-.27-4.77-2.91-7.36-1.68-1.06,2.61.55,6.2-.52,8.46-.36.77-7.63,8.94-7.29,14.41-1.47-2.48-1.65-4.78-4.01-6.69-.45.8-.34,1.94-1.31,2.53-.93-1.57-.89-3.64-2.26-3.64-1.4,0-2.42,1.33-3.26.99-4.97-1.99-2.5-4.8-7.73-5.83-.58-.12-9.79,3-10.39,7.25-1.19-2.88-.86-5.44-.39-7.78.93.83,1.01,1.61,1.34,1.67.62.13,1.08-.11,1.1-.46.03-.47.27-1.55-.16-2.07-1.55-1.89-3.39-.04-5.61-.38.64-1.17,2.61-1.05,2.8-2.28.2-1.26-.84-1.92-1.42-2.35-.91-.68-2.51,1.04-3.28,1.01-6.13-.24,2.21-10.12-10.13-13.78,8.08-6.6-8.18-6.36-2.51-20.64.67-1.73-2.6-4.82-4.31-5.2.44,3.17.96,6.59-2.37,6.99-1.73.21-3.04-2.7-4.37-4.26-3.1,3.5-15.84,3.78-18.07,1.84-1.44-1.25-3.14-3.32-3.2-5.69,4.22-5.01-3.61-1.2-8.62-4.96-1.95-1.46-4.2-.05-5.26,1.54-.44-1.69.88-4.68-.63-5.5-7.74-.2-5.74-2.79-15.86-4.97-8.2,16.17-14.04,6.12-18.36,11.58l-17.08-1.71c-6.27-.63-12.52,5.79-11.04,12.35.9,3.97,3.42,7.85,5.42,10.87,1.82.09,3.58.54,4.73,1.89,1,1.17-1.04,3.26-.59,4.3,4.33,9.94-3.53,14.33-.26,15.95,1.75.87,4.48-1.83,5.31-1.12,1.75,1.5-1.29,3.07-.23,4.32.63.73,2.11.69,2.69,1.55.74,1.11-.31,2.53-.24,4.28,3.81.31,2.98,4.02,4.36,5.91,1.51,2.07,3.45,2.02,3.65,4.86.18,2.49-1.04,3.81-3.08,5.88-1.61,1.63-.36,5.98,1.37,7.37-.15,3.02-2.86,4.37-3.49,7.11-.65,2.85-.55,5.59-3.55,6.94,1.24,1.52.92,4.56,2.58,5.51,2.79,1.6,5.99,4.52,9.08,5.33,10.1,2.66,16.1,9.34,21.91,17.33,4,5.51,5.62,11.74,8.02,18.13,3.05,8.14,2.37,15.1-.39,23.41-5.13,15.45-15.81,27.35-29.98,35.92,2.64,2.79,4.34,4.92,5.12,7.61.99,3.42,3.59,3.26,5.95,4.43,3.58,1.78,2.58,7.69,4.27,10.58,1.06,1.82.87,3.3,1.04,4.38,1.69,3.06,4.46,6.21,4.34,10.24-.07,2.47,3.75,3.61,4.01,5.19.66,4.03,2.87,5.84,5.48,8.4,4.88,4.79-.47,13.57-2.46,19.05,4.95,1.84,6.35,5.86,4.48,10.32.15-2.36-7.31-6.94-8.01-7.26-1.17-.55-2.29,2.24-2.51,3.22-.27,1.15,1.61,1.79,1.97,3.25l23.37,95.27c1.97,8.02,4.28,11.84,10.33,17,3.59,3.06,6.57,9.58,12.22,9.22l16.16-1.03c5.48-.35,9.49,2.77,13.7,6.5.91-1.86,1.95-3.08,3.41-2.97,1.67.13,3.29.68,4,2.89.77,2.36,5.35,6.5,7.76,4.31,3.02-2.75,6.15-1.37,9-.9l13.19-9.85c2.95-2.2,9.13-4.15,12.18-.9,1.44,1.54-.75,6.45,2.94,7.24,1.88.4,4.5-3.05,3.93-4.91-2.09-1.36-4.42-.19-5.66-1.97,1.82-1.14,3.1.51,4.38-.12.74-.37,1.24-1.19,2.04-2.26.62,1.84,2.15,2.13,4.39,2.09-4.17,8.87-8.9,6.57-9.39,14.27l47.92-17.47c6.6-2.41-1.49-4.87,6.22-10.05,1.68-1.13,3.37-2.53,5.42-1.91-.56-2.63.38-4.24,1.36-5.12-2.91-7.14.13-.56,2.07-13.69.44-2.97-3.66-7.22-3.07-10.05,2.47-3.89.68-6.47.61-10.18-.14-7.95,3.38-15.92,4.21-24.02,2.49-.79,4.21.8,6.02-.27,2.87-1.69,5.01-5.44,4.81-8.82-.15-2.58-2.97-4.7-2.52-7.61,1.49-3.33,6.43-8.08,10.81-7.93,10.15.35,9.58-3.32,11.26-5.41-5.37-1.02-5.02-9.9,1.05-9.9,3.54,0,5.13,3.01,4.78,5.73,2.61-3.05,3.6.32,7.89-6.19l11.56-1.31c2.19-1.58,4.25-6.19,4.73-8.69.75-3.91,2.76-4.98,4.75-7.89,2.27-3.31-1.86-8.7-5.74-8.81.71-1.61,2.39-.88,3.27-2.11-14.84-9.45-33.63,4.89-43.21,16.42-7.91,9.52-15.67,18.27-21.8,29.22-11.53,20.63-3.54,15.69-9.77,28.16-1.45,2.9-1.81,15.69-8.24,18.58-1.3-1.55-1.25-3.61-.07-5.85,1.33-2.51-.74-6.58,1.22-8.6,2.13-2.19,2.78-3.6,3.26-6.39.59-3.42,2.79-6.64,1.96-10.6-.83-3.95,1.23-7.05,2.37-10.64,1.44-4.55.09-10.15,2.82-14.35,1.8-2.77,2.13-6.54,3.94-8.89,1.8-2.35,4.4-3.55,3.76-7.32l10.65-8.23c2.54-1.96-4.87-10.74,1.66-21.42,1.14-1.87,1.58-3.51,1.58-5.83,4.43,1.5,5.74-1.36,8.24-3.45,6.43-5.37,11-5.64,21.66-12.12,2.21-1.34,4.95-2.04,7.3-3.75,3.08-2.24,7.93-1.13,10.92-4.11.58-.58.64-2.05,1.49-2.5,5.63-3.02,11.32-5.67,17.59-7.29,1.35-.35,2.86,1.18,3.98.35.92-.68,1.11-2.37,2.32-3.26l20.17-14.81c-.57-2.91.78-5.82,1.22-8.35.77-4.43.97-7.94,4.18-11.26,1.27-1.31-4.47-1.49,1.58-11.15l-1.88-.62c-.74-.25-.66-.75.13-.98.46-.13.96-.4,1.76-.79,2.16-.57,1.57-1.23-1.18-1.38.75-1.77,2.98-.81,4.35-2.25,2.48-4.57,5.01-9.59,9.88-12.52-1.23-3.42-3.75-6.53-6.55-10.31ZM826.15,746.32c-.42.65-1.8.7-2.59.68-.64-.02-4.17-4.16-3.91-3.19-1.04-3.97,9.62-2.72,12.31-11.11-.8-.97-2.64.05-3.2-1.9,9.74,1.25,2.32-7.16,8.44-4.48.59.26.06,1.45.08,2.72.01,3.63-3.33,6.62-5.29,10.22,1.83,1.39,3.16-.99,5.15-.78.59.06-.04,1.5.35,1.71.68.37,1.61-.59,2.62.36-9.61,8.28-10.3.13-13.95,5.78ZM929.92,686c-1.08-.99-1.09-3.08-2.5-3.35l-8.04-1.5c-.46,1.84.62,2.6,1.29,3.64.68,1.06,2.3.41,3.45.95,2.05.97,1.2,4.1.47,5.69,2.48.26,2.62-1.81,3.03-.55.22.68-.1,1.94-.33,2.77.86,1.7,3.54.8,5.79,1.63-1.85,1.14-2.21,2.99-2.39,5.38-1.36-.15-3.13.5-4.12-.33-.78-.65-.4-2.89-1.75-3.11-.86-.14-2.17.19-3.36,1.76-.8-.85-.29-2.18-.89-3.64l-7.04-1.88c1.11-2.92,3.32-4.25,6.73-5.17-1.53-1.97-3.36-1.47-5.22-2.69.66-10.01,14.48-8.36,14.56-8.93.98.8,1.03,3.35,2.66,4.49.68.47,1.89.21,3.35.36-1.2,2.11-3.29,3.2-5.68,4.47Z" />
            </g>
            <g class="prov-group" id="prov-ON" data-label="Ontario">
              <path id="ON" d="M828.81,981.23c3.08,1.09,6.67,1.19,9.8,1.1,1.25-.17,1.2-3.21.77-4.17-.52-1.15-1.78-2.2-3.4-3.02.48-1.1,1.45-2.51,2.3-2.4,2.05.26,3.78,1.94,5.58.97-7.77-5.64-7.28-6.64-12.53-10.89,1-.81,1.17-1.35,1.87-1.82.49-.33.33-1.1-.62-1.4-.67,0-1.61,1.09-2.28,1.14-1.19.09-2.49-.89-2.74-2.15-.95-4.85-3.21,2-9.01-9.48l-14.39,1.37c-1.06-1.11-1.49-2.18-2.51-3.05-.69,1.87-3.07,2.87-3.35,4.73-.14,1.68,1.8,3.45,2.32,4.97.65-1.6,1.07-4.41,2.14-3.14,1.72,2.03.47,6.81-1.3,8.36-2.16,1.88-5.71-.29-8.05-.71l-18.62-3.36c5.78-4.87,9.54,1.64,11.39-.84.84-1.12,1.03-2.66,2.46-3.37,1.69-.84,2.85,0,4.54,1.69,2.05-2.06,3.1-4.14,4.88-6.79-4.02.85-7.4-1.11-10.95-.39l-5.72,1.16c-16.47,3.35-7.66-1.04-21.44.63,1.51,1.12,3.11,1.45,2.8,3-.2,1.01-1.26,3.42-2.76,1.77-.8-.88-1.37-1.47-2.13-2.4-.47-.58.19-1.38.94-2.27-2.78-.58-3.14-2.81-3.05-5.38-2.46.25-4.32,2.9-6.46,2.23-1.97-.62-.71-3.67-.39-5.15-.81-.85-1.5-.73-1.65-1.23-.27-.9.17-2.45,1.5-2.3,1.1.13,1.07-.74,1.11-1.25.23-2.91-4.28.24-7.56-1.84-.16-2.73,1.86-5.62,1.28-8.36-.67-3.16-4.56-4.21-7.06-5.78-.92-3.21.43-6.05.44-9.85-8.46-1.15-13.94,8.6-22.56-4.1-4.21-6.21-1.48-11.85-11.71-12.83-12.48,5.59-30.01-9.19-25.03,1.97,3.13.69,6.37-1.97,9.77.28-8.18.65-7.57,6.2-13.25,6.63.57-1.87,1.31-2.64,1.64-3.3.61-1.24.14-3.95-1.9-3.03-4.13,1.85-1.28,12.89-6.54,13.31l1.2-6.03c-2.57,1.1-5.1,1.9-7.36,3.8,1.04,4.07-.47,7.85-2.68,9.92-3.76,3.54-7.84,1.54-12,.17-3.21-1.06-7.37,1.98-10.71-.26-1.09-.73-2.07-2.03-3.33-2-2.3.06-6.92,7.55-10.81,4.15-2.11-1.85-9.73-6.48-10.19-6.41-1.64.23-2.46.94-3.46,2.99-.55-3.53-3.59-3.91-4.95-5.81-5.89-8.21-14.55-1.31-17.54-.84-1.8.28-2.34-2.21-4.02-2.56l-12.64-2.62c-2.12-5.93-.02-14.13-6.87-17.11v-86.4c3.6-3.32,5.74-6.52,8.79-9.65l13.86-14.24,62-81.19c10.03,9.94,11.88,3.75,17.98,14.59,2.76,4.91,6.76,4.25,11.05,5.51,6.11,1.8,12.14,3.71,18.15,5.93,4.63,1.71,8.67,7.75,14.07,6.55l13.79-3.06c-.58.13,19.34-1.21,22.25.89,5.66,4.09,1.25,18.35,3.88,22.33,1.79,2.71,5.06,4.13,5.15,7.97.05,2.54-.47,5.73.99,7.89,4.5,6.63-.83,7.64,1.51,13.89-.6-1.59,11.01,8.35,11.76,8.94,2.62,2.07,3.8,7.43,6.83,8.62,3.59.71,5.94,1.49,8.75,3.69,4.65,3.63,3.76.58,9.43,11.9,3.63.8,7.75,1,11.54,3.11.47-3.33-1.39-5.38.82-8.22l23.35,94.07c.47,1.89-.79,5.05.8,6.08,2.76,1.79,2.46,4.91,4.16,6.97,3.93,4.77,8.56,8.84,12.67,13.22,8.38,8.92,21.61-1.65,30.82,4.49,3.14,2.09,5.28,6.06,10.09,3.58,2.45-1.26,3.94,5.76,9.99,5.81,2.39.02,4.3-1.18,6.35-3.18l5.63,2.73c2.5-4.29,18.87-17.48,22-11.47.98,1.88.93,5.57,3.12,6.69-.05,3.7-4.4,5.8-6.72,7.93-6.3,5.79-9.35,13.4-13.49,20.67-3.32,5.82-12.45,7.25-14.86,12-.9,1.77.6,6.95-1.94,6.96l-12.05.05c-7.9.03-17.1,5.3-22.72,11.29l-7.31,7.79-3.97,8.64c5.89,2.91,9.76,1.13,14.28-2.83,1.02,4.32,2.02,6.41,5.6,9.03-8.58,2.98-19.31,2.73-23.96,9.21l-4.69,6.54c-18.82-4.79-21.4,10.05-25.09,12.84-3.48,2.63-7.19,5.55-9.84,9-4.08.79-10.07,5.03-11.93.41-.62-1.55-1.92-5.71.4-5.97l9.03-1.04c1.75-.2,2.39-1.79,2.73-3.04.63-2.31-2.14-2.21-3.64-2.88,1.79-22.28-1.82-4.81,11.08-24.49.3-11.08-6-13.55-1.74-26.38.84-2.52,3.53-4.72,3.18-7.85-.7-6.19-5.27-11.07-11.2-13.75,2.28-1.83,4.65-.79,6.87-1.49,4.01,10.8,8.74,4.14,6.46,11.4l3.11-2.04c1.21.07.6,6.06,1.99,4.22.78-1.03,1.3-2.39,2.43-2.9,1.29-.59,1.98,1.56,3.21,1.99ZM670.49,879.4c1.41,1.04.54,2.8,2.1,4.6-.28-2.11,3.69-2.52,3.94-3.99.75-4.31-.38-9,.94-12.88-1.38-.63-2.85-.39-3.01-1.01-.15-.57.67-2.26.09-2.85-1.87-1.88-6.12-2.85-8.67-1.38-3.91,2.25-2.62,9.23-3.2,12.1-1.63-.87-2.01-2.23-2.26-1.11-.09.41-.49,1.61.02,1.92,1.33.82,2.52.75,2.86,1.61.49,1.22.67,2.93.3,4.02,2.66.16,3.72,2.3,5.58,1.34.52-.27.41-2.04,1.32-2.37Z" />
            </g>
            <g class="prov-group" id="prov-NE" data-label="Nouvelle-Écosse">
              <path id="NE" d="M1100.68,913.67c-2.28-1.39-3.56-4.68-6.07-4.22-.71.81-.98,2.5-2.09,2.56-1.46.08-1.38-1.28-2.26-2.26-8.89-9.9-3.63-18.19-3.66-18.72s-1.12-.34-1.39-1.61c3.73-6.87,6.99-13.61,11.22-20.02,2-3.04,5.38-4.87,5.93-8.47,3.55,9.18,4.43-2.7,14.35-8.3-3.07-1.27-4.85,1.57-7.2,2.49-2.48.97-4.89,1.23-6.95,3.51-1.27,1.41-4.15.99-5.26,2.72-.78,1.21-.91,2.21-1.9,2.75-8.06,4.45,4.11-15.62,3.94-18.93-.31-5.79,7.37.38,9.92-4.94l6.31,1.47c8.07-11.04,11.11,3.75,18.54-14.29,1.98.3,2.79,3.15,4.97,3.28,1.87.11,4.36-2.65,4.13-4.3-.36-2.58-3.12-4.13-4.15-6.18-2-3.97.94-8.19.68-12.26-.42-6.75-2.22-13.14,1.88-20.06.84,2.51,3.35,2.29,4.2,3.1,4.16,3.95,3.19,10.77,4.54,16.01,1.58-1.8.58-3.92,2.44-4.57,1.66-.58,2.48.07,3.86,2.66.37-2.95.45-3.9,3.4-3.99,2.35-.07,3.28.85,3.73,3.44.08.46,1.68.8,1.61,1.17s-.53,1.28-.97,1.61c-2,1.55-1.16,3.3-1.41,5.09-1.18,8.44-11.04,11.14-15.59,15.82,1.31.67,2.32,1.17,2.32,2.1,0,.65-1.67,1.81-1.54,2.76-1.59,3.43,11.05-6.44,7.29.38-.23.42-.76.09-2.16.14-.4,3.25-3.18,4.65-4.79,6.87l-13.38,18.43c-1.68,2.31-3.05,3.96-5.59,5.74-1.84,1.29-3.61,3.75-5.25,5.38-.46.46,1.31,2.06,1.09,2.61-.75,1.85-5.48,2.83-5.75,2.74-1.06-.37-1.48-1.93-2.31-2.94-1.78.92-.67,2.68-.41,5.25-1.21.2-1.38-1.48-2.09-1.33-1.18.26-1.63.9-1.83,1.56-.35,1.13.51,3.29,2.31,3.22.1,2.54-2.91,13.17-3.26,15.2-.67,3.83-.46,8-5.4,9.47-.44,2.42-.45,4.59-1.33,6.66-.76,1.78-2.52,2.52-4.66,1.21Z" />
            </g>
            <g class="prov-group" id="prov-IPE" data-label="Île-du-Prince-Édouard">
              <path id="IPE" d="M1093.28,833.4c-.68-.96-1.21-3.16-1.83-3.69-.66-.56-2.15-.26-3.46.39-5.39,2.71-1.03-9.19-.63-12.19,1.44,2,1.3,4.15,2.05,6.77,3.54.74,5.75,2.85,8.61,5.38-.42-6.64,7.52-2.33,12.33-5.74s8.69-7.72,14.72-9.69c.88-.29.3,2.35-.38,2.86-4.24,3.13-3.47,7.29-1.48,11.51.87,1.86-5.06,4.72-6.52,2.69-.84-1.18-1.51-2.18-2.35-2.4-1.18-.31-2.48.56-3.2,1.77-1.41,2.38-11.05,4.11-12.18.05-2.13.58-3.66,2.19-5.67,2.27Z" />
            </g>
            <g class="prov-group" id="prov-CB" data-label="Colombie-Britannique">
              <path id="CB" d="M51.62,726.27c.29-1.84-4.18-1.54-2.25-11.13,2.75,1.13,7.69-.14,9.22,2.49l6.15,10.56c2.55,4.38,6.03,8.74,11.62,10.45,1.18-.27,1.58-1.43-.24-1.53-.85-.49-2.39-.72-2.56-1.57-.15-.75,1.77-1.08,2.01-1.91.16-.57-.94-1.13-1.1-1.53-.37-.94,1.2-1.04,2.93-2.18-7.75-2.81-13.61-10-13.66-18.77,0-1.22,3.04-.99,4.26-1.38l-2.63-2.19c1.94-.71,3.67-.35,5.29-1.81-6-3.96.59-10.01,1.25-14.83-1.56,1.02-2.49,2.73-4.15,3.46-.49-.2-1.12-1.24-1.17-1.89-.06-.81,1.18-1.43,1.55-2.28.99-2.27-1.35-4.51-.55-6.83.22-.64,2.08-1.42,2.59-2.16.36-.53-.93-1.05-.31-2.2.66-.52,1.03-1.11,1.17-1.53.98-3.09-5.13,1.26-5.28,2.1-1.9-2.71.46-5.46,2.76-7.47-1.39-2.84-3.13-5.1-2.28-8.25,1.66-6.11,6.76-7.47,8.45-9.85.59-.83.72-3.74-.65-3.92s-2.19,1.12-3.09,1.33-1.76-.6-2.54-1.4c-.48,1.52-1.99,3.46-2.76,2.5-2.07-2.55-.06-5.8-1.93-8.88-1.59,2.61.08,5.28-1.01,8.15-3,3.11-5.59-14.97-4.9-15.12.94-.71,1.86.84,2.77,2.29.64-1.9,1.57-3.55,3.71-4.37.29-.94.21-1.33.63-1.44.95-.24,2.53.27,1.75,2.05.22,2.08,1.55,2.05,1.61-.09-.11-1.87-.15-3.75.39-4.67.94-1.62,3.35-1.16,6.13-1.82-1.64-1.46-3.4-.25-6.27-.88.84-1.52-.64-3.85-.01-5.47,2.2-5.72,10.95-11.94,17.57-9.35-1.61-1.84-3.61-2.28-4.15-5.07,10.84-3.13,3.23-9.93,13.23-16.65,1.32-.89,1.3-2.72,1.74-3.72.7-1.6-3.02-2.03-3.08-3.27-.23-4.62-2.81-8.09-4.87-12.05-1.6-3.06-3.29-6.92-5.32-9.34-.44-1.76,1.52-5.62.05-7.57-.24-.6,1.53-1.85,1.83-2.43.39-.75-.15-2.2-1.1-3.42,3.01-2.32,2.4-5.89,2.71-9.48l1.14-12.99c.31-3.55.42-7.51,1.19-10.9.64-2.81,1.61-4.29,1.04-7.28l-2.06-10.78c-.86-4.51,1.19-8.7-1.13-13.29-.73-1.44,6.14-11.45.59-14.41-1.32-.71-4.1.35-9.35-2.22.64.32-7.46,3.35-8.17,5.21l-14.88-.95c8.44-11.58-2.97-26.32,4.63-29.29,59.73,48.8,125.63,88.75,197.61,118.59l-58.83,142.88c-2.43,5.91-.35,12.48,4,15.07-.15,4.66,3.56,6.06,6.24,7.38,2.19,14.17,2.79,3.78,2.26,21.67l5.53.68c-.44,2.07-2.1,3.12-1.49,4.7,1.96,5.12,6.91-.05,8.96,17.49.84.91,3.14.17,4.06-.1l2.54,12.61c.38,1.87,1.78,4.27,2.07,5.97,2.62,2.02,3.87,4.19,3.56,8.01,0-.06,3.57,13.44,6.23,10.61,3.32,4.15,1.62,9.81.92,14.82-1.66,11.9-6.8,3.46-.87,20.68.76,2.21,4.38,2.75,2.68,5.86-46.05-14.23-90.84-31.43-134.26-52.12-1.72-.82-2.82-3.1-4.34-2.99-2.6-1.53.63-4.96.83-6.87s.88-4.84,1.75-7.27l-3.91,2.43c-1.61,1-7.39-2.73-7.37-9.45,0-.63-1.94-1.44-1.06-2.22,1.29-1.15,2.64-.05,4.67-.15.91-.05.68-2.29-.55-2.51s-2.81,1.59-3.98,1.22c-3.17-1.01-6.71-9.01-4.4-11.25.57-.55,2.38-.92,2.37-1.59s-.47-1.85-.86-2.21c-1.02,1.69-2.12,2.07-3.43,2.38s-1.99-1.34-3.15-2.95c-.69.83-.41,2.14-1.85,2.67-.49-2.05,1.07-4.06-.67-6.28-1.54,5.02.01,10.01,1.11,15.22.16.76-1.18,1.95-1.24,2.94-.06.82.81,1.16,1.12,2.1.62,1.89.08,3.99,1.93,5.6,11.38,9.88,5.3,11.71,8.17,20.28,1.17-1.12.91-2,1.26-2.54,1.28.99,1.64,2.41,1,3.7-.46.92-1.97.96-3.8,2.29,2.5,1.56,3.08,5.23.86,6.8-1.53.38-3.64-.09-5.26.06-6.73-5.11-11.56-11.62-16.3-18.61-.78-1.15-3.5-2.73-1.69-4.26,1.23-1.04,3.85.04,4.66-2.85-3.85-2.01-9.27-1.81-10.03-5.26-.37-1.7.42-2.88,2.74-3.76l-7.74-7.15,4.12-1.83c-2.38-1.06-4.97-1.17-6.58-3.47-1.17-1.67,1.57-4.19,3.83-4.73-.81-.83-1.25-1.63-2.03-3.41-.97.66-.34,2.01-1.33,3.03-1.95-.52-2.83-2.7-2.68-4.08.11-1.02.84-2.07,2.45-3.46-.5-.75-2.52-.45-2.97-1.54l-4.04-9.9c-.62-1.51-3.79-.77-4.51-2.39,2.59-.7,2.74-2.57,2.3-4.92-.16-.83.78-1.85.88-2.47Z" />
            </g>
            <g class="prov-group" id="prov-AB" data-label="Alberta">
              <path id="AB" d="M239.94,799.84c-1.38-11.18-2.85-5.93-6.23-16.35-.63-1.94-2.65-14.33-3.71-14.94-.66-.37-1.78-.1-3.43.27-1.07-3.64-1.75-8.54-3.25-12.25-.48-1.2-4.98-.81-4.55-2.78.52-2.31,1.73-3.58.04-6-1.02-1.46-2.56-2.53-4.49-2.7-.96-3.13.88-7.16-.31-9.78-4.45-9.81.07-14.29-7.92-12.43,1.16-9.89-9.13-3.57-3.42-12.35l-.48-8.43,57.88-139.54c38.21,16.12,76.52,28.02,116.16,38.03l-4.39,17.08c-1.51,2-4.44,1.1-5.98,2.26-3.29,2.47-5.16,5.86-9.92,7.32,3.06,2.22,5.43,3.66,9.19,4.82-.25-3.89,1.55-6.96,5.07-6.21l-58.38,242.58-62.96-17.4c-.79-1.85-.54-3.67-1.19-5.85-2.54-1.32-4.31-3.48-4.19-6.51.55-14.2,6.48-15.64,2.14-31.31.54,1.94-5.38-4.94-5.7-7.55Z" />
            </g>
            <g class="prov-group" id="prov-SK" data-label="Saskatchewan">
              <path id="SK" d="M461.91,753.59l-.8,21.36c-.47,12.46-1.16,22.64-1.85,35.04l-1.01,18.01-.97,15.07-.68,19.36c-.21,5.84-.78,11.54-.45,17.56-1.34,4.32-1.12,8.39-1.31,12.83-47.5-5.07-94-12.61-140.57-24l58.75-244.49c3.28.06,6.08-.87,8.32-1.42,7.04,2.8,15.85,3.44,22.25-.98,1.88.68,3.25,3.23,5.52,1.58l-11.33-5.19c-3.07-1.41-6.14-.93-9.38-.66.26-1,1.82-2.56.69-2.74l-2.39-.38-1.52-2.7c-1.87-3.32-7.81.21-9.85,4.13l3.24-14.91c31.17,7.52,61.53,12.63,92.76,16.46,1.85.23,3.07,1.55,2.85,3.5l-3.92,35.13c-.33,2.94-1.61,11.61-2.47,13.04-.5.84-1.92.13-2.96.79-2.68,1.7-3.8,6-5.48,8.31-.46.63-3.15.39-2.58,1.92.34.94,2.13,1.28,2.63,2.95.32,1.06-.66,2.43-.48,3.88.29,2.3,2.71,6.01.34,8.41-7.23,7.32-9.72,5.88-10.92,9.48,2.37-1,3.43.61,4.26.4,1.32-.34,1.65-2.3,2.76-2.93,10.71-6.04,5.63-7.72,8.17-7.86.81-.04,2.92-.46,2.6,1.45-2.48,15.06-2.95,29.74-3.36,44.84-.11,4.06-.66,7.92-.85,12.75Z" />
            </g>
            <g class="prov-group" id="prov-MB" data-label="Manitoba">
              <path id="MB" d="M559.02,807.61l-.05,90.33-35.83-.15c-3.71-.02-7.27-1.41-11.13-.83-1.21.18-49.18-2.36-53.89-4.51-.04-5.91-.15-12.03.18-18.22l1.22-23.48.58-15.79,2.3-37.79.76-15.2,1.1-27.94c.92-23.32,1.96-45.63,5.54-68.18.4-2.53.32-4.75.38-7.51,2.77-2,5.38-4.89,5.58-7.94-.94-.54-2.44.08-3.07-.04-.7-.12-1.38-1.23-1.27-2.28l5.52-50.06,21.15,2.1,16.9,1.13c5.07.34,9.87,1.32,15.11.64,4.5-.59,8.7.79,13.06.9l20.74.5c2.69.07,1.76,20,2.06,22.14l4.65,8.16c5.21-1.32,10.89-2.74,15.67-.59,2.45,7.99,7.2,24.63,10.91,31.94,2.3,4.54-1.25,9.6-3.72,12.93,3.74-2.69,5.38-3.95,9.12-3.78,4.96.23,9.06-4.46,13.86-5.59,5.16-1.22,9.76,3.39,14.4,4.61l12.86,3.39-60.64,79.51-22.1,22.91c-1.76,1.83-1.92,5.33-1.93,8.7ZM531.72,841.88c3.19-.6,2.84-3.34,5.4-2.52-6.68,2.79-7.43,19.78-4.66,25.45,8.81-3.34.78-12.16,9.01-8.11-.27-4.49-2.12-12.32,1.21-15.46-3.22-2.12-3.78-5.43-4.91-8.31-.98-2.5-2.61-3.62-2.43-6.72s-3.91-6.14-3.92-9.29c-.01-4.2-.04-8.1-2.65-11.69-2.39-3.28-2.01-6.37-2.51-10.01-.77-5.56-4.06-11.22-5.78-16.62-8.34-5.32-5.33-4.22-16.21-5.54-1.64,3.56-1.18,7.06-2.97,10.31-1.07,1.94-2.85,3.81-2.23,5.97.37,1.29,1.17,3.85,3.08,4.35,2.96.78,5.74.1,8.9.76l-7.62,2.72c-.73,4.83,3.65,7.25,5.31,10.73,1.22,2.56,1.35,4.27,4.39,6.23,1.44.32,1.51,11.04,5.36,9.55,1.31-.51,1.08-4.9,2.5-5.18.59-.12,1.6-.21,2.52.05-.41,1.49-1.4,2.5-1.32,4.52,1.41-.97,1.98-2.27,3.62-2.77,4.13,5.79-.87,10.57,2.09,15.5,1.46-3.49,2.33-6.41,5.99-7.37,3.06,7.87-2.06,6.65-2.16,13.44ZM487.28,829.26c1.85.83,2.95-1.58,2.76-2.73-.24-1.38.94-3.47-1.14-3.42.09,2.86-1.36,3.51-1.96,1.25,1.71-2.18,2.6-4.52,1.52-6.71-1.68-3.4,1.43-5.51,1.79-8.39l1.64-13.13-12.56-6.66c-2.14.77-5.17,1.73-7.36-.36-.89.18-2.01,1.45-1.24,1.9,2.18,1.27-.69,7.23,3.43,7.82,2.57.37,3.12-3.54,2.34-5.25,1.1-.39,2.28.15,2.84.62,1.2,1.01.01,6.38,2.82,7.83,1.19-1.75-.34-2.98-.02-4.83,3.78.09,2.94,3.38,3.43,5.39.94,3.87-.41,4.98-1.91,8.02-.79,1.59-2.65,15.82,3.62,18.62ZM514.53,861.54c-.99-9.11-11.47-13.64-11.55-15.1-.39-6.8,6.16-3.94.88-9.31-2.11-2.15,1.59-9.67-2.28-8.99-1.02.18-1.61,1.08-2.21,1.9-1.04-1.77-.68-4.06-2.29-3.71.05,1.33.75,1.82.55,2.08-.27.35-.94.76-1.2.55-.36-.29-.68-1.07-.66-1.64.06-1.93-1.79-2.42-3.04-2.17-.69.14-.94,1.39-1.26,2.28-.23.65,1.41,1.38,2.19,1.75,8.48,4.06.82,10.46,8.16,10.61-.28,2.11-2.08,2.41-2.57,3.73,2.22,6.29,5.64,11.71,6.27,18.07.11,1.07-2.34,2.47-.98,3.42.73.51,1.63,1.39,2.98,1.7,2.21.52,7.35-1.91,7-5.17Z" />
            </g>
            <g class="prov-group" id="prov-Yukon" data-label="Yukon">
              <path id="Yukon" d="M193.03,410.41c-2.28,3.6-.41,6.77.68,10.07.61,1.83-1.96,3.12-1.51,5.04,1.51,1.3,2.18,3.52,1.36,5.27-.59,1.25-2.39,1.99-5.05,2.04-.28,3.07.87,5.89.93,8.91-3.82,4-3.5,9.09-2.88,14.15-.51,2.06-3.89,4.88-3.7,7.63.21,3.11,5.18,4.23,6.45,6.88,1.6,3.33.72,7.67.94,11,.3,4.54,2.84,6.41,4.97,9.1,2.25,2.84-3.29,6.27-3.48,8.62-.22,2.74-.4,6.32-1.06,8.45,1.89,3.67,8.2,3.05,10.83,4.88,4.76,3.31,8.72,6.5,15.38,4.88-5.66,5.79-.96,10.52-3.14,21.71-6.17-2.22-10.68-5.27-16.62-8.34-50.08-25.91-96.81-57.25-140.4-93.36,1.41-2.9,3.72-2.98,5.54-5.35-10.1-9.74-10.27,1.47-12.85-8.65-1.35-1.44-3.23-.77-3.69-1.79-.97-2.14-2.52-3.86-.81-5.79l151.88-171.92c8.71,9.64,8.81,5.69,10.58,19.12.28,2.1,1.23,3.67,1.23,5.9,0,5.34,3.82,9.75,5.85,14.53l-16.76,23.08c-2.15,2.96-1.25,8.08-4.83,10.53-1.65,1.12-5.22,4.75-3.3,6.26l13.95,10.99c1.16.92.98,3.14,1.4,4.23s-2.59,1.6-2.62,2.63c-.13,3.73-1,6.2-4.85,7.42,1.36,8.21-2.52,4.04-3.03,8.43-.15,1.27-.28,3.22,1.85,3.83.77.22,1.77.33,2.76.53.64.13,0,1.28.68,2.38,2.18,2.03,3.61,6.8,1.38,9.33-2.56,2.91-7.03,3.19-10.24,4.87-1.19,1.98.79,4.51-.62,6.39-.77,1.02-4.48,2.59-3.04,4.38,3.31,4.1,3.21,7.9,1.25,12.48-.8,1.86,3.25,2.6,4.49,3.13,2.03.85,1.26,4.35.13,6.15Z" />
            </g>
            <g class="prov-group" id="prov-TNO" data-label="Territoires du Nord-Ouest">
              <path id="TNO" d="M216.89,529.68c-.39-3.7-1.99-6.32,1.29-9.71,1.26-1.3,1.32-5.68-1.29-5.8-3.12-.14-7.63,1.8-9.84-1.01-7.36-9.35-6.09-1.29-13.27-6.99-.03-6.41,5.84-13.67,3.55-16.61-2.84-3.63-4.56-6.6-4.83-11.85l-.55-10.51c-1.68-1.53-4.5-.86-5.51-2.44-2.07-3.2,2.46-5.61,3.34-7.91-1.57-4.71-2.53-11.44,3.07-13.96-.37-2.89-1.15-4.93-1.14-7.47,1.42-.89,4.58-2.23,4.14-4.4-.69-3.41,1.78-6.05,1.67-8.87s-4.45-6.53-2.8-9.36c3.06-5.24-.57-8.54-1.6-12.81l-2.95-12.27c1.52-1.83,3.74-1.61,4.29-3.1s-.38-3.27.26-4.4c1.63-2.88,10.71-3.84,9.08-9.06-.97-3.11,2.55-3.77,2.77-5.35.59-4.21-5.56-7.06-8.95-7.19l2.76-5.76c1.28-2.67,1.96-3.88,3.48-6.06,2.26-3.25,2.34-8.07,5.25-11.53l-17.96-13.58c5.39-3.63,6.87-8.79,8.92-13.85,3.41-8.43,11.64-14.73,16.45-23.18,6.22,3.43,1.38,8.05,5.38,9.34,1.5-4.42-5.75-14.6,1.61-9.05-.74-2.3-.4-4.58,1.86-5.47,9.97-3.93,12.48,6.17,17.25-2,2.26.93.01,2.85-.2,4.54-.14,1.14,1.72,2.38,1.27,3.42-.49,1.13-2.29.56-3.86,2.53,2.82.64,5.77.97,9.2,2.42,6.34-8.3,7.45,1.65,18.38-2.67,2.66,5.24,2.76-1.16,7.03-.71,2.91.3,3.34,3.75,5.87,3.7.68-.01,1.63-1.64,2.74-1.4,1.61.34,1.16,3.75.36,4.72-2.48,3-15.63-3.01-20.47,8.22,3.58.68,5.6-2.83,8.62-3.69-.99.28,13.92-1.3,9.92,4.09,4.26.81,9.15-1.03,10.75-5.5,2.17-.27,3.58,1.12,6.33.46-1.01-1.71-2.16-2.64-1.96-3.85.32-1.89,1.99-4.98,3.57-3.94,7.18,14.67-7.26,24.95,4.84,36.75.64-.48,1.57-.69,2.16-.98,1.08-.54-.42-.75-2.13-2.13,2.39-1.72,2.54.62,3.96-3.47,9.25-2.96,6.69-9.48,11.69-7.09,1.02,3.44-2.65,5.88-4.23,8.45.32,1.2,1.88,1.53,3.16,2.34-2,2.02-4.98,2.49-6.38,5.93,1.76.79,4.55,2.82,6.65,2.71,4.11-.22,6.75-3.41,8.95-6.79,6.33.94,13.28,6.22,15.38,12.1l-15.79,37.49,55.49,85.77,17.96,5.24,9.88,19.21,74.57,27.73-11.65,102.41c-90.59-10.35-176.59-35.4-257.48-74.67-3.13-3.41.07-7.64-.29-11.16ZM310.99,439.62l3.9.69c.73-3.23-2.33-4.74-2.8-7.68,1.57-.02,4.06-1.89,5.55-2.05,3.54-.38,8.44,2,8.8,6.09l4.8-2.51c-4.37,2.29,15.46-13.25,12.86-11.61-1.54-1.09-2.49-.53-3.68-1.19l3.16-3.74c-5.46-3.38-6.96,6.48-13.21,2.89s-4.08.3-14.94-8.45c6-4.53,11.99-3.43,18.74-5.61-3.4.08-4.47-5.47-7.31-5.91-19.45-3.05-10.89,1.76-29.1-.24l-7.9-.87c-5.89-.65-11.68.57-17.07-3.79-2.32,3.29-2.02,6.37-1.53,9.15,2.65-.99,3.45-2.49,4.89-3.42,2.09-1.36,3.18,2.46,4.43,2.91l6.8,2.41c.87.31,1.52,2.77,2.04,2.17l2.1-2.43c9.5,3.01,9.29,2.71,13.48,11.23-4.76,3.66-6.52-.68-10.91-4.15-.81-.64-2.43-.36-1.93,1.34s2.19,3,1.62,4.47c-2.03,5.24-8.04-.33-9.98,6.58-1.51,5.38-7.9.11-8.5,1.75-.84,2.31,9.2,9.78,11.15,10.68,4.62,2.15,6.97-3.03,8.86-5.88,2.27-2.36,7.44-2.3,10.43-2.31,2.06,0,2.39,2.16,2.22,3.31-.5,3.39-9.33,7.24-12.41,8.28-1.36.13-2.99-1.35-4.01.26-.6.95-.23,1.86.55,3.33.43.81,21.93-5.15,18.88-5.69ZM347.23,554.4c1.04-.27,3.02-.59,3.9.42.39.45.08.9.89,1.99,12.63-2.06,9.55-5.9,19.67-9.79-.95-1.71-2.46-1.39-3.83-2.94,5.23.64,8.31-3.15,12.24-4.5,1.96-.67,3.61.08,5.37-1.54,2.46-2.26,6.84,1.13,9.95-2.24-24.55-9.14-20.63,6.67-39.23,6.01-4.58-.16-6.13-2.06-8.9-6.55l-4.05-6.57c-2.09.25-3.49-.32-4.84-2.2l-4.99-6.96c-1.44-2.01-3.55-3.53-5.78-5.82-.1,4.27,1.42,9.13,5.98,9.95-2.46,2.71,1.43,13.77,2.96,15.37-3.17,3.38-6.51,1.91-9.83.11-1.52-.11-3.89,2.62-4.37,4-.91,2.6-3.06,1.97-4.39,2.97-1.25.95-.77,4.1-2.52,4.61-1.83-2.28-4.51-5.6-7.2-5.22-1.25.18-2.41,2.38-4.22,1.95-1.28-.3-2.35-.93-3.43-2.07-1.19,1.81.58,4.05,2.32,4.55,9.66,2.75,1.03,6.29,17.18,11.63,4.55,1.51,9.03-.47,13.75.68,1.89.46,6.84,1.89,7.49-.55.97-3.62,1.03-6.04,5.87-7.31ZM402.63,538.45c2.86-1.76,2.51-3.08,1.85-4.45s-18.91-13.33-28.11-2.38c13.5.02,15.95-5.09,26.27,6.83ZM422.65,347.66c-.3-.67-.68-1.24-1.25-2.8-1.17,1.48-2.18,2.34-3.59,2.5-2.32.27-3.49-.83-5.38-1.77l-8.73-4.36c-3.07-1.54-22.61-4.68-25.55-2.43,9.8,4.61,19.63,7.01,29.74,10.25,2.94.94,5.41,1.57,5.39,5.02-.05-6.41,17.14,2.94,21.71,2.01l14.14-60.05c.79-3.36-3.72,3.14-3.15.39.19-.9.78-1.29.45-2.39-2.63.95-4.11,3.48-4.89,5.96-.66-1.11-2.02-3.22-2.63-2.67l-1.45,1.34c-1-1.11-2.24-.56-3.38-1.17,2.8-1.19,8.22-3.59,8.45-7.4-1.79-4.15-6.53-8.81-10.65-10.66-4.76-2.14-3.61,7.92-7.15,7.21-.66-.13-1.53-.92-2.61-1.01-1.01-.08-2.04,1.09-2.6.91-6.52-2.12,11.2-5.34,5.41-19.78-8.34,2.11-16.97,1.33-24.26,5.1l-7.88,4.07c-1.6.83-5.13,2.79-4.33,4.6.57,1.29,3.79,2.42,2.19,4.12-3.81-.66-14.08,6.55-12.49,11.41,9.25,5.07,7.8.04,8.33,4.95.16,1.46-4.74.54-4.99,2.47-.12.91,4.07,3.28,4.83,2.01l1.08-1.81c1.7,6.86,13.43-.49,18,6.97-.67,2.99-4.37-4.23-6.86-.97-.28.36.98.67,1.34,2.24-3.8-2.01-7.58-.12-11.94-.14-3.56-.01-8.81-1.77-11.48,1.68,2.23,13.57,5.48,11.44,12.96,13.99-2.07-.7,4.32,4.09,12.75,4.07,3.71,0,7.2.4,10.43,2.94l8.32,6.55c1.8,1.42,2.51,3.79,4.29,5.62.56.58-2.05,2.14-2.55,1.02ZM374.47,278.03c2.07,1.43,3.46,2.23,5.39,1.37,6.49-2.9,1.22-5.1,9.73-7.65,7.24-2.17,13.96-4.73,21.57-6.59,2.48-.61,7.42-.58,8.39-3.5-.85-6.53-4.07-12.66-6.22-19.04-1.59-4.72-7.16-7.51-11.34-4.62-.6.42-.5,1.95-.99,2.39-.39.36-1.66.76-1.74-.44,5.31-6.28-5.02-3.08-8.72-16.56-.79-2.87-4.27-1.89-6.5-2.41l-16.75-3.91c-.21,4.5-1.78,8.51-1.53,12.69.06.98,1.14,1.83.96,2.63-.19.87-12.32,10.31-14.54,12.71.22,1.87.75,3.02.58,4.71-1.68.19-2.85-.41-4.62-.65-2.22,11.65-6.51,3.14-14.87,17.5.95,1.15,3.32,1.21,3.42,2.44.63,8.05,8.43,3.69,4.38,23.87-.34,1.7,1.05,2.79,1.89,3.49.94.78,10.83-2.76,12.81-4.18,1.91,2.39,4.11,3.57,7.59,2.71,5.76-1.44,3.11-6,9.36-12.23,1.23-1.23,1.14-2.79,1.72-4.72ZM446.67,219.87c-2.93,1.32-4.93,3.98-4.81,6.69,1.56,1.21,3.94,1.07,5.24,1.21,1.82.19,1.87-1.71,3.19-3.58.9,2.83,1.84,3.9,4.18,4.41s3.62-.85,6.51-1.25c-8.32,11.88-8.85-.47-18.19,6.18-1.53,1.09-6.3-.9-7.33,1.96-.78,2.17,1.36,6.88,4.09,8.08,7.65,3.36,18.86,1.05,23.65-6.06,2.77-4.12,2.19-10.02,3.69-14.44.81-2.4-5-1.41-6.13-3.3s1.3-6.17-.61-6.94l-2.89-1.17c.85-1.03,2.41-1.82,2.11-2.83-.35-1.18-1.73-2.11-1.84-3.19-.24-2.44-.41-3.81-1.64-5.05-1.71-1.73-3.72-.32-5.9-.84-2.66-.64.5-5.07-.24-6.8-1.73-4.08-8.78-2.52-10.83.87.95,2.27,3.59,1.18,4.26,3.43-2.85.53-4.92-2.23-7.53-1.51-2.36.65-3.58,4.13-4.4,5.89,3.82,2.52,7.82,1.17,10.92,4.03-5.59.25-15.44-4.28-16.09,1.66,3.24,4.49,7.29,1.61,13,2.15-2.62,1.24-6.07,3.94-9.43,2.82-3.15-1.05-8.19-2.56-9.48,2.04-.98,3.5,4.21,5.76,7.38,4.85-.32,2.65-.14,4.97,1.86,6.47.93-2.06,1.91-3.47,4.24-3.03-.21,1.31-.7,2.52-.41,4.14,2,.42,3.77-.31,5.14-1.79,1.01-1.09.54-2.98.16-4.89,1.63-.18,1.61,1.1,2.34,1.48l2.23-4.03,3.55,2.35ZM431.38,178.44c-.45,2.35-5.9,10.4-1.62,12.31,1.17.52,3.53.28,3.74-.8l.77-4c.46-2.39,5.2.15,6.86-1.69s.9-4.81.41-6.7c1.12-.28,2.18.28,2.99-.23-.92-1.59-1.62-3.25-1.29-4.67.47-2.07,5.94-1.93,6.21-4.16-.07.58-1.14-6.48-3.8-6.42-.89.02-3.02.74-2.9,1.78.11.93,1.3,1.91.22,2.48-3.61,1.92-7.29-6.57-12.8-3.79-7.1,3.58-7.64,8.16-18.5,11.31-1.27.37-1.98,4.31-4.54,3.16-2.79-1.25-4.3-.63-6.96,1.39-1.66,1.26-.69,2.32-1.53,5.11-.31,1.03-3.16,3.86-.48,4.12,1.23.12,2.79-2.29,4.26-1.52,1.06.55,1.48,2.34,2.14,3.26,1.97-.04,3.01-2.42,4.85-4.01.27,3,.56,6.23-.82,9.05,2.28.54,5.55.47,6.78-2.07.99-2.05.4-4.77,2.39-6.44.91,2.17-.44,3.65,1.25,5.36,1.77-2.1,4.85-3.26,4.86-5.41l.03-4.38c6.43,3.54,2.35-7.02,7.49-3.04ZM479.78,170.39c.91-2.64.69-4.16-2.77-6.7,1.94-.68,4.06-.83,4.63-2.22.36-.87,1.76-3.29-.29-3.65-4.08-.71-11.15-.72-14.75,1.94-4,2.97-2.45,10.85.69,13.67,1.78,1.6,4.04-.25,5.63.04,3.36.63,5.67.32,6.85-3.08Z" />
            </g>
            <g class="prov-group" id="prov-Nunavut" data-label="Nunavut">
              <path id="Nunavut" d="M579.94,155.39c-2.65-.92-5.5-.5-8.51-1.08-1.12-.39-1.58-3.04-1.82-3.98,1.61-.41,2.34,1.22,3.46.12-2.61-1.14-5.39-2.06-5.94-5.64,1.2-1.2,2.77-3.4,4.36-4.39,4.82-.64,9.25-1.23,13.62-2.98-2.16-1.65-7.73,2.17-9.05.91-.45-.42-1.28-1.3-.76-1.76.92-.82,2.21.19,3.75-.87-.6-1.26-1.75-.84-2.95-1.14-.69-.17-.3-1.48-.94-2.58-3.19.57-4.68,2.98-7.3,5-1.09-1.37.11-3.12-1.47-4.06-.76,1.71-1.34,2.77-2.31,3.5-1.89,1.42-4.55-4.16-3.83-5.7.36-.76,1.45-1.31,2.69-1.82l2.49-1.03c.96-.4.9-1.71.16-2.82-1.14,1.41-4.07,3.72-6.09,2.5-3.95-2.4-3.65-12.82-4.23-11.94,1.39-2.09,3.05,1.72,7.09,1.41,1.44-.11,1.7-2.47,3.98-3.11,1.34-.71-.34-1.12-.93-.92-.86.29-1.43,1.23-2.31,1.45-2.78.7-6.19-2.41-6.48-4.93.44-.88,2.83-1.19,2.96-2.15.17-1.23-.56-2.57-.43-4.11,3.11-.99,5.72,1.44,8.39.14-.57-.97-2.05-1.2-2.15-1.98-.12-1,1.26-1.37,1.62-2.18-2.31-.47-4.81-1.17-5.45-3.2-.44-1.39.35-4.89,2.34-4.64l2.39.29c.65.08.05-1.71.52-2.02s3.59.66,3.97-1.21c.24-1.18-.72-2.03-1.99-2.25l-2.17-.38c-.61-.11-.71-1.59-.41-2.14.28-.52,1.59-1.12,2.61-1.17,9.04-.44,7.45,16.36,16.18,21.89.76.05,2-1.42,2.55-1.11,2.95,1.65-.02,6.65,2.63,9.49,8.91,9.52-3.35-7.56,2.77-7.46,1.92.03,2.69,2.21,3.03,3.3.55,1.73-.92,3.54-1.34,4.77,6.93,1.66,5.55,9.96,4.53,15.65,1.35-2.12,2.56-4.67,5.57-3.74.28,1.55-.53,2.92.58,4.86,1.51-1.17.76-2.91,1.33-4.77,1.89,2.39,3.3,5.12,4.96,7.99-8.66,10.24-5.41,3.01-11.26,17.2-2.39-.98-2.54-3.8-2.06-6.72-.27-2.18-1.41-2.11-1.58.03l2.17,11.15c.17.87-1.43.93-1.81.36s-.59-1.58-1.74-1.93c.19,3.91,1.44,7.5.12,11.29l-6.4-8.84c.34,3.56,3.04,5.61,2.63,9.21-1.89-1.19-1.97-4.26-5.21-3.71.62,1.22,2.06,3.03,1.17,4.37-5.59.38-11.7-1.96-14-7.74,1.68-1.41,3.39-.89,6.26-2.61ZM591.78,558.7c-3.08-.68,13.27-1.46,6.77-3.19-2.63-.7-5.75-1.71-6.01-4.68,14.49-1.48,5.44,4.17,18.95-5.18,1.08-2.83.4-9.3-1.8-10.17l-7.85-3.11c-1.51-.6-1.92-3.16-3.39-3.31s-2.62.3-3.94,1.95c-.84-.4-.92-2.95.16-2.85,2.39.23,4.3-.48,6.17.13,4.09,1.32,6.93,4.58,10.67,5.38.36-1.21-.44-1.5-.96-1.74-.84-.39-.46-.75.24-.96l1.64-.49c2.15-.64,3.86-.88,4.88-2.67-.66,1.16,2.94-11.36.75-11.82,4.68,3.25,10.86,5.03,15.39,1.76,4.11-2.98,5.18-7.4,7.02-11.44,1.2-2.63,9.91-18.76,4.42-20.01-6.5-1.49-19.2,3.34-23.35-3.95-2.21-3.89-5.64-6.81-10.6-7.68,1.76-1.6,3.54-.64,6.24-.86-.24-1.46-2.57-.2-1.92-2.1,7.05,2.37,12.43,7.05,18.32,11.97,2.12,1.77,8.77,2.11,10.26-.49,1.88-3.27,3.46-5.85,5.53-8.76,1.6-2.25.44-5.87,3.44-7.74,1.39-.87,1.48-3.45.92-4.91-.72-1.87-6.45-2.06-8.21-3.79-.71-.7-.87-3.78.36-3.84,4.39-.21,8.24.66,11.12-3.56l3.13,6.36c1.11,2.27,5.76,1.84,8.47,1.67-.63-1.55-2.37-2.16-1.92-4.19l9.3,4.1c-.69-6.45-8.55-9.51-5.44-13.62,1.62,5.99,4.89,2.09,6.93,8.53,2.57-.29,2.54-3.66,4.09-4.87,4.23-3.3,7.66-7.21,8.01-12.89,1.16-.9,3.3-1.14,3.81-1.99,1.19-2-.38-12.25-.05-11.3-1.57-4.54-7.21-6.04-9.82-9.73-2.24-3.16-2.53-7.23-5.32-9.39,2.25,0,3.45.38,3.86-1.18-2.04-.67-3.09.11-5.44-.26-.09-1.3,1.31-2.12,1.89-2.91,1-1.38,2.45.32,3.33.33,2.89-2.7,6.97-5.85,4.36-9.57-.7-1-2.31-1.21-4.61-1.79-.32-2.02,2.41-3.81,2.14-6.29-.29-2.78-5.94-3.42-8.05-1.09-.58-2.38-2.21-3.77-3.97-5.43-.96-1.12-.91-3.24-1.38-4.66-3.14-.03-6.23,1.99-9.52.98-.37-.71-.06-2.18-.56-2.25-1-.13-1.42.65-2.07,1.74-7.23-4.25-4.58-.09-12.58.9.01,4.89.53,10.23,2.97,14.68.96,1.55,3.95,1.3,4.69,2.95.92,2.08-1.26,3.89-1.95,5.32,13.07,2.92-5.68.4-4.14,6.11.9,3.34,1.65,15.84-2.69,19.71-1.63-2.85-1.2-6.64-3.34-9.5-5.1-1.01-2.1,10.75-1.23,11.91,1.31,1.75,4.18,1.61,3.93,3.85-.21,1.85,1.17,4.94-.74,5.77-1.12.48-2.5-.24-3.03.9-1.13,2.42.85,1.08-2.64,5.49-5.62-11.86-13.53-9.48-12.65-27.76,1.52.86,1.6,2.24,3.49,2.89,1.91-3.28.21-6.33-.7-9.56-2.94-10.39-7.12-7.97-10.85-14.29-.69-1.16-2.07-1.39-3.11-1.07.95-.3-5.91,7.42-2.72,13.64l-1.88-2.15c-.34-.38-.53-.1-.62.19-1.4,4.65,4.51,6.07-1.09,13.66-8.1-7.25,2.8-14.34-10.63-24.51,3.21-1.23,5.25,1.11,8.03-.69.82-2.15-1.57-5.92-4.26-5.89-1.08.01-2.98.49-3.94-.08-.7-.42-.73-1.15-1.53-2.56-2.54.77-2.79,3-4.72,3.98-2.39-2.73-4.45-5.17-8.24-5.02,2.43-2.47,5.12-4.66,6.47-8.19.51-1.33-6.45-2.49-2.79-3.38,1.64-.4,4.14,1.79,5.72-.22.65-.83-.38-5.36-1.6-4.5-.36.25-.67.59-1.42,1.46l-2.44-7.81c-11.73-7.41.74-14.21-12.31-24.8-1.06-.86-.87-4.96-3.53-3.79-1.2.53-1.68,1.21-2.89.18-2.88-2.44,3.52-3.59-2.2-6.29l4.23-1.22c1.43-3.29,4.27-6.28,4.95-9.87s-3.34-6.69-6.16-8c9.34-2.82,13.36,2.65,16.42.01,3.42-2.94,3.52-8.03,4.91-12.09,2.79-8.16,6.06-11.57,6.9-17.59-4.6-4.34-10.33-1.28-13.66-2.2-3.74-1.03-6.68-5.34-11.52-2.62-2.42,1.35-8.29-.01-9.8,4.37-.98,2.85,1.76,5.69,3.45,7.44-2.63-.19-2.71-1.85-3.61-2.24s-2.65-.33-2.62.91l.27,13.39c.12,6.13-.85,10.81,2.34,16.21,2.16,3.65-.02,8.72,1.13,12.73.22.77,1.5,1.42,1.52,1.87-.1-1.86-7.08,9.46-7.94,10.18.97,1.31,3.18,1.78,3.18,2.84,0,.96.28,3.2-.92,3.16-1.72-.05-2.6-2.04-3.53-2.32-4.01,2.63-2.69,6.18-3.29,9.93-.29,1.82-1.85,4.77-.32,6.82.8,1.07,1.8,2.17,4.17,2.85-3.58,4.02-7.22,9.3-3.95,13.89,1.34,1.88,2.21,4.45,2.97,6,2.61,3.03,7.32,4.28,10.18,6.57,3.19-6.94,2.17,4.06,7.01,3.52,1.5-.17,2.9-1.53,4.4-2.75,1.41,1.67-.16,2.74-.65,3.6-.66,1.18-3.05,1.02-4.17,1.48-2.36.98,0,5.2-.63,7-.8,2.25-4.69,3.23-3.15,6.88.48,1.14,2.67.87,3.67.28.6-.36,1.67-1.03,1.32-2.02-.43-1.2-.45-1.56-.06-1.99,4.75-5.18,3.02,6.9,3.26,9.64-7,4.36-2.07,6.61-10.7,12.41-1.53.39-3.52-1.22-4.89-.36-1.1.69-.9,2.75-1.48,3.87-3.48,6.75,4.5,9.22,2.22,17.52-.51,1.85-2.71.18-2.94-.65-.28-1.05-.14-2.04-.02-3.46-1.97.7-3.08,2.25-5.9,2.55l.93-4.14c-.71-.54-2.14-.8-2.63-1.42-.93-1.18.54-2.11,1.24-3.22-.32.5,4.17-17.71,1.38-14.87-1-.1-3,1.56-3.03.92l-.11-2.78c-7.52,2.94-2.54-6.95-12.87-5.98.18,1.57.62,3.41-1.42,4.14-1.27.46-2.79-1.16-3.78-.9-2.32.6-.13,3.83.77,5.1.96,1.36-1.96,2.19-2.6,2.93-1.26,1.46.27,3.51,1.56,5.44-1.81.85-4.73,2.6-6.73,1.5-2.62-1.43-5.33-1.35-7.85-2.32-10.46-3.99-6.15,3.61-19.05.39-5.47-1.36-12.72-5.63-11.78-11.81-3.13,2.55-6,2.4-9.64,1.5-1.93-.48-1.3-3.65-1.75-5.3-11.76-7.01-1.89-12.51-8.34-17.71-4.66-3.75-12.34,4.79-23.01,2.27-2.19,2.7-4.45,4.88-5.95,7.75,1.49.68,2.78-.52,3.41.2.33.37.04,1.57.54,2.25.94,1.29,2.47,2.24,3.94,1.55.75-.35.06-2.13.63-2.9,1.48-2,4.11,1.07,5.64.6,1.94-.6,4.48-1.01,6.27-.49-.34-4.08,7.34-5.71,8.11-4.52,4.07,6.29-6.34,2.36-10.9,10.26-10.04,1.07-6.87-1.6-12.74,6.18-1.4,1.85,1.31,4.24,1.47,6.15.31,3.54-1.5,7.22-.66,10.92.5,2.19,3.19,3.98,1.89,6.31-.57-.74-.61-1.81-.93-1.79-.34.03-.82.93-1.11,1.48-.61,1.14-1.21.45-2.09-.69-1.52,2.7-.92,5.57-.83,9.01-1.65-3.91-3.74-8.46-4.05-12.82,1.08-1.08,3.5-1.41,3.47-2.82-.21-9.45-4.11-2.76-4.3-13.49-1.88,2-.72,4.97-3.48,5.62-.63-2.95,1.36-5.09.61-7.39-.69-2.12-4.06-1.22-5.22-1.93l-3.69-8.75c-6.25,4.09-12.29,3.59-19,2.83-4.24-.49-7.79-.57-11.75-2.41-4.34-2.02-15.83-7.27-17.75-11.35,2.62-.41,3.14-3.53,4.58-4.39,3.69-2.21,7.62-.56,10.29.12,6.37-3.1-4.7-23.11-12.96-23.02-.48,1.39.72,2.38.04,3.35-5.46-4.58-12.12-6.08-15.36-11.59-8.72-14.8-4.71-3.62-16.04-16.67l-13.99,32.67,54.47,84.36,18.36,4.82,9.94,19.37,74.94,27.98-11.42,104.14c6.65.92,13.05,1.48,19.89,2l14.74,1.12,17.1,1.1,36.56.2c1.68-4.43,1.11-11.77.1-11.11l2.59-1.7c2.11-1.38,7.18-21.1,8.39-18.95-4.4-2.78,3.79-7.95,6.56-10.2-1.24-1.96-2.97-1.64-3.44-4.51,4.39.31,5.52-3.83,6.25-7.11.82-.25,2.11.13,2.48-.39.3-.42-.8-1.69.9-2.12.61-.16.13,2.04,1.41,2.08.51-2.59-1.25-3.74-1.16-6.4,2.77,2.01,5.14,3.79,7.25,1.77-2.95-.95-5.49-1.06-5.15-2.73ZM479.57,393.42c8.27,8.19,22.78,1.06,23.29-2.07.25-1.5-1.18-2.66-1.51-4.55-.23-1.34,1.12-4.53-1.15-4.54-5.29-.04-6.86,4.78-10.02,7.73,6.9-13.84-2.63-8.39.65-14.59,1.75,1.88,3.16,4.87,6.28,4.47,1.91-.24-.16-3.8.64-4.57,1.19-1.14,3.11-1.38,3.68-4.34,1.18,2.16,1.85,4.35,3.92,5.6,1.24-1.06,1.28-2.66,2.41-3.29.34,2.16.1,3.94.81,4.51.51.41,2.89.45,3.04-.2.79-3.5.45-7.44.11-11.17-1.27.21-2.14.15-2.83.21-.48.04-7.77-8.49-9.2-9.15-6.07-2.77-10.44-6.75-11.53-13.79-.18-1.15-2.7-2.23-2.75-3.62-.19-5.63,6.34-6.43,2.44-17.83-2.37-6.95-2.09-14.55-1.97-21.88.09-5.22-2.09-9-5.07-13.2l-3.67-5.16-2.06,2.55c-.98-1.3-3.01-5.28-4.9-4.2-.52.76-2.78,2.86-2.85,4.15l-1.32,26.5c-.13,2.64,3.18,5.21.28,7.55-1.41,1.14-3.55,2.19-3.95,4.06-3.2-2.35-2.01-5.57-2.16-8.77-.25-5.31-.45-11.12.57-16.08-2.49.04-3.08-6.91-6.44-10.72l-17.65,71.99-19.76-4.44c-.88,1.87-2.75,2.72-4.59,2.63-3-.14-2.12-2.77-2.71-5.1l-31.5-9.96c-.31,3.94,1.27,5.94,1.34,8.55-4.94,4.87,4.8,11.79,10.28,12.68,3.94.64,8.64,2.03,10.44,5.91.88,1.89-.9,4.36-1.57,6.48-1.19,3.75-.52,10.15,3.8,11.53,4.47,1.43,9.27,1.61,13.87,2,11.17.95,17.96,4.87,27.3-2.7,13.59,2.43,10.93-3.92,18.39-10.72,3.13,2.05,1.24,6.05,1.82,8.23,18.81,6.68,7.83,5.4,8.66,7.86,1.17,3.47,5.37.92,7.11,1.45ZM538.19,335.06c.92-1.15-1.17-2.75-.62-4.52,2.31.55,4.46,1.25,6.48.98.3-.04,6.9-5.41,7.1-6.85s.67-2.34-2.22-3.08c1.22-.85,2.41-1.13,2.91-2.22.58-1.24-1.05-2.55-1.06-3.77-.01-4.53,4.55,1.68-.38-15.26-5.82,7.2-1.78-2.81-8.04-4.03-1.94-.38-2.7,1.25-4.31,2.75.71-5.39,4.34-8.77,8.65-11.39.8-1.03-1.53-2.91-2.27-3.41,1.16-1.38,3.33-1.08,4.19-2.59,2.09-3.7-3.3-7.78-5.33-6.8-2.76,1.33-7.2,4.58-10.33,2.28-2.45-1.81-3.38-3.47-7-2.61-1.4.33-3.54-.73-4.81,1.07-2.12,3.02,2.91,2.2-5.06,6.79.79,2.17,2.09,4.85,4.3,6.12,1.55.89,3.1-.73,4.02-2.46.6.27,1.24,2.35,1.03,3.19-.16.62-2.46.14-2.26,1.71.34,2.71,3.68,5.1,1.55,8.08-.73-.35-.36-1.85-1.66-2.09-.05,2.09.37,4.37-1.45,5.09-1.14.45-3.39,1.48-4.78.13-2.49-2.4-2.85-7.49-5.94-9.07-3.27-.92-4.44,3.65-4.81,5.57-.51,2.68.39,5.66,2.68,7.53,3.17,2.58,1.72,6.67,4.64,7.16.53.09,1.21-.71,2.1-.8.14,2.24,3.59,2.96,4.47,4.41,2.08,3.45,3.21,6.94,5.92,10.07,3.47,4-.07,9.81,7.83,12.26,1.77.12,3.23-2.72,4.45-4.24ZM550.89,410.76l6.27-6.25c.79-.79,2.35-.21,2.9-.98.83-1.16,1.08-2.12.2-3.06-.82.81-1.95,1.44-2.67,1.27-.66-.16-1.81-1.6-1.85-2.4-.34-7.16-4.94-11.09-9.64-15.45-2.54-2.35-3.65-6.91-7.77-8.38-1.12-.4-5.63,2.96-3.64,8.41l-1.61-1.18c-.55-.4-.59-.15-.7.17-.16.45-.61,1.52-.33,2.09,1.74,3.49-.81,6.75-4.56,7.48-2.32.45-4.69,1.48-4.61,3.78.05,1.48,1.02,4.82,3.12,3.84.86-.4,2.07-3.33,2.75-1.03.38,1.3.65,2.85,1.87,3.25.77.26,1.64-.96,2.82-.98,1.6,3.95,6.57,7.44,11.4,7.55,1.68.04,4.26,3.66,6.04,1.88ZM492.76,273.96c-1.6-1.89-3.29-1.23-6.42-1.51-1.9-.17-5.16-1.12-6.04,1.04-.5,1.22-2.83,2.83-1.59,4.62,1.13,1.62,3.04,2.57,3.61,4.52,1.28,4.38,2.33,8.55,5.06,12,5.42-8.43,11.02-14.03,5.38-20.67ZM613.57,167.84c-.48,1.38,1.58,2.07,2.57,3.67.55.9-.35,2.72.39,3.46.68.68,2,.97,2.32,1.59-.99,1.05-3.09.95-2.17,1.39.25.12.85,1.32,1.22,1.26,1.52-.24,2.91-1.45,5.05-1.3,5.88.39,3.03-7.15,5.63-12.54-1.42-1.79-.28-2.96,1.32-1.3.07,4.98-4,9.97-3.64,14.23l.18,2.11c-.05-.62-4.79,2.61-9.14,1.6-3.66-.85-3.3-3.3-4.24-6.05-1.12-3.28-4.26-5.98-7.45-5.5-2.18.33-7.18,3.2-5.15,5.81s3.12,5.25,4.73,7.64c.8,1.19,2.9.38,2.98,2.96.19,5.6-4.31-.16-10.72,8.67-1.65,2.27-2.8,3.56-1.9,6.77.42,1.5-.89,3.65.87,4.72.85.51,2.29,2.92,3.38,1.08.44-.75-.23-2.18.57-3.06.9-.99-.04,1.48,1.99,1.53.55,2.12,1.14,3.53,3.15,3.63s2.12-2.13,3.54-2.73c.76-.32,2.2,2.35,3.19,1.3.62-.65,1.06-1.43,1.46-2.23,3.44,3.4,8.9,3.39,13,1.77-2.26-2.01-5.5-3.11-4.08-5.88,1.26.92,1.24,2.18,1.94,2.36,1.26.32,2.1.02,2.22-.59.23-1.13-.15-1.92.57-3.26,1.3,1.88,2.88,3.74,5.34,3.84.48-1.61-.35-2.91.88-3.83.85,1.8,1.05,3.33,2.29,3.49,1.51.2,2.6-.42,2.85-1.3.37-1.28.14-2.48.48-4.18,4.2,5.16,3.95-1.52,7.82,1.65,2.34,1.93-1.42,4.84.98,6.8.54.44,1.49.7,2.25-.17,6.21-7.16,5.7-2.29,8.39-12.09,1.39.5,1.97,1.49,2.41,1.15.62-.48,4.73-9.43-2.14-13.23-1.75.87-1.5,3.41-1.61,5.44-2.01-.38-3.37-1.47-4.95-2.22,1.2-2.35,1.57-4.54-.08-6.5-.81-.96-2.52-.75-3.22-.43-.66.31-.91,2.34-1.62,2.22-1.97-.33-3.73-2.09-5.69-1.43-4.28,1.45-2.38,5.41-5.02,1.84-.88-1.19,2.57-1.46,2.96-2.49.89-2.36-3.29-1.18-3.43-3.15-.08-1.13-.65-2.12.2-3.18,1.53,3.13,5.98,6.96,10.26,5.76,2.21-.62,5.76-2.31,7.35-3.8,4.84-4.49,1.83-11.52-3.38-14.79,2.63-3.84,7.44,2.11,11.5-4.68.91-.17,2.61.32,2.65-.37.06-.86.41-2.56-.29-2.91-2.47-1.22-5.31.96-7.93-1.6,3.89-1.34,7.55-1.23,8.98-5.45-2.28-2.12-5.97-1.52-9.74-1.55,2.05-1.97,4.96-1.12,7.39-2.53,2.16-1.26,1.39-3.69,1.28-5.58.88-.14,1.48.11,1.76-.03,1.84-.86-5.85-5.8-11.08-1.34-.36-1.57,1.07-1.82,1.37-2.62-2.87-1.72-5.09-.54-8.28,1.69.39-3.38,3.57-3.66,6.89-4.78-2.14-2.33-4.51.77-7.39-1.12,8.36-4.18,7.48,3.24,15.34-.24-1.32-1.98-2.08-3.25-2.91-5.01-3,2.22-5.92,1.8-9.06,2.26-2.53.37-5.02,2.36-7.31-.21,1.6-.78,3.57,0,3.56-.86l-.05-2.48c.65,1.01,2.13,1.98,3.5,1.91.9-.05,1.27-2.24,2.15-2.44,2.88-.28,4-.35,5.15-2.56,1-1.93,6.16-1.95,6.12-5.63-.02-1.83-1.87-3.51-3.09-4.07-1.75-.8-3.81.73-6.22.27,2.34-4.03,7.21-1.93,10.49-.33,2.13.96,5.28-2.92,5.58-4.94s-1-2.56-1.71-3.17c.78-1.94,2.84-4.08.66-6.13-2.15,1.79-3.05,4.58-6.22,3.91,5.11-5.68,11.6-6.58,5.16-9.28,2.65-.68,3.18-2.71,3.09-5.49-.21-6.29,2.64-12.04,3.65-18.02,1.48-8.73,5.61-12.32,1.41-15.1l-13.01,16.36c-1.67-.96,13.52-17.5.27-10.43.94-2.59,5.32-3.13,6.54-5.81s3.11-4.07,4.73-6.27c3.52-4.77,6.83-21.01-.67-21.1-1.66-.02-3.49.89-4.59,2-1.69-3.13-4.29-5.03-5.94-6.61-.97.34-2.05,1.86-2.64,1.56s-.92-1.68-1.72-1.45c-1.09.31-.32,1.48-.54,1.95s-1.16.46-1.62.89c-2.33,2.16-1.23,6.25-4.95,7.37.39-3.69,3.36-5.66,1.56-8.97-3.97,3.57-21.34-2.41-13.44,6.38-2.82,1.17-2.01-2.39-3.34-3.1-2.35-1.26-4.34,1.67-4.77,3.85s1,3.49,2.76,5.26c-3.59-.68-3.18-3.71-4.78-4.3-1.1-.41-10.59-.27-11.11,2.59l4.73,4.3c1.69,1.53,2.81,3.07,4.67,4.86-4.13.57-4.9-4.41-8.06-5.41-1.96-.62-5.08,1.14-7.24-.45-.71-.53-2.34,1.67-1.89,2.23-.66-.81,2.45,3.72,2.43,3.8-.14.58-.46,1.53-1.34,1.68-.21-1.82-1.34-2.9-2.48-2.99-5.87-.47,3.3,6.56,2.28,7.9-1.63-.55-1.77-2.19-2.84-2.6-.85-.33-3.38-.44-2.97.61l1.35,3.48c-1.19,1.21-1.75,2.55-1.33,3.84.88,2.73,6.31,3.31,7.95,6.72-3.54-.04-4.83-2.79-8.22-4.49-.87,3.11,1.65,4.04,4.22,4.71-6.34,3.66-1.4-3.63-12.1-7.19-6.63-2.21.42,2.91-2.65,5.65-.58.52-3.33.15-3.24,1.37.22,3.18,5.07,2.92,7.92,4.28-4.58,3.78-6.63-4.3-8.44,2.75-1.11-.67-.69-2.83-2.1-3.32-2.11-.74-3.73.98-4.94,2.31s-.89,2.9-.83,4.91c-3.16-4.32-8.71,3.51-9.08,4.97-.32,1.23,1.01,2.39,2.11,3.59,1.39-1.16,1.98-3.45,3.76-3.97,5.12,1.25-2.16,3.46-1.26,5.91,2.21,6.02,6.54-7.36,11.93-2.73-5.2-.89-8.87,5.97-8.65,6.16.43.36,1.37.61,2.05,1.46-1.53.79-3.57,1.19-3.31,2.95.16,1.09.95,2.21,1.74,3.13,1.06,1.22,2.41-.74,3.65-.81,6.09-.34,11.22-2.24,14.15-8.24,1.31-.98,3.02-.12,1.18,1.16-11.93,15.4-7.82,2.19-16.34,11.04,1.4,2.4,5.85,5.4,8.91,6.37,7.3-12.87.76-10.09,17.51-17.05-5.96,7-14.12,2.24-13.94,17.88,3.64-.64,7.23,1.12,10.55-.3,2.36-1.01.92-5.03,1.28-7.31,1.35-.3,1.34,1.17,1.67,2.24,2.31-.66,3.2-2.97,5.19-4.11-1.27,3.77-5.03,4.02-3.53,7.64,5.63-.7,17.58-12.12,12.15-18.25.3-.37,1.61-.05,2.1-.39.99-3.82,1.67-8.2,5.75-10.35-2.79,5.73-6.13,10.72-4.64,16.96,4.03-1.32,6.97-4.14,11.14-3.03-5.91,2.82-11.11,4.64-12.48,10.67,2.77-.85,5.26-2.72,7.91-.87-5.73,1-9.23,3.28-12.92,6.59-2.09,1.87-6.1,1.87-7.43,4.74.92,4.41,6.1,6.05,8.1,8.98,1.19,1.75.88,4.3,3.29,5.49s2.97-1.31,6.46-1.43c-.67,2.39-6.27,4.6-8.39,2.62-4.44-4.16-7.74-10.83-13.73-13.27-2.1-.86-7.06,1.14-9.6-.5-2.62,2.43-.31,6.83.17,9.46.3,1.62-.79,3.34.35,4.54,2.97,3.11,3-1.3,8.45,6.12,5.87,7.98,1.24,4.37,7.75,10.72-2.05-.29-2.9-.65-3.53-1.21s-1.87,1.11-1.61,1.81c1.22,3.22,5.25,2.56,8.39,2.16,1.74-.22,3.63,1.09,4.73-.4.8-1.09.9-2.36,1.73-4.21.86,1.09,1.2,4.2-.29,4.95-2.21,1.11-4.85.65-5.98,3.01,2.4,1.71,5.63,2.24,5.32,5.78-5.6-6.3-12.95-7.55-19.19-4.62-3.94,1.85-3.68,4.03-4.87,7.62-1.36,4.09-2.2,7.51.44,12.22,2.31.29,3.94-2.1,5.79-3.94.62,1.49-.88,2.69-.36,3.46.6.88,2.35.54,3.23-.08,3.33-2.37,1.92-6.46,2.83-10.41,2.15,1.43-1.63,8.58,2.73,10.9-2.4.27-3.76.84-4.52,3.03ZM663.95,235.53c-2.76-1.99-5.52,3.93-5.86-.07-.2-2.34,5.22-2.08,3.34-10.17-6.41-2.41-3.14-3.91-5.53-4.83-3.09-1.19-7.92,3.09-7.29-1.93-4.32-1.84-8.06.09-11.87,2.32-1,.59-3.1-.7-4.09-.54-1.24.2-1.5,1.76-2.73,2.65l-9.56,6.94c-.43.31.34,2.53-.65,2.68-2.98.45-5.22-1.62-7.83-3.86-1.57,1.58-.11,3.25-1.41,4.3-1.03-1.28-.84-3.32-1.8-2.79l-1.98,1.08c-1.46.8-2.55-2.37-4.38-2.8-.73,2.5,1.22,3.72-.17,5.68l-4.36-3.99c-.23-1,2.52-3.12.73-4.79-1.47-1.37-3.02-3.94-4.79-4.55s-3.91,1.37-6,.05l4.41-1.84c-2.47-1.89-5.45-.79-7.61-3.63,1.72.18,14.87,2.13,13.26-1.91-.63-1.59-9.27-3.44-10.84-3.94,1.33-1.28,3.13-.62,2.83-2.03-.09-.42-3.18-4.33-6.12-4.26-1.4.03-2.5,1.78-4.08,2.18-2.52.64-4.79.37-6.54,2.44,1.36-3.6.06-8.99-3.68-9.45s-6.42-2.36-9.85-3.83c-2.19-.94-6.45,1.21-6.88,3.41-.2.99.95,2.1.95,2.92,0,.55-1.94.61-1.36,1.78,1.43,2.85,4.51,3.68,6.83,4.7-1.36,1.29-2.71.73-2.35,1.6,2,4.89,5.18.1,5.24,6.68,7.26-4.72,5.31,2.91,12.04-3.89,2.44,3.01,2.83,6.56,4.65,9.66,1.61,2.73,3.7,5.83,2.85,9.56-.65,2.88-3.5,7.11-1.67,10.4,4.74,8.51.74,12.14,8.72,13.94.18-1.03-.31-2.24,1.06-3.39,1.8,3.98,8.06,7.1,11.75,3.42,1.32-1.32,1.5-6.05,4.01-5.59,2.84.52.24,5.18,1.09,6.66,1.92,3.36,12.49-.87,12.06-.11.23-.41-.93-1.69-.11-2.18.49-.29,1.55,1.69,2.16,1.44l2.05-.85c.84-.35,1.87,1.29,2.49.76l2.27-1.96c.49-.42,1.6,1.46,2.04.99.63-.68.56-1.46,1.09-2.56.44.94,1.24,1.69,2.13,1.91.21.05,7.72-1.13,7.52-4.46-.14-2.23.22-4.27-2.15-6.11,5.69-1.07,3.42,7.73,7.05,7.29,2.12-.26,4.89.13,6.86.25,2.16-2.63,4.51-3.62,7.77-4.51,2.59-.71,1.12-4.83-.83-6.4,1.47-1.51,3.14.3,4.57-.85,1.89-1.51,1.58-2.89.55-3.63ZM514.39,230.99c5.34-2.26,10.25-.23,15.69-.23-.94-1.15-2.92,1.35-3.29-1.58,2.59-.46,4.42-.36,6.3.48-2.7,6.61-10.66,5.52-3.53,9.43-2.31,1.12-1.88,2.5-1.66,3.91.79,5.16,5.06,1.99,13.24,3.03,2.13.27,5.71-2.43,4.41-4.45-1.58-2.45-2.6-3.5-1.02-6.44.24-.45.2-1.25.6-1.55.76-.56,1.5-.52,1.53.32.02.58-.05,1.18.92,2.12,1.31-2.84.56-6.22-1.91-8.42,1.75-4.34,2.48-9.54.78-14.59-.4-1.19,1.03-2.91.33-4.28-.46-.89-2.35-1.11-3.06-2.31-1.13-1.93-2.11-2.1-4.04-1.17-1.23.59.13,2.09-.57,3.87-2.35-2.35-3.44-4.31-6.11-4.72-1.82-.28-5.11.86-5.32,3.31s2.66,3.41,4.8,4.1c-.24,1.28-1.69.98-2.53,2.28l3.64,2.11c-1.12.84-1.79,2.23-1.8,4.03,0,.93,3.02,1.18,1.2,3.18-2.35-4.02-4.22-6.73-6.46-9.97-1.15-1.66-.66-4.94-3.68-5.38-1.98-.29-2.1,2.33-2.63,3.7s.7,2.13,2.77,2.71c-1.47.64-2.61,2.26-1.57,4.08.69,1.21,1.23,1.94.67,3.09s-.71.75-1.81.46c-6.84-1.85,1.55,1.12-2.2-3.97-.83-1.13-2.7-1.06-4.1-1.47,1.12-.95,2.6-1.21,2.85-2.36.21-.98.62-2.96-.79-3.12-3.45-.4-8.19-.5-10.41,2.18.35,2.68,4.92,3.73,7.29,3.98l-4.12,2.12c-.57.29-.4,1.86.09,2.33l4.53-1.42c0,1.41-2.34,1.93-2.13,3.68.35,2.93,5.09-1.08,5.77-.26,2.84,3.45-4.17.66-2.66,7.2ZM490.98,214.88c-2.14,2.01-.73,6.13-2.56,8.17-1.19-2.04.47-4.8-1.32-6.19-1.64-1.26-3.42,1.44-4.89,1.8.65-3.11,4.12-4.1,2.24-6.2-.65-.73-4.29-.19-3.29-2.53.66-1.54,2.4-2.31,2.61-3.46.35-1.96-.79-2.94-1.24-4.69-1.28-5,2.57-7.11.29-9.18-3.2-2.9-9.09,7.94-8.53,12.22.24,1.83,2.31,3.38,1.41,5.2-.52,1.05-2.39,2.04-2.93,3.31-.59,1.8,1.89,3.43,2.85,5.08,1.19,2.06,1.3,5.6-1.37,5.94-1.19.15-3.69-2.24-4.72-1.04-1.51,5.2-2.69,10.07-3.46,15.63,3.09-.79,5.17-2.47,7.8-3.61l1.97,4.81c1.62-.99,2.42-1.94,3.52-1.84-.34-.03,5.98,5.89,11.71-.11,1.67-1.76,7.73-14.26,6.84-19.18-.52-2.86-4.4-6.5-6.91-4.14ZM526.15,161.36c.55.35.78,2.08,1.38,1.86,5.12-1.98,4.23,8.82,6.62,10.23.97.57,3.49,1.17,4.91.36,3.48-2,3.1-7.45.56-10.3s-1.83-5.93-.03-8.7c-3.33-3.66-1.05-5.07-3.63-6.2-.76-.33-1.87-.13-3.52-.45.48-2.62-.32-6.9-3.05-7.36-2.27-.39-3,3.13-4.13,4.26-.74-3.9.51-7.5-1.85-10.55s-6.36-3.03-9.85-1.86c-2.53.85-2.87,4.52-2.27,7.01,1.49.18,2.55-.79,3.73,0l-1.94,6.35c1.9-.6,1.98-3.23,3.9-3.69,1.02-.24,1.97,1.29,1.34,1.66-.45.27-1.04,1.21-1.12,2.1.69,1.26,2.7.56,3.91,1.75-.7,1.8-2.09,1.83-2.93,2.65.87.85,1.66,1.44,1.75,2.02.55,3.87-5.74-1.35-7.32-.25-.93.65-1.42,6.48,2.13,7.49,2.52.71,5.41-3.1,7.61-1.21.48.41-.5,1.93.62,2.53s2.46-.2,3.2.28ZM572.17,240.13c-.45-2.82-4-6.35-5.68-8.58-5.37-1.79-9.64,3.42-10.82,8.14-.56,2.23-4.07,4.39-2.78,6.77,1.72,3.18,5.06,4.73,9.08,5.96.54,3.84,8.95,5.75,10.34,1.78,1.57-4.5.53-9.94-.13-14.07ZM561.56,164.33c.59-1.17,2.13-2.11,2.11-3.25-.07-3.45-4.27-3.06-5.79-4.82-2.72-3.16-5.88-7.95-10.95-7.12-1.9.31-.29,3.89-.44,5.15-.63,5.33.7,10.73,6.89,12.25-1.52,2.28-3.08.92-4.56,2.38,1.83,2.2,2.9,3.71,3.98,5.45,3.3-2.06,7.26-1.23,9.84-4.38,1.41-1.73-.66-4.25-1.07-5.65ZM572.47,176.58c-5.24.24-16.06-2.69-16.45,3.06-.43,6.29,8.69,3.98,14.4,4.32,1.49-2.51,2.59-4.78,2.05-7.38ZM872.06,436.84c-.82-.97-2.5-.04-3.83-.17.39-1.87.94-3.75-1.11-4.44-1.67,2.11.25,4.34-1.98,5.92-.02-3.96-1.51-6.4-5.44-7.21-.7,1.88,0,3.71-1.43,5.38-1.55-1.23.02-2.37-.52-2.74-2.39-1.62-6.62-.22-6.93-.42-1.09-.71-1.8-2.65-1.94-4.18-1.92.16-.13,1.34-1.25,3.12-.95-2.06-2.06-3.59-4.56-4.3-.7-1.33.04-2.69-.84-3.8-1.55.29-2.83.76-2.93.1l-.48-3.16c-.25-1.62-2.42-.16-3.2.23-1.27.65-1.13,2.06-2.03,3.91-1.08-1.64-1.59-3.45-2.2-5.37-2.4-.25-5.8-1.1-5-4.44,1.87,1.6,4.65,1.8,6.76.81,1.45-.68,3.42-3.3,1.93-4.92-3.9-1.62-7.1-2.63-10.52-6.74,1.83-1.17,4.09-.09,7,1.51-.88-2.99-1.11-5.67.91-8.83,3.03,4.97,3.24,1.53,7.07,6.42,3-2.6,5.85,2.45,7.96,2.63,5.45.47,8.49-6.91,7.69-12.15,4.58,10.37-2.65,9.27-1.79,12.96.95,4.1,8.53,8.4,8.11,8.39.78.02,1.27-.94,3.01-1.27.07,2.27,2.6,5.44,4.89,4.91,1.07-.25,1.51-2.58,3.87-1.46.99.47,1.79-2.03,2.28-2.5,1.02,1.65,1.46,2.68,2.48,3.28.95.55,1.88-.78,2.46-1.39.99-1.04-2.72-8.53-7.02-10.96,1-1.29.8-3.04.26-4l7.07-3.09c-.83-.85-1.85-.82-2.39-1.19-.42-.29,2.56-1.59.79-1.31-2.56.13-2.64-1.6-.33-1.53,1.2.14,2.56-.61,2.69-1.27.49-2.6-7-2.18-5.61-3.38,2.17-.56,5.56-.61,5.28-2.5-.39-1.95-4.27-1.04-5.51-2.25s-1.23-3.17-3.68-3.85c1.34-1.27,2.75-1.21,4.6-.49,1.62.63,5.06-.99,4.97-2.97s-2.71-2.64-3.47-3.65c1.39-.87,3.1-2.48,2.08-3.63-2.48-2.8-6.39-3.22-9.73-3.74-.59-.09-.85-1.78-1.22-1.56-2.42,1.46-.11,6.34-2.51,5.58-4.07-1.28-6.54,3.87-8.29,6.88-1.56-1.98,1.1-4.06.7-5.56-2.04-7.69,1.26-5.27-1.08-7.81-1.25-1.36-3.07,1.85-7.08,2.83,1.06,1.05,1.98.87,2.5,1.26-9.18,8.31-5.36,7.98-6.44,9.77-4.12,6.85,2.44-8.18-2.03-8.04,1.78-1.19,2.96-.8,4.23-2.61-2.53-2.25-7.77-8.05-10.65-7.48-1.37.27-2.14,1.83-2.98,3.61-2.03-1.86-.77-4.28-1.19-7.53-4.38,1.67-2.9,5.82-3.34,9.47-2.71-.11-.28-3.77-1.95-5.16-2.82-2.34-.72,5.9-2.4,4.77l-2.56-3.15c-1.77,1.59-.81,3.5-.78,5.66-3.58.61,1.28-5.65-3.97-6.82-1.57-.35-2.1,1.43-3.02,2.83-.75-1.56-.1-2.97-1.17-3.73,2.21,1.58-9.51-3.99-10.2-3.89-3.65.53-6.84,3.22-10.77,2.07,1.45-2.05,4.02-1.6,5.83-3.22-4.26-1.08-7.46,1.88-11.19.2,9.65-1.64,2.07-3.39,12.09-3.74-2.09-1.93-4.47-2.48-7.04-3.19,1.38-1.07,4.08-.85,3.91-2.62-1.99-2.22-5.27-.6-7.84.63-.88-2.86,1.88-3.76,2.62-4.87,1.97-2.95,2.07,3.88,13.61-3.49-3.91-6.02-9.48-4.12-14.48-1.52-1.25.65-4.59-1.48-6.37-.24-.99.69-1.25,3.29-3.84,3.05,2.79-3.95,7.03-4.81,7.38-9.6,2.45-.99,4.75-.6,7.07-2.12.24-3.67-10.78-13.52-13.57-7.89-.43.87.67,1.57.83,2.7.46,3.26-2.54,5.72-4.02,8.43-.68,1.24-.3,2.95-1.22,3.57-.75.51-1.77.95-3.15-.14,2.84.6,6.73-11.16,4.43-12.01-2.05,1.56-6.33,2.28-6.7,5.06-.32,2.38,1.01,4.47-2.28,7.22-.16-5.7,2.4-10.35,6.79-14.54,1.39-1.32,2.75-6.13,1.09-7.22-2.82-1.84-6.81-.76-9.87-1.96-1.83,2.13-2.05,4.09-3.75,5.67l-.22-5.35c-9.48,10.18-5.36,8.28-5.71,17.81-1.54-1.41-.43-3.39-1.24-5.43-1.54-.76-2.92,1.02-5.03-1.16,3.66-1.34,5.09-3.28,5.4-5.56.5-3.79.86-7.29-2.92-9.18-1.55,2.17-1.59,4.54-2.19,7.25-.99-.79-1.09-2.62-2.15-2.5l-3.46.39c-1.67,1.36-2.86.86-1.58-1.1,1.53-.62,3.74-.66,4.11-1.65.34-.91,1.63-3.11.93-4.49-2.09-4.1-9.32-6.33-13.29-3.73-1,.66-1.05,3.11-2.12,3.3-.63.11-2.02-.22-2.55.26-1.16,1.05,1.1,2.33,1.75,3.29-1.32,2.39-.79,5.58-1.91,8.27-.62-4.53-.84-8.28-3.41-11.69-1.28,1.87.09,3.65-1.77,4.74-1.09-3.38.32-5.83.6-8.92-4.51.48-6.31,4.49-5.97,8.89-3.88,1.48-3.91-5.66-4.53-4.62.21-.35,5.67-3.36,5.32-5.81-.21-1.5-.81-4.29-2.57-4.42-3.78-.28-6.7,2.46-10.08,1.4,1.68-1.45,3.62-.69,3.95-2.23.36-1.67-.29-2.87-1.1-4.18-2.32-3.74-5.88-4.52-9.66-3.01-2.09.84-4.39-1.69-6.37-2.01-5.27-.84-11.96,6.65-10.32,9.77.75,1.44,2.38,1.52,4.6,2.34-1.04,1.34-2.34.6-4.46.65-1.08,2.76-.89,6.75,1.13,9.5-6.34,2.9,1.55-5.02-6.23-8.99-.52,1.23-.21,2.5-1.6,3.63l-2.36-5.08c-2.16,2.18-.59,4.58-1.33,6.1-.47.96-2.35,1.32-1.53,3.49.92,2.44-1.25,4.65-2.41,6.12-.2-1.95-1.4-2.8-1.67-3.5-.2-.54,1.26-.85,2.32-1.7-5.44-2.33,3.25-12.13-3.92-7.76.47-2.8,3.2-5.78,2.67-8.71-2.09-2.43-4.54-4.84-3.74-8.95-1.94-1.31-4.04-.8-5.73-2.39-.11-3.26-1.12-8.13-4.69-9.16-6.22-1.81-11.87,2.23-15.13,7.13-1.97,2.97-5.88,4.34-8.73,7.2,2.79,3.68,6.82,3.54,10.42,4.56-3.13,1.47-5.81-.05-8.87-.29-.01,3.52,3.1,4.12,5.6,5.55-3.82.19-5.53-2.92-8.55-1.4-1.04,4.78.4,10.89,4.73,13.55.8.49,2.93-1,3.31.17.21.64-.14,2.19.03,3.32-1.32.26-3.85-.76-3.97,1.54-.07,1.43.13,2.66-.54,3.44-.9,1.04-2.04,1.07-3.91,2.27,3.45.83,5.31,2.83,6.37,6.05.8,2.44,4.41.42,5.72.86,3.37,1.13.05,12.95,1.24,11.47-2.34,2.93-4.69.15-11.31,5.76,2.01-5.38,8.02-4.8,10.56-9.59-21.93-11.63-13.28-23.09-16.21-26.23-4.14-4.43-3.55-8.35-2.1-13.68,1.55-5.73,2.31-11.63,7.13-16.08,1.01-.93,1.03-3.57-.04-3.82-12.41-2.86-21.51,4.58-25.96,15.2-2.01,4.81-2.27,8.42-2.5,13.53-.11,2.54-6.15,13.6-.54,15.98.87.37.93.49,0,.87-4.75,1.99-1.01,7.68-.78,9.97.28,2.73-1.88,6.69.77,8.77,4.5,3.54,11.66,1.31,15.91,2.33,2.01.94,3.62,5.48,8.02,4.18-13.37,6.96-9.54-1.93-17.7,1.58-.39.17-1.35-.82-1.93-.76-1.86.22,1.38,1.68,1.08,2.32-1.31,2.8,1.33,4.54,2.27,6.54,1.81,3.84,4.54,6.34,8.38,7.34,1.96,6.78,3.31-.3,9.5.48.41-1.51-1.16-2.55-.34-3.69,1.1,1.07.98,2.42,2.09,2.7.56.14,1.11-.63,2.22-1.6-.13,4.93,4.97,8.86,9.63,8.24-5.28-5.85,9.2.85,17.23-1.91,8.57-2.94,7.89,4.63,12.63,1.38,1.96-1.35,2.65-2.82.94-4.78,5.2,0,6.38,4.15,10.93,4.85-1.73-4.13-5.89-5.37-10.08-7.46l13.77-.77c2.15-.12,2.31.9,3.85,1.7,1.74.91,6.57-.22,7.74-1.97,1.69-2.55-1.38-7.43-3.69-8.82-1.98-1.19-4.44-.02-5.37-2.34,1.87-.52,2.66-1.72,3.05-2.99l8.97,5.58c1.05-.45,2.32-1.82,3.04-1.53,3.46,1.38,2.53,6.83,5.24,10.78.55-2.01,1.26-3.16,2.72-2.75.91.25,2.26.4,2.94,1.68.49.93-.78,1.51-1.26,3.68,3.35-.93,5.46,1.75,8.22,2.37,2.93.65,6.36.41,8.41,3.46.83,1.23.24,3.1-.58,4.05-1.86,2.16-8.16,1.5-6.84,4.22s1.67,4.49,2.57,7.6c3.73-3.42,7.32-6.42,7.01-11.2,2.62,2.54,4.46.79,7.42-.22.09,2.04.45,4.14,2.76,5.22,1.49.7,1.27,4.14,6.71,1.95.02-1.61-1.78-2.25-2.04-3.37-.09-.71,1.96-.97,2.73-.52,1.74,1.02-.24,5.69,1.67,7.26,3.25,2.66,6.84.04,8.34,1.14,2.86,2.08-.3,2.63,6.36,8.6,3.05,2.73,4.46,6.79,7.33,9.65,1.43,1.42,2.11,2.67,1.99,4.52-.09,1.35-1.63,1.87-2.44,3.19-1.57,2.57-.33,5.76-.34,8.54,0-2.05-7.17,17.06-9.31,17.86,6.58,9.26,8.39,3.25,14.61,13.59-2.94.09-4.48-.58-5.95.57-1.36,1.07-1.14,3.78-2.6,5.06s-4.8-.41-5.58,2.23c-.28.94.09,1.99-.38,2.4-.54.47-2.24,1.42-2.78,1.02-2.83-2.13-4.55,1.58-6.17,1.51-.51-.02-18.41-2.6-13.78.91-.48.97-1.26,2.05-.82,2.7.32.47,2.44,1.24,2.16,2.04-1.49,4.32-7.55,2.37-4.75,14.46,1.08,4.66,6.39,8.02,11.34,7.56,2.18-.2,4.65-1.14,6.67-.71,8.3-9.2,9.47-4.21,7.82-11.2,4.57-.19,9.02,2.13,13.75.74-.55-1.48-2.71-4.15-1-5.35,1.58-1.12,2.22-1.87,3-4.63.99,3.38,1.04,6.36,3.15,9.22.29-2.74,1.21-3.89,3.14-5.08.45,1.58.39,3.02,1.33,3.32,4.38-2.01,7.41,1.86,10.27,4.26-.95-.8,9.33,5.68,8.94,5.43,1.33.86,3.25-2.2,4.5-2.3,2.2-.17,3.88,2.41,6.53,1.9.48,1.5-.76,3.03-1.46,4.06-1.05,1.54-3.19.38-5.07,1.96,6.6.79,5.19,6.34,11.43,6.01,1.79-.1,2.24-2.35,3.35-3.63.5,1.76,1.25,5.12,3.33,4.61,2.7-.67,4.68.76,7.02.79,2.51.03,4.72-3.23,7.56-2.77-.72,5.91,14.14,6.94,15.73,6.67,8.15-1.37,15.55-3.2,23.84-2.36,2.53.26,4.85.26,7.23-1.97-3.27-1.47-3.3-3.83-4.06-6.13-.68-2.06-4.56-1.32-6.47-1.57,2.55-3.08-28.19-12.15-26.38-11.18l-10.72-5.8c-.64-.35-2.03-1.91-2.1-2.65-.37-4.11,13.79,1.44,15.19,1.98-.96-2.67-3.7-3.13-3.09-5.53,3.77.89,5.47,4.58,8.79,4.97s6.09,1.32,8.2,3.71c4.06-2.77,6.4,1.96,8.81-3.82.73,1.87,1.06,3,2.94,2.91,1.75-.09,3.27-.09,4.18-1.87,1.15,2.47,1.79,5.34,4.36,5.71,1.62.23,2.26-2.13,1.79-3.04-1.35-2.6-5.12-1.97-4.72-4.12.07-.35-.12-.96.52-1.24.37-.16,1.12.1,1.73.31,1.7.59,2.47.31,2.66-1.08.26-1.84-8.18-7.19-7.13-6.27-.92-.8-.99-3.32-2.76-4.23,2.05-3.73,5.89,4.35,6.71,1.28.5-1.89-4.41-6.99-4.19-11.31-1.2,1.63-1.96,2.04-2.97,2.18-1.34.18-3.19.21-2.37-1.62,1.72-.23,2.41.35,3.41-.05.43-.17-.19-1.18-.54-1.6-.27-.31-.89-.64-1.45-.35-.35.18.24,1.58-1,1.5-1.72-.1.14-2.68-.33-3.46-.75-1.22-2.89.49-4.14,1.07-1.1-1.5-3.57-2.74-5.33-4.16-.44-1.33,1.44-4.11.14-5.66ZM811.29,416.74c-.41,1.04-2.5.29-3.6,1.09-1.86,1.37-3.34,2.72-6.09,1.24l-2.31,8.97c-.12.48-1.06.63-1.5.61-.42-.02-.13-.62-.29-1.47-.59-4.74-1.99-10.31-8.1-11.55,2.59-2.79,2.35-7.79.06-10.98,3.94-2.72,6.16,2.61,8.46,3.31,2.57.78,7.05-2.33,8.76,1.63,1.34-.94,2.51-1.85,3.9-.99l-2.13,2.79c1.15.76,3.36.03,3.98,1.31.77,1.59-.68,2.9-1.13,4.03ZM808.5,461.16c-2.28,1.15-4.31-.18-6-2.42l3.04-4.06c-2.54-.3-1.84-2.81-2.67-4.66-.58-1.31-4.42-2.81-2.34-4.52,2.22-.92,5.24,1.8,7.68,2.11,5.12.64,9.3,6.29,9.54,7.04.38,1.17.03,2.77-1.93,2.96-.45-.68.08-2.09-.67-2.38-7.11-2.71-.54,2.84-6.65,5.93ZM725,509.61c-1.05-1.31-3.51-.85-4.73-2.02-1.02-.99-2.08-4.63-4.33-3.79-1.47.55-2.1,2.08-4.87,1.55-2.46-.47-4.36,3.36-7.04,2.01.5-1.92,2.31-2.2,2.71-3.11.69-1.54-.93-2.38-1.31-3.79-4.15-15.47-15.16-5.48-21.66-17.32-13.81.08-4.25-2.08-13.12-6.12l-2.46,6.68-4.5-7.5c1.58-2.18.56-3.84-.82-5.45-.78-.92-2.21-1.71-4.35-2-6.12-.83-2.38,22.05-2.93,28.07s-1.17,11.2,3.35,16.05c-4.17,3.08-8.33,5.5-9.78,10.17-.72,2.3.08,4.68,2.21,4.96,1.64.21,2.39-1.87,3.89-2.73,3.26-1.88,7.42-2.1,10.95-3.08,2.05,4.18-1.03,11.36,3.48,13.65,5.83,2.96,12.34-13.47,11.37-12.38,3.74-4.21,7.24-7.4,5.45-13.28-.39-1.9,5.06-4.46,6.1-3.59.08,1.91-1.19,3.34.23,4.37,2.48-1.13,6.66-1.65,7.68.93.61,1.56-1.69,3.84.33,5.35,7.4-3.76,13.91,2.78,16.36,1.5,1.07-.56,3.75-8.97,7.78-9.12ZM746.46,396.45c-1.06-1.9.18-5.56-1.96-7.24-4.37-2.13-11.75-1.05-14.37,3.84-3.71,6.94-2.29,16.64,3.32,23.32,1.11,1.32,12.39-.17,15.08-8.49,1.37-4.24.01-7.72-2.07-11.43ZM700.53,553.36c4.24-1.77,4.57-7.15,7.55-10.5,1.8-2.02,4.16-8.08.95-9.47-4.04-1.76-7.9,3.24-11,4.26-1.41.46-3.11-.87-3.97-.08-2.68,2.44-.71,4.93-3.74,11.68-.27,2.41,2.93,4.12,3.52,6.97,2.24-3.92,4.83-2.08,6.7-2.86ZM743.31,550.3c.76-2.87.78-5.22-.84-7.35-1.13-1.48-4.81-3.49-6.57-.98-2.47,3.52-2.89,10.11-.78,13.89,1.21,2.16,4.61,2.71,5.75,5.56,3.18-4.16,1.48-7.51,2.44-11.12ZM687.28,283.42c4.59-.78,9.25-.39,13.79-2.54-4.86-8.51-16.79-21.15-27.2-12.81-3.67-.73-6.88-1.12-10.77-.82.06,3.75-.11,8.36,2.26,11.33.88,1.09,4.84-.37,5.27,1.9.75,3.97,2.92,10.33,8.34,8.98,3.57-.89,5.01-5.47,8.31-6.03ZM483.4,153.26c1.83-3.87,2.59-7.93,2.49-12.01-3.13-.33-4.95,2.18-7.53,2.82-3.48.85-8.17.38-9.41,4.59l5.17-.23c1.27-.06,1.42,1.92,2.52,3.1.88.95,2.19-1.07,3.04-2.06,1.43,1.1,1.4,2.71,3.72,3.78Z" />
            </g>
            <g class="prov-group" id="prov-TNL" data-label="Terre-Neuve-et-Labrador">
              <path id="TNL" d="M1129.28,661.67l-9.08-12.08c-27.73,18.84-56.41,36.36-86.23,52.38-3.5-2.55-6.62-5.66-11.32-5.39.41-5.06,3.82-6.31,7.3-9.03-2.3-1.73-4.59-2.35-7.21-4.89-.76,3.43-3.14,4.94-4.87,7.09-2.71,3.37.6,6.41,2.11,9.09,2.33,4.15,1.85,9.18,3.6,13.43.42,1.01,1.73,1.58,2.04,2.46s-.62,2.32-1.38,2.67c-2.55-2.33-5.46-1.37-7.87,1.02-3.52,3.49-8.23-9.67-16.81-.98-2.37-1.77-6.69-2.58-8.57-5.32.12-3-1.47-4.78-2.79-7.16-1.04-1.88-1.67-6.82-5.76-6.09-4.41.78.02,10.53-2.99,10.67-.59.03-1.57-1.84-2.2-1.87-3.07-.13-5.96-.06-6.67-3.46,2.44-.34,4.01.15,5.61-1.08-2.02-3.19-2.23-6.42-3.29-9.24-.94-2.5-4.8-1.45-6.64-1.62-3.09-.29-4.47-3.15-6.75-4.81-1.34-.97-4.47-1.25-5.16-2.71.99-3.25-.11-4.88-1.76-7.24-2.26-3.23,6.18-1.96,4.16-12.77,1.71-1.39,4.23-.22,6.28-1.35l-5.95-11.1c4.33,3.37,7.85,3.7,12.68,3.44,1.83-.1,3.36,3.02,6.07,1.79,4.46-2.03,1.86-6.13,5.01-8.29,2.64-1.8,4.18,2.81,5.51,2.47,4.04-1.01,2.72-4.67,7.72-3.4-4.02,2.85,2.1,8.44.68,11.76-.73-.59-1.01-1.29-1.68-1.65-.83-.44-1.2.31-1.59.72-.71.76-.63,2.37.94,2.81,1.9.42,1.77,2.41-.61,1.69-1.48,2.56-3.27,2.68-5.28,3.21-2.31.61-1.66,4.63-.23,6.18,1.21,1.32,4.43,2.31,6.02.02,4.49-6.49,4.94-3.27,5.2-6.54.09-1.14-1.04-.88-2.6-2.08l6.98-2.08c1.26,2.36,2.24,4.01,4.85,3.07,6.48-2.35-.94-6.57,9.97-9.2-2.66-2.95-5.02-5.71-8.05-7.71-3.95,4.51-.77,4.98-1.35,6.62-.32.91-.9,2.42-1.93,2.23-6.98-1.31-4.23-2.44-10.65-4.63-.86-.29-.81-1.31-.61-3.78,2.51,1.87,7.25-.79,7.33-3.58.02-.83-2.2-2.13-2.86-2.67-1.06-.87,1.16-2.95.98-3.93-.83-4.57-2.77-8.64-1.58-13.55-2.41-.48-3.79.62-5.56,1.42-1.76-3.31-3.43-6.27-6.39-8.54.86-2.01,2.3-3.12,2.97-5.45-9.26-.85-.99,2.79-11.61-3.08,2.29-3.58-2.69-5.07-3.71-7-.54-7.19-2.54-14.47-6.5-20.8-1.13-1.81.92-4.8-1.56-6.36-7.49,3.81-7.18-2.09-13.15-2.88-.68-4.87,3.83-9.94,1.98-12.45-.58-.79-1.81-1.1-3.06-1.73.93-1.42,3.43-1.84,4.13-3.75.77-2.1-1.89-3.28-3.2-3.62-2.34-.61-3.93,1.25-5.41,3.09s-3.03-2.28-4.41-2.83c-10.94-4.4-1.2-16.08-10.18-7.83-3.12-.75-3.71-5.87-3.61-9.13.03-1.07-2.37-2.63-.81-3.49,1.18-.65,1.79,2.24,2.06,3.5,1.93-1.2.5-2.49,1.23-4.34,1.58,1.79,2.96,3.14,2.84,6.12,1.55-.44,1.08-1.73,1.81-2.76,3.17,8.15,7.23.59,7.03,8.67,3.57-1.14,7.38-.04,8.63,3.13.24.6,1.94.05,2.47.24.79.28,1.7,3.56,2.68,4.33.48.38,1.14-.99,2.08-1.18,1.56-.33,3.28,2.17,3.8,3.41.65,1.59-.6,4-1.15,5.54,3.6,1.1.28.2,5.45-2.81,1.11-.65,3.66,4.23,3.92,4.94.4,1.1-1.18,1.87-1.51,3.25l3.72-2.16c1.18-.69,2.38,1.1,3.54,2.09-.48,1.07-2.53.57-2.45,2.75-.18-.23,13.74-5.47,9.46-1.34l-1.6,1.54c1.52.67,2.72.42,3.51,1.31,1.95,2.17-4.36,6.6-1.73,8.47.93-1.09,2.09-2.44,3.69-2.65,2.9-.38,2.34,2.05,3.35,3.47,1.78,2.5,5.33-.74,7.36-.83,2.58-.12,1.76,3.77.72,6,1.31.88,1.39-1.12,2.59-1.25.98,2.54,2.99,3.68,3.22,6.1h-3.28c-1,0-.99-2.51-1.69-3.12-2.6,1.36.85,4.04.87,5.2,0,.64-1.92.2-3.93,1.58-9.35,6.36,10.32-3.26,6.58,1.1-1.53,1.42-3.77.72-4.2,3.54,8.72-1.33,6.11,4.85,11.24.89l3.74,4.51c3.29-1.68,7.03-2.93,9.54.95.41-1.07.28-2.07,1.01-2.94,1.19,1.76,1.02,3.04,2.18,4.08.51-1.93.14-3.46,1.6-4.66,1.67,4.14.57,4.51,6.22,5.33-.04,1.68-1.67,1.82-2.46,3.32,1.55,1.42,2.38,2.55,3.59,5,.07-3.12,2.2-4.13,4.72-5.5.37-.2.37-1.66.62-1.87.38-.32,1.43-.77,1.75-.03s-.14,1.6-.14,2.54c0,.78,1.29.23,2.02-.16.59-.31,1.02-1.02,1.15-2.34,1.06-.34,1.24.13,1.64.42,2.06,1.45-1.71,4.92.11,6.95.94-3.54,2.02-6.78,2.28-10.78,1.14.49,1.14,1.51,1.86,2,.38.26,1.03-.52,1.76-.78,2.31,3.24,5.08,4.2,8.01,3.57,4.42-.96,5.8-3.07,6.69-8.34,1.4,1.14,1.15,2.45,2.24,3.73,10.19.55,6.83-3.13,12.15-.73-5.29,12.84-7.53,4.12-8.69,15.4,3.56-2.72,6.94-6.11,11.31-6.63,3.13.58,7.23,3.51,9.69,5.68,1.38,1.21-.78,3.93-1.39,5.12,1.17.69,2.55,1.46,4.23,1.48,1.11-1.85-1.36-3.51-1.62-4.84l7.6-7.72c-.06,1.93-.72,2.73-.42,3.18,1.42,2.1,7.36-2.71,9.78-2.36-.2,1.33-1.79.6-2.16,1.5,11.78,4.33,8.89,2.11,11.01,9.22.32,1.09,2.12,1.62,1.89,2.66-.3,1.32-1.12,2.03-2.76,3.19,2.04.95,4.21-.09,6.58-.36.28,1.34-.15,2.19.34,2.58,1.65,1.3,5.57,1.28,5.51,2.44-.03.64-1.45,1.23-1.95,2.03,1.59,1.01,4.71,2.05,4.9,4.35.56,6.76-2.33,12.55-5.11,18.58-.84,1.83.42,4.3-1.97,6.46ZM1063.86,645.14c-.86-3.57,2.28-3.94,3.12-6.07,1.03-2.61,3.68-5.76,4.13-7.92.8-3.77,0-7.53,3.26-11.05-2.9.96-3.97,3.2-6.4,2.54.95-1.37,2.75-1.98,1.31-2.75-1.76,1.05-2.59,2.03-2.99,2.71-.63,1.07,2.83,1.69,2.43,2.29-1.37,1.9-2.4,5.28-4.18,6.72-2.96,2.38-7.64,5.59-5.11,10.09,1.21,2.16,2.99,3.58.52,6.59,2.12.26,4.34-1.28,3.89-3.15ZM1240.85,696.44c2.24,3.88,2.9,7.01.38,10.06-.69-1.13-3.65-.98-4.05-2.61l-3.47-14.14-2.42,6.55c-1.72,4.65,1.95,8.68,2.55,13.58-1.84-.44-5.2-.83-6.26-2.14-1.2-9.96-4.9-6.97-4.47-8.2.19-.53,1.5-.95,1.56-1.68.21-2.72-1.34-5.74-.02-8.27.92-1.77,2.75-2.75,1.88-4.67-.61-1.34-.93-4.45-3.09-3.4-.94.46.21,3.83-1.37,4.15-1.05.21-2.23-.22-2.5.4-.86,1.96,1.17,3.97-.51,5.16-.46.33-1.26-1.17-1.82-1.14-.62,1.74.01,3.45-1.14,4.6l-2.26-7.38c-1.74.49-2.28,2.2-3.71,2.41.4-1.71.82-2.88.24-3.32-.31-.24-1.62.26-1.82-.1-1.71-3.08,2.64-5.06.09-13.12-8.48,1.64-9.18-1.8-15.14,7.72l-3.05-3.88c-2.69,5.65,2.44,9.63-5.8,12.43-1.51.51-3.34-.27-5-2.04-.42,1.75-1.17,3-3,3.98-.95-.2-.7-1.33-1.71-2.74-1.67,1.62-2.05,3.25-3.73,4.4-.41-1.34,1.02-2.81-.08-3.53-.58-.38-1.67-.8-2.78-1.16.02-3.69,3.34-6.19,2.29-10.53-1.04,3.06-3.16,4.26-6.31,4-.76-.06-1.42,2.51-2.32,1.46-.53-.62-.79-1.63-2.32-2.32-.73,4.81-2.55,12.99-.48,17.1.06,2.09-1.61,2.48-1.18-.04-2.57-1.28-2.26-4.28-3.21-5.74-1.92-2.94-1.52-4.42-1.29-7.57.38-5.2-1.66-10.46-1.75-15.88-.07-4.17.83-9.54-2.45-12.81-1.49.5-3.08,2.24-4.1,1.26-5.66-5.46,3.87-2.11,4.39-6.8.19-1.75-.24-4.94-2.74-5.18-1.34-.13-1.2,2.55-1.5,3.34-.47,1.24-1.44,1.3-2.34.74l-1.66-1.02-5.92,12.94c-1.23,2.69,4.38,14.09.7,18.26-.47.53,1.62,1.7,2.76,1.87-4.25,2.21-.03,6.79,1.03,16.57.22,2,1.27,3.2,2.61,4.42-.57.93-1.9,2.33-1.55,3.79.24,1.02,2.34,2.37,2.14,3.62-.37,2.32-2.75,6.9.48,8.47,2.21,1.08,4.31.63,5.03,4.51-1.4.43-3.3.81-5.43.9,2.05,5.4,1.02,12.78,4.5,14.06,1.19.44,2.32.12,4.04-.27-1.64,4.03-2.18,7.94-3.02,12.35-.6,3.11-3.46,6.52-2.1,9.5.67,1.37,2.83,2.81,3.96,4.04,2.91,3.17,12.17-7.47,13.46-9.17,2.72-3.55,6.41-3.71,9.81-5.42l14.11-7.11c1.94-.98,2.71-4.43,5.42-5.34,2-.67,2.12-3.48,1.7-5.32,1.17.43,1.77,1.72,3.06,1.64.75-.04,1.53-1.08,2.76-.81s2.06,1.88,2.74,1.52c1.15-.61,1.08-2.19,1.58-3.43.65.52.98,1.17,1.62,1.6.75.51,1.2-.25,1.54-.62,2-2.15-3.32-4.05-2.31-6.4,3.12.69,5.89-1.04,8.25-2.95.33-.27,1.41.61,1.19.96-3.74,5.8-3.07,6.47-2.04,10.49,1.33,5.18-7.58,10.92-3.95,13.67.79.6,3.43-.07,3.96-1.1,1.19-2.31,3.57-2.62,4.92-4.1,4.58-5.03-5-14.16,2.61-17.38,5.85-2.47-1.99-9.31,1.16-16.09,3.66.97,7.62,3.68,9.45,7.39,1.48,3,2.18,15.67,5.77,14.34,3.15-1.16.4-10.45,2.17-13.49l8.37,12.06,1.82-5.44c11.34,4.18-.21-11.82-1.23-18.57-.6-3.99-3.3-8.25-7.74-7.9ZM1066.98,639.07c1.03-2.61,3.68-5.76,4.13-7.92.8-3.77,0-7.53,3.26-11.05-2.9.96-3.97,3.2-6.4,2.54.95-1.37,2.75-1.98,1.31-2.75-1.76,1.05-2.59,2.03-2.99,2.71-.63,1.07,2.83,1.69,2.43,2.29-1.37,1.9-2.4,5.28-4.18,6.72-2.96,2.38-7.64,5.59-5.11,10.09,1.21,2.16,2.99,3.58.52,6.59,2.12.26,4.34-1.28,3.89-3.15-.86-3.57,2.28-3.94,3.12-6.07Z" />
            </g>
            <g class="prov-group" id="prov-NB" data-label="Nouveau-Brunswick">
              <path id="NB" d="M1089.3,840.22c-3.15.09-5.94-4.88-8.61-6.68-1.76-1.19-4.43-.97-5.27-2.96-.83-2,.09-4.42-1.67-6.28-2.35,1.01-3.83,3.46-7.25,3.66,6.19-16.08-1.2-9.78,2.06-19.38-12.08-1.27-10.15,8.5-14.07,8.37-.74-.03-1.58-.4-2.13-1.33-2.87-4.77-5.18-.73-12.37-1.76-2.38-.34-2.67,1.37-4.73,2.59-.38.53-.88.98-1.49,1.31.05.17.07.37.05.59-.03.37-.96.94-.93,1.31.47,2.87-1.46,2.94-3.24,4.1-3.02,1.98-5.33,4.48-9.32,2.82-5.55-2.31-9.02,7.25-9.1,6.76.28,1.73,1.27,3.08,1.85,4.73,1.63,4.58-2.53,8.3-5.32,11.66l-1.06,1.28c-.34.41-.11.52.15.59,3.5.95,5.91-6.11,10.21-7.39,2.71-.8,8.39,1.31,9.99,3.57,2.89,4.07,12.46,25.49,14.68,31.38,1.84,1.06,4.98.66,7.11.36l4.87,9.11c14.25-4.21,8.14,1.07,15.3-5.52,1.13-1.04,4.28-.56,4.47-3.68.15-2.47,2.39-2.97,4.15-3.83,4.41-2.15,5.39-7.22,8.22-10.75,3.33-4.14,6.4-9.01,7.43-13.9,5.75-2.24-.89-4.48,9.1-13.35-4.82-.49-8.57,2.49-13.12,2.62Z" />
            </g>
            <text class="rtee-prov-label cls-2 region-rouge" transform="translate(270.93 715.52)">
              <tspan x="0" y="0">ALB.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-bleu" transform="translate(130.52 608.14)">
              <tspan x="0" y="0">C.-B.</tspan>
            </text>
            <text class="rtee-prov-label cls-1 region-eucal" transform="translate(1066.55 808.47)">
              <tspan x="0" y="0">Î.-</tspan>
              <tspan class="cls-3" x="21.33" y="0">P</tspan>
              <tspan x="34.24" y="0">.-É.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-rouge" transform="translate(512.44 730.16)">
              <tspan x="0" y="0">MAN.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-eucal" transform="translate(1031.93 861.18)">
              <tspan x="0" y="0">N.-B.</tspan>
            </text>
            <text class="rtee-prov-label cls-1 region-eucal" transform="translate(1126.49 892.81)">
              <tspan x="0" y="0">N.-É.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-noir" transform="translate(677.47 814.61)">
              <tspan x="0" y="0">ON</tspan>
              <tspan class="cls-4" x="36" y="0">T</tspan>
              <tspan x="48" y="0">.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-rouge" transform="translate(369.23 789.13)">
              <tspan x="0" y="0">SASK</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-eucal" transform="translate(1021.43 655.74)">
              <tspan class="cls-4" x="0" y="0">T</tspan>
              <tspan x="12" y="0">.-N.-L.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-rouge" transform="translate(300.23 488.88)">
              <tspan class="cls-4" x="0" y="0">T</tspan>
              <tspan x="12" y="0">.N.-O.</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-rouge" transform="translate(529.32 502.45)">
              <tspan x="0" y="0">NT</tspan>
            </text>
            <text class="rtee-prov-label cls-2 region-bleu" transform="translate(118.98 416.8)">
              <tspan x="0" y="0">YN</tspan>
            </text>
          </svg>
          <div class="rtee-legende">
            <div class="rtee-legende-item">
              <span class="rtee-legende-dot" style="background:#1F36C2;"></span>
              <span class="rtee-legende-txt">Prairies &amp; Nord</span>
            </div>
            <div class="rtee-legende-item">
              <span class="rtee-legende-dot" style="background:#F22D12;"></span>
              <span class="rtee-legende-txt">C.-B. &amp; Yukon</span>
            </div>
            <div class="rtee-legende-item">
              <span class="rtee-legende-dot" style="background:#111533;"></span>
              <span class="rtee-legende-txt">Ontario</span>
            </div>
            <div class="rtee-legende-item">
              <span class="rtee-legende-dot" style="background:#3CD3AE;"></span>
              <span class="rtee-legende-txt">Atlantique</span>
            </div>
          </div>
          <div class="rtee-prov-infobox rtee-hidden" id="rtee-prov-infobox">
            <div class="rtee-prov-nom" id="rtee-prov-nom">—</div>
            <div class="rtee-prov-count" id="rtee-prov-count">—</div>
          </div>
        </div>
      </div>
      <div class="rtee-liste-panel" id="rtee-liste-panel">
        <div class="rtee-spinner-wrap">
          <div class="rtee-spinner"></div><span>Chargement…</span>
        </div>
      </div>
    </div>
  </div>
  <script>
    // Auto-resize iframe from inside
    (function() {
      function sendHeight() {
        var h = document.body.scrollHeight;
        window.parent.postMessage({
          rteeHeight: h
        }, '*');
      }
      window.addEventListener('load', function() {
        sendHeight();
        setTimeout(sendHeight, 800);
        setTimeout(sendHeight, 2000);
      });
      var obs = new MutationObserver(sendHeight);
      obs.observe(document.body, {
        childList: true,
        subtree: true
      });
    })();
  </script>
<?php
        }
