# Répertoire des Établissements TÉÉ — Plugin WordPress

Plugin WordPress pour le **Réseau National des Travailleurs en Établissement dans les Écoles (RNTÉÉ)**.

## 📁 Structure du plugin

```
repertoire-tee/
├── repertoire-tee.php       ← Fichier principal du plugin
└── assets/
    ├── repertoire.css       ← Styles du répertoire
    └── repertoire.js        ← JavaScript (carte + filtres + liste)
```

## 🚀 Installation

1. **Télécharger** le dossier `repertoire-tee` complet
2. **Déposer** dans `/wp-content/plugins/repertoire-tee/`
3. Dans WordPress → **Extensions** → Activer **Répertoire des Établissements TÉÉ**
4. L'activation crée automatiquement la table `wp_tee_etablissements` et insère des données exemples

## 🎯 Utilisation

Insérez le shortcode sur n'importe quelle page ou article :

```
[repertoire_tee]
```

## ⚙️ Gestion des données

### Depuis l'admin WordPress

Menu **Répertoire TÉÉ** dans la barre latérale admin :
- **Établissements** → Voir/désactiver les entrées
- **Ajouter** → Formulaire complet d'ajout/modification

### Champs disponibles par établissement

| Champ | Description |
|-------|-------------|
| `nom` | Nom officiel de l'école |
| `type_etablissement` | `elementaire`, `secondaire`, ou `mixte` |
| `niveaux` | Ex: K-6, JK-8, 7-12, 1-6 |
| `province` | Province ou territoire |
| `ville` | Ville |
| `adresse` | Adresse civique |
| `code_postal` | Code postal |
| `telephone` | Téléphone de l'école |
| `courriel` | Courriel général de l'école |
| `site_web` | URL du site web |
| `coordonnateur_tee` | Nom du/de la coord. TÉÉ |
| `courriel_tee` | Courriel coord. TÉÉ |
| `telephone_tee` | Téléphone coord. TÉÉ |
| `conseil_scolaire` | Conseil scolaire affilié |
| `latitude` / `longitude` | Coordonnées GPS (optionnel) |

## 🔌 API REST (accès public)

```
GET /wp-json/rtee/v1/etablissements
GET /wp-json/rtee/v1/etablissements?province=Alberta
GET /wp-json/rtee/v1/etablissements?province=Alberta&ville=Edmonton
GET /wp-json/rtee/v1/etablissements?type=elementaire
GET /wp-json/rtee/v1/filtres
```

## 🎨 Personnalisation CSS

Les couleurs principales sont dans les variables CSS :
```css
--vert:      #3aaa35   /* vert RNTÉÉ */
--bleu:      #1a3a5c   /* bleu foncé */
--jaune:     #d4e84a   /* jaune accent */
```

Modifiez `assets/repertoire.css` pour adapter aux couleurs de votre thème.

## 🏗️ Ajouter des champs personnalisés

Pour ajouter un champ (ex: `langues_enseignment`) :

1. Ajoutez la colonne dans `rtee_create_tables()` dans le fichier PHP principal
2. Ajoutez le champ dans `rtee_admin_add_page()` dans la fonction `$f(...)`
3. Ajoutez l'affichage dans `renderItem()` dans `assets/repertoire.js`

## 📱 Fonctionnalités

- ✅ Carte interactive du Canada (SVG, cliquable par province)
- ✅ Filtres : type d'établissement, province, ville, niveau scolaire
- ✅ Liste avec détails dépliables par établissement
- ✅ Coordonnées TÉÉ (coordinateur, courriel, téléphone)
- ✅ API REST WordPress native
- ✅ Interface admin complète (ajouter/modifier/désactiver)
- ✅ Données exemples pré-remplies
- ✅ Design responsive (mobile-friendly)
- ✅ Couleurs thématiques RNTÉÉ (vert/bleu)
