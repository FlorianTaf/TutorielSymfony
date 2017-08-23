-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mar 22 Août 2017 à 17:52
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `symfony`
--
CREATE SCHEMA IF NOT EXISTS `symfony` DEFAULT CHARACTER SET utf8 ;
USE `symfony` ;

-- --------------------------------------------------------

--
-- Structure de la table `oc_user`
--

CREATE TABLE `oc_user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_user`
--

INSERT INTO `oc_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`) VALUES
(1, 'Florian', 'florian', 'florian.taffaneau@laposte.net', 'florian.taffaneau@laposte.net', 1, NULL, 'Florian', NULL, NULL, NULL, ''),
(2, 'Candice', 'candice', 'flick.candice@laposte.net', 'flick.candice@laposte.net', 1, NULL, 'candice', NULL, NULL, NULL, ''),
(3, 'Edith', 'edith', 'edith.taffaneau@laposte.net', 'edith.taffaneau@laposte.net', 1, NULL, 'edith', NULL, NULL, NULL, '');


-- --------------------------------------------------------

--
-- Structure de la table `oc_advert`
--

CREATE TABLE `oc_advert` (
  `id` int(11) NOT NULL,
  `image_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nb_applications` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_advert`
--

INSERT INTO `oc_advert` (`id`, `image_id`, `user_id`, `date`, `title`, `author`, `content`, `published`, `updated_at`, `nb_applications`, `email`, `slug`) VALUES
(1, NULL, 1, '2017-08-14 17:06:50', 'Recherche développeur Symfony', 'Florian', 'Symfony avec 1 an d\'expérience', 1, '2017-08-30 00:00:00', 0, 'florian@florian.fr', 'recherche-developpeur-symfony'),
(2, NULL, 1, '2017-08-14 17:06:50', 'Recherche développeur Android', 'Quentin', 'Android avec 2 ans d\'expérience', 1, '2017-08-15 00:00:00', 0, 'quentin@quentin.fr', 'recherche-developpeur-android'),
(3, NULL, 1, '2017-08-14 17:06:50', 'Recherche développeur HTML/CSS', 'Candice', 'HTML/CSS avec 3 ans d\'expérience', 1, '2017-08-15 00:00:00', 0, 'candice@candice.fr', 'recherche-developpeur-html-css'),
(4, NULL, 1, '2017-08-14 17:06:50', 'Recherche intégrateur', 'Lucie', 'Intégrateur avec 4 ans d\'expérience', 1, '2017-08-18 00:00:00', 0, 'lucie@lucie.fr', 'recherche-integrateur'),
(5, NULL, 1, '2017-08-14 17:06:50', 'Recherche ingénieur réseau', 'Maxence', 'Ingénieur réseau avec 5 ans d\'expérience', 1, '2017-08-26 00:00:00', 0, 'maxence@maxence.fr', 'recherche-ingenieur-reseau'),
(6, NULL, 1, '2017-08-14 17:06:50', 'Recherche développeur Eclipse', 'Adrien', 'Eclipse avec 6 ans d\'expérience', 1, '2017-08-30 00:00:00', 0, 'adrien@adrien.fr', 'recherche-developpeur-eclipse'),
(7, NULL, 2, '2017-08-14 17:06:50', 'Recherche développeur Swift', 'César', 'Swift avec 7 ans d\'expérience', 1, '2017-08-15 00:00:00', 0, 'cesar@cesar.fr', 'recherche-developpeur-swift'),
(8, NULL, 2, '2017-08-14 17:06:50', 'Recherche développeur javascript', 'Adeline', 'Javascript avec 8 ans d\'expérience', 1, '2017-08-15 00:00:00', 0, 'adeline@adeline.fr', 'recherche-developpeur-javascript'),
(9, NULL, 2, '2017-08-14 17:06:50', 'Recherche intégrateur expérimenté', 'Cédric', 'Intégrateur avec 9 ans d\'expérience', 1, '2017-08-15 00:00:00', 0, 'cedric@cedric.fr', 'recherche-integrateur-experimente'),
(10, NULL, 3, '2017-08-14 17:06:50', 'Recherche technicien réseau', 'Aline', 'Technicien réseau avec 10 ans d\'expérience', 1, '2017-08-28 00:00:00', 0, 'aline@aline.fr', 'recherche-technicien-reseau');

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert_category`
--

CREATE TABLE `oc_advert_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert_skill`
--

CREATE TABLE `oc_advert_skill` (
  `id` int(11) NOT NULL,
  `advert_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `oc_application`
--

CREATE TABLE `oc_application` (
  `id` int(11) NOT NULL,
  `advert_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_application`
--

INSERT INTO `oc_application` (`id`, `advert_id`, `author`, `content`, `email`, `date`) VALUES
(1, 1, 'Roger', 'Bonjour ,je postule pour l\'offre de "Développeur Symfony"', 'roger@roger.fr', '0000-00-00 00:00:00'),
(2, 1, 'Bertrand', 'Bonjour ,je postule pour l\'offre de "Développeur Android"', 'bertrand@bertrand.fr', '0000-00-00 00:00:00'),
(3, 1, 'Maxime', 'Bonjour ,je postule pour l\'offre de "Développeur HTML/CSS"', 'maxime@maxime.fr', '0000-00-00 00:00:00'),
(4, 2, 'Kévin', 'Bonjour ,je postule pour l\'offre de "Intégrateur"', 'kevin@kevin.fr', '0000-00-00 00:00:00'),
(5, 3, 'Marion', 'Bonjour ,je postule pour l\'offre de "Ingénieur Réseau"', 'marion@marion.fr', '0000-00-00 00:00:00'),
(6, 7, 'Amélie', 'Bonjour ,je postule pour l\'offre de "Développeur Eclipse"', 'amelie@amelie.fr', '0000-00-00 00:00:00'),
(7, 8, 'Yohann', 'Bonjour ,je postule pour l\'offre de "Développeur Swift"', 'yohann@yohann.fr', '0000-00-00 00:00:00'),
(8, 9, 'Olivier', 'Bonjour ,je postule pour l\'offre de "Développeur javascript"', 'olivier@olivier.fr', '0000-00-00 00:00:00'),
(9, 9, 'Brigitte', 'Bonjour ,je postule pour l\'offre de "Intégrateur expérimenté"', 'brigitte@brigitte.fr', '0000-00-00 00:00:00'),
(10, 10, 'Nicolas', 'Bonjour ,je postule pour l\'offre de "Technicien réseau"', 'nicolas@nicolas.fr', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `oc_category`
--

CREATE TABLE `oc_category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_category`
--

INSERT INTO `oc_category` (`id`, `name`) VALUES
(1, 'Développement web'),
(2, 'Développement mobile'),
(3, 'Graphisme'),
(4, 'Intégration'),
(5, 'Réseau');

-- --------------------------------------------------------

--
-- Structure de la table `oc_image`
--

CREATE TABLE `oc_image` (
  `id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `oc_skill`
--

CREATE TABLE `oc_skill` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_skill`
--

INSERT INTO `oc_skill` (`id`, `name`) VALUES
(1, 'PHP'),
(2, 'Symfony'),
(3, 'C++'),
(4, 'Java'),
(5, 'Photoshop'),
(6, 'Blender'),
(7, 'Bloc-note'),
(8, 'Netware'),
(9, 'Novell'),
(10, 'Android'),
(11, 'Joomla');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `oc_advert`
--
ALTER TABLE `oc_advert`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_B193175BDAFD8C8` (`author`),
  ADD UNIQUE KEY `UNIQ_B193175989D9B62` (`slug`),
  ADD UNIQUE KEY `UNIQ_B1931753DA5256D` (`image_id`),
  ADD KEY `IDX_B193175A76ED395` (`user_id`);

--
-- Index pour la table `oc_advert_category`
--
ALTER TABLE `oc_advert_category`
  ADD PRIMARY KEY (`advert_id`,`category_id`),
  ADD KEY `IDX_435EA006D07ECCB6` (`advert_id`),
  ADD KEY `IDX_435EA00612469DE2` (`category_id`);

--
-- Index pour la table `oc_advert_skill`
--
ALTER TABLE `oc_advert_skill`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_32EFF25BD07ECCB6` (`advert_id`),
  ADD KEY `IDX_32EFF25B5585C142` (`skill_id`);

--
-- Index pour la table `oc_application`
--
ALTER TABLE `oc_application`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_39F85DD8D07ECCB6` (`advert_id`);

--
-- Index pour la table `oc_category`
--
ALTER TABLE `oc_category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `oc_image`
--
ALTER TABLE `oc_image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `oc_skill`
--
ALTER TABLE `oc_skill`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `oc_user`
--
ALTER TABLE `oc_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7866CFC992FC23A8` (`username_canonical`),
  ADD UNIQUE KEY `UNIQ_7866CFC9A0D96FBF` (`email_canonical`),
  ADD UNIQUE KEY `UNIQ_7866CFC9C05FB297` (`confirmation_token`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `oc_advert`
--
ALTER TABLE `oc_advert`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `oc_advert_skill`
--
ALTER TABLE `oc_advert_skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `oc_application`
--
ALTER TABLE `oc_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `oc_category`
--
ALTER TABLE `oc_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `oc_image`
--
ALTER TABLE `oc_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `oc_skill`
--
ALTER TABLE `oc_skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `oc_user`
--
ALTER TABLE `oc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `oc_advert`
--
ALTER TABLE `oc_advert`
  ADD CONSTRAINT `FK_B1931753DA5256D` FOREIGN KEY (`image_id`) REFERENCES `oc_image` (`id`),
  ADD CONSTRAINT `FK_B193175A76ED395` FOREIGN KEY (`user_id`) REFERENCES `oc_user` (`id`);

--
-- Contraintes pour la table `oc_advert_category`
--
ALTER TABLE `oc_advert_category`
  ADD CONSTRAINT `FK_435EA00612469DE2` FOREIGN KEY (`category_id`) REFERENCES `oc_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_435EA006D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `oc_advert_skill`
--
ALTER TABLE `oc_advert_skill`
  ADD CONSTRAINT `FK_32EFF25B5585C142` FOREIGN KEY (`skill_id`) REFERENCES `oc_skill` (`id`),
  ADD CONSTRAINT `FK_32EFF25BD07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`);

--
-- Contraintes pour la table `oc_application`
--
ALTER TABLE `oc_application`
  ADD CONSTRAINT `FK_39F85DD8D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
