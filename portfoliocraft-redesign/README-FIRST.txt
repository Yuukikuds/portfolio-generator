PORTFOLIOCRAFT NEON REDESIGN (Laravel + Blade)
==============================================

Applies the new look to your Laravel project: dark neon-pink theme (with a light mode),
new landing page, pill navbar with a phone menu, new footer, and restyled forms, previews and Manage page.
Only these files are replaced:
  public\css\app.css
  resources\views\home.blade.php
  resources\views\layouts\app.blade.php
  resources\views\partials\navbar.blade.php
  resources\views\partials\footer.blade.php

Run it (Command Prompt):
     cd /d %USERPROFILE%\Downloads
     tar -xf portfoliocraft-redesign.zip
     cd portfoliocraft-redesign
     APPLY.cmd

  (In PowerShell use  .\APPLY.cmd )   Other folder:  APPLY.cmd -Project "D:\my\folder"

Then:
     cd /d C:\dev\portfolio-generator
     php artisan serve
  Open http://127.0.0.1:8000

The site now opens in dark mode. The sun/moon button switches to light mode and remembers your choice.
The reviews on the landing page are SAMPLE feedback: replace the text in resources\views\home.blade.php ($quotes)
with real feedback when you have it.

To deploy: git add -A, git commit -m "Neon redesign", git push  (Railway rebuilds automatically).
Undo: git reset --hard HEAD
