
<?php

    $query0="select id, tipo_intervento, 
    stato_intervento, 
    data_creazione, 
    data_last,
    elemento_id,
    piazzola_id, 
    concat(piazzola_id,' - ', indirizzo, ' (' , riferimento ,')') as piazzola,
    utente,
    odl_id,
    priorita,
    quartiere, 
    ut,
    desc_intervento,
    st_y(st_transform(geoloc,4326)) as lat,
    st_x(st_transform(geoloc,4326)) as lon,
    string_agg(distinct desc_elemento, ' - ') as desc_elemento,
    sum(volume) as volume, 
    string_agg(distinct rifiuto, ' - ') as rifiuto
    from 
    (
    select i.id,
    string_agg(ti.descrizione, ' ; ') as tipo_intervento,
    tsi.id as id_stato_intervento,
    tsi.descrizione as stato_intervento,
    i.data_creazione,
    si.data_last, 
    i.elemento_id, 
    i.piazzola_id,
    i.utente,
    i.odl_id,
    concat(v.nome,', ', p.numero_civico) as indirizzo,
    p.riferimento, p.note,
    pp.geoloc,
    q.nome as quartiere,
    u.descrizione as ut,
    tp.descrizione as priorita,
    i.descrizione as desc_intervento,
    te.nome as desc_elemento,
    te.volume, 
    tr.nome as rifiuto
    from gestione_oggetti.intervento i 
    join (select a.intervento_id, b.tipo_stato_intervento_id as stato_corrente, a.data_ora as data_last 
    from (select intervento_id, max(data_ora) as data_ora 
    from gestione_oggetti.intervento_tipo_stato_intervento 
    group by intervento_id) a
    join gestione_oggetti.intervento_tipo_stato_intervento b on a.intervento_id= b.intervento_id and a.data_ora= b.data_ora ) si on i.id = si.intervento_id 
    join gestione_oggetti.tipo_stato_intervento tsi on si.stato_corrente = tsi.id
    JOIN gestione_oggetti.intervento_tipo_intervento iti on iti.intervento_id = i.id 
    JOIN gestione_oggetti.tipo_intervento ti on ti.id = iti.tipo_intervento_id 
    join gestione_oggetti.tipo_priorita tp on i.tipo_priorita_id = tp.id 
    JOIN elem.elementi e2 on e2.id_elemento = i.elemento_id
    JOIN elem.tipi_elemento te on te.tipo_elemento = e2.tipo_elemento 
    join elem.tipi_rifiuto tr on tr.tipo_rifiuto = te.tipo_rifiuto 
    JOIN elem.piazzole p on p.id_piazzola = i.piazzola_id 
    join geo.piazzola pp on pp.id=p.id_piazzola
    JOIN elem.aste a on a.id_asta = p.id_asta
    JOIN topo.vie v on a.id_via = v.id_via
    JOIN topo.comuni c on c.id_comune = v.id_comune
    JOIN topo.ut u on a.id_ut = u.id_ut
    JOIN topo.quartieri q on q.id_quartiere = a.id_quartiere
    group by i.id,
    tsi.id,
    tsi.descrizione,
    i.data_creazione, 
    si.data_last,
    i.elemento_id, 
    i.piazzola_id,
    i.utente,
    i.odl_id,
    v.nome, p.numero_civico,
    p.riferimento, p.note,
    pp.geoloc,
    q.nome,u.descrizione,
    tp.descrizione,
    i.descrizione,
    te.volume, 
    te.nome,
    tr.nome
    UNION
    select i.id,
    string_agg(ti.descrizione, ' ; ') as tipo_intervento,
    tsi.id as id_stato_intervento,
    tsi.descrizione as stato_intervento,
    i.data_creazione, 
    si.data_last,
    i.elemento_id, 
    i.piazzola_id,
    i.utente,
    i.odl_id,
    concat(v.nome, ', ', p.numero_civico) as indirizzo,
    p.riferimento, p.note,
    case when pp.geoloc is not null
    then pp.geoloc else pp2.geoloc
    end geoloc,
    q.nome as quartiere,
    u.descrizione as ut,
    tp.descrizione as priorita,
    i.descrizione as desc_intervento,
    te.nome as desc_elemento,
    te.volume, 
    tr.nome as rifiuto
    from gestione_oggetti.intervento i 
    join (select a.intervento_id, b.tipo_stato_intervento_id as stato_corrente , a.data_ora as data_last
    from (select intervento_id, max(data_ora) as data_ora 
    from gestione_oggetti.intervento_tipo_stato_intervento 
    group by intervento_id) a
    join gestione_oggetti.intervento_tipo_stato_intervento b on a.intervento_id= b.intervento_id and a.data_ora= b.data_ora ) si on i.id = si.intervento_id 
    join gestione_oggetti.tipo_stato_intervento tsi on si.stato_corrente = tsi.id
    JOIN gestione_oggetti.intervento_tipo_intervento iti on iti.intervento_id = i.id 
    JOIN gestione_oggetti.tipo_intervento ti on ti.id = iti.tipo_intervento_id 
    join gestione_oggetti.tipo_priorita tp on i.tipo_priorita_id = tp.id 
    left JOIN elem_temporanei.elementi e2 on e2.id_elemento = i.elemento_id
    left JOIN elem.tipi_elemento te on te.tipo_elemento = e2.tipo_elemento
    LEFT JOIN elem.tipi_materiale tm  on tm.id_materiale = e2.id_materiale
    left join elem.tipi_rifiuto tr on tr.tipo_rifiuto = te.tipo_rifiuto 
    JOIN elem.piazzole p on p.id_piazzola =i.piazzola_id 
    left JOIN elem_temporanei.piazzola_geo pp on pp.id_piazzola= p.id_piazzola
    left join geo.piazzola pp2 on pp2.id=p.id_piazzola
    JOIN elem.aste a on a.id_asta = p.id_asta
    JOIN topo.vie v on a.id_via = v.id_via
    JOIN topo.comuni c on c.id_comune = v.id_comune
    JOIN topo.ut u on a.id_ut = u.id_ut
    JOIN topo.quartieri q on q.id_quartiere = a.id_quartiere
    group by i.id,
    tsi.id,
    tsi.descrizione,
    i.data_creazione,
    si.data_last,
    i.elemento_id, 
    i.piazzola_id,
    i.utente,
    i.odl_id,
    v.nome,p.numero_civico,
    p.riferimento, p.note,
    pp.geoloc,pp2.geoloc,
    q.nome,
    u.descrizione,
    tp.descrizione,
    i.descrizione,
    te.nome,
    te.volume, 
    tr.nome
    ) idf";

    $query_group="group by id, tipo_intervento,
    stato_intervento, data_creazione, data_last,
    elemento_id,
    piazzola_id,
    riferimento, note,
    utente,
    odl_id,
    priorita,
    desc_intervento,
    indirizzo, 
    quartiere, ut, geoloc
    order by 1;";


    // dettagli elemento 
    $select_el="select e.matricola, e.serratura, tm.descrizione as materiale,
            mc.descrizione as categoria, ep.descrizione as nome--, ep.nome_attivita  
            from elem.elementi e 
            left join elem.tipi_materiale tm on tm.id_materiale = e.id_materiale 
            left join elem.elementi_privati ep on ep.id_elemento = e.id_elemento 
            left join utenze.macro_categorie mc on mc.id_macro_categoria = ep.id_macro_categoria 
            where e.id_elemento = $1
            union 
            select e.matricola, e.serratura, tm.descrizione as materiale,
            mc.descrizione as categoria, ep.descrizione as nome--, ep.nome_attivita  
            from elem_temporanei.elementi e 
            left join elem.tipi_materiale tm on tm.id_materiale = e.id_materiale 
            left join elem_temporanei.elementi_privati ep on ep.id_elemento = e.id_elemento 
            left join utenze.macro_categorie mc on mc.id_macro_categoria = ep.id_macro_categoria 
            where e.id_elemento = $2 and e.eliminazione=0";

?>