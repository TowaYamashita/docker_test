CREATE TABLE public.test
(
    id serial primary key,
    title text not null,
    description text not null,
    deadline_at timestamp without time zone not null
);

INSERT INTO test(title, description, deadline_at) VALUES ('test1', '1_this is test message', '2021-04-01 10:00:00');
INSERT INTO test(title, description, deadline_at) VALUES ('test2', '2_this is test message', '2021-04-02 11:00:00');
INSERT INTO test(title, description, deadline_at) VALUES ('test3', '3_this is test message', '2021-04-03 12:00:00');