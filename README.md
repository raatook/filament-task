# 📋 Task Manager - Clean Architecture

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Filament-4.x-FFAA00?style=for-the-badge" alt="Filament">
  <img src="https://img.shields.io/badge/Architecture-Clean-00C853?style=for-the-badge" alt="Clean Architecture">
</p>

<p align="center">
  Un système de gestion de tâches moderne et professionnel construit avec Laravel et Filament,<br>
  suivant les principes de <strong>Clean Architecture</strong> et <strong>SOLID</strong>.
</p>

---

## 📑 Table des matières

- [✨ Caractéristiques](#-caractéristiques)
- [🏗️ Architecture](#️-architecture)
- [🚀 Installation](#-installation)
- [👥 Rôles et permissions](#-rôles-et-permissions)
- [📊 Tableaux de bord](#-tableaux-de-bord)
- [🎯 Principes SOLID](#-principes-solid)
- [🧪 Tests](#-tests)
- [📚 Documentation](#-documentation)
- [🤝 Contribution](#-contribution)
- [📄 Licence](#-licence)

---

## ✨ Caractéristiques

### 🎯 Gestion complète des tâches
- ✅ Création, édition et suppression de tâches
- ✅ Statuts multiples (Pending, In Progress, Done)
- ✅ 5 niveaux de priorité (Low → Critical)
- ✅ Dates d'échéance et alertes de retard
- ✅ Assignation aux utilisateurs

### 📁 Gestion des projets
- ✅ Organisation par projets
- ✅ Suivi de l'avancement
- ✅ Assignation d'équipes
- ✅ Statistiques par projet

### 👥 Gestion des utilisateurs
- ✅ Deux rôles : Admin et User
- ✅ Permissions granulaires
- ✅ Interface multilingue (FR/EN)
- ✅ Profils personnalisables

### 📊 Tableaux de bord intelligents
- ✅ Statistiques en temps réel
- ✅ Graphiques de progression
- ✅ Tâches urgentes
- ✅ Indicateurs de performance

### 🎨 Interface moderne
- ✅ Interface Filament v4
- ✅ Design responsive
- ✅ Dark mode
- ✅ Notifications temps réel

---

## 🏗️ Architecture

Ce projet suit les principes de **Clean Architecture** et implémente tous les **principes SOLID**.

### 📦 Structure du projet

```
app/
├── Actions/                    # Command Pattern
│   ├── Task/
│   ├── Project/
│   └── User/
│
├── DataTransferObjects/        # DTOs
│   ├── TaskData.php
│   ├── ProjectData.php
│   └── UserData.php
│
├── Enums/                      # Type-safe Enums
│   ├── TaskStatus.php
│   ├── TaskPriority.php
│   └── UserRole.php
│
├── Events/                     # Domain Events
│   └── Task/
│
├── Listeners/                  # Event Listeners
│   └── Task/
│
├── Observers/                  # Model Observers
│   └── TaskObserver.php
│
├── Policies/                   # Authorization
│   ├── TaskPolicy.php
│   ├── ProjectPolicy.php
│   └── UserPolicy.php
│
├── Repositories/               # Data Access Layer
│   ├── Contracts/
│   ├── TaskRepository.php
│   ├── ProjectRepository.php
│   └── UserRepository.php
│
├── Services/                   # Business Logic
│   ├── TaskService.php
│   ├── ProjectService.php
│   └── UserService.php
│
└── Filament/                   # Presentation Layer
    ├── Resources/
    ├── Widgets/
    └── Pages/
```

### 🎨 Design Patterns implémentés

| Pattern | Utilisation |
|---------|-------------|
| **Repository** | Abstraction de l'accès aux données |
| **Service Layer** | Logique métier centralisée |
| **Command** | Actions encapsulées |
| **DTO** | Transfert de données typé |
| **Observer** | Événements du cycle de vie |
| **Strategy** | Policies d'autorisation |
| **Dependency Injection** | IoC Container Laravel |

---

## 🚀 Installation

### Prérequis

- PHP 8.2 ou supérieur
- Composer
- MySQL 8.0 ou supérieur
- Node.js & NPM

### Étapes d'installation

```bash
# 1. Cloner le repository
git clone https://github.com/raatook/filament-task.git
cd filament-task

# 2. Installer les dépendances
composer install
npm install

# 3. Configuration
cp .env.example .env
php artisan key:generate

# 4. Configurer la base de données dans .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=

# 5. Migrations
php artisan migrate

# 6. (Optionnel) Données de test
php artisan db:seed

# 7. Assets
npm run build

# 8. Lancer le serveur
php artisan serve
```

### 🔑 Créer un administrateur

```bash
php artisan make:filament-user
```

---

## 👥 Rôles et permissions

### 👑 Administrateur

- Gestion complète : projets, tâches, utilisateurs
- Accès aux statistiques globales
- Assignation des ressources
- Tous les widgets

### 👤 Utilisateur

- Création de tâches
- Modification du statut de ses tâches uniquement
- Vue de ses projets assignés
- Statistiques personnelles

> ⚠️ **Sécurité** : Les utilisateurs simples ne peuvent modifier que le statut de leurs tâches. Protection multi-niveaux (UI, Logic, Policy, Service).

---

## 📊 Tableaux de bord

### Dashboard Admin
- Statistiques globales
- Graphiques de progression
- Top utilisateurs
- Vue d'ensemble complète

### Dashboard Utilisateur
- Mes statistiques
- Tâches urgentes
- Progression personnelle

---

## 🎯 Principes SOLID

### ✅ Single Responsibility
Chaque classe a une seule responsabilité

### ✅ Open/Closed
Extensible sans modification

### ✅ Liskov Substitution
Implémentations interchangeables

### ✅ Interface Segregation
Interfaces spécifiques

### ✅ Dependency Inversion
Dépendance aux abstractions

---

## 🛠️ Technologies

- Laravel 12.x
- Filament 4.x
- MySQL 8.0
- PHP 8.2+
- Livewire, Alpine.js, Tailwind CSS
