create table public.todo_statuses
(
	id serial not null
		constraint todo_statuses_pk
			primary key,
	status text default 'unknown' not null
);

create table public.todos
(
	id serial not null
		constraint todos_pk
			primary key,
	title text default 'NO TITLE' not null,
	status_id integer default 1 not null,
	foreign key (status_id) references public.todo_statuses(id),
	finished_at timestamp without time zone default '2999-12-31 23:59:59' not null,
	created_at timestamp without time zone default now() not null,
	updated_at timestamp without time zone default now() not null
);
