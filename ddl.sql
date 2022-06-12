create table Brand
(
    id   bigint auto_increment,
    name varchar(63) not null,
    constraint Brand_id_uindex
        unique (id)
);

create table Shoe
(
    id       bigint auto_increment,
    name     varchar(63) not null,
    brand_id bigint      null,
    photo    varchar(63) null,
    constraint Shoe_id_uindex
        unique (id),
    constraint brand_id
        foreign key (brand_id) references Brand (id)
);

create table Order_Shoe
(
    id      bigint auto_increment,
    shoe_id bigint null,
    size    int    not null,
    constraint Order_Shoe_id_uindex
        unique (id),
    constraint shoe_id
        foreign key (shoe_id) references Shoe (id)
);

create table User
(
    id        bigint auto_increment,
    email     varchar(255)  not null,
    password  varchar(255)  null,
    shoe_size int           not null,
    money     int default 0 not null,
    constraint User_email_uindex
        unique (email),
    constraint User_id_uindex
        unique (id)
);

create table Bid
(
    id            bigint auto_increment,
    price         int                  not null,
    user_id       bigint               not null,
    is_success    tinyint(1) default 0 not null,
    order_shoe_id bigint               not null,
    date          date                 null,
    deadline      date                 null,
    constraint Bid_id_uindex
        unique (id),
    constraint bid_order_shoe
        foreign key (order_shoe_id) references Order_Shoe (id),
    constraint user_id
        foreign key (user_id) references User (id)
);

create table Sell
(
    id            bigint auto_increment,
    price         int                  not null,
    user_id       bigint               null,
    is_sold       tinyint(1) default 0 not null,
    order_shoe_id bigint               not null,
    date          date                 not null,
    deadline      date                 not null,
    constraint Sell_id_uindex
        unique (id),
    constraint shell_order_shoe
        foreign key (order_shoe_id) references Order_Shoe (id),
    constraint user_sell_id
        foreign key (user_id) references User (id)
);

