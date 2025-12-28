<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer un admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Créer des utilisateurs avec noms réalistes
        $users = collect([
            ['name' => 'Sophie Martin', 'email' => 'sophie.martin@example.com'],
            ['name' => 'Thomas Dubois', 'email' => 'thomas.dubois@example.com'],
            ['name' => 'Marie Laurent', 'email' => 'marie.laurent@example.com'],
            ['name' => 'Lucas Bernard', 'email' => 'lucas.bernard@example.com'],
            ['name' => 'Emma Petit', 'email' => 'emma.petit@example.com'],
        ])->map(fn ($user) => User::factory()->create([
            ...$user,
            'password' => bcrypt('password'),
            'role' => 'user',
        ]));

        // Projets réalistes avec assignations multiples
        $projects = [
            [
                'name' => 'Refonte Site E-commerce',
                'description' => 'Modernisation complète de la plateforme e-commerce avec amélioration de l\'UX et optimisation des performances.',
                'due_date' => now()->addDays(45),
                'users' => [$users[0]->id, $users[1]->id, $admin->id],
                'tasks' => [
                    ['title' => 'Analyse des besoins utilisateurs', 'status' => 'done', 'priority' => 3, 'user' => $users[0]->id, 'days' => -5],
                    ['title' => 'Maquettes UI/UX', 'status' => 'done', 'priority' => 4, 'user' => $users[0]->id, 'days' => -3],
                    ['title' => 'Développement frontend React', 'status' => 'in_progress', 'priority' => 4, 'user' => $users[1]->id, 'days' => 15],
                    ['title' => 'Intégration API paiement', 'status' => 'in_progress', 'priority' => 5, 'user' => $users[1]->id, 'days' => 20],
                    ['title' => 'Tests de charge', 'status' => 'pending', 'priority' => 3, 'user' => $admin->id, 'days' => 30],
                    ['title' => 'Migration base de données', 'status' => 'pending', 'priority' => 5, 'user' => $users[1]->id, 'days' => 25],
                    ['title' => 'Documentation technique', 'status' => 'pending', 'priority' => 2, 'user' => $users[0]->id, 'days' => 40],
                ],
            ],
            [
                'name' => 'Application Mobile iOS/Android',
                'description' => 'Développement d\'une application mobile native pour la gestion des commandes en temps réel.',
                'due_date' => now()->addDays(60),
                'users' => [$users[2]->id, $users[3]->id],
                'tasks' => [
                    ['title' => 'Setup environnement React Native', 'status' => 'done', 'priority' => 3, 'user' => $users[2]->id, 'days' => -7],
                    ['title' => 'Architecture de l\'application', 'status' => 'done', 'priority' => 4, 'user' => $users[2]->id, 'days' => -4],
                    ['title' => 'Écrans de connexion et authentification', 'status' => 'in_progress', 'priority' => 4, 'user' => $users[3]->id, 'days' => 10],
                    ['title' => 'Module de notifications push', 'status' => 'in_progress', 'priority' => 3, 'user' => $users[2]->id, 'days' => 15],
                    ['title' => 'Synchronisation offline', 'status' => 'pending', 'priority' => 4, 'user' => $users[3]->id, 'days' => 30],
                    ['title' => 'Tests unitaires', 'status' => 'pending', 'priority' => 3, 'user' => $users[2]->id, 'days' => 45],
                ],
            ],
            [
                'name' => 'Migration Cloud AWS',
                'description' => 'Migration de l\'infrastructure on-premise vers AWS avec mise en place de l\'architecture microservices.',
                'due_date' => now()->addDays(90),
                'users' => [$users[4]->id, $admin->id],
                'tasks' => [
                    ['title' => 'Audit infrastructure actuelle', 'status' => 'done', 'priority' => 4, 'user' => $admin->id, 'days' => -10],
                    ['title' => 'Configuration VPC et réseaux', 'status' => 'in_progress', 'priority' => 5, 'user' => $users[4]->id, 'days' => 20],
                    ['title' => 'Setup pipelines CI/CD', 'status' => 'pending', 'priority' => 4, 'user' => $users[4]->id, 'days' => 35],
                    ['title' => 'Migration base de données RDS', 'status' => 'pending', 'priority' => 5, 'user' => $admin->id, 'days' => 50],
                    ['title' => 'Configuration auto-scaling', 'status' => 'pending', 'priority' => 3, 'user' => $users[4]->id, 'days' => 65],
                ],
            ],
            [
                'name' => 'Dashboard Analytics',
                'description' => 'Création d\'un tableau de bord analytique pour le suivi des KPIs en temps réel.',
                'due_date' => now()->addDays(30),
                'users' => [$users[0]->id, $users[2]->id],
                'tasks' => [
                    ['title' => 'Définition des métriques clés', 'status' => 'done', 'priority' => 4, 'user' => $users[0]->id, 'days' => -8],
                    ['title' => 'Intégration Google Analytics', 'status' => 'in_progress', 'priority' => 3, 'user' => $users[2]->id, 'days' => 5],
                    ['title' => 'Graphiques et visualisations', 'status' => 'in_progress', 'priority' => 3, 'user' => $users[0]->id, 'days' => 10],
                    ['title' => 'Export PDF des rapports', 'status' => 'pending', 'priority' => 2, 'user' => $users[2]->id, 'days' => 20],
                ],
            ],
            [
                'name' => 'Système de Gestion des Stocks',
                'description' => 'Développement d\'un système complet de gestion des stocks avec suivi en temps réel et alertes automatiques.',
                'due_date' => now()->addDays(75),
                'users' => [$users[1]->id, $users[3]->id, $users[4]->id],
                'tasks' => [
                    ['title' => 'Modélisation de la base de données', 'status' => 'done', 'priority' => 5, 'user' => $users[1]->id, 'days' => -6],
                    ['title' => 'API REST pour la gestion des stocks', 'status' => 'in_progress', 'priority' => 4, 'user' => $users[3]->id, 'days' => 12],
                    ['title' => 'Interface de scanning code-barres', 'status' => 'in_progress', 'priority' => 4, 'user' => $users[1]->id, 'days' => 18],
                    ['title' => 'Système d\'alertes de réapprovisionnement', 'status' => 'pending', 'priority' => 3, 'user' => $users[4]->id, 'days' => 35],
                    ['title' => 'Rapports d\'inventaire automatisés', 'status' => 'pending', 'priority' => 3, 'user' => $users[3]->id, 'days' => 50],
                    ['title' => 'Intégration avec fournisseurs', 'status' => 'pending', 'priority' => 4, 'user' => $users[1]->id, 'days' => 60],
                ],
            ],
            [
                'name' => 'Campagne Marketing Q1',
                'description' => 'Planification et exécution de la campagne marketing digitale pour le premier trimestre.',
                'due_date' => now()->addDays(20),
                'users' => [$users[0]->id],
                'tasks' => [
                    ['title' => 'Stratégie de contenu réseaux sociaux', 'status' => 'done', 'priority' => 3, 'user' => $users[0]->id, 'days' => -12],
                    ['title' => 'Création visuels publicitaires', 'status' => 'done', 'priority' => 3, 'user' => $users[0]->id, 'days' => -5],
                    ['title' => 'Lancement campagne Google Ads', 'status' => 'in_progress', 'priority' => 4, 'user' => $users[0]->id, 'days' => 3],
                    ['title' => 'A/B testing landing pages', 'status' => 'in_progress', 'priority' => 3, 'user' => $users[0]->id, 'days' => 8],
                    ['title' => 'Analyse des résultats', 'status' => 'pending', 'priority' => 2, 'user' => $users[0]->id, 'days' => 18],
                ],
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create([
                'name' => $projectData['name'],
                'description' => $projectData['description'],
                'due_date' => $projectData['due_date'],
            ]);

            // Attacher les utilisateurs au projet
            $project->users()->attach($projectData['users']);

            // Créer les tâches
            foreach ($projectData['tasks'] as $taskData) {
                Task::create([
                    'project_id' => $project->id,
                    'user_id' => $taskData['user'],
                    'title' => $taskData['title'],
                    'description' => 'Description détaillée de la tâche : '.$taskData['title'],
                    'status' => $taskData['status'],
                    'priority' => $taskData['priority'],
                    'due_date' => now()->addDays($taskData['days']),
                ]);
            }
        }
    }
}
