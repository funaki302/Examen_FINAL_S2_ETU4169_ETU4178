

create or replace view v_categ_object as
select ob.id_objet as id_o , ob.nom_objet , ob.id_categorie , co.nom_categorie
from objet ob 
join categorie_objet co on ob.id_categorie = co.id_categorie;

create or replace view v_image_objet as
select o.*,i.id_image,i.nom_image from images_objet i join objet o
on i.id_objet = o.id_objet;

create or replace view v_categ_object_emprunt as
select * from emprunt e join v_categ_object vco 
on e.id_objet = vco.id_o; 