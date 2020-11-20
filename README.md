# Receptų svetainė
Saitynas. Justas Gaurylius

yarn encore dev-server --hot
symfony serve -d

## API dokumentacija

Yra **trys** dalykinės srities kategorijos ir vartotojai. API yra pasiekiama per puslapį: *https://arcane-spire-27910.herokuapp.com/api/*

Autentifikacija realizuota naudojantis JWT tokenu. Autentifikacija reikalinga visur apart registracijos. 
### Vartotojai
1. Registracija

POST | /register
------------ | -------------
Parametrai | username, password, email
Atsakymas | User XXX successfully created

2. Prisijungimas

POST | /api/login_check
------------ | -------------
Parametrai | username, password
Atsakymas | token'as naudojamas autentifikacijai
Komentaras | gautas tokenas naudojamas siunčiant užklausas, pridėjus jį prie "Bearer Token"

3. Vartotojų sąrašas

GET | /api/users
------------ | -------------
Parametrai | -
Atsakymas | visų vartotojų sąrašas
Komentaras | Atsakymą gali gauti tik administratorius

4. Vartotojų blokavimas(ban)

POST | /api/users/ban/{id}
------------ | -------------
Parametrai | id - vartotojo id, kurios statusas bus keičiamas
Atsakymas | "User banned status changed"
Komentaras | Keisti statusą gali tik administratorius


### Ingredientai

1. Ingredientų sąrašas

GET | /api/ingredients
------------ | -------------
Parametrai | -
Atsakymas | visų ingredientų sąrašas

2. Ingrediento informacija

GET | /api/ingredients/{id}
------------ | -------------
Parametrai | id - ingrediento id
Atsakymas | ingrediento pagal id informacija

3. Ingrediento sukūrimas

POST | /api/ingredients
------------ | -------------
Parametrai | name, recipe, calories, amount
Atsakymas | "Ingredient added successfully"
Komentaras | Name - ingrediento pavadinimas, <br /> recipe - recepto kuriam priskirtas ingredientas id, <br /> calories - kalorijos 100 g. <br /> amount - kiek gramų reikia naudoti

4. Ingrediento atnaujinimas

PUT | /api/ingredients/{id}
------------ | -------------
Parametrai | id - ingrediento id, name, calories, amount 
Atsakymas | "Ingredient updated successfully"
Komentarai | Name - ingrediento pavadinimas, <br /> calories - kalorijos 100 g. <br /> amount - kiek gramų reikia naudoti <br /> **Bus atnaujinti tik pateikti argumentai**

5. Ingrediento ištrynimas

DELETE | /api/ingredients/{id}
------------ | -------------
Parametrai | id - ingrediento id,
Atsakymas | "Ingredient deleted successfully"

### Komentarai

1. Komentarų sąrašas

GET | /api/comments
------------ | -------------
Parametrai | -
Atsakymas | visų komentarų sąrašas

2. Komentarų informacija

GET | /api/comments/{id}
------------ | -------------
Parametrai | id - komentaro id
Atsakymas | komentaro pagal id informacija

3. Komentaro sukūrimas

POST | /api/comments
------------ | -------------
Parametrai | text, recipe
Atsakymas | "Comment added successfully"
Komentaras | text - komentaro tesktas, <br /> recipe - recepto kuriam priskirtas ingredientas id, <br /> **Vartotojas iškart priskiriamas kaip komentaro kūrėjas**

4. Komentaro atnaujinimas

PUT | /api/comments/{id}
------------ | -------------
Parametrai | id - komentaro id, text
Atsakymas | "Comment updated successfully"
Komentarai | text - komentaro tesktas

5. Komentaro ištrynimas

DELETE | /api/comments/{id}
------------ | -------------
Parametrai | id - komentaro id,
Atsakymas | "Comment deleted successfully"

### Receptai

1. Receptų sąrašas

GET | /api/recipes
------------ | -------------
Parametrai | -
Atsakymas | visų receptų sąrašas

2. Recepto komentarų sąrašas

GET | /api/recipes/{id}/comments
------------ | -------------
Parametrai | -
Atsakymas | visų recepto komentarų sąrašas

3. Recepto ingredientų sąrašas

GET | /api/recipes/{id}/ingredients
------------ | -------------
Parametrai | -
Atsakymas | visų recepto ingredientų sąrašas

4. Recepto informacija

GET | /api/recipes/{id}
------------ | -------------
Parametrai | id - recepto id
Atsakymas | recepto pagal id informacija

5. Recepto sukūrimas

POST | /api/recipes
------------ | -------------
Parametrai | name, text
Atsakymas | "Recipe added successfully"
Komentaras | name - recepto pavadinimas,<br />  text - recepto tesktas, 

6. Recepto atnaujinimas

PUT | /api/recipes/{id}
------------ | -------------
Parametrai | id - recepto id, name, text
Atsakymas | "Recipe updated successfully"
Komentarai | name - recepto pavadinimas,<br />  text - recepto tesktas, <br /> **Bus atnaujinti tik pateikti argumentai**

7. Recepto ištrynimas

DELETE | /api/recipes/{id}
------------ | -------------
Parametrai | id - recepto id,
Atsakymas | "Recipe deleted successfully"
Komentaras | **Bus ištrinti visi priskirti ingredientai ir komentarai**
