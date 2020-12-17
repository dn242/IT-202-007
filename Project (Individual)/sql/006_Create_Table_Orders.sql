CREATE TABLE IF NOT EXISTS `Y_Orders`
(
    `id`          			int auto_increment,
    `user_id`     			int NOT NULL,
    `created`     			TIMESTAMP      default current_timestamp,
	`total_price` 			decimal(10, 2) default 0.00,
	`address`	  			varchar(60),
	`payment_method`	  	varchar(60),
    primary key  (`id`),
    foreign key  (`user_id`) references Y_Users (`id`)
)