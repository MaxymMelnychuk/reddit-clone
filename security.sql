-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 23 fév. 2026 à 01:36
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `security`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id` int NOT NULL,
  `discussion_id` int NOT NULL,
  `user_id` int NOT NULL,
  `content` text NOT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `discussion_id`, `user_id`, `content`, `hidden`, `created_at`) VALUES
(1, 1, 2, 'CSS is logical. It just has trust issues.', 0, '2026-02-23 02:26:30'),
(2, 1, 3, 'You probably triggered margin collapsing without realizing it.', 0, '2026-02-23 02:27:22'),
(3, 1, 1, 'I didn’t even KNOW that was a thing.', 0, '2026-02-23 02:27:54'),
(4, 1, 4, 'It’s always a thing.', 0, '2026-02-23 02:28:31'),
(5, 2, 6, 'For local only? You’ll survive.', 0, '2026-02-23 02:30:19'),
(6, 2, 2, 'You should still create a separate user though.', 0, '2026-02-23 02:30:42'),
(7, 2, 5, 'Yeah… I know. I just wanted it to work.', 0, '2026-02-23 02:31:03'),
(8, 2, 1, 'We’ve all done worse.', 0, '2026-02-23 02:31:26'),
(9, 3, 3, 'Check PHP version differences.', 0, '2026-02-23 02:32:17'),
(10, 3, 4, 'Or missing environment variables.', 0, '2026-02-23 02:32:36'),
(11, 3, 5, 'Did you commit your config file?', 0, '2026-02-23 02:32:55'),
(12, 3, 6, 'Maybe.', 1, '2026-02-23 02:33:18'),
(13, 3, 1, '“It works on my machine” should be illegal.', 0, '2026-02-23 02:33:39');

-- --------------------------------------------------------

--
-- Structure de la table `discussions`
--

CREATE TABLE `discussions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `discussions`
--

INSERT INTO `discussions` (`id`, `user_id`, `title`, `slug`, `content`, `created_at`) VALUES
(1, 1, 'Why does CSS work perfectly… until you touch one thing?', 'why-does-css-work-perfectly-until-you-touch-one-thing', 'I swear my layout was fine for 2 hours. I change ONE margin and suddenly everything shifts like it’s alive. Is CSS actually logical or are we all just pretending we understand it?', '2026-02-23 02:22:27'),
(2, 5, 'Title: Is removing the MySQL root password locally a terrible idea?', 'title-is-removing-the-mysql-root-password-locally-a-terrible-idea', 'I removed the root password in my local setup because I kept getting authentication errors. It’s just for development. Am I building bad habits or is this fine?', '2026-02-23 02:29:34'),
(3, 6, 'Why does everything work on my machine but break everywhere else?', 'why-does-everything-work-on-my-machine-but-break-everywhere-else', 'My project runs perfectly locally. I push it. Someone else pulls it. Suddenly errors. Same code. Same files. I feel cursed.', '2026-02-23 02:31:55');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('USER','ADMIN') DEFAULT 'USER',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'PixelNomad', 'PixelNomad@gmail.com', '$2y$10$NfZMivyPqdwBS1vNaA3TdeVfrSGqwYbRHS98qudDObd7B4P7tfrQC', 'ADMIN', '2026-02-23 01:21:59', '2026-02-23 01:34:57'),
(2, 'CodeMoth', 'CodeMoth@gmail.com', '$2y$10$x2LHnOWRJkK8bZX6rCsIA.NnLFvmRsbIiTM0mADuZQyrQUILUz1Cu', 'USER', '2026-02-23 01:26:19', '2026-02-23 01:26:19'),
(3, 'StackSlayer', 'StackSlayer@gmail.com', '$2y$10$lV1immYmmiHt0JITGIW5xuyQPyxUkNgS9cLc1t9lNU0zp50yfzFm.', 'USER', '2026-02-23 01:27:04', '2026-02-23 01:27:04'),
(4, 'ByteBender', 'ByteBender@gmail.com', '$2y$10$Ccyb2srFF/yIwgK0YrGnZ.STX4XpxzxaaFUa3rHGIIYerauXMahkK', 'USER', '2026-02-23 01:28:23', '2026-02-23 01:28:23'),
(5, 'NullHunter', 'NullHunter@gmail.com', '$2y$10$PK4P88v3t8FvMHDkDE7weeM.8fa9DxAp84oAr3r/UkNSOkbwopP4a', 'USER', '2026-02-23 01:29:16', '2026-02-23 01:29:16'),
(6, 'SyntaxStorm', 'SyntaxStorm@gmail.com', '$2y$10$XrGHSWq6L04aNo7TlXJnR.TxPZE.F122Vpw.mS4Sv6ahcjN9oc0Je', 'USER', '2026-02-23 01:30:04', '2026-02-23 01:30:04');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_comments_discussion` (`discussion_id`);

--
-- Index pour la table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_discussions_slug` (`slug`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
