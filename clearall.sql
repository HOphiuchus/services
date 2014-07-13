create table appointment (id integer primary key auto_increment, title varchar(50), description varchar(500), location varchar(200), owner_id integer, determineddate_id integer);
create table appointmentdate (id integer primary key auto_increment, date datetime, appointment_id integer, determined bool);
create table person (id integer primary key auto_increment, username varchar(50), password varchar(20), name varchar(50), isjujuuser bool, phone varchar(20), email varchar(50), devicetoken varchar(64));
create table person_contact (id integer primary key auto_increment, person_id integer, contact_id integer);
create table person_invitedappointment (id integer primary key auto_increment, person_id integer, appointment_id integer);
create table vote (id integer primary key auto_increment, appointmentdate_id integer, attendee_id integer);

delete from person;
delete from person_contact;

delete from appointment;
delete from appointmentdate;
delete from person_invitedappointment;
delete from vote;

drop table appointment;
drop table appointmentdate;
drop table person;
drop table person_contact;
drop table person_invitedappointment;
drop table vote;

