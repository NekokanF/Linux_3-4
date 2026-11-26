CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE rarities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    probability INT NULL
);

CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    rarity_id INT NOT NULL,
    FOREIGN KEY (rarity_id) REFERENCES rarities(id)
);

CREATE TABLE user_items (
   user_id INT NOT NULL,
   item_id INT NOT NULL,
   quantity INT NOT NULL DEFAULT 0,

   PRIMARY LEY (user_id,item_id),

   FOREIGN KEY (user_id) REFERENCES users(id),
   FOREIGN KEY (item_id) REFERENCES items(id)
);

-- ユーザー --
INSERT INTO users (name)
VALUES ('Player1');

-- レア度 --
-- probability は 10000 を 100% とする
INSERT INTO rarities (name,probability) VALUES
('COMMON',6000),
('UNCOMMON',2500),
('RARE',1200),
('EPIC',300),

-- アイテム --
INSERT INTO items (name,rarity_id) VALUES
('コモン',1),
('アンコモン',2),
('レア',3),
('エピック',4),

