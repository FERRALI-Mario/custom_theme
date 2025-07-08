# 🎨 WP Theme Boilerplate

Thème WordPress moderne et modulaire, prêt pour des projets réutilisables ou clients, avec :
- PHP 8.1+, WordPress 6.8+
- Timber 2+, ACF PRO
- Tailwind 3 + Vite.js pour le workflow front-end
- Architecture PSR‑4 via Composer
- Blocs ACF orientés objet avec CLI
- Vues Twig (layouts, partials, components, pages)

---

## 📦 Installation

1. Cloner le dépôt dans `wp-content/themes/wp-theme-boilerplate`
2. Installer les dépendances PHP :
   ```bash
   cd wp-theme-boilerplate
   composer install
   ```
3. Installer les dépendances JS/CSS :
   ```bash
   npm install
   ```
4. Pour le développement (watch + live reload) :
   ```bash
   npm run dev
   ```
5. Pour construire les assets en production :
   ```bash
   npm run build
   ```
---

## 📁 Structure du thème

```python
   wp-theme-boilerplate/
   ├── acf-blocks/          # Dossiers de blocs ACF (block.json, Controller, twig, scss)
   ├── acf-json/            # Champs ACF versionnés
   ├── app/                 # Code PHP (PSR‑4) : Core, Support, Providers
   ├── assets/              # CSS/JS/images
   ├── bin/                 # Scripts CLI (make-block.php)
   ├── views/               # Twig (layouts, partials, components, pages)
   ├── vendor/              # Composer
   ├── functions.php        # Bootstrap du thème
   ├── index.php            # Front controller Timber
   ├── style.css            # Header WP
   ├── tailwind.config.js
   ├── vite.config.js
   ├── postcss.config.js
   ├── package.json
   └── README.md
```
---

## 🔨 Créer un nouveau bloc ACF

Utilise le script CLI :
```bash
   php bin/make-block.php <block-name>
```
Exemple :
```bash
   php bin/make-block.php hero
```

Cela génère :
- acf-blocks/hero/ avec block.json, Controller.php, template.twig, style.scss
- Un fichier vide acf-json/group_hero.json