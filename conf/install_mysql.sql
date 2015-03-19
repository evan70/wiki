create table #prefix#wiki (
	id char(72) primary key,
	link_title char(72) not null,
	body text not null
);
