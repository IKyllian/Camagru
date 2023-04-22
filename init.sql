USE camagru_db

CREATE TABLE IF NOT EXISTS users ( 
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username  VARCHAR(100) NOT NULL, 
    email VARCHAR(128) NOT NULL, 
    password VARCHAR(100) NOT NULL,
    token VARCHAR(100) DEFAULT NULL,
    active_notif tinyint(1) DEFAULT 1,
    active tinyint(1) DEFAULT 0,
    activation_code varchar(255) NOT NULL,
    password_reset_code varchar(255) DEFAULT NULL,
    activation_expiry datetime NOT NULL,
    activated_at datetime DEFAULT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    updated_at datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
);
CREATE TABLE IF NOT EXISTS posts (
    post_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    picture_path VARCHAR(255) NOT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    user_id INT NOT NULL,
    CONSTRAINT post_fk_user 
        FOREIGN KEY(user_id) 
        REFERENCES users(user_id) 
        ON DELETE RESTRICT
);
CREATE TABLE IF NOT EXISTS comments (
    comment_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    content VARCHAR(255) NOT NULL,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT comment_fk_post
        FOREIGN KEY(post_id)
        REFERENCES posts(post_id) 
        ON DELETE CASCADE,
    CONSTRAINT comment_fk_user 
        FOREIGN KEY(user_id) 
        REFERENCES users(user_id) 
        ON DELETE RESTRICT
);
CREATE TABLE IF NOT EXISTS likes (
    like_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT like_fk_post 
        FOREIGN KEY(post_id) 
        REFERENCES posts(post_id) 
        ON DELETE CASCADE,
    CONSTRAINT like_fk_user 
        FOREIGN KEY(user_id) 
        REFERENCES users(user_id) 
        ON DELETE RESTRICT
);