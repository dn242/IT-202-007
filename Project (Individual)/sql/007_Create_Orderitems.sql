CREATE TABLE IF NOT EXISTS `Y_Orderitems`
(
    `id`          int auto_increment,
    `order_id`    int NOT NULL,
    `product_id`  int NOT NULL,
	`quantity`	  int NOT NULL,
	`unit_price`  decimal(10, 2) default 0.00,
    `created`     TIMESTAMP      default current_timestamp,
    primary key   (`id`),
    foreign key   (`order_id`) references Y_Orders (`id`),
	foreign key   (`product_id`) references Y_Products (`id`)
)