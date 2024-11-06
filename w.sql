CREATE DATABASE recycle_guide;

USE recycle_guide;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    total_recycled FLOAT DEFAULT 0,
    reward_points INT DEFAULT 0
);

CREATE TABLE rewards (
    reward_id INT AUTO_INCREMENT PRIMARY KEY,
    reward_name VARCHAR(100) NOT NULL,
    points_required INT NOT NULL,
    description TEXT
);
CREATE TABLE user_rewards (
    user_reward_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    reward_id INT,
    earned_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (reward_id) REFERENCES rewards(reward_id)
);
CREATE TABLE leaderboard (
    leaderboard_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    points INT NOT NULL,
    rank INT,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
CREATE TABLE recycling_data (
    recycling_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    item_type VARCHAR(50) NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL,
    points_earned INT NOT NULL,
    recycling_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

