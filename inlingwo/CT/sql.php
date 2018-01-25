<?php

require("../config.php");

// Table
$Table = array(
    'member' =>
    'create table IF NOT EXISTS member(
        mem_id int AUTO_INCREMENT  PRIMARY KEY, 
        name varchar(30) not null,
        passwd char(50) not null,
        created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );',
    'login_check'=>
    'create table IF NOT EXISTS login_check(
        id int AUTO_INCREMENT  PRIMARY KEY,
        mem_id int not null,
        cookie varchar(80) not null,
        created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );',
    'funds'=>
    'create table IF NOT EXISTS funds(
        id int AUTO_INCREMENT  PRIMARY KEY,
        mem_id int not null,
        purpose varchar(150) not null,
        cost float not null,
        created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );',      
    'work_attendance'=>
    'create table IF NOT EXISTS work_attendance(
        id int AUTO_INCREMENT PRIMARY KEY,
        mem_id int not null,
        date TIMESTAMP default NULL,
        mon_attendance TIMESTAMP default NULL,
        aft_attendance TIMESTAMP default NULL,
        eve_attendance TIMESTAMP default NULL
    );',
    'blog_article'=>
    'create table IF NOT EXISTS blog_article(
        id int AUTO_INCREMENT  PRIMARY KEY,
        blog_id int not null,
        title varchar(80) not null,
        article_id char(80) not null,
        created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
    );',
    'blog_msg'=>
        'create table IF NOT EXISTS blog_msg(
            blog_id int AUTO_INCREMENT  PRIMARY KEY, 
            name varchar(30) not null,
            url varchar(100) not null,
            path varchar(100) not null,
            created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        );',
    'schedule'=>
        'create table IF NOT EXISTS schedule(
            id int AUTO_INCREMENT  PRIMARY KEY,
            mem_id int not null,
            Mon_fir char(50) default null,
            Mon_sec char(50) default null,
            Mon_thi char(50) default null,
            Mon_fou char(50) default null,
            Mon_fif char(50) default null,
            Tue_fir char(50) default null,
            Tue_sec char(50) default null,
            Tue_thi char(50) default null,
            Tue_fou char(50) default null,
            Tue_fif char(50) default null,
            Wed_fir char(50) default null,
            Wed_sec char(50) default null,
            Wed_thi char(50) default null,
            Wed_fou char(50) default null,
            Wed_fif char(50) default null,
            Thu_fir char(50) default null,
            Thu_sec char(50) default null,
            Thu_thi char(50) default null,
            Thu_fou char(50) default null,
            Thu_fif char(50) default null,
            Fri_fir char(50) default null,
            Fri_sec char(50) default null,
            Fri_thi char(50) default null,
            Fri_fou char(50) default null,
            Fri_fif char(50) default null,
            Sat_fir char(50) default null,
            Sat_sec char(50) default null,
            Sat_thi char(50) default null,
            Sat_fou char(50) default null,
            Sat_fif char(50) default null,
            created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );',
        'device_lend_return'=>
            'create table IF NOT EXISTS lend_device(
                id int AUTO_INCREMENT  PRIMARY KEY,
                mem_id int not null,
                device char(50) not null,
                remark char(100) default null,
                lend_day TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                promise_date_of_return datetime not null,
                return_status TIMESTAMP default NULL,
                created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );',
);

// Trigger
$Trigger = array(
    'drop_work_Trigger' =>
        'drop trigger IF EXISTS synchro_delete_mem_work_atten;',
    'synchro_delete_mem_work_atten'=>
        'create trigger synchro_delete_mem_work_atten
        after delete on member
        for each row
        begin
            delete from work_attendance 
            where mem_id = old.mem_id;
        end',
    'drop_login_check_Trigger' =>
        'drop trigger IF EXISTS synchro_delete_mem_login_check;',
    'synchro_delete_mem_login_check'=>
        'create trigger synchro_delete_mem_login_check
        after delete on member
        for each row
        begin
            delete from login_check 
            where mem_id = old.mem_id;
        end',
);

// Event
$Event = array(
    'set_on'=>
        'SET GLOBAL event_scheduler="ON"',
    'Event_delete_work_atten_history'=>
        'create event IF NOT EXISTS delete_work_atten_history 
         on schedule every 1 week
         starts current_timestamp 
         do 
            delete from work_attendance 
            where week(CURRENT_DATE)-week(mon_attendance)>3',
);

// Alter
$Alter = array(
    'mem_id'=>
    'alter table member AUTO_INCREMENT=10001;' ,
);

// 引用所有
$sqls = array(
    'Table' => $Table,  
    'Trigger' => $Trigger,
    'Event' => $Event,
    'Alter' => $Alter,    
);
