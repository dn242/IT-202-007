CREATE TABLE IF NOT EXISTS `Y_Ratings`
(
    `id`          int auto_increment,
    `user_id`    int NOT NULL,
    `product_id`  int NOT NULL,
	`rating`	  int NOT NULL,
	`rating_comment`  varchar(20),
    `created`     TIMESTAMP      default current_timestamp,
    primary key   (`id`),
    foreign key   (`user_id`) references Y_Users (`id`),
	foreign key   (`product_id`) references Y_Products (`id`)
)