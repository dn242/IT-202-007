CREATE TABLE IF NOT EXISTS `Y_Cart`
(
    `id`          int auto_increment,
    `user_id`     int NOT NULL,
    `product_id`  int NOT NULL,
	`quantity`	  int NOT NULL,
	`cartPrice`	  int NOT NULL,
    `created`     TIMESTAMP      default current_timestamp,
    primary key  (`id`),
    foreign key  (`user_id`) references Y_Users (`id`),
	foreign key  (`product_id`) references Y_Products (`id`),
	UNIQUE KEY (`product_id`, `user_id`)
)