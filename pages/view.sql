

create or replace view v_categ_object as
select ob.id_objet as id_o , ob.nom_objet , ob.id_categorie , co.nom_categorie
from objet ob 
join categorie_objet co on ob.id_categorie = co.id_categorie;

create or replace view v_categ_object_emprunt as
select * from emprunt e join v_categ_object vco 
on e.id_objet = vco.id_o; 