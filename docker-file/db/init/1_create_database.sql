create table public.todos
(
	id serial not null
		constraint todos_pk
			primary key,
	title text default '' not null,
	status text default 'todo' not null,
	finished_at timestamp without time zone default '2999-12-31 23:59:59' not null,
	created_at timestamp without time zone default now() not null,
	updated_at timestamp without time zone default now() not null
);
