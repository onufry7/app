DROP TABLE IF EXISTS `user_group`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `groups`;


CREATE TABLE `groups` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `users` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`password` varchar(255) NOT NULL,
	`first_name` varchar(255) DEFAULT NULL,
	`last_name` varchar(255) DEFAULT NULL,
	`birth_date` DATE DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `user_group` (
	`id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) UNSIGNED NOT NULL,
	`group_id` bigint(20) UNSIGNED NOT NULL,
	PRIMARY KEY (`id`),
	KEY `fk_group_id` (`group_id`),
	KEY `fk_user_id` (`user_id`),
	CONSTRAINT `fk_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
