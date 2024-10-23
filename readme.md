Nette Web Project
=================

Welcome to the Nette Web Project! This is a basic skeleton application built using
[Nette](https://nette.org), ideal for kick-starting your new web projects.

Nette is a renowned PHP web development framework, celebrated for its user-friendliness,
robust security, and outstanding performance. It's among the safest choices
for PHP frameworks out there.

If Nette helps you, consider supporting it by [making a donation](https://nette.org/donate).
Thank you for your generosity!


Requirements
------------

This Web Project is compatible with Nette 3.2 and requires PHP 8.1.


Installation
------------

To install the Web Project, Composer is the recommended tool. If you're new to Composer,
follow [these instructions](https://doc.nette.org/composer). Then, run:

	composer create-project nette/web-project path/to/install
	cd path/to/install

Ensure the `temp/` and `log/` directories are writable.


Web Server Setup
----------------

To quickly dive in, use PHP's built-in server:

	php -S localhost:8000 -t www

Then, open `http://localhost:8000` in your browser to view the welcome page.

For Apache or Nginx users, configure a virtual host pointing to your project's `www/` directory.

**Important Note:** Ensure `app/`, `config/`, `log/`, and `temp/` directories are not web-accessible.
Refer to [security warning](https://nette.org/security-warning) for more details.


Minimal Skeleton
----------------

For demonstrating issues or similar tasks, rather than starting a new project, use
this [minimal skeleton](https://github.com/nette/web-project/tree/minimal).

Popis projektu
--------------

- XML file sa cita vo funkcii readDatabase pod PetFacade do Array.
- Array vsetkych zvierat sa nacitava v renderAll v PetsPresenter do all.latte.
- Aplikacia pouziva formulare addPet, editPet a searchPet ktore sa renderuju v add,edit a find latte templatoch. Kazdy formular ma adekvatnu akciu fo forme formSucceed funkcii
- Kazde zviera ma atributy Meno, Kategoria (Macka,Pes,Ryba,Korytnacka ktora je rozsiritelna cez getCategory funkciu ktora obsahuje array vsetkych typov zvierat, ta sa pouziva na vsetkych miestach pre jednoduche rozsirenie Selectov vo formularoch a renderovych Loopoch v templatoch), obdobne statusy su v getStatus() taktiez pre rychle rozsirenie novych statusov.
Obrazok sa pridava priamo vo add formulary a uklada pod www/images. v ramci Editu sa uklada novy obrazok iba ak bol pridany novy, pokial nebol prilozeny novy obrazok ostava povodny.
Pouzite Image som vygeneroval v Canve. Pokial uzivatel image neuploadol ukazuje sa na jeho mieste hlaska: image missing, add one using edit
- Novy potencialny field pre Vek je odkomentovany vo formularoch Add aj Edit aj v adekvatnych formsucceed funkciach aby sa vytvoril novy tag v XML file. taktiez je odkomentovana cast v all.latte, home.latte pre zobrazenie noveho fieldu.
- Kedze v ramci zadania nebolo nic extra co by som pridal na Home, pridal som tam search pre ID a Status podla zadania.
- Pri kazdom recorde je funkcia edit aj delete s prislusnymi akciami v succeed funkciach pre Update a Delete z XML filu
- Pre editaciu XML filu pouzivam DOMDocument







