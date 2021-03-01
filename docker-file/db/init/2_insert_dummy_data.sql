INSERT INTO todo_statuses
    (status)
VALUES
    ('todo'),
    ('doing'),
    ('done')
;

INSERT INTO todos
    (title, status_id, finished_at, created_at)
VALUES
    ('test1', 1, '2021-04-01 10:00:00', '2021-03-22 10:00:00'),
    ('test2', 2, '2021-04-02 11:00:00', '2021-03-21 10:00:00'),
    ('test3', 3, '2021-02-03 12:00:00', '2021-02-01 10:00:00'),
    ('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 2, '2021-04-04 13:00', '2021-03-25 10:00:00'),
    ('<script>alert("crack");</script>', 1, '2021-04-04 13:00:00', '2021-03-20 10:00:00')
;
