CREATE TABLE public.test
(
    id serial primary key,
    title text not null,
    description text not null,
    deadline_at timestamp without time zone not null
);
