create database wp;

create table wp.Authority
(
    id   bigint auto_increment
        primary key,
    tag  varchar(63) not null,
    name varchar(63) not null
);

create table wp.Brand
(
    id   bigint auto_increment,
    name varchar(63) not null,
    constraint Brand_id_uindex
        unique (id)
);

create table wp.Shoe
(
    id       bigint auto_increment,
    name     varchar(63)  not null,
    brand_id bigint       null,
    photo    varchar(63)  null,
    `desc`   varchar(255) null,
    constraint Shoe_id_uindex
        unique (id),
    constraint brand_id
        foreign key (brand_id) references wp.Brand (id)
);

create table wp.Order_Shoe
(
    id      bigint auto_increment,
    shoe_id bigint null,
    size    int    not null,
    constraint Order_Shoe_id_uindex
        unique (id),
    constraint shoe_id
        foreign key (shoe_id) references wp.Shoe (id)
);

create table wp.User
(
    id           bigint auto_increment,
    email        varchar(255)     not null,
    password     varchar(255)     null,
    shoe_size    int              not null,
    money        int    default 0 not null,
    authority_id bigint default 1 not null,
    photo        varchar(255)     null,
    constraint User_email_uindex
        unique (email),
    constraint User_id_uindex
        unique (id),
    constraint authority_id
        foreign key (authority_id) references wp.Authority (id)
);

create table wp.Bid
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
        foreign key (order_shoe_id) references wp.Order_Shoe (id),
    constraint user_id
        foreign key (user_id) references wp.User (id)
);

create table wp.Bought
(
    id      bigint auto_increment
        primary key,
    bid_id  bigint   null,
    user_id bigint   null,
    dtime   datetime not null,
    constraint Bought___fk_bid
        foreign key (bid_id) references wp.Bid (id),
    constraint Bought___fk_user
        foreign key (user_id) references wp.User (id)
);

create table wp.Sell
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
        foreign key (order_shoe_id) references wp.Order_Shoe (id),
    constraint user_sell_id
        foreign key (user_id) references wp.User (id)
);

create table wp.Sold
(
    id      bigint auto_increment
        primary key,
    sell_id bigint   null,
    user_id bigint   null,
    dtime   datetime not null,
    constraint Purchased___fk_sell
        foreign key (sell_id) references wp.Sell (id),
    constraint Purchased___fk_user
        foreign key (user_id) references wp.User (id)
);

create table wp.Wish
(
    id      bigint auto_increment
        primary key,
    shoe_id bigint   null,
    user_id bigint   null,
    dtime   datetime not null,
    constraint Wish___fk_shoe
        foreign key (shoe_id) references wp.Shoe (id),
    constraint Wish___fk_user
        foreign key (user_id) references wp.User (id)
);

