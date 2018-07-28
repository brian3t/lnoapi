delete from venue
WHERE id > 2;

update `venue`
set system_note = replace(system_note, 'https://www.sandiegoreader.comhttps://www.sandiegoreader.com/', 'https://www.sandiegoreader.com/');

update event
set sdr_name = replace(sdr_name, 'https://www.sandiegoreader.com', '');


select *
from venue
where not (sdr_name is null);


select *
FROM
  (select distinct band_id
   FROM (SELECT *
         FROM event
         WHERE date >= DATE_SUB(CURDATE(), INTERVAL :event_date_start DAY)
               AND date <= DATE_ADD(CURDATE(), INTERVAL :event_date_end DAY)) ev
     INNER JOIN (SELECT distinct band_id, event_id
                 FROM band_event) band_event on band_event.event_id = ev.id)
  band_performing
  INNER JOIN band b on band_performing.band_id = b.id;


DELETE
from band
where logo is null or logo = '';

######## assign random lno ratings for bands #########
update band
set lno_score = FLOOR( 5 + RAND( ) * 5 )
WHERE lno_score is null;
