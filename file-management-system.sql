CREATE TABLE `cities` (
  `id_city` int(11) AUTO_INCREMENT,
  `name` varchar(100),
  `country` varchar(100),
  PRIMARY KEY (`id_city`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `airports` (
  `id_airport` int(11) AUTO_INCREMENT,
  `city_id` int(11),
  `name` varchar(100),
  `code` varchar(10),
  PRIMARY KEY (`id_airport`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id_city`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `flights` (
  `id_flight` int(11) AUTO_INCREMENT,
  `flight_number` varchar(50),
  `departure_airport_id` int(11),
  `arrival_airport_id` int(11),
  `departure_time` datetime,
  `arrival_time` datetime,
  `seat_capacity` int(11),
  `reserved_seats` int(11) DEFAULT 0,
  PRIMARY KEY (`id_flight`),
  FOREIGN KEY (`departure_airport_id`) REFERENCES `airports` (`id_airport`) ON DELETE CASCADE,
  FOREIGN KEY (`arrival_airport_id`) REFERENCES `airports` (`id_airport`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `users` (
  `id_user` int(11) AUTO_INCREMENT,
  `name` varchar(100),
  `email` varchar(100),
  `password` varchar(255),
  `phone_number` varchar(15) DEFAULT NULL,
  `role` ENUM('admin', 'customer') DEFAULT 'customer',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `hotels` (
  `id_hotel` int(11) AUTO_INCREMENT,
  `city_id` int(11),
  `name` varchar(100),
  `description` text DEFAULT NULL,
  `rating` int(1),
  `number_of_rooms` int(11),
  `reserved_rooms` int(11) DEFAULT 0,
  PRIMARY KEY (`id_hotel`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id_city`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `taxis` (
  `id_taxi` int(11) AUTO_INCREMENT,
  `city_id` int(11),
  PRIMARY KEY (`id_taxi`),
  FOREIGN KEY (`city_id`) REFERENCES `cities` (`id_city`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `flight_bookings` (
  `id_flight_booking` int(11) AUTO_INCREMENT,
  `user_id` int(11),
  `flight_id` int(11),
  `status` ENUM('confirmed', 'pending', 'canceled') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_flight_booking`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  FOREIGN KEY (`flight_id`) REFERENCES `flights` (`id_flight`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `hotel_bookings` (
  `id_hotel_booking` int(11) AUTO_INCREMENT,
  `user_id` int(11),
  `hotel_id` int(11),
  `status` ENUM('confirmed', 'pending', 'canceled') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_hotel_booking`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id_hotel`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `taxi_bookings` (
  `id_taxi_booking` int(11) AUTO_INCREMENT,
  `user_id` int(11),
  `taxi_id` int(11),
  `status` ENUM('confirmed', 'pending', 'canceled') DEFAULT 'pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_taxi_booking`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  FOREIGN KEY (`taxi_id`) REFERENCES `taxis` (`id_taxi`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `chats` (
  `id_chat` int(11) AUTO_INCREMENT,
  `user_id` int(11),
  `chat_user_id` int(11),
  `message` text,
  `is_bot` boolean DEFAULT false,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_chat`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;