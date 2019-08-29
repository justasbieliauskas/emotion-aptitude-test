CREATE TABLE messages (
    id            INTEGER       PRIMARY KEY AUTOINCREMENT,
    first_name    VARCHAR (255) NOT NULL,
    last_name     VARCHAR (255) NOT NULL,
    date_of_birth DATE          NOT NULL,
    email         VARCHAR (255),
    content       TEXT          NOT NULL,
    created_at    DATETIME      NOT NULL
);
